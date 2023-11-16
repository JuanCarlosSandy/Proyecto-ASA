<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Detalle_SalidaProducto extends Model
{
    protected $table = 'detalle_salidasproductos';
    protected $fillable = [
        'idventa', 
        'idproducto',
        'cantidad',
    ];
    public $timestamps = false;
}