<?php

namespace App\Http\Controllers;

use App\Detalle_SalidaProducto;
use App\SalidaProducto;
use App\Producto;
use App\Eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use FPDF;
use Carbon\Carbon;

class SalidaProductosController extends Controller
{

    public function __construct()
    {
        session_start();
    }

    public function index(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $usuario = Auth::user();

        if ($buscar == '') {
            $ventas = SalidaProducto::join('users', 'salidas_productos.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_productos.evento', '=', 'eventos.id')
                ->select(
                    'salidas_productos.id',
                    'eventos.nombre_evento as evento',
                    'salidas_productos.fecha_hora',
                    'users.usuario'
                )
                ->orderBy('salidas_productos.id', 'desc')->paginate(3);
        } elseif($criterio == 'nombre_evento') {
            $ventas = SalidaProducto::join('users', 'salidas_productos.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_productos.evento', '=', 'eventos.id')
                ->select(
                    'salidas_productos.id',
                    'eventos.nombre_evento as evento',
                    'salidas_productos.fecha_hora',
                    'users.usuario'
                )
                ->where('eventos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('salidas_productos.id', 'desc')->paginate(3);
        }else{
            $ventas = SalidaProducto::join('users', 'salidas_productos.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_productos.evento', '=', 'eventos.id')
                ->select(
                    'salidas_productos.id',
                    'eventos.nombre_evento as evento',
                    'salidas_productos.fecha_hora',
                    'users.usuario'
                )
                ->where('salidas_productos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('salidas_productos.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem(),
            ],
            'ventas' => $ventas,
            'usuario' => $usuario
        ];
    }

    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $venta = SalidaProducto::join('users', 'salidas_productos.encargadoRegistro', '=', 'users.id')
            ->join('eventos', 'salidas_productos.evento', '=', 'eventos.id')
            ->select(
                'salidas_productos.id',
                'eventos.nombre_evento as evento',
                'salidas_productos.fecha_hora',
                'users.usuario'
            )
            ->where('salidas_productos.id', '=', $id)
            ->orderBy('salidas_productos.id', 'desc')->take(1)->get();

        return ['venta' => $venta];
    }

    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $detalles = Detalle_SalidaProducto::join('productos', 'detalle_salidasproductos.idproducto', '=', 'productos.id')
            ->select(
                'detalle_salidasproductos.cantidad',
                'productos.nombre_producto as producto'
            )
            ->where('detalle_salidasproductos.idventa', '=', $id)
            ->orderBy('detalle_salidasproductos.id', 'desc')->get();

        return ['detalles' => $detalles];
    }

    public function obtenerDatosEvento(Request $request){
        if (!$request->ajax()) return redirect('/');

        $categorias = Eventos::select('id', 'nombre_evento')
        ->orderBy('id', 'desc')
        ->get();

        return ['categorias' => $categorias];

    }

    public function imprimirTicket($id)
    {
        $venta = SalidaProducto::join('eventos', 'salidas_productos.evento', '=', 'eventos.id')
            ->select(
                'salidas_productos.id',
                'eventos.nombre_evento as evento',
                'salidas_productos.fecha_hora'
            )
            ->where('salidas_productos.id', '=', $id)
            ->orderBy('salidas_productos.id', 'desc')
            ->take(1)
            ->first();
                        
        $detalles = Detalle_SalidaProducto::join('productos', 'detalle_salidasproductos.idproducto', '=', 'productos.id')
            ->select(
                'detalle_salidasproductos.cantidad',
                'productos.nombre_producto as producto'
            )
            ->where('detalle_salidasproductos.idventa', '=', $id)
            ->orderBy('detalle_salidasproductos.id', 'desc')
            ->get();

    $pdf = new FPDF();
    $fechaActual = now()->setTimezone('America/La_Paz');
    $nombreEvento = $venta->evento;
    $fechaEvento = Carbon::parse($venta->fecha_hora)->format('Y-m-d');


    $pdf->AddPage('P', 'Letter');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 10);

    $rutaImagen = public_path('img\logoasagrande.png'); // Reemplaza con la ruta de tu imagen
    $pdf->Image($rutaImagen, $pdf->GetPageWidth() - 50, 10, 40);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'REPORTE DE DONACION', 0, 1, 'C');
    $pdf->Cell(0, 10, "Fecha: $fechaActual", 0, 1, 'C');

    $mitadAnchoPagina = $pdf->GetPageWidth() / 2;   // Obtener la mitad del ancho de la página

    $pdf->SetFillColor(255, 255, 255); // Restablecer el color de fondo
    $pdf->Cell($mitadAnchoPagina - 10, 10, '', 0, 1, 'C', true);


    // Mostrar "EVENTO" con fondo dorado medio oscuro y letras blancas
    $pdf->SetFillColor(184, 134, 11); // Color dorado medio oscuro
    $pdf->SetTextColor(255, 255, 255); // Texto en blanco
    $pdf->Cell($mitadAnchoPagina - 10, 5, 'EVENTO', 0, 1, 'C', true);

    // Mostrar el nombre del evento debajo del texto "EVENTO"
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(255, 255, 255); // Restablecer el color de fondo
    $pdf->SetTextColor(0, 0, 0); // Restablecer el color de texto negro
    $pdf->Cell(0, 10, "Nombre: $nombreEvento", 0, 1, 'L');
    $pdf->Cell(0, 7, "Fecha del Evento: $fechaEvento", 0, 1, 'L');

    $pdf->Cell($mitadAnchoPagina - 10, 10, '', 0, 1, 'C', true);


    // Ancho de las columnas
    $anchoColumnaProducto = 100;
    $anchoColumnaCantidad = 30;

    // Calcular el ancho total de la tabla
    $anchoTabla = $anchoColumnaProducto + $anchoColumnaCantidad;

    // Calcular la posición horizontal para centrar la tabla
    $centrarHorizontal = ($pdf->GetPageWidth() - $anchoTabla) / 2;

    // Establecer posición horizontal para comenzar la tabla
    $pdf->SetX($centrarHorizontal);

    // Crear una tabla para los detalles de la venta
    // Mostrar los títulos "PRODUCTO" y "CANTIDAD" con fondo dorado medio oscuro y letras blancas
    $pdf->SetFillColor(184, 134, 11);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($anchoColumnaProducto, 10, 'PRODUCTO', 0, 0, 'C', true);
    $pdf->Cell($anchoColumnaCantidad, 10, 'CANTIDAD', 0, 1, 'C', true);

    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetFillColor(255, 255, 255); // Restablecer el color de fondo
    $pdf->SetTextColor(0, 0, 0); // Restablecer el color de texto negro
    foreach ($detalles as $detalle) {
        $cantidad = $detalle->cantidad;
        $nombreArticulo = $detalle->producto;

        // Llenar la tabla con los datos de los productos
        $pdf->SetX($centrarHorizontal);
        $pdf->Cell($anchoColumnaProducto, 10, $nombreArticulo, 1, 0, 'L');
        $pdf->Cell($anchoColumnaCantidad, 10, $cantidad, 1, 1, 'C');
    }

    // Calcular el total de productos
    $pdf->SetX($centrarHorizontal);
    $totalP = $detalles->sum('cantidad');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($anchoColumnaProducto, 10, 'Total de productos:', 1, 0, 'R');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell($anchoColumnaCantidad, 10, $totalP, 1, 1, 'C');

    $pdf->Cell($mitadAnchoPagina - 10, 18, '', 0, 1, 'C', true);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Dios te bendiga :)", 0, 1, 'C');


    $pdfPath = public_path('docs/donacion.pdf');
    $pdf->Output($pdfPath, 'F');

    return response()->download($pdfPath);
    }
    

    public function store(Request $request)
    {
        if (!$request->ajax())
        return redirect('/');

        //try {
          //  DB::beginTransaction();

            $detalles = $request->data; //Array de detalles
            $perfil =Auth::user()->id;
            $venta = new SalidaProducto();            
                $venta->encargadoRegistro = $perfil;
                $venta->evento = $request->evento;
                $venta->fecha_hora = now()->setTimezone('America/La_Paz');
                $venta->save();

                        foreach ($detalles as $ep => $det) {

                            $disminuirStock = Producto::where('id', $det['idarticulo'])->firstOrFail();
                            $disminuirStock->cantidad -= $det['cantidad'];
                            $disminuirStock->save();

                            $detalle = new Detalle_SalidaProducto();
                            $detalle->idventa = $venta->id;
                            $detalle->idproducto = $det['idarticulo'];
                            $detalle->cantidad = $det['cantidad'];
                            $detalle->save();
                        }
                        

                        //$fechaActual = date('Y-m-d');
                        //$numVentas = DB::table('salidas_productos')->whereDate('created_at', $fechaActual)->count();

                        //DB::commit();
                        //return Response()->json(['detalles' => $detalles]);

            }
        //}catch (Exception $e) 
          //  {
            //    DB::rollBack();
            //}

            
        }
