<?php

namespace App\Http\Controllers;

use App\Detalle_SalidaRopa;
use App\Eventos;
use App\Ropa;
use App\SalidaRopas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalidaRopasController extends Controller
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
        $usuario = Auth::user();

        if ($buscar == '') {
            $ventas = SalidaRopas::join('users', 'salidas_ropas.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_ropas.evento', '=', 'eventos.id')
                ->select(
                    'salidas_ropas.id',
                    'eventos.nombre_evento as evento',
                    'salidas_ropas.fecha_hora',
                    'users.usuario'
                )
                ->orderBy('salidas_ropas.id', 'desc')->paginate(10);
        } else {
            $ventas = SalidaRopas::join('users', 'salidas_ropas.encargadoRegistro', '=', 'users.id')
                ->join('eventos', 'salidas_ropas.evento', '=', 'eventos.id')
                ->select(
                    'salidas_ropas.id',
                    'eventos.nombre_evento as evento',
                    'salidas_ropas.fecha_hora',
                    'users.usuario'
                )
                ->where('salidas_ropas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('salidas_ropas.id', 'desc')->paginate(10);
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

    public function store(Request $request)
    {
        if (!$request->ajax())
        return redirect('/');

        //try {
          //  DB::beginTransaction();

            $detalles = $request->data; //Array de detalles
            $perfil =Auth::user()->id;
            
            $venta = new SalidaRopas();            
            $venta->encargadoRegistro = $perfil;
            $venta->evento = $request->evento;
            $venta->fecha_hora = now()->setTimezone('America/La_Paz');
            $venta->save();

                        foreach ($detalles as $ep => $det) {
                            $disminuirStock = Ropa::where('id', $det['idRopa'])->firstOrFail();
                            if ($disminuirStock->cantidad >= $det['cantidad']) {

                                $disminuirStock->cantidad -= $det['cantidad'];
                                $disminuirStock->save();
                                $detalle = new Detalle_SalidaRopa();
                                $detalle->idventa = $venta->id;
                                $detalle->idropa = $det['idRopa'];
                                $detalle->cantidad = $det['cantidad'];
                                $detalle->save();
                            }
                        }
            
            
                        

                        //$fechaActual = date('Y-m-d');
                        //$numVentas = DB::table('salidas_productos')->whereDate('created_at', $fechaActual)->count();

                        //DB::commit();
                        //return Response()->json(['detalles' => $detalles]);

    }
    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $venta = SalidaRopas::join('users', 'salidas_ropas.encargadoRegistro', '=', 'users.id')
            ->join('eventos', 'salidas_ropas.evento', '=', 'eventos.id')
            ->select(
                'salidas_ropas.id',
                'eventos.nombre_evento as evento',
                'salidas_ropas.fecha_hora',
                'users.usuario'
            )
            ->where('salidas_ropas.id', '=', $id)
            ->orderBy('salidas_ropas.id', 'desc')->take(1)->get();

        return ['venta' => $venta];
    }
    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $detalles = Detalle_SalidaRopa::join('ropas', 'detalle_salidasropas.idropa', '=', 'ropas.id')
            ->select(
                'detalle_salidasropas.cantidad',
                'ropas.nombre_ropa',
                'ropas.talla',
                'ropas.sexo'
            )
            ->where('detalle_salidasropas.idventa', '=', $id)
            ->orderBy('detalle_salidasropas.id', 'desc')->get();

        return ['detalles' => $detalles];
    }
    public function obtenerDatosEvento(Request $request){
        if (!$request->ajax()) return redirect('/');

        $categorias = Eventos::select('id', 'nombre_evento')
        ->orderBy('id', 'desc')
        ->get();

        return ['categorias' => $categorias];

    }
}
