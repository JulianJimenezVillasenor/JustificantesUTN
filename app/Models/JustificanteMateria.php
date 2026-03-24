<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JustificanteMateria extends Model
{
    use HasFactory;

    protected $table = 'justificante_materias';

    protected $fillable = [
        'justificante_id',
        'materia',
        'docente_id',
        'firma_docente',
        'fecha_firma_docente'
    ];

    public function justificante()
    {
        return $this->belongsTo(Justificante::class);
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }
}
