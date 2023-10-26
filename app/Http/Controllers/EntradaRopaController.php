<?php

namespace App\Http\Controllers;

use App\Donador;
use App\Entrada_ropa;
use App\Ropa;
use Illuminate\Http\Request;

class EntradaRopaController extends Controller
{
    public function index(Request $request){

        
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar == '') {
            $entrada = Ropa::with(['donador','Donador.persona'])
                        ->paginate(3);
        }
        else {
            $entrada = Ropa::with(['donador','Donador.persona'])->paginate(3);

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
        $registro = new Entrada_ropa();
        $donador=Donador::find($request->input('idDonador'));
        if ($donador){
            $producto = Ropa::find($request->input('idRopa'));

            if($producto && $producto->talla==$request->input('talla') && $producto->sexo==$request->input('sexo') && $producto->estacion==$request->input('estacion')){
                $producto->cantidad+=$request->input('cantidad');
                $producto->save();
            }
            else{
                $producto=new Ropa();
                $producto->nombre_ropa=$request->input('nombre_ropa');
                $producto->cantidad=$request->input('cantidad');
                $producto->sexo =$request->input('sexo');
                $producto->talla=$request->input('talla');
                $producto->estacion = $request->input('estacion');
                $producto->save();
            }
            $registro->idRopa=$producto->id;
            $registro->idDonador=$donador->id;
            $registro->save();
        }
    }

    public function show(){
        $posts = Ropa::with('donador')->get();
        return response()->json($posts);
    }
    public function edit($id){

    }   

    public function update(Request $request, $id){


    }

    public function destroy($id){

    }
}
