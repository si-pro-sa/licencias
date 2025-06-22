<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\FhirObservationStatus;

class FhirObservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhir_observations';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_observation_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fhir_id',
        'status',
        'category',
        'code',
        'fhir_patient_id',
        'fhir_encounter_id',
        'effective_datetime',
        'issued',
        'value',
        'data_type',
        'note'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fhir_id' => 'string',
        'status' => 'string',
        'category' => 'json',
        'code' => 'json',
        'effective_datetime' => 'datetime',
        'issued' => 'datetime',
        'value' => 'json'
    ];

    /**
     * Get the patient that owns the observation.
     *
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(FhirPatient::class, 'fhir_patient_id');
    }

    /**
     * Get the encounter associated with the observation.
     *
     * @return BelongsTo
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(FhirEncounter::class, 'fhir_encounter_id');
    }

    /**
     * Get the observaciones associated with this FHIR observation.
     *
     * @return HasMany
     */
    public function observaciones(): HasMany
    {
        return $this->hasMany(Observacion::class, 'fhir_observation_id');
    }

    /**
     * Set the status of the observation.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        if (!in_array($status, FhirObservationStatus::values())) {
            throw new \InvalidArgumentException('Status inválido para una observación FHIR');
        }

        $this->status = $status;
        $this->save();

        return $this;
    }

    /**
     * Get the code display from the FHIR CodeableConcept.
     *
     * @return string|null
     */
    public function getCodeDisplay(): ?string
    {
        if (!$this->code) {
            return null;
        }

        if (isset($this->code['text'])) {
            return $this->code['text'];
        }

        if (isset($this->code['coding'][0]['display'])) {
            return $this->code['coding'][0]['display'];
        }

        return null;
    }

    /**
     * Get the code value from the FHIR CodeableConcept.
     *
     * @return string|null
     */
    public function getCodeValue(): ?string
    {
        if (!$this->code) {
            return null;
        }

        if (isset($this->code['coding'][0]['code'])) {
            return $this->code['coding'][0]['code'];
        }

        return null;
    }

    /**
     * Get the category code from the FHIR CodeableConcept.
     *
     * @return string|null
     */
    public function getCategoryCode(): ?string
    {
        if (!$this->category) {
            return null;
        }

        if (isset($this->category['coding'][0]['code'])) {
            return $this->category['coding'][0]['code'];
        }

        return null;
    }

    /**
     * Get the category display from the FHIR CodeableConcept.
     *
     * @return string|null
     */
    public function getCategoryDisplay(): ?string
    {
        if (!$this->category) {
            return null;
        }

        if (isset($this->category['text'])) {
            return $this->category['text'];
        }

        if (isset($this->category['coding'][0]['display'])) {
            return $this->category['coding'][0]['display'];
        }

        return null;
    }

    /**
     * Get the formatted value for display based on data type.
     *
     * @return string|null
     */
    public function getFormattedValue(): ?string
    {
        if (empty($this->value)) {
            return null;
        }

        if ($this->data_type === 'Quantity' && isset($this->value['value'], $this->value['unit'])) {
            return $this->value['value'] . ' ' . $this->value['unit'];
        }

        if ($this->data_type === 'CodeableConcept' && isset($this->value['text'])) {
            return $this->value['text'];
        }

        if (is_string($this->value) || is_numeric($this->value)) {
            return (string) $this->value;
        }

        // For complex objects, return as JSON string
        return json_encode($this->value);
    }
}
