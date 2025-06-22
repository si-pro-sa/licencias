<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivoAprobadoVencimientoToGrupoFamiliaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grupo_familiares', function (Blueprint $table) {
            $table->boolean('aprobado');
            $table->boolean('activo');
            $table->date('vencimiento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grupo_familiares', function (Blueprint $table) {
            $table->dropColumn('aprobado');
            $table->dropColumn('activo');
            $table->dropColumn('vencimiento');
        });
    }
}
