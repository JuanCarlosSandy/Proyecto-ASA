<?php

namespace App\Http\Controllers;

use App\Detalle_SalidaProducto;
use App\SalidaProducto;
use App\Producto;
use App\Eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalidaProductosController extends Controller
{

    public function __construct()
    {
        session_start();
    }

    public function index(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $usuario = \Auth::user();

        if ($buscar == '') {
            $ventas = SalidaProducto::join('users', 'salidas_productos.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_productos.evento', '=', 'eventos.id')
                ->select(
                    'salidas_productos.id',
                    'eventos.nombre_evento as evento',
                    'salidas_productos.fecha_hora',
                    'users.usuario'
                )
                ->orderBy('salidas_productos.id', 'desc')->paginate(3);
        } else {
            $ventas = SalidaProducto::join('users', 'salidas_productos.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_productos.evento', '=', 'eventos.id')
                ->select(
                    'salidas_productos.id',
                    'eventos.nombre_evento as evento',
                    'salidas_productos.fecha_hora',
                    'users.usuario'
                )
                ->where('salidas_productos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('salidas_productos.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem(),
            ],
            'ventas' => $ventas,
            'usuario' => $usuario
        ];
    }

    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $venta = SalidaProducto::join('users', 'salidas_productos.encargadoRegistro', '=', 'users.id')
            ->join('eventos', 'salidas_productos.evento', '=', 'eventos.id')
            ->select(
                'salidas_productos.id',
                'eventos.nombre_evento as evento',
                'salidas_productos.fecha_hora',
                'users.usuario'
            )
            ->where('salidas_productos.id', '=', $id)
            ->orderBy('salidas_productos.id', 'desc')->take(1)->get();

        return ['venta' => $venta];
    }

    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $detalles = Detalle_SalidaProducto::join('productos', 'detalle_salidasproductos.idproducto', '=', 'productos.id')
            ->select(
                'detalle_salidasproductos.cantidad',
                'productos.nombre_producto as producto'
            )
            ->where('detalle_salidasproductos.idventa', '=', $id)
            ->orderBy('detalle_salidasproductos.id', 'desc')->get();

        return ['detalles' => $detalles];
    }

    public function obtenerDatosEvento(Request $request){
        if (!$request->ajax()) return redirect('/');

        $categorias = Eventos::select('id', 'nombre_evento')
        ->orderBy('id', 'desc')
        ->get();

        return ['categorias' => $categorias];

    }

    public function store(Request $request)
    {
        if (!$request->ajax())
        return redirect('/');

        //try {
          //  DB::beginTransaction();

            $detalles = $request->data; //Array de detalles
            $perfil =Auth::user()->id;
            $venta = new SalidaProducto();            
                $venta->encargadoRegistro = $perfil;
                $venta->evento = $request->evento;
                $venta->fecha_hora = now()->setTimezone('America/La_Paz');
                $venta->save();

                        foreach ($detalles as $ep => $det) {

                            $disminuirStock = Producto::where('id', $det['idarticulo'])->firstOrFail();
                            $disminuirStock->cantidad -= $det['cantidad'];
                            $disminuirStock->save();

                            $detalle = new Detalle_SalidaProducto();
                            $detalle->idventa = $venta->id;
                            $detalle->idproducto = $det['idarticulo'];
                            $detalle->cantidad = $det['cantidad'];
                            $detalle->save();
                        }
                        

                        //$fechaActual = date('Y-m-d');
                        //$numVentas = DB::table('salidas_productos')->whereDate('created_at', $fechaActual)->count();

                        //DB::commit();
                        //return Response()->json(['detalles' => $detalles]);

            }
        //}catch (Exception $e) 
          //  {
            //    DB::rollBack();
            //}

    }
