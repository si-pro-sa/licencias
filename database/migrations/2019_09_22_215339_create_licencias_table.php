<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLicenciasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_licencias', function (Blueprint $table) {
            $table->bigIncrements('idtipoLicencia');
            $table->integer('codigo');
            $table->string('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('licencias', function (Blueprint $table) {
            $table->bigIncrements('idlicencia');
            $table->bigInteger('idpuesto');
            $table->bigInteger('idagente');
            $table->bigInteger('idtipoLicencia');
            $table->date('fecha_pedido_inicio');
            $table->date('fecha_pedido_final');
            $table->date('fecha_evento_inicio')->nullable();
            $table->date('fecha_evento_final')->nullable();
            $table->date('fecha_efectiva_inicio')->nullable();
            $table->date('fecha_efectiva_final')->nullable();
            $table->date('fecha_interrupcion_inicio')->nullable();
            $table->date('fecha_interrupcion_final')->nullable();
            $table->integer('dias')->nullable();
            $table->boolean('primer_visado')->nullable();
            $table->boolean('segundo_visado')->nullable();
            $table->boolean('tercera_visado')->nullable();
            $table->boolean('cuarta_visado')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('resolucion')->nullable();
            $table->string('razon')->nullable();
            $table->string('evento_nombre')->nullable();
            $table->string('evento_lugar')->nullable();
            $table->string('observacion_primera')->nullable();
            $table->string('observacion_segunda')->nullable();
            $table->string('observacion_tercera')->nullable();
            $table->string('observacion_cuarta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('idagente')->references('idagente')->on('agente');
            $table->foreign('idpuesto')->references('idpuesto')->on('puesto');
            $table->foreign('idtipoLicencia')->references('idtipoLicencia')->on('tipo_licencias');
        });

        Schema::create('licencia_familiares', function (Blueprint $table) {
            $table->bigIncrements('idlicenciaFamiliares');
            $table->bigInteger('idlicencia');
            $table->bigInteger('idpersona');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('idlicencia')->references('idlicencia')->on('licencias');
            $table->foreign('idpersona')->references('idpersona')->on('personas');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licencia_familiares');
        Schema::dropIfExists('licencias');
        Schema::dropIfExists('tipo_licencias');


    }
}
