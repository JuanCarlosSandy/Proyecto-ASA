<?php

namespace App\Http\Controllers;

use App\Donador;
use App\Entrada_producto;
use App\Categoria_Alimentos;
use App\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use FPDF;
use Carbon\Carbon;

class EntradaProductoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar == '') {
            $entrada = DB::table('entrada_productos')
            ->join('productos', 'entrada_productos.idProducto', '=', 'productos.id')
            ->join('donadores', 'entrada_productos.idDonador', '=', 'donadores.id')
            ->join('personas', 'donadores.idPersona', '=', 'personas.id')
            ->join('categoria_alimentos', 'productos.idCategoria_Alimentos', '=', 'categoria_alimentos.id')
            ->join('users','entrada_productos.encargadoRegistro','=','users.id')
            ->select('productos.nombre_producto','productos.id as idProducto','entrada_productos.id as idEntradaProducto', 'donadores.idPersona', 'personas.nombre','categoria_alimentos.tipo_producto as categoria','entrada_productos.cantidad','entrada_productos.created_at','users.usuario as encargado')
            ->orderBy('entrada_productos.id','desc')
            ->paginate(10);

        }
        else {
            $entrada = DB::table('entrada_productos')
            ->join('productos', 'entrada_productos.idProducto', '=', 'productos.id')
            ->join('donadores', 'entrada_productos.idDonador', '=', 'donadores.id')
            ->join('personas', 'donadores.idPersona', '=', 'personas.id')
            ->join('categoria_alimentos', 'productos.idCategoria_Alimentos', '=', 'categoria_alimentos.id')
            ->join('users','entrada_productos.encargadoRegistro','=','users.id')
            ->select('productos.nombre_producto','productos.id as idProducto','entrada_productos.id as idEntradaProducto', 'donadores.idPersona', 'personas.nombre','categoria_alimentos.tipo_producto as categoria','entrada_productos.cantidad','entrada_productos.created_at','users.usuario as encargado')
            ->orderBy('entrada_productos.id','desc')
            ->paginate(10);

        }
        return [
            'pagination' => [
                'total' => $entrada->total(),
                'current_page' => $entrada->currentPage(),
                'per_page' => $entrada->perPage(),
                'last_page' => $entrada->lastPage(),
                'from' => $entrada->firstItem(),
                'to' => $entrada->lastItem(),
            ],
            'entradas' => $entrada->items(),
        ];

    }

    public function store(Request $request){
        $registro = new Entrada_producto();
        $donador=Donador::find($request->input('idDonador'));
        if ($donador){
            $producto = Producto::find($request->input('idProducto'));

            if($producto){
                $producto->cantidad+=$request->input('cantidad');
                $producto->save();
            }
            else{
                $producto = new Producto();
                $producto->idCategoria_Alimentos=$request->input('idCategoria_Alimentos');
                $producto->nombre_producto=$request->input('nombre_producto');
                $producto->cantidad =$request->input('cantidad');
                $producto->save();
            }
            $registro->cantidad=$request->input('cantidad');
            $registro->idProducto=$producto->id;
            $registro->idDonador =$donador->id;
            $registro->save();
        }
    }

    public function show(){
        /*$entrada = Producto::with('donador')->get();
        return response()->json( $entrada);*/

        $entrada = DB::table('entrada_productos')
                    ->join('productos', 'entrada_productos.idProducto', '=', 'productos.id')
                    ->join('donadores', 'entrada_productos.idDonador', '=', 'donadores.id')
                    ->join('personas', 'donadores.idPersona', '=', 'personas.id')
                    ->select('productos.*', 'donadores.*', 'personas.*')
                    ->orderBy('entrada_productos.id','desc')
                    ->paginate(10);
                    return [
                        'pagination' => [
                            'total' => $entrada->total(),
                            'current_page' => $entrada->currentPage(),
                            'per_page' => $entrada->perPage(),
                            'last_page' => $entrada->lastPage(),
                            'from' => $entrada->firstItem(),
                            'to' => $entrada->lastItem(),
                        ],
                        'entradas' => $entrada->items(),
                    ];
        
    }
    public function edit($id){
        
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->idProducto);
        $entradaProducto = Entrada_producto::findOrFail($request->idEntradaProducto);
        $producto->nombre_producto = $request->nombre_producto;


        $producto->cantidad -= $entradaProducto->cantidad;
        $entradaProducto->cantidad = $request->cantidad;
        $producto->cantidad+=$request->cantidad;
        $entradaProducto->save();
        $producto->save();
    }  
    public function eliminar(Request $request, $id, $idProducto){
        if (!$request->ajax()) return redirect('/');

        try {
            $entradaProducto = Entrada_producto::findOrFail($id);
            $producto = Producto::findOrFail($idProducto);
            $producto->cantidad -= $entradaProducto->cantidad;
            $producto->save();
            $entradaProducto->delete();

            return response()->json(['message' => 'Producto eliminado correctamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo eliminar el producto'], 500);
        }
    }

    public function imprimirDonacion()
    {
        $venta = DB::table('entrada_productos')
            ->join('productos', 'entrada_productos.idProducto', '=', 'productos.id')
            ->join('donadores', 'entrada_productos.idDonador', '=', 'donadores.id')
            ->join('personas', 'donadores.idPersona', '=', 'personas.id')
            ->join('categoria_alimentos', 'productos.idCategoria_Alimentos', '=', 'categoria_alimentos.id')            
            ->select(
                'productos.nombre_producto',
                'productos.id as idProducto',
                'entrada_productos.id as idEntradaProducto', 
                'donadores.idPersona', 
                'personas.nombre',
                'categoria_alimentos.tipo_producto as categoria',
                'entrada_productos.cantidad',
                'entrada_productos.created_at'
            )
            ->orderBy('entrada_productos.id','desc')
            ->get();

            $pdf = new FPDF();
            $fechaActual = now()->setTimezone('America/La_Paz');
            
            $pdf->AddPage('P', 'Letter');
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(true, 10);
        
            $rutaImagen = public_path('img\logoasagrande.png');
            $pdf->Image($rutaImagen, $pdf->GetPageWidth() - 50, 10, 40);
        
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'REPORTE DE DONACION', 0, 1, 'C');
            $pdf->Cell(0, 10, "Fecha: $fechaActual", 0, 1, 'C');
        
            $mitadAnchoPagina = $pdf->GetPageWidth() / 2;
            
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell($mitadAnchoPagina - 10, 10, '', 0, 1, 'C', true);
            $pdf->Cell($mitadAnchoPagina - 10, 10, '', 0, 1, 'C', true);
        
            $anchoColumnaDonador = 50;
            $anchoColumnaProducto = 30;
            $anchoColumnaCategoria = 30;
            $anchoColumnaFecha = 40;
            $anchoColumnaCantidad = 30;
        
            $anchoTabla = $anchoColumnaDonador + $anchoColumnaProducto + $anchoColumnaCategoria + $anchoColumnaFecha + $anchoColumnaCantidad;
            $centrarHorizontal = ($pdf->GetPageWidth() - $anchoTabla) / 2;
            
            $pdf->SetX($centrarHorizontal);
        
            $pdf->SetFillColor(184, 134, 11);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($anchoColumnaDonador, 10, 'Donador', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaProducto, 10, 'Producto', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaCategoria, 10, 'Categoria', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaFecha, 10, 'Fecha Registro', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaCantidad, 10, 'Cantidad', 1, 1, 'C', true);
        
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);
        
            foreach ($venta as $detalle) {
                $donador = $detalle->nombre;
                $cantidad = $detalle->cantidad;
                $nombreArticulo = $detalle->nombre_producto;
                $categoria = $detalle->categoria;
                $fechadonacion = Carbon::parse($detalle->created_at)->format('Y-m-d');
        
                $pdf->SetX($centrarHorizontal);
                $pdf->Cell($anchoColumnaDonador, 10, $donador, 1);
                $pdf->Cell($anchoColumnaProducto, 10, $nombreArticulo, 1);
                $pdf->Cell($anchoColumnaCategoria, 10, $categoria, 1);
                $pdf->Cell($anchoColumnaFecha, 10, $fechadonacion, 1);
                $pdf->Cell($anchoColumnaCantidad, 10, $cantidad, 1, 1);
            }
        
            $pdf->SetX($centrarHorizontal + $anchoColumnaDonador + $anchoColumnaProducto + $anchoColumnaCategoria);
            $totalP = $venta->sum('cantidad');
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($anchoColumnaFecha, 10, 'Total de productos:', 0, 0, 'R');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell($anchoColumnaCantidad, 10, $totalP, 0, 1, 'L');
        
            $pdf->Cell($mitadAnchoPagina - 10, 18, '', 0, 1, 'C', true);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, "Dios te bendiga :)", 0, 1, 'C');
        
            $pdfPath = public_path('docs/entrada.pdf');
            $pdf->Output($pdfPath, 'F');
            return response()->download($pdfPath);
    }
}