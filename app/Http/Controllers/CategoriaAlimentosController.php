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
use App\Categoria_Alimentos;

class CategoriaAlimentosController extends Controller
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
            $categorias = Categoria_Alimentos::select('categoria_alimentos.*')
            ->orderBy('categoria_alimentos.id', 'desc')->paginate(3);
        }
        else{
            $categorias = Categoria_Alimentos::select('categoria_alimentos.*')
            ->where('categoria_alimentos.' .$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('categoria_alimentos.id', 'desc')->paginate(3);
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
        $categorias = Categoria_Alimentos::select('id','tipo_producto')->orderBy('id', 'asc')->get();
        return ['categorias' => $categorias];
    }

    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $categoria = new Categoria_Alimentos();
        $categoria->id = $request->id;
        $categoria->tipo_producto = $request->tipo_producto;
        $categoria->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria_Alimentos::findOrFail($request->id);
        $categoria->tipo_producto = $request->tipo_producto;
        $categoria->save();
    }
}
