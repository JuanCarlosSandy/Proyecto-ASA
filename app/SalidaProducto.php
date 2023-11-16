<?php
 
 namespace App;
  
 use Illuminate\Database\Eloquent\Model;
  
 class SalidaProducto extends Model
 {
    protected $table = 'salidas_productos';
    protected $fillable =[
         'encargadoRegistro', 
         'evento',
         'fecha_hora',
     ];

     public function evento(){
        return $this->belongsTo('App\Eventos');
    }
 }