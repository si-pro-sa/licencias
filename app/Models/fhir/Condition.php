<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condition extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_conditions';
    protected $primaryKey = 'fhir_condition_id';

    protected $fillable = [
        'fhir_id',
        'clinicalStatus',
        'verificationStatus',
        'category',
        'severity',
        'code',
        'bodySite',
        'subject',
        'encounter',
        'onset',
        'abatement',
        'recordedDate',
        'recorder',
        'asserter',
        'stage',
        'evidence',
        'note',
        'fhir_patient_id',
        'fhir_encounter_id',
        'fhir_provider_id'
    ];

    protected $casts = [
        'clinicalStatus' => 'array',
        'verificationStatus' => 'array',
        'category' => 'array',
        'severity' => 'array',
        'code' => 'array',
        'bodySite' => 'array',
        'subject' => 'array',
        'encounter' => 'array',
        'onset' => 'array',
        'abatement' => 'array',
        'recorder' => 'array',
        'asserter' => 'array',
        'stage' => 'array',
        'evidence' => 'array',
        'note' => 'array',
        'recordedDate' => 'datetime'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'fhir_patient_id');
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class, 'fhir_encounter_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'fhir_provider_id');
    }
}
