namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Justificante extends Model
{
    protected $fillable = [
        'user_id', 'tutor_id', 'tipo_falta', 'fecha',
        'horas', 'motivo', 'tipo_comprobante',
        'evidencia_path', 'status', 'firma_docente'
    ];
}
