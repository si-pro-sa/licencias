<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAntiguedadsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antiguedades', function (Blueprint $table) {
            $table->bigIncrements('idantiguedad');
            $table->bigInteger('idagente');
            $table->integer('año');
            $table->integer('pedido')->nullable();
            $table->integer('disponible')->nullable();
            $table->boolean('vigente');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('antiguedades');
    }
}
