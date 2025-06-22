<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLdCambioEstadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql_public')->table('ld_cambio_estado', function (Blueprint $table) {
            $table->date('fdesde')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql_public')->table('ld_cambio_estado', function ($table) {
            $table->dropColumn('fdesde');
        });
    }
}
