<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justificante extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tipo_falta', 'tipo_justificante', 'fecha', 'horas', 'motivo', 'tipo_comprobante', 'evidencia_path', 'status', 'tutor_id', 'firma_docente', 'fecha_firma_docente'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'firma_docente');
    }

    public function materias()
    {
        return $this->hasMany(JustificanteMateria::class, 'justificante_id');
    }
}
