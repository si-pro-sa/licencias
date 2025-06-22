<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql_public')->create('cargo', function (Blueprint $table) {
            $table->increments('idcargo');
            $table->integer('agente_propuesto_id');
            $table->string('agente_propuesto_type');
            $table->integer('idtipo_campania');
            $table->foreign('idtipo_campania')->references('idtipo_campania')->on('tipo_campania');
            $table->integer('idtipo_cargo');
            $table->foreign('idtipo_cargo')->references('idtipo_cargo')->on('tipo_cargo');
            $table->integer('idtipo_cese');
            $table->foreign('idtipo_cese')->references('idtipo_cese')->on('tipo_cese');
            $table->integer('idefector');
            $table->foreign('idefector')->references('iddependencia')->on('dependencia');
            $table->integer('idservicio');
            $table->foreign('idservicio')->references('iddependencia')->on('dependencia');
            $table->integer('idtipo_funcion');
            $table->foreign('idtipo_funcion')->references('idtipo_funcion')->on('tipo_funcion');
            $table->integer('idtipo_nivel');
            $table->foreign('idtipo_nivel')->references('idtipo_nivel')->on('tipo_nivel');
            $table->integer('idtipo_agrupamiento');
            $table->foreign('idtipo_agrupamiento')->references('idtipo_agrupamiento')->on('tipo_agrupamiento');
            $table->integer('idtitulo');
            $table->foreign('idtitulo')->references('idtitulo')->on('titulo');
            $table->integer('idtipo_especialidad');
            $table->foreign('idtipo_especialidad')->references('idtipo_especialidad')->on('tipo_especialidad');
            $table->integer('idtipo_referido')->nullable();
            $table->foreign('idtipo_referido')->references('idtipo_referido')->on('tipo_referido');
            $table->text('produccion_esperada');
            $table->text('razones_brecha');
            $table->boolean('foto_carnet')->default(false);
            $table->boolean('diagrama_servicio')->default(false);
            $table->boolean('resolucion_ministerial')->default(false);
            $table->boolean('formulario_baja_cobertura')->default(false);
            $table->boolean('titulo_academico')->default(false);
            $table->boolean('copia_dni')->default(false);
            $table->boolean('copia_cuil')->default(false);
            $table->boolean('resumen_evaluacion')->default(false);
            $table->boolean('curso_induccion')->default(false);
            $table->boolean('titulo_especialidad')->default(false);
            $table->boolean('declaracion_jurada')->default(false);
            $table->boolean('prioritario')->default(false);

            $table->integer('created_by')->nullable();
            $table->foreign('created_by')->references('idusuario')->on('sistema.usuario');
            $table->integer('updated_by')->nullable();
            $table->foreign('updated_by')->references('idusuario')->on('sistema.usuario');
            $table->integer('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('idusuario')->on('sistema.usuario');
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
        Schema::connection('pgsql_public')->dropIfExists('cargo');
    }
}
