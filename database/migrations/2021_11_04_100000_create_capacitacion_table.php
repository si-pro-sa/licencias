<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacitacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacitacion', function (Blueprint $table) {
            $table->bigIncrements('idCapacitacion');
            $table->string('resolucion')->nullable();
            $table->string('razon')->nullable();
            $table->string('evento_nombre')->nullable();
            $table->string('evento_lugar')->nullable();
            $table->date('fecha_evento_inicio')->nullable();
            $table->date('fecha_evento_final')->nullable();
            $table->bigInteger('idCaracter');
            $table->foreign('idCaracter')->references('idCaracter')->on('caracter');
            $table->bigInteger('idTipoEvento');
            $table->foreign('idTipoEvento')->references('idTipoEvento')->on('tipo_evento');
            $table->bigInteger('idAlcanceCapacitacion');
            $table->foreign('idAlcanceCapacitacion')->references('idAlcanceCapacitacion')->on('alcance_capacitacion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capacitacion');
    }
}
