<?php

namespace App\Http\Controllers;

use App\Donador;
use App\Persona;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonadorController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personas = Donador::join('personas','donadores.idPersona','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.telefono','personas.email','donadores.id as idDonador')
            ->orderBy('personas.id', 'desc')->paginate(10);
        }
        else{
            $personas = Donador::join('personas','donadores.idPersona','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.telefono','personas.email')
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function store(Request $request){
        
            $persona = new Persona();
            $persona->nombre = $request->input('nombre');
            $persona->tipo_documento = $request->input('tipo_documento');
            $persona->num_documento = $request->input('num_documento');
            $persona->direccion = $request->input('direccion');
            $persona->telefono = $request->input('telefono');
            $persona->email = $request->input('email');
            $persona->save();

            $donador = new Donador();
            $donador->idPersona = $persona->id;
            $donador->save();  
        
    }
    public function update(Request $request){
       

        try{
            $donador = Donador::findOrFail($request->input('id'));
            $persona = Persona::findOrFail($request->input('idPersona'));
            $persona->nombre = $request->input('nombre');
            $persona->tipo_documento = $request->input('tipo_documento');
            $persona->num_documento = $request->input('num_documento');
            $persona->direccion = $request->input('direccion');
            $persona->telefono = $request->input('telefono');
            $persona->email = $request->input('email');
            $persona->save();
        } catch (Exception $e){
           
        }

    }

    public function buscarDonador(Request $request){
        
        $buscar = trim($request->input('ci'));
        if (!empty($buscar) && $buscar!=''){
        $resultados =Donador::join ('personas','donadores.idPersona','=','personas.id')
        ->select('personas.*','donadores.id as idDonador')
        ->where ('personas.num_documento','LIKE',$buscar.'%')->get ();       
        }
        else {
            $resultados =[];
        }
        return ['resultados' => $resultados];
    }

}
