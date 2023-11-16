<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Eventos;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $categorias = Eventos::select('eventos.*')
            ->orderBy('eventos.id', 'desc')->paginate(3);
        }
        else{
            $categorias = Eventos::select('eventos.*')
            ->where('eventos.' .$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('eventos.id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $categorias->total(),
                'current_page' => $categorias->currentPage(),
                'per_page'     => $categorias->perPage(),
                'last_page'    => $categorias->lastPage(),
                'from'         => $categorias->firstItem(),
                'to'           => $categorias->lastItem(),
            ],
            'categorias' => $categorias
        ];
    }

    public function selectEvento(Request $request){
        if (!$request->ajax()) return redirect('/');
        $categorias = Eventos::select('id','nombre_evento')->orderBy('id', 'asc')->get();
        return ['categorias' => $categorias];
    }

    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $categoria = new Eventos();
        $categoria->id = $request->id;
        $categoria->nombre_evento = $request->nombre_evento;
        $categoria->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Eventos::findOrFail($request->id);
        $categoria->nombre_evento = $request->nombre_evento;
        $categoria->save();
    }
}
