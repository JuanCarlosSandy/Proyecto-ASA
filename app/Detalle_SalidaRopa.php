<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_SalidaRopa extends Model
{
    protected $table = "detalle_salidasropas";
    protected $fillable = [
        'idventa',
        'idproducto',
        'cantidad'
    ] ;

    public $timestamps = false;
}

