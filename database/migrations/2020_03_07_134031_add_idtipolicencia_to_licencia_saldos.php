<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdtipolicenciaToLicenciaSaldos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licencia_saldos', function (Blueprint $table) {
            $table->bigInteger('idtipoLicencia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('licencia_saldos', function (Blueprint $table) {
            $table->dropColumn("idtipoLicencia");
        });
    }
}
