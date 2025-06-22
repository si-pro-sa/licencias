<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->bigIncrements('idDiagnostico');
            $table->bigInteger('idlicencia');
            $table->bigInteger('idagente');
            $table->bigInteger('idObservacion');
            $table->foreign('idObservacion')->references('idObservacion')->on('observaciones');
            $table->foreign('idlicencia')->references('idlicencia')->on('licencias');
            $table->foreign('idagente')->references('idagente')->on('agente');
            $table->dateTime('fecha');
            $table->text('descripcion')->nullable();
            $table->string('codigo_icd10')->nullable(); // Código ICD-10
            $table->text('archivo_url')->nullable(); // URL del informe en Firebase
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
        Schema::dropIfExists('diagnosticos');
    }
}
