<?php

namespace App\Http\Controllers;

use App\Donador;
use App\Entrada_ropa;
use App\Ropa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                    ->select('ropas.nombre_ropa','ropas.talla','ropas.sexo','donadores.idPersona', 'personas.nombre','entrada_ropas.cantidad','entrada_ropas.created_at','users.usuario as encargado')
                    ->orderBy('entrada_ropas.id','desc')
                    ->paginate(10);
        }
        else {
            $entrada = DB::table('entrada_ropas')
                    ->join('ropas', 'entrada_ropas.idRopa', '=', 'ropas.id')
                    ->join('donadores', 'entrada_ropas.idDonador', '=', 'donadores.id')
                    ->join('personas', 'donadores.idPersona', '=', 'personas.id')
                    ->join('users','entrada_ropas.encargadoRegistro','=','personas.id')
                    ->select('ropas.nombre_ropa','ropas.talla','ropas.sexo','donadores.idPersona','personas.nombre','entrada_ropas.cantidad','entrada_ropas.created_at','users.usuario as encargado')
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

    public function update(Request $request, $id){


    }

    public function destroy($id){

    }
    public function registroAutentificacion(){
        $perfil =Auth::user()->id;

        return $perfil;
    }

}
