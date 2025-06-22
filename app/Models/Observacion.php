<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Observacion extends Model
{
    use HasFactory, SoftDeletes;

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
        'idagente',
        'idjuntamedica',
        'fhir_observation_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'datetime',
        'valor' => 'json',
    ];

    /**
     * Get the diagnostico that owns the observacion.
     *
     * @return BelongsTo
     */
    public function diagnostico(): BelongsTo
    {
        return $this->belongsTo(Diagnostico::class, 'idDiagnostico', 'idDiagnostico');
    }

    /**
     * Get the agente that the observacion is about.
     *
     * @return BelongsTo
     */
    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class, 'idagente', 'idagente');
    }

    /**
     * Get the junta médica associated with the observacion.
     *
     * @return BelongsTo
     */
    public function juntaMedica(): BelongsTo
    {
        return $this->belongsTo(JuntaMedica::class, 'idjuntamedica', 'idjuntamedica');
    }

    /**
     * Get the FHIR observation associated with this observacion.
     *
     * @return BelongsTo
     */
    public function fhirObservation(): BelongsTo
    {
        return $this->belongsTo(FhirObservation::class, 'fhir_observation_id', 'fhir_observation_id');
    }

    /**
     * Create or get a FHIR Observation resource from this observacion.
     *
     * @return FhirObservation
     */
    public function toFhirObservation(): FhirObservation
    {
        // Si ya existe, lo retornamos
        if ($this->fhir_observation_id) {
            return $this->fhirObservation;
        }

        // Obtener el paciente FHIR correspondiente al agente
        $patient = FhirPatient::where('idagente', $this->idagente)->first();

        if (!$patient) {
            throw new \Exception('No existe un recurso FhirPatient para este agente');
        }

        // Preparar categoría y código basado en el tipo de observación
        $category = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                    'code' => $this->determinarCategoria(),
                    'display' => $this->tipo
                ]
            ]
        ];

        $code = [
            'text' => $this->descripcion,
        ];

        if ($this->codigo) {
            $code['coding'] = [
                [
                    'code' => $this->codigo,
                    'display' => $this->descripcion
                ]
            ];
        }

        // Determinar el tipo de dato y valor
        $dataType = $this->determinarTipoDato();
        $value = $this->formatearValor($dataType);

        // Crear el recurso FHIR Observation
        $observation = FhirObservation::create([
            'fhir_id' => \Illuminate\Support\Str::uuid(),
            'status' => 'final',
            'category' => $category,
            'code' => $code,
            'fhir_patient_id' => $patient->fhir_patient_id,
            'effective_datetime' => $this->fecha,
            'issued' => now(),
            'value' => $value,
            'data_type' => $dataType,
            'note' => $this->descripcion,
        ]);

        // Actualizar la referencia en este modelo
        $this->fhir_observation_id = $observation->fhir_observation_id;
        $this->save();

        return $observation;
    }

    /**
     * Determine la categoría FHIR para esta observación.
     *
     * @return string
     */
    private function determinarCategoria(): string
    {
        $tipo = strtolower($this->tipo);

        if (strpos($tipo, 'lab') !== false || strpos($tipo, 'laboratorio') !== false) {
            return 'laboratory';
        } elseif (strpos($tipo, 'vital') !== false) {
            return 'vital-signs';
        } elseif (strpos($tipo, 'imagen') !== false || strpos($tipo, 'radiolog') !== false) {
            return 'imaging';
        } elseif (strpos($tipo, 'social') !== false) {
            return 'social-history';
        } elseif (strpos($tipo, 'examen') !== false || strpos($tipo, 'fisico') !== false) {
            return 'exam';
        } else {
            return 'survey';
        }
    }

    /**
     * Determine el tipo de dato FHIR para el valor.
     *
     * @return string
     */
    private function determinarTipoDato(): string
    {
        if (empty($this->valor)) {
            return 'string';
        }

        if (is_numeric($this->valor)) {
            return is_float($this->valor + 0) ? 'Quantity' : 'integer';
        }

        if (is_array($this->valor) || is_object($this->valor)) {
            return 'CodeableConcept';
        }

        return 'string';
    }

    /**
     * Formatear el valor según el tipo de dato FHIR.
     *
     * @param string $dataType
     * @return array|string|float|int
     */
    private function formatearValor(string $dataType)
    {
        switch ($dataType) {
            case 'Quantity':
                return [
                    'value' => (float) $this->valor,
                    'unit' => $this->unidad ?: '',
                    'system' => 'http://unitsofmeasure.org',
                    'code' => $this->unidad ?: ''
                ];

            case 'CodeableConcept':
                return [
                    'coding' => [
                        [
                            'code' => $this->codigo ?: '',
                            'display' => $this->descripcion
                        ]
                    ],
                    'text' => $this->descripcion
                ];

            case 'integer':
                return (int) $this->valor;

            case 'string':
            default:
                return (string) $this->valor;
        }
    }
}
