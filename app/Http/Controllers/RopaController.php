<?php

namespace App\Http\Controllers;

use App\CategoriaRopa;
use App\Ropa;
use App\TallaRopa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RopaController extends Controller
{
    public function index(Request $request)
    {
        

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            /*$ropas = Ropa::select('ropas.*')
                        ->orderBy('ropas.nombre_ropa', 'desc')->paginate(10);*/

            $ropas = DB::table('ropas')
                    ->select('nombre_ropa', DB::raw('SUM(cantidad) as total'))
                    ->groupBy('nombre_ropa')
                    ->paginate(10);
        }
        else{
            /*$ropas = Ropa::select('ropas.*')
            ->where('ropas.' .$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ropas.nombre_ropa', 'desc')->paginate(10);*/
            $ropas = DB::table('ropas')
                    ->select('nombre_ropa as nombre', DB::raw('SUM(cantidad) as total'))
                    ->groupBy('nombre_ropa')
                    ->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $ropas->total(),
                'current_page' => $ropas->currentPage(),
                'per_page'     => $ropas->perPage(),
                'last_page'    => $ropas->lastPage(),
                'from'         => $ropas->firstItem(),
                'to'           => $ropas->lastItem(),
            ],
            'ropa' => $ropas
        ];
    }

    public function store(Request $request)
    {
        $ropa = new Ropa();
        $ropa->nombre_ropa = $request->input('nombre_producto');
        $ropa->cantidad = $request->input('cantidad');
        $ropa->sexo = $request->input('sexo');
        $ropa->talla = $request->input('idTallas');
        $ropa->estacion = $request->input('idCategoriaRopa');
        $ropa->save();
    }
    public function obtenerDatosTallas(Request $request){
        if (!$request->ajax()) return redirect('/');

        $tallas = TallaRopa::select('id', 'talla')
        ->orderBy('id', 'desc')
        ->get();

        return ['tallas' => $tallas];

    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try{
        /*
        $ropa->nombre_ropa = $request->input('nombre_producto');
        $ropa->cantidad = $request->input('cantidad');
        $ropa->sexo = $request->input('sexo');
        $ropa->talla = $request->input('idTallas');
        $ropa->save();*/
        $datosAActualizar = $request->input('datos');

        foreach ($datosAActualizar as $dato) {
            Ropa::where('id', $dato['id'])
                ->update(['nombre_ropa' => $request->nombre_ropa]);
        }
        }
        catch (Exception $e){
            dd($e->getMessage());

        }
    }

    public function eliminar(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            $producto = Ropa::findOrFail($id);
            $producto->delete();

            return response()->json(['message' => 'Producto eliminado correctamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo eliminar el producto'], 500);
        }
    }
    public function obtenerDatosCategoriaRopa(Request $request){
        if (!$request->ajax()) return redirect('/');

        $categoriaRopa = CategoriaRopa::select('id', 'estacion')
        ->orderBy('id', 'desc')
        ->get();

        return ['categoriaRopa' => $categoriaRopa];

    }
    public function buscarRopa(Request $request){
        
        $buscar = $request->input('nombre_ropa');
        if ($buscar!=''){
        $resultados =Ropa::select('ropas.nombre_ropa')
        ->where ('ropas.nombre_ropa','LIKE',$buscar.'%')
        ->groupBy('ropas.nombre_ropa')
        ->get ();
        
        }
        else {
            $resultados =[];
        }
        return ['resultados' => $resultados];
    } 
    public function buscarRopaId(Request $request){
        
        $buscar = $request->id;
        if ($buscar!=''){
        $resultados =Ropa::select('ropas.cantidad')
        ->where ('ropas.id','=',$buscar)->get ();
        
        }
        else {
            $resultados =[];
        }
        return ['resultados' => $resultados];
    } 
    public function buscarRopaNombre(Request $request){
        
        $buscar = $request->nombre;
        if ($buscar!=''){
        $resultados =Ropa::select('ropas.id')
        ->where ('ropas.nombre_ropa','=',$buscar)->get ();
        
        }
        else {
            $resultados =[];
        }
        return ['resultados' => $resultados];
    } 
    public function obtenerDetalles(Request $request)
    {
        
        $nombre = $request->nombre;
        $detalles = Ropa::select('ropas.*')
            ->where('ropas.nombre_ropa', '=', $nombre)
            ->orderBy('ropas.id', 'desc')->get();

        return ['detalles' => $detalles];
    }

    public function buscarRopaVenta(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $filtro = $request->filtro;

        $ropas = Ropa::select('ropas.id', 'ropas.nombre_ropa', 'ropas.cantidad as stock','ropas.sexo','ropas.talla')
                    ->where('ropas.id', '=', $filtro)
                    ->orderBy('ropas.nombre_ropa', 'asc')->take(1)->get();
        Log::info('ARTICULO:', [
            'DATA' => $ropas,
        ]);

        return ['ropas' => $ropas];
    }
    public function listarRopaVenta(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $ropas = Ropa::select('ropas.id', 'ropas.nombre_ropa', 'ropas.cantidad', 'ropas.sexo', 'ropas.talla')
                ->where('ropas.cantidad', '>', '0')
                ->orderBy('ropas.id', 'desc')->paginate(10);
        } else {
            $ropas = Ropa::select('ropas.id', 'ropas.nombre_ropa', 'ropas.cantidad', 'ropas.sexo', 'ropas.talla')
                ->where('ropas.' . $criterio, 'like', '%' . $buscar . '%')
                ->where('ropas.cantidad', '>', '0')
                ->orderBy('ropas.id', 'desc')->paginate(10);
        }
        return ['ropas' => $ropas];
    }

    public function ropasBajoStock(Request $request)
    {
       
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $ropasBajo = Ropa::select('ropas.*')
                ->whereRaw('ropas.cantidad < 10')
                ->orderBy('ropas.id', 'desc')->paginate(3);
        } else {
            $ropasBajo = Ropa::select('ropas.*')
                ->whereRaw('ropas.cantidad < 10')
                ->where('ropas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('ropas.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total' => $ropasBajo->total(),
                'current_page' => $ropasBajo->currentPage(),
                'per_page' => $ropasBajo->perPage(),
                'last_page' => $ropasBajo->lastPage(),
                'from' => $ropasBajo->firstItem(),
                'to' => $ropasBajo->lastItem(),
            ],
            'ropasBajo' => $ropasBajo
        ];
    }
}   
