<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Observation extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_observations';
    protected $primaryKey = 'fhir_observation_id';

    protected $fillable = [
        'fhir_id',
        'status',
        'category',
        'code',
        'subject',
        'focus',
        'encounter',
        'effective',
        'issued',
        'performer',
        'value',
        'dataAbsentReason',
        'interpretation',
        'note',
        'bodySite',
        'method',
        'specimen',
        'device',
        'referenceRange',
        'hasMember',
        'derivedFrom',
        'component',
        'fhir_patient_id',
        'fhir_encounter_id',
        'fhir_provider_id'
    ];

    protected $casts = [
        'status' => 'array',
        'category' => 'array',
        'code' => 'array',
        'subject' => 'array',
        'focus' => 'array',
        'encounter' => 'array',
        'effective' => 'array',
        'performer' => 'array',
        'value' => 'array',
        'dataAbsentReason' => 'array',
        'interpretation' => 'array',
        'note' => 'array',
        'bodySite' => 'array',
        'method' => 'array',
        'specimen' => 'array',
        'device' => 'array',
        'referenceRange' => 'array',
        'hasMember' => 'array',
        'derivedFrom' => 'array',
        'component' => 'array',
        'issued' => 'datetime'
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
