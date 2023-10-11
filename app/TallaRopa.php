<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TallaRopa extends Model
{
    protected $table = 'talla_ropas';
    protected $fillable = ['talla'];

    public function ropas()
    {
        return $this->hasMany('App\Ropa');
    }
}
