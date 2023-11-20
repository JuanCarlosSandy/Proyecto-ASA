<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrada_producto extends Model
{
    protected $table = 'entrada_productos';

    protected $fillable =[
        'idPersona',
        'idDonador',
    ];
    
}
