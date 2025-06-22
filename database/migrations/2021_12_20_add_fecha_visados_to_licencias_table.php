<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FechaVisadosToLicenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licencias', function (Blueprint $table) {
            $table->timestamp('fecha_visado_primero')->nullable();
            $table->timestamp('fecha_visado_segundo')->nullable();
            $table->timestamp('fecha_visado_interrupcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('licencias', function (Blueprint $table) {
            $table->dropColumn('fecha_visado_primero');
            $table->dropColumn('fecha_visado_segundo');
            $table->dropColumn('fecha_visado_interrupcion');
        });
    }
}

