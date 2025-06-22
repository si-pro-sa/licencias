<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargoReemplazadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql_public')->create('cargo_reemplazado', function (Blueprint $table) {
            $table->increments('idcargo_reemplazado');
            $table->integer('idcargo');
            $table->foreign('idcargo')->references('idcargo')->on('cargo');
            $table->integer('idpuesto');
            $table->foreign('idpuesto')->references('idpuesto')->on('puesto');
            $table->integer('idtipo_funcion');
            $table->foreign('idtipo_funcion')->references('idtipo_funcion')->on('tipo_funcion');
            $table->integer('idtipo_nivel');
            $table->foreign('idtipo_nivel')->references('idtipo_nivel')->on('tipo_nivel');
            $table->integer('idtipo_agrupamiento');
            $table->foreign('idtipo_agrupamiento')->references('idtipo_agrupamiento')->on('tipo_agrupamiento');
            
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
        Schema::connection('pgsql_public')->dropIfExists('cargo_reemplazado');
    }
}
