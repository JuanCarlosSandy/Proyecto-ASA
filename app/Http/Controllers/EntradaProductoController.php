<?php

namespace App\Http\Controllers;

use App\Donador;
use App\Entrada_producto;
use App\Producto;
use Illuminate\Http\Request;

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
        $entrada = Producto::with('donador')->get();
        return response()->json( $entrada);
    }
    public function edit($id){
        
    }
    public function update(Request $request, $id){

    }   
    public function destroy($id){

    }
}
