<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    protected $table = 'eventos';
    protected $fillable = ['nombre_evento'];

    public function salidas()
    {
        return $this->hasMany('App\SalidaProducto');
    }
}