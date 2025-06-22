<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSancionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanciones', function (Blueprint $table) {
            $table->bigIncrements('idsancion');
            $table->bigInteger('idagente');
            $table->string('resolucion')->nullable();
            $table->string('reseña')->nullable();
            $table->string('conclusion')->nullable();
            $table->string('acuerdo')->nullable();
            $table->string('expediente')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('idagente')->references('idagente')->on('agente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sanciones');
    }
}
