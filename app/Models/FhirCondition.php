<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\FhirConditionStatus;

class FhirCondition extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhir_conditions';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_condition_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fhir_id',
        'clinical_status',
        'fhir_patient_id',
        'fhir_encounter_id',
        'recorded_date',
        'code',
        'note'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fhir_id' => 'string',
        'clinical_status' => 'string',
        'recorded_date' => 'date',
        'code' => 'json',
    ];

    /**
     * Get the patient that owns the condition.
     *
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(FhirPatient::class, 'fhir_patient_id');
    }

    /**
     * Get the encounter associated with the condition.
     *
     * @return BelongsTo
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(FhirEncounter::class, 'fhir_encounter_id');
    }

    /**
     * Get the diagnosticos associated with this FHIR condition.
     *
     * @return HasMany
     */
    public function diagnosticos(): HasMany
    {
        return $this->hasMany(Diagnostico::class, 'fhir_condition_id');
    }

    /**
     * Set the clinical status of the condition.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        if (!in_array($status, FhirConditionStatus::values())) {
            throw new \InvalidArgumentException('Status inválido para una condición FHIR');
        }

        $this->clinical_status = $status;
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
     * Get the code system from the FHIR CodeableConcept.
     *
     * @return string|null
     */
    public function getCodeSystem(): ?string
    {
        if (!$this->code) {
            return null;
        }

        if (isset($this->code['coding'][0]['system'])) {
            return $this->code['coding'][0]['system'];
        }

        return null;
    }
}
