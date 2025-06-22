<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGrupoFamiliarPersonasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_familiar_personas', function (Blueprint $table) {
            $table->bigIncrements('idgrupoPersona');
            $table->bigInteger('idpersona');
            $table->bigInteger('idgrupoFamiliar');
            $table->bigInteger('idtipoParentesco');
            $table->foreign('idpersona')->references('idpersona')->on('personas');
            $table->foreign('idgrupoFamiliar')->references('idgrupoFamiliar')->on('grupo_familiares');    
            $table->timestamps();
            $table->softDeletes();                    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo_familiar_personas');
    }
}
