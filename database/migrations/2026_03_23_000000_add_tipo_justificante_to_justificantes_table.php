<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoJustificanteToJustificantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('justificantes', function (Blueprint $table) {
            $table->string('tipo_justificante')->default('Completa')->after('tipo_falta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('justificantes', function (Blueprint $table) {
            $table->dropColumn('tipo_justificante');
        });
    }
}
