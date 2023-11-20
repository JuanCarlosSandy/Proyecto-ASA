<?php

namespace App\Http\Controllers;

use App\Detalle_SalidaRopa;
use App\Eventos;
use App\Ropa;
use App\SalidaRopas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use FPDF;
use Carbon\Carbon;

class SalidaRopasController extends Controller
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
            $ventas = SalidaRopas::join('users', 'salidas_ropas.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_ropas.evento', '=', 'eventos.id')
                ->select(
                    'salidas_ropas.id',
                    'eventos.nombre_evento as evento',
                    'salidas_ropas.fecha_hora',
                    'users.usuario'
                )
                ->orderBy('salidas_ropas.id', 'desc')->paginate(10);
        } elseif($criterio == 'nombre_evento') {
            $ventas = SalidaRopas::join('users', 'salidas_ropas.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_ropas.evento', '=', 'eventos.id')
                ->select(
                    'salidas_ropas.id',
                    'eventos.nombre_evento as evento',
                    'salidas_ropas.fecha_hora',
                    'users.usuario'
                )
                ->where('eventos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('salidas_ropas.id', 'desc')->paginate(10);

            }else{
            $ventas = SalidaRopas::join('users', 'salidas_ropas.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_ropas.evento', '=', 'eventos.id')
                ->select(
                    'salidas_ropas.id',
                    'eventos.nombre_evento as evento',
                    'salidas_ropas.fecha_hora',
                    'users.usuario'
                )
                ->where('salidas_ropas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('salidas_ropas.id', 'desc')->paginate(10);
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

    public function store(Request $request)
    {
        if (!$request->ajax())
        return redirect('/');

        //try {
          //  DB::beginTransaction();

            $detalles = $request->data; //Array de detalles
            $perfil =Auth::user()->id;
            
            $venta = new SalidaRopas();            
            $venta->encargadoRegistro = $perfil;
            $venta->evento = $request->evento;
            $venta->fecha_hora = now()->setTimezone('America/La_Paz');
            $venta->save();

                        foreach ($detalles as $ep => $det) {
                            $disminuirStock = Ropa::where('id', $det['idRopa'])->firstOrFail();
                            if ($disminuirStock->cantidad >= $det['cantidad']) {

                                $disminuirStock->cantidad -= $det['cantidad'];
                                $disminuirStock->save();
                                $detalle = new Detalle_SalidaRopa();
                                $detalle->idventa = $venta->id;
                                $detalle->idropa = $det['idRopa'];
                                $detalle->cantidad = $det['cantidad'];
                                $detalle->save();
                            }
                        }
            
            
                        

                        //$fechaActual = date('Y-m-d');
                        //$numVentas = DB::table('salidas_productos')->whereDate('created_at', $fechaActual)->count();

                        //DB::commit();
                        //return Response()->json(['detalles' => $detalles]);

    }
    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $venta = SalidaRopas::join('users', 'salidas_ropas.encargadoRegistro', '=', 'users.id')
            ->join('eventos', 'salidas_ropas.evento', '=', 'eventos.id')
            ->select(
                'salidas_ropas.id',
                'eventos.nombre_evento as evento',
                'salidas_ropas.fecha_hora',
                'users.usuario'
            )
            ->where('salidas_ropas.id', '=', $id)
            ->orderBy('salidas_ropas.id', 'desc')->take(1)->get();

        return ['venta' => $venta];
    }
    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $detalles = Detalle_SalidaRopa::join('ropas', 'detalle_salidasropas.idropa', '=', 'ropas.id')
            ->select(
                'detalle_salidasropas.cantidad',
                'ropas.nombre_ropa',
                'ropas.talla',
                'ropas.sexo'
            )
            ->where('detalle_salidasropas.idventa', '=', $id)
            ->orderBy('detalle_salidasropas.id', 'desc')->get();

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
        $venta = SalidaRopas::join('eventos', 'salidas_ropas.evento', '=', 'eventos.id')
            ->select(
                'salidas_ropas.id',
                'eventos.nombre_evento as evento',
                'salidas_ropas.fecha_hora'
            )
            ->where('salidas_ropas.id', '=', $id)
            ->orderBy('salidas_ropas.id', 'desc')
            ->take(1)
            ->first();
                        
        $detalles = Detalle_SalidaRopa::join('ropas', 'detalle_salidasropas.idropa', '=', 'ropas.id')
            ->select(
                'detalle_salidasropas.cantidad',
                'ropas.nombre_ropa as producto',
                'ropas.talla',
                'ropas.sexo'
            )
            ->where('detalle_salidasropas.idventa', '=', $id)
            ->orderBy('detalle_salidasropas.id', 'desc')
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
    $anchoColumnaTalla = 30;
    $anchoColumnaGenero = 30;

    // Calcular el ancho total de la tabla
    $anchoTabla = $anchoColumnaProducto + $anchoColumnaCantidad + $anchoColumnaTalla + $anchoColumnaGenero;

    // Calcular la posición horizontal para centrar la tabla
    $centrarHorizontal = ($pdf->GetPageWidth() - $anchoTabla) / 2;

    // Establecer posición horizontal para comenzar la tabla
    $pdf->SetX($centrarHorizontal);

    // Crear una tabla para los detalles de la venta
    // Mostrar los títulos "PRODUCTO" y "CANTIDAD" con fondo dorado medio oscuro y letras blancas
    $pdf->SetFillColor(184, 134, 11);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($anchoColumnaProducto, 10, 'PRODUCTO', 1, 0, 'C', true);
    $pdf->Cell($anchoColumnaTalla, 10, 'TALLA', 1, 0, 'C', true);
    $pdf->Cell($anchoColumnaGenero, 10, 'GENERO', 1, 0, 'C', true);
    $pdf->Cell($anchoColumnaCantidad, 10, 'CANTIDAD', 1, 1, 'C', true);

    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetFillColor(255, 255, 255); // Restablecer el color de fondo
    $pdf->SetTextColor(0, 0, 0); // Restablecer el color de texto negro
    foreach ($detalles as $detalle) {
        $cantidad = $detalle->cantidad;
        $nombreArticulo = $detalle->producto;
        $talla = $detalle->talla;
        $genero = $detalle->sexo; 

        // Llenar la tabla con los datos de los productos
        $pdf->SetX($centrarHorizontal);
        $pdf->Cell($anchoColumnaProducto, 10, $nombreArticulo, 1, 0, 'L');
        $pdf->Cell($anchoColumnaTalla, 10, $talla, 1, 0, 'L'); // Nueva columna "Talla"
        $pdf->Cell($anchoColumnaGenero, 10, $genero, 1, 0, 'L');
        $pdf->Cell($anchoColumnaCantidad, 10, $cantidad, 1, 1, 'C');
    }

    // Calcular el total de productos
    $pdf->SetX($centrarHorizontal);
    $totalP = $detalles->sum('cantidad');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($anchoColumnaProducto + $anchoColumnaTalla + $anchoColumnaGenero, 10, 'Total de productos:', 1, 0, 'R');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell($anchoColumnaCantidad, 10, $totalP, 1, 1, 'C');

    $pdf->Cell($mitadAnchoPagina - 10, 18, '', 0, 1, 'C', true);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Dios te bendiga :)", 0, 1, 'C');


    $pdfPath = public_path('docs/donacionRopa.pdf');
    $pdf->Output($pdfPath, 'F');

    return response()->download($pdfPath);
    }
}
