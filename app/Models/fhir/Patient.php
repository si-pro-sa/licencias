<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_patients';
    protected $primaryKey = 'fhir_patient_id';

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

    protected $casts = [
        'active' => 'boolean',
        'name' => 'array',
        'telecom' => 'array',
        'communication' => 'array',
        'generalPractitioner' => 'array',
        'managingOrganization' => 'array',
        'link' => 'array',
        'birth_date' => 'date',
        'deceased' => 'datetime'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'fhir_address_id');
    }

    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    public function encounters()
    {
        return $this->hasMany(Encounter::class, 'fhir_patient_id');
    }

    public function conditions()
    {
        return $this->hasMany(Condition::class, 'fhir_patient_id');
    }

    public function observations()
    {
        return $this->hasMany(Observation::class, 'fhir_patient_id');
    }

    public function documentReferences()
    {
        return $this->hasMany(DocumentReference::class, 'fhir_patient_id');
    }
}
