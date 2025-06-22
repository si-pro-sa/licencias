<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Diagnostico extends Model
{
    use HasFactory, SoftDeletes;

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
        'idjuntamedica',
        'fhir_condition_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones

    /**
     * Relación con el modelo Observacion.
     *
     * @return HasMany
     */
    public function observaciones(): HasMany
    {
        return $this->hasMany(Observacion::class, 'idDiagnostico', 'idDiagnostico');
    }

    /**
     * Relación con el modelo Licencia.
     *
     * @return BelongsTo
     */
    public function licencia(): BelongsTo
    {
        return $this->belongsTo(Licencia::class, 'idlicencia', 'idlicencia');
    }

    /**
     * Relación con el modelo LicenciaSaludOcupacional.
     *
     * @return BelongsTo
     */
    public function licenciaSaludOcupacional(): BelongsTo
    {
        return $this->belongsTo(LicenciaSaludOcupacional::class, 'idlicencia', 'idlicencia_salud_ocupacional');
    }

    /**
     * Relación con el modelo Agente.
     *
     * @return BelongsTo
     */
    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class, 'idagente', 'idagente');
    }

    /**
     * Relación con el modelo JuntaMedica.
     *
     * @return BelongsTo
     */
    public function juntaMedica(): BelongsTo
    {
        return $this->belongsTo(JuntaMedica::class, 'idjuntamedica', 'idjuntamedica');
    }

    /**
     * Relación con el modelo FhirCondition.
     *
     * @return BelongsTo
     */
    public function fhirCondition(): BelongsTo
    {
        return $this->belongsTo(FhirCondition::class, 'fhir_condition_id', 'fhir_condition_id');
    }

    /**
     * Convert to a FHIR Condition resource.
     *
     * @return FhirCondition
     */
    public function toFhirCondition(): FhirCondition
    {
        // Si ya existe, lo retornamos
        if ($this->fhir_condition_id) {
            return $this->fhirCondition;
        }

        // Obtener el paciente FHIR correspondiente al agente
        $patient = FhirPatient::where('idagente', $this->idagente)->first();

        if (!$patient) {
            throw new \Exception('No existe un recurso FhirPatient para este agente');
        }

        // Crear el código ICD-10
        $code = [
            'coding' => [
                [
                    'system' => 'http://hl7.org/fhir/sid/icd-10',
                    'code' => $this->codigo_icd10 ?: 'UNCODED',
                    'display' => $this->descripcion
                ]
            ],
            'text' => $this->descripcion
        ];

        // Crear el recurso FHIR Condition
        $condition = FhirCondition::create([
            'fhir_id' => \Illuminate\Support\Str::uuid(),
            'clinical_status' => 'active',
            'fhir_patient_id' => $patient->fhir_patient_id,
            'recorded_date' => $this->fecha,
            'code' => $code,
            'note' => $this->descripcion,
        ]);

        // Actualizar la referencia en este modelo
        $this->fhir_condition_id = $condition->fhir_condition_id;
        $this->save();

        return $condition;
    }
}
