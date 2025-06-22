<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargoTipoCeseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql_public')->create('cargo_tipo_cese', function (Blueprint $table) {
            $table->increments('idcargo_tipo_cese');
            $table->integer('idtipo_cargo');
            $table->foreign('idtipo_cargo')->references('idtipo_cargo')->on('tipo_cargo');
            $table->integer('idtipo_cese');
            $table->foreign('idtipo_cese')->references('idtipo_cese')->on('tipo_cese');
            $table->boolean('agente_reemplazado')->default(false);
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
        Schema::connection('pgsql_public')->dropIfExists('cargo_tipo_cese');
    }
}
