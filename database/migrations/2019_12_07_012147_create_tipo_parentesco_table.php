<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoParentescoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tipo_parentescos', function (Blueprint $table) {
            $table->bigIncrements('idtipoParentesco');
            $table->integer('codigo');
            $table->string('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });        
        Schema::table('grupo_familiar_personas', function (Blueprint $table) {
            $table->foreign('idtipoParentesco')->references('idtipoParentesco')->on('tipo_parentescos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        Schema::dropIfExists('tipo_parentescos');
        
    }
}
