<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encounter extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_encounters';
    protected $primaryKey = 'fhir_encounter_id';

    protected $fillable = [
        'fhir_id',
        'status',
        'class',
        'type',
        'serviceType',
        'subject',
        'participant',
        'period',
        'length',
        'reasonCode',
        'diagnosis',
        'account',
        'hospitalization',
        'location',
        'fhir_patient_id',
        'fhir_provider_id',
        'fhir_facility_id',
        'fhir_location_id',
        'idlicencia'
    ];

    protected $casts = [
        'class' => 'array',
        'type' => 'array',
        'serviceType' => 'array',
        'subject' => 'array',
        'participant' => 'array',
        'period' => 'array',
        'length' => 'array',
        'reasonCode' => 'array',
        'diagnosis' => 'array',
        'account' => 'array',
        'hospitalization' => 'array',
        'location' => 'array'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'fhir_patient_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'fhir_provider_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'fhir_facility_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'fhir_location_id');
    }

    public function licencia()
    {
        return $this->belongsTo(\App\Models\Licencia::class, 'idlicencia');
    }

    public function conditions()
    {
        return $this->hasMany(Condition::class, 'fhir_encounter_id');
    }

    public function observations()
    {
        return $this->hasMany(Observation::class, 'fhir_encounter_id');
    }

    public function documentReferences()
    {
        return $this->hasMany(DocumentReference::class, 'fhir_encounter_id');
    }

    public function informes()
    {
        return $this->hasMany(\App\Models\Informe::class, 'fhir_encounter_id');
    }
}
