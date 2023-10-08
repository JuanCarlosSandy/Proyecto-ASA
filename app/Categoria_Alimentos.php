<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria_Alimentos extends Model
{
    protected $table = 'categoria_alimentos';
    protected $fillable = ['tipo_producto'];

    public function articulos()
    {
        return $this->hasMany('App\Producto');
    }
}
