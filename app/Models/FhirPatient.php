<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\FhirGender;

class FhirPatient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhir_patients';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_patient_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fhir_id',
        'active',
        'name',
        'gender',
        'birth_date',
        'deceased',
        'fhir_address_id',
        'telecom',
        'communication',
        'generalPractitioner',
        'managingOrganization',
        'link',
        'idagente'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'name' => 'array',
        'gender' => FhirGender::class,
        'birth_date' => 'date',
        'deceased' => 'datetime',
        'telecom' => 'array',
        'communication' => 'array',
        'generalPractitioner' => 'array',
        'managingOrganization' => 'array',
        'link' => 'array'
    ];

    /**
     * Get the address associated with the patient.
     */
    public function address()
    {
        return $this->belongsTo(FhirAddress::class, 'fhir_address_id');
    }

    /**
     * Get the agente (personnel) associated with the patient.
     */
    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    /**
     * Get the encounters associated with the patient.
     */
    public function encounters()
    {
        return $this->hasMany(FhirEncounter::class, 'fhir_patient_id');
    }

    /**
     * Get the conditions (diagnoses) associated with the patient.
     */
    public function conditions()
    {
        return $this->hasMany(FhirCondition::class, 'fhir_patient_id');
    }

    /**
     * Get the observations associated with the patient.
     */
    public function observations()
    {
        return $this->hasMany(FhirObservation::class, 'fhir_patient_id');
    }

    /**
     * Get the document references associated with the patient.
     */
    public function documentReferences()
    {
        return $this->hasMany(FhirDocumentReference::class, 'fhir_patient_id');
    }

    /**
     * Get the clinical notes associated with the patient.
     */
    public function clinicalNotes()
    {
        return $this->hasMany(FhirClinicalNote::class, 'fhir_patient_id');
    }

    /**
     * Get the patient's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (!$this->name || !isset($this->name['text'])) {
            return 'Unknown Patient';
        }

        return $this->name['text'];
    }

    /**
     * Get the patient's age based on birth date.
     *
     * @return int|null
     */
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        return $this->birth_date->age;
    }
}
