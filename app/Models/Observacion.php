<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Observacion extends Model
{

    // Nombre de la tabla en la base de datos
    protected $table = 'observaciones';

    // Clave primaria personalizada
    protected $primaryKey = 'idObservacion';

    // Indica si el modelo tiene timestamps
    public $timestamps = true;

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'fecha',
        'tipo',
        'descripcion',
        'valor',
        'unidad',
        'archivo_url',
        'sitio_estudio',
        'usuario',
        'clave',
        'codigo',
        'idDiagnostico',
    ];

    // Define la relación con otros modelos (si aplica)
    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class, 'idDiagnostico', 'idDiagnostico');
    }
}
