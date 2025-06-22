<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicenciaSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licencia_saldos', function (Blueprint $table) {
            $table->bigIncrements('idLicenciaSaldos');
            $table->bigInteger('idlicencia');
            $table->bigInteger('idagente');
            $table->integer('año')->nulleable();
            $table->integer('mes')->nulleable();
            $table->integer('dias')->nulleable();
            $table->integer('saldoMensual')->nulleable();
            $table->integer('saldoAnual')->nulleable();
            $table->timestamps();
            $table->softDeletes();	
            $table->foreign('idlicencia')->references('idlicencia')->on('licencias');
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
        Schema::dropIfExists('licencia_saldos');
    }
}
