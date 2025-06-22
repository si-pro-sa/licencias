<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigIncrements('idusuario');
            $table->bigInteger('idagente')->nullable();
            $table->bigInteger('idperfil')->nullable();
            $table->string('nombreusuario')->nullable();
            $table->string('usuario')->nullable();
            $table->string('email')->nullable();
            $table->boolean('activo')->nullable();
            $table->string('password')->nullable();
            $table->string('clave')->nullable();
            $table->char('operacion')->nullable();
            $table->timestamp('foperacion')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('api_token', 60)->nullable();
            $table->string('remember_token', 100)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
