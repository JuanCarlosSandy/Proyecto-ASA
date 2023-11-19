<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Http\Request;
 
class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio=date('Y');
        $ingresos=DB::table('entrada_ropas as i')
        ->select(DB::raw('MONTH(i.created_at) as mes'),
        DB::raw('YEAR(i.created_at) as anio'),
        DB::raw('SUM(i.cantidad) as total'))
        ->whereYear('i.created_at',$anio)
        ->groupBy(DB::raw('MONTH(i.created_at)'),DB::raw('YEAR(i.created_at)'))
        ->get();
        
        $ventas = DB::table('detalle_salidasropas')
            ->join('ropas', 'detalle_salidasropas.idropa', '=', 'ropas.id')
            ->join('salidas_ropas', 'detalle_salidasropas.idventa', '=', 'salidas_ropas.id')
            ->select(
                DB::raw('MONTH(fecha_hora) as mes'),
                DB::raw('YEAR(fecha_hora) as anio'),
                DB::raw('SUM(detalle_salidasropas.cantidad) as total')
            )
            ->whereYear('fecha_hora', $anio) // Cambia CURDATE() por now()
            ->groupBy(DB::raw('MONTH(fecha_hora)'), DB::raw('YEAR(fecha_hora)'))
            ->get();

        
        $ingresosProducto=DB::table('entrada_productos as i')
            ->select(DB::raw('MONTH(i.created_at) as mes'),
            DB::raw('YEAR(i.created_at) as anio'),
            DB::raw('SUM(i.cantidad) as total'))
            ->whereYear('i.created_at',$anio)
            ->groupBy(DB::raw('MONTH(i.created_at)'),DB::raw('YEAR(i.created_at)'))
            ->get();

        $ventasProducto = DB::table('detalle_salidasproductos')
            ->join('productos', 'detalle_salidasproductos.idproducto', '=', 'productos.id')
            ->join('salidas_productos', 'detalle_salidasproductos.idventa', '=', 'salidas_productos.id')
            ->select(
                DB::raw('MONTH(fecha_hora) as mes'),
                DB::raw('YEAR(fecha_hora) as anio'),
                DB::raw('SUM(detalle_salidasproductos.cantidad) as total')
            )
            ->whereYear('fecha_hora', $anio) // Cambia CURDATE() por now()
            ->groupBy(DB::raw('MONTH(fecha_hora)'), DB::raw('YEAR(fecha_hora)'))
            ->get(); 
        /**$ventas=DB::table('ventas as v')
        ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
        DB::raw('YEAR(v.fecha_hora) as anio'),
        DB::raw('SUM(v.total) as total'))
        ->whereYear('v.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(v.fecha_hora)'),DB::raw('YEAR(v.fecha_hora)'))
        ->get();*/
 
        return ['ingresos'=>$ingresos,'ventas'=>$ventas,'ingresosProducto'=>$ingresosProducto,'ventasProductos'=>$ventasProducto,'anio'=>$anio];      
 
    }
}