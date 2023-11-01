<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Producto;
use App\Donador;
use App\Persona;
use App\Categoria_Alimentos;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
                $productos = Producto::join('categoria_alimentos', 'productos.idCategoria_Alimentos', '=', 'categoria_alimentos.id')
                    ->select(
                        'productos.*', 'categoria_alimentos.tipo_producto as categoria'
                    )
                ->orderBy('productos.id', 'desc')->paginate(3);
        } else {
            $productos = Producto::join('categoria_alimentos', 'productos.idCategoria_Alimentos', '=', 'categoria_alimentos.id')
                ->select(
                    'productos.*', 'categoria_alimentos.tipo_producto as categoria'
                )
                ->where('productos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('productos.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total' => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page' => $productos->perPage(),
                'last_page' => $productos->lastPage(),
                'from' => $productos->firstItem(),
                'to' => $productos->lastItem(),
            ],
            'productos' => $productos
        ];
    }

    
    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $producto = new Producto();
        $producto->idCategoria_Alimentos = $request->idCategoria_Alimentos;
        $producto->nombre_producto = $request->nombre_producto;
        $producto->cantidad = $request->cantidad;
        $producto->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->nombre_producto = $request->nombre_producto;
        $producto->cantidad = $request->cantidad;
        $producto->idCategoria_Alimentos = $request->idCategoria_Alimentos;
        $producto->save();
    }

    public function obtenerDatosCategoria(Request $request){
        if (!$request->ajax()) return redirect('/');

        $categorias = Categoria_Alimentos::select('id', 'tipo_producto')
        ->orderBy('id', 'desc')
        ->get();

        return ['categorias' => $categorias];

    }

    public function buscarPersona(Request $request)
    {
        $ci = $request->input('num_documento');
        $personas = Donador::join('personas', 'donadores.idPersona', '=', 'personas.id')
        ->select('donadores.id', 'personas.num_documento', 'personas.nombre')
        ->where('personas.num_documento', 'like', $ci . '%')->get();

        return ['personas' => $personas];
    }   

    public function buscarProducto(Request $request){    
        $buscar = $request->input('nombre_producto');
        if ($buscar!=''){
        $resultados = Producto::select('productos.*')
        ->where ('productos.nombre_producto', 'like', $buscar. '%')->get ();
        }
        else {
            $resultados =[];
        }
        return ['resultados' => $resultados];
    }   

    public function eliminar(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return response()->json(['message' => 'Producto eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo eliminar el producto'], 500);
        }
    }

}