<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable =[
        'idCategoria_Alimentos','nombre_producto','cantidad',
    ];
    public function categoria(){
        return $this->belongsTo('App\Categoria_Alimentos');
    }
}
