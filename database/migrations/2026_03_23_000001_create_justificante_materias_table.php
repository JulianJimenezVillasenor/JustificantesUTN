<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJustificanteMateriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('justificante_materias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('justificante_id');
            $table->string('materia');
            $table->unsignedBigInteger('docente_id');
            $table->boolean('firma_docente')->default(false);
            $table->timestamp('fecha_firma_docente')->nullable();
            $table->timestamps();

            $table->foreign('justificante_id')->references('id')->on('justificantes')->onDelete('cascade');
            $table->foreign('docente_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('justificante_materias');
    }
}
