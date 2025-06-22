<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesAgenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agente', function (Blueprint $table) {
            $table->index('documento');
            $table->index('apellido');
        });
        Schema::table('puesto', function (Blueprint $table) {
            $table->index('idagente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agente', function (Blueprint $table) {
            $table->dropIndex('documento');
            $table->dropIndex('apellido');
        });
        Schema::table('puesto', function (Blueprint $table) {
            $table->dropIndex('idagente');
        });
    }
}
