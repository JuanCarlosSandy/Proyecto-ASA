<?php

namespace App\Http\Controllers;

use App\Donador;
use App\Entrada_producto;
use App\Categoria_Alimentos;
use App\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class EntradaProductoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar == '') {
            $entrada = DB::table('entrada_productos')
            ->join('productos', 'entrada_productos.idProducto', '=', 'productos.id')
            ->join('donadores', 'entrada_productos.idDonador', '=', 'donadores.id')
            ->join('personas', 'donadores.idPersona', '=', 'personas.id')
            ->join('categoria_alimentos', 'productos.idCategoria_Alimentos', '=', 'categoria_alimentos.id')
            ->join('users','entrada_productos.encargadoRegistro','=','users.id')
            ->select('productos.nombre_producto','productos.id as idProducto','entrada_productos.id as idEntradaProducto', 'donadores.idPersona', 'personas.nombre','categoria_alimentos.tipo_producto as categoria','entrada_productos.cantidad','entrada_productos.created_at','users.usuario as encargado')
            ->orderBy('entrada_productos.id','desc')
            ->paginate(10);

        }
        else {
            $entrada = DB::table('entrada_productos')
            ->join('productos', 'entrada_productos.idProducto', '=', 'productos.id')
            ->join('donadores', 'entrada_productos.idDonador', '=', 'donadores.id')
            ->join('personas', 'donadores.idPersona', '=', 'personas.id')
            ->join('categoria_alimentos', 'productos.idCategoria_Alimentos', '=', 'categoria_alimentos.id')
            ->join('users','entrada_productos.encargadoRegistro','=','users.id')
            ->select('productos.nombre_producto','productos.id as idProducto','entrada_productos.id as idEntradaProducto', 'donadores.idPersona', 'personas.nombre','categoria_alimentos.tipo_producto as categoria','entrada_productos.cantidad','entrada_productos.created_at','users.usuario as encargado')
            ->orderBy('entrada_productos.id','desc')
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
            $registro->cantidad=$request->input('cantidad');
            $registro->idProducto=$producto->id;
            $registro->idDonador =$donador->id;
            $registro->save();
        }
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
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->idProducto);
        $entradaProducto = Entrada_producto::findOrFail($request->idEntradaProducto);
        $producto->nombre_producto = $request->nombre_producto;


        $producto->cantidad -= $entradaProducto->cantidad;
        $entradaProducto->cantidad = $request->cantidad;
        $producto->cantidad+=$request->cantidad;
        $entradaProducto->save();
        $producto->save();
    }  
    public function eliminar(Request $request, $id, $idProducto){
        if (!$request->ajax()) return redirect('/');

        try {
            $entradaProducto = Entrada_producto::findOrFail($id);
            $producto = Producto::findOrFail($idProducto);
            $producto->cantidad -= $entradaProducto->cantidad;
            $producto->save();
            $entradaProducto->delete();

            return response()->json(['message' => 'Producto eliminado correctamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo eliminar el producto'], 500);
        }
    }
}