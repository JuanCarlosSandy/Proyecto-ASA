<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalidaRopas extends Model
{
    protected $table = "salidas_ropas";

    protected $fillable = ["encargadoRegistro","evento","fecha_hora"];

    public function evento(){
        return $this->belongsTo("App\Eventos");
    }
}
