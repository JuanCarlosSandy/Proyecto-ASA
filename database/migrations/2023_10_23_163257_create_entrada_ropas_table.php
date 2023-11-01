<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntradaRopasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_ropas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idDonador')->unsigned();
            $table->foreign('idDonador')->references('id')->on('donadores')->onDelete('cascade');
            $table->integer('idRopa')->unsigned();
            $table->foreign('idRopa')->references('id')->on('ropas')->onDelete('cascade');
            $table->integer('cantidad');
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
        Schema::dropIfExists('entrada_ropas');
    }
}
