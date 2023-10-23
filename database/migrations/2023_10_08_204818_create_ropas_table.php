<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRopasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ropas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_ropa');
            $table->integer('cantidad');
            $table->string('sexo');
            $table->integer('idTalla')->unsigned();
            $table->integer('idEstacion')->unsigned();
            $table->string('talla');
            $table->string('estacion');
            //$table->foreign('idTalla')->references('id')->on('talla_ropas')->onDelete('cascade');
            //$table->foreign('idEstacion')->references('id')->on('categoria_ropas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ropas');
    }
}
