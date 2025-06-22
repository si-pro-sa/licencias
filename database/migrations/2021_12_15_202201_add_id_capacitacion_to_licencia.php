<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCapacitacionToLicencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licencias', function (Blueprint $table) {
            $table->bigInteger('idCapacitacion')->nullable();
            $table->foreign('idCapacitacion')->references('idCapacitacion')->on('capacitacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('licencias', function (Blueprint $table) {
            $table->dropForeign(['idCapacitacion']);
            $table->dropColumn('idCapacitacion');
        });
    }
}
