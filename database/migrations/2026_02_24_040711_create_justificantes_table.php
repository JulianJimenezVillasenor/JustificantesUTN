<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJustificantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('justificantes', function (Blueprint $table) {
            $table->id();
            // Relaciones (Llaves Foráneas)
            $table->unsignedBigInteger('user_id'); // El alumno que lo crea
            $table->unsignedBigInteger('tutor_id')->nullable(); // El tutor que valida
            
            // Datos del Justificante
            $table->string('tipo_falta'); // Ejemplo: Familiar, Médica
            $table->date('fecha');
            $table->string('horas')->nullable(); // "Todo el día" o rango de horas
            $table->text('motivo'); // La explicación detallada
            $table->string('tipo_comprobante')->nullable(); // Ej: "Cita Médica de su papá"
            $table->string('evidencia_path'); 
            
            // Control y Firmas
            $table->string('status')->default('PENDIENTE'); // PENDIENTE, ACEPTADO, RECHAZADO
            $table->boolean('firma_docente')->default(false);
            $table->timestamp('fecha_firma_docente')->nullable();
            
            $table->timestamps();

            // Definición de llaves foráneas (Asumiendo que tienes tabla 'users')
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('justificantes');
    }
}
