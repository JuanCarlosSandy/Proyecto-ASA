<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donador extends Model
{
    protected $table = 'donadores';
    protected $fillable =[
        'idPersona'
    ];

    public function producto(){
        return $this->belongsTo('App\Producto');
    }
    public function ropa(){
        return $this->belongsTo('App\Ropa');
    }
}
