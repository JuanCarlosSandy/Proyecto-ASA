<?php

namespace App\Http\Controllers;

use App\Donador;
use App\Entrada_producto;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaProductoController extends Controller
{
    public function index(){
        
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
            $registro->idProducto=$producto->id;
            $registro->idDonador =$donador->id;
            $registro->save();
            return response()->json('producto registrado');
        }
        else{
            return response()->json(['error' => 'no existe donador'], 400);
        }
        
        /*$nuevo_producto = new Producto();
        $nuevo_producto->idProducto = $request->input('idProducto');
        

        $producto = Producto::find($nuevo_producto->idProducto);
        if ($producto){
            $nuevo_producto->cantidad= $request->input('cantidad');
        }*/

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
    public function update(Request $request, $id){

    }   
    public function destroy($id){

    }
}
