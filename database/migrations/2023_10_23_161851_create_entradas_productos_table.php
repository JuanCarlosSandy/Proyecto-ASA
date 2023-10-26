<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntradasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idDonador')->unsigned();
            $table->foreign('idDonador')->references('id')->on('donadores')->onDelete('cascade');
            $table->integer('idProducto')->unsigned();
            $table->foreign('idProducto')->references('id')->on('productos')->onDelete('cascade');
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
        Schema::dropIfExists('entradas');
    }
}
