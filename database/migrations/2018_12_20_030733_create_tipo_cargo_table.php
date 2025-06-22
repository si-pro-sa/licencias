<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoCargoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql_public')->create('tipo_cargo', function (Blueprint $table) {
            $table->increments('idtipo_cargo');
            $table->string('tipocargo');
            $table->string('tipocargo_corto');
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
        Schema::connection('pgsql_public')->dropIfExists('tipo_cargo');
    }
}
