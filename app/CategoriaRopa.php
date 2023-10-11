<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaRopa extends Model
{
    protected $table = 'categoria_ropas';
    protected $fillable = ['estacion'];

    public function ropas()
    {
        return $this->hasMany('App\Ropa');
    }
}
