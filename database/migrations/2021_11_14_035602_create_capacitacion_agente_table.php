<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacitacionAgenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacitacion_agente', function (Blueprint $table) {
            $table->bigIncrements('idCapacitacionAgente');
            $table->bigInteger('idCapacitacion');
            $table->bigInteger('idAgente');
            $table->foreign('idAgente')->references('idagente')->on('agente');
            $table->foreign('idCapacitacion')->references('idCapacitacion')->on('capacitacion');
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
        Schema::dropIfExists('capacitacion_agente');
    }
}

