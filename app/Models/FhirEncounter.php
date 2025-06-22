<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\FhirEncounterStatus;

class FhirEncounter extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhir_encounters';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_encounter_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fhir_id',
        'status',
        'class',
        'fhir_patient_id',
        'fhir_provider_id',
        'fhir_facility_id',
        'fhir_location_id',
        'start',
        'end',
        'reason',
        'diagnosis',
        'idjuntamedica'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => FhirEncounterStatus::class,
        'start' => 'datetime',
        'end' => 'datetime',
        'diagnosis' => 'array'
    ];

    /**
     * Get the patient associated with the encounter.
     *
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(FhirPatient::class, 'fhir_patient_id');
    }

    /**
     * Get the provider associated with the encounter.
     *
     * @return BelongsTo
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(FhirProvider::class, 'fhir_provider_id');
    }

    /**
     * Get the facility associated with the encounter.
     *
     * @return BelongsTo
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(FhirFacility::class, 'fhir_facility_id');
    }

    /**
     * Get the location associated with the encounter.
     *
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(FhirLocation::class, 'fhir_location_id');
    }

    /**
     * Get the conditions (diagnoses) associated with the encounter.
     *
     * @return HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(FhirCondition::class, 'fhir_encounter_id');
    }

    /**
     * Get the observations associated with the encounter.
     *
     * @return HasMany
     */
    public function observations(): HasMany
    {
        return $this->hasMany(FhirObservation::class, 'fhir_encounter_id');
    }

    /**
     * Get the clinical notes associated with the encounter.
     *
     * @return HasMany
     */
    public function clinicalNotes(): HasMany
    {
        return $this->hasMany(FhirClinicalNote::class, 'fhir_encounter_id');
    }

    /**
     * Get the associated junta médica (medical board).
     *
     * @return BelongsTo
     */
    public function juntaMedica(): BelongsTo
    {
        return $this->belongsTo(JuntaMedica::class, 'idjuntamedica');
    }

    /**
     * Check if the encounter is active.
     *
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, [
            FhirEncounterStatus::IN_PROGRESS,
            FhirEncounterStatus::ARRIVED,
            FhirEncounterStatus::TRIAGED
        ]);
    }

    /**
     * Get the duration of the encounter in hours.
     *
     * @return float|null
     */
    public function getDurationHoursAttribute(): ?float
    {
        if (!$this->start || !$this->end) {
            return null;
        }

        return $this->start->diffInHours($this->end, false);
    }
}
