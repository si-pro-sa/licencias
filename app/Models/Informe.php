<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Informe extends Model
{
    use SoftDeletes;

    protected $table = 'informes';
    protected $primaryKey = 'informe_id';

    protected $fillable = [
        'numero_informe',
        'fecha_emision',
        'fecha_evaluacion',
        'tipo_informe',
        'contenido',
        'recomendaciones',
        'conclusiones',
        'estado',
        'idlicencia',
        'fhir_encounter_id',
        'fhir_document_reference_id',
        'fhir_provider_id'
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'fecha_evaluacion' => 'datetime',
        'contenido' => 'array',
        'recomendaciones' => 'array',
        'conclusiones' => 'array'
    ];

    public function licencia()
    {
        return $this->belongsTo(Licencia::class, 'idlicencia');
    }

    public function encounter()
    {
        return $this->belongsTo(\App\Models\Fhir\Encounter::class, 'fhir_encounter_id');
    }

    public function documentReference()
    {
        return $this->belongsTo(\App\Models\Fhir\DocumentReference::class, 'fhir_document_reference_id');
    }

    public function provider()
    {
        return $this->belongsTo(\App\Models\Fhir\Provider::class, 'fhir_provider_id');
    }
}
