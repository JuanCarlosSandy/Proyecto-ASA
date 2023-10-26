<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donador extends Model
{
    protected $table = 'donadores';
    protected $fillable =[
        'idPersona'
    ];

    /*public function producto(){
        return $this->belongsTo('App\Producto');
    }*/
    public function producto()
    {
        return $this->belongsToMany(Producto::class, 'entrada_productos','idDonador','idProducto');
    }
    public function ropa()
    {
        return $this->belongsToMany(Ropa::class, 'entrada_ropas','idDonador','idRopa');
    }
    public function persona(){
        return $this->belongsTo('App\Persona', 'idPersona');
    }
}
