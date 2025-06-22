<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observaciones', function (Blueprint $table) {
            $table->bigIncrements('idObservacion');
            $table->dateTime('fecha');
            $table->string('tipo'); // Tipo de observación
            $table->text('descripcion')->nullable();
            $table->text('valor')->nullable(); // Valor del resultado
            $table->string('unidad')->nullable(); // Unidad de medida
            $table->text('archivo_url')->nullable(); // URL en Firebase
            $table->text('sitio_estudio')->nullable();
            $table->text('usuario')->nullable();
            $table->text('clave')->nullable();
            $table->text('codigo')->nullable();
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
        Schema::dropIfExists('observaciones');
    }
}
