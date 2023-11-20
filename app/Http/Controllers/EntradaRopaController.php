<?php

namespace App\Http\Controllers;

use App\Donador;
use App\Entrada_ropa;
use App\Ropa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\ElseIf_;
use FPDF;
use Carbon\Carbon;

class EntradaRopaController extends Controller
{
    public function index(Request $request){  
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar == '') {
            /*$entrada = Ropa::with(['donador','Donador.persona'])
        ->paginate(10);*/
        $entrada = DB::table('entrada_ropas')
                    ->join('ropas', 'entrada_ropas.idRopa', '=', 'ropas.id')
                    ->join('donadores', 'entrada_ropas.idDonador', '=', 'donadores.id')
                    ->join('personas', 'donadores.idPersona', '=', 'personas.id')
                    ->join('users','entrada_ropas.encargadoRegistro','=','users.id')
                    ->select('ropas.nombre_ropa','ropas.id as idRopa','entrada_ropas.id as idEntradaRopa','ropas.talla','ropas.sexo','donadores.idPersona', 'personas.nombre','entrada_ropas.cantidad','entrada_ropas.created_at','users.usuario as encargado')
                    ->orderBy('entrada_ropas.id','desc')
                    ->paginate(10);
        }
        else {
            $entrada = DB::table('entrada_ropas')
                    ->join('ropas', 'entrada_ropas.idRopa', '=', 'ropas.id')
                    ->join('donadores', 'entrada_ropas.idDonador', '=', 'donadores.id')
                    ->join('personas', 'donadores.idPersona', '=', 'personas.id')
                    ->join('users','entrada_ropas.encargadoRegistro','=','personas.id')
                    ->select('ropas.nombre_ropa','ropas.id as idRopa','entrada_ropas.id as idEntradaRopa','ropas.talla','ropas.sexo','donadores.idPersona','personas.nombre','entrada_ropas.cantidad','entrada_ropas.created_at','users.usuario as encargado')
                    ->orderBy('entrada_ropas.id','desc')
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
        $perfil =Auth::user()->id;
        $registro = new Entrada_ropa();
        $donador=Donador::find($request->input('idDonador'));
        if ($donador){
            $producto = Ropa::find($request->input('idRopa'));

            if($producto && $producto->talla==$request->input('talla') && $producto->sexo==$request->input('sexo')){
                $producto->cantidad+=$request->input('cantidad');
                $producto->save();
            }
            else{
                $producto=new Ropa();
                $producto->nombre_ropa=$request->input('nombre_ropa');
                $producto->cantidad=$request->input('cantidad');
                $producto->sexo =$request->input('sexo');
                $producto->talla=$request->input('talla');
                $producto->save();
            }
            $registro->cantidad=$request->input('cantidad');
            $registro->idRopa=$producto->id;
            $registro->idDonador=$donador->id;
            $registro->encargadoRegistro=$perfil; 
            $registro->save();
        }
    }

    public function show(){
        $posts = Ropa::with('donador')->get();
        return response()->json($posts);
    }
    public function edit($id){

    }   

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Ropa::findOrFail($request->idRopa);
        $entradaProducto = Entrada_ropa::findOrFail($request->idEntradaRopa);
        if ($producto->sexo == $request->sexo && $producto->talla == $request->talla) {
            $producto->nombre_ropa = $request->nombreRopa;
                    $producto->cantidad -= $entradaProducto->cantidad;
                    $entradaProducto->cantidad = $request->cantidad;
                    $producto->cantidad+=$request->cantidad;
                    $entradaProducto->save();
                    $producto->save();
        }
        
        
    }  

    public function eliminar(Request $request, $id, $idRopa){
        if (!$request->ajax()) return redirect('/');

        try {
            $entradaProducto = Entrada_ropa::findOrFail($id);
            $producto = Ropa::findOrFail($idRopa);
            $producto->cantidad -= $entradaProducto->cantidad;
            $producto->save();
            $entradaProducto->delete();

            return response()->json(['message' => 'Producto eliminado correctamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo eliminar el producto'], 500);
        }
    }
    public function registroAutentificacion(){
        $perfil =Auth::user()->id;

        return $perfil;
    }

    public function imprimirDonacion()
    {
        $venta = DB::table('entrada_ropas')
            ->join('ropas', 'entrada_ropas.idRopa', '=', 'ropas.id')
            ->join('donadores', 'entrada_ropas.idDonador', '=', 'donadores.id')
            ->join('personas', 'donadores.idPersona', '=', 'personas.id')
            ->select(
                'ropas.nombre_ropa',
                'ropas.id as idRopa',
                'entrada_ropas.id as idEntradaRopa', 
                'ropas.talla',
                'ropas.sexo',
                'donadores.idPersona', 
                'personas.nombre',
                'entrada_ropas.cantidad',
                'entrada_ropas.created_at'
            )
            ->orderBy('entrada_ropas.id','desc')
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
            $anchoColumnaTalla = 30;
            $anchoColumnaGenero = 30;
            $anchoColumnaFecha = 40;
            $anchoColumnaCantidad = 30;
        
            $anchoTabla = $anchoColumnaDonador + $anchoColumnaProducto + $anchoColumnaTalla + $anchoColumnaGenero + $anchoColumnaFecha + $anchoColumnaCantidad;
            $centrarHorizontal = ($pdf->GetPageWidth() - $anchoTabla) / 2;
            
            $pdf->SetX($centrarHorizontal);
        
            $pdf->SetFillColor(184, 134, 11);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($anchoColumnaDonador, 10, 'Donador', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaProducto, 10, 'Producto', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaTalla, 10, 'Talla', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaGenero, 10, 'Genero', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaFecha, 10, 'Fecha Registro', 1, 0, 'C', true);
            $pdf->Cell($anchoColumnaCantidad, 10, 'Cantidad', 1, 1, 'C', true);
        
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);
        
            foreach ($venta as $detalle) {
                $donador = $detalle->nombre;
                $cantidad = $detalle->cantidad;
                $nombreArticulo = $detalle->nombre_ropa;
                $talla = $detalle->talla;
                $genero = $detalle->sexo;
                $fechadonacion = Carbon::parse($detalle->created_at)->format('Y-m-d');
        
                $pdf->SetX($centrarHorizontal);
                $pdf->Cell($anchoColumnaDonador, 10, $donador, 1);
                $pdf->Cell($anchoColumnaProducto, 10, $nombreArticulo, 1);
                $pdf->Cell($anchoColumnaTalla, 10, $talla, 1);
                $pdf->Cell($anchoColumnaGenero, 10, $genero, 1);
                $pdf->Cell($anchoColumnaFecha, 10, $fechadonacion, 1);
                $pdf->Cell($anchoColumnaCantidad, 10, $cantidad, 1, 1);
            }
        
            $pdf->SetX($centrarHorizontal + $anchoColumnaDonador + $anchoColumnaProducto + $anchoColumnaTalla + $anchoColumnaGenero);
            $totalP = $venta->sum('cantidad');
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($anchoColumnaFecha, 10, 'Total de productos:', 0, 0, 'R');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell($anchoColumnaCantidad, 10, $totalP, 0, 1, 'L');
        
            $pdf->Cell($mitadAnchoPagina - 10, 18, '', 0, 1, 'C', true);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, "Dios te bendiga :)", 0, 1, 'C');
        
            $pdfPath = public_path('docs/entradaRopa.pdf');
            $pdf->Output($pdfPath, 'F');
            return response()->download($pdfPath);
    }

}
