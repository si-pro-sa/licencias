<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearPagosDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_reintegros', function (Blueprint $table) {
            $table->bigIncrements('idEstadoReintegro');
            $table->integer('codigo');
            $table->string('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('reintegros', function (Blueprint $table) {
            $table->bigIncrements('idReintegro');
            $table->bigInteger('idlicencia')->nullable();
            $table->bigInteger('idAgente');
            $table->bigInteger('idEstadoReintegro');
            $table->foreign('idAgente')->references('idagente')->on('agente');
            $table->foreign('idlicencia')->references('idlicencia')->on('licencias');
            $table->foreign('idEstadoReintegro')->references('idEstadoReintegro')->on('estado_reintegros');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('reintegro_detalles', function (Blueprint $table) {
            $table->bigIncrements('idReintegroDetalle');
            $table->bigInteger('idReintegro')->nullable();
            $table->integer('numero');
            $table->string('descripcion');
            $table->string('codigo');
            $table->integer('monto');
            $table->foreign('idReintegro')->references('idReintegro')->on('reintegros');
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
        //
    }
}
