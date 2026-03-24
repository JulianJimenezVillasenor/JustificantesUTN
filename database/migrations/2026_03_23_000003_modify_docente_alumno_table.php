<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDocenteAlumnoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docente_alumno', function (Blueprint $table) {
            $table->dropForeign(['docente_id']);
            $table->dropForeign(['alumno_id']);

            $table->dropUnique('docente_alumno_docente_id_alumno_id_unique');

            $table->foreign('docente_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('alumno_id')->references('id')->on('users')->onDelete('cascade');
        });

        if (!Schema::hasColumn('docente_alumno', 'horario')) {
            Schema::table('docente_alumno', function (Blueprint $table) {
                $table->string('horario')->nullable()->after('materia');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docente_alumno', function (Blueprint $table) {
            $table->dropColumn('horario');
            // Nota: Podría fallar al hacer rollback si ya hay duplicados,
            // pero es necesario restaurar la lógica para down() de todos modos.
            $table->unique(['docente_id', 'alumno_id']);
        });
    }
}
