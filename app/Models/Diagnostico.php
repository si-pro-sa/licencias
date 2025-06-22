<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnostico extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'diagnosticos';

    // Clave primaria personalizada
    protected $primaryKey = 'idDiagnostico';

    // Indica si el modelo tiene timestamps
    public $timestamps = true;

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'idlicencia',
        'idagente',
        'fecha',
        'descripcion',
        'codigo_icd10',
        'archivo_url',
    ];

    // Relaciones

    /**
     * Relación con el modelo Observacion.
     */
    public function observaciones()
    {
        return $this->hasMany(Observacion::class, 'idDiagnostico', 'idDiagnostico');
    }

    /**
     * Relación con el modelo Licencia.
     */
    public function licencia()
    {
        return $this->belongsTo(Licencia::class, 'idlicencia', 'idlicencia');
    }

    /**
     * Relación con el modelo Agente.
     */
    public function agente()
    {
        return $this->belongsTo(Agente::class, 'idagente', 'idagente');
    }
}
