<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorarioPuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql_public')->create('horario_puesto', function (Blueprint $table) {
            $table->integer('idhorario_puesto')->autoIncrement()->unsigned()->unique();
            $table->integer('puesto_id');
            $table->string('puesto_type');
            $table->integer('idtipo_dia');
            $table->foreign('idtipo_dia')->references('idtipo_dia')->on('tipo_dia');
            $table->integer('iddependencia');
            $table->foreign('iddependencia')->references('iddependencia')->on('dependencia');
            $table->time('hora_desde');
            $table->time('hora_hasta');
            $table->integer('cantidad_mensual')->default(0);
            $table->integer('cantidad_horas')->default(0);

            $table->primary(['puesto_id', 'puesto_type', 'idtipo_dia', 'iddependencia']);

            $table->integer('created_by')->nullable();
            $table->foreign('created_by')->references('idusuario')->on('sistema.usuario');
            $table->integer('updated_by')->nullable();
            $table->foreign('updated_by')->references('idusuario')->on('sistema.usuario');
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
        Schema::connection('pgsql_public')->dropIfExists('horario_puesto');
    }
}
