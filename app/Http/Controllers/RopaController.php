<?php

namespace App\Http\Controllers;

use App\CategoriaRopa;
use App\Ropa;
use App\TallaRopa;
use Exception;
use Illuminate\Http\Request;

class RopaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $ropas = Ropa::join('talla_ropas','ropas.idTalla','=','talla_ropas.id')
                        ->join('categoria_ropas','ropas.idEstacion',"=","categoria_ropas.id")
                        ->select('ropas.id','ropas.nombre_ropa','ropas.cantidad','ropas.sexo','talla_ropas.talla as talla','talla_ropas.id as idTallas','categoria_ropas.estacion as estacion','categoria_ropas.id as idCategoriaRopas')
                        ->orderBy('ropas.id', 'desc')->paginate(3);
        }
        else{
            $ropas = Ropa::select('ropas.*')
            ->where('ropas.' .$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ropas.id', 'desc')->paginate(3);
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
        $ropa->idTalla = $request->input('idTallas');
        $ropa->idEstacion = $request->input('idCategoriaRopa');
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
        $ropa = Ropa::findOrFail($request->input('id'));
        $ropa->nombre_ropa = $request->input('nombre_producto');
        $ropa->cantidad = $request->input('cantidad');
        $ropa->sexo = $request->input('sexo');
        $ropa->idTalla = $request->input('idTallas');
        $ropa->idEstacion = $request->input('idCategoriaRopa');
        $ropa->save();
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
}
