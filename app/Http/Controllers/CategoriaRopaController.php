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
use App\CategoriaRopa;

class CategoriaRopaController extends Controller
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
            $categorias = CategoriaRopa::select('categoria_ropas.*')
            ->orderBy('categoria_ropas.id', 'desc')->paginate(3);
        }
        else{
            $categorias = CategoriaRopa::select('categoria_ropas.*')
            ->where('categoria_ropas.' .$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('categoria_ropas.id', 'desc')->paginate(3);
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

    public function selectCategoria(Request $request){
        if (!$request->ajax()) return redirect('/');
        $categorias = CategoriaRopa::select('id','estacion')->orderBy('id', 'asc')->get();
        return ['categorias' => $categorias];
    }

    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $categoria = new CategoriaRopa();
        $categoria->id = $request->id;
        $categoria->estacion = $request->estacion;
        $categoria->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = CategoriaRopa::findOrFail($request->id);
        $categoria->estacion = $request->estacion;
        $categoria->save();
    }
}
