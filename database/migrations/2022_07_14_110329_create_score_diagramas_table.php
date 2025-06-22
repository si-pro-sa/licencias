<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoreDiagramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_diagramas', function (Blueprint $table) {
            $table->integer('idscorediagrama')->autoIncrement()->unsigned()->unique();
            $table->integer('idpuesto');
            $table->integer('idpuestoadicional');
            $table->integer('iddependencia');
            $table->date('fecha');
            $table->time('hora_desde');
            $table->integer('cantHoras');
            $table->boolean('licencias');
            
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
        Schema::dropIfExists('score_diagramas');
    }
}
