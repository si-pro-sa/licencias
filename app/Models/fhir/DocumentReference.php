<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentReference extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_document_references';
    protected $primaryKey = 'fhir_document_reference_id';

    protected $fillable = [
        'fhir_id',
        'status',
        'docStatus',
        'type',
        'category',
        'subject',
        'encounter',
        'event',
        'facilityType',
        'practiceSetting',
        'period',
        'date',
        'author',
        'attester',
        'custodian',
        'relatesTo',
        'description',
        'securityLabel',
        'content',
        'context',
        'fhir_patient_id',
        'fhir_encounter_id',
        'fhir_provider_id'
    ];

    protected $casts = [
        'status' => 'array',
        'docStatus' => 'array',
        'type' => 'array',
        'category' => 'array',
        'subject' => 'array',
        'encounter' => 'array',
        'event' => 'array',
        'facilityType' => 'array',
        'practiceSetting' => 'array',
        'period' => 'array',
        'author' => 'array',
        'attester' => 'array',
        'custodian' => 'array',
        'relatesTo' => 'array',
        'description' => 'array',
        'securityLabel' => 'array',
        'content' => 'array',
        'context' => 'array',
        'date' => 'datetime'
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

    public function informes()
    {
        return $this->hasMany(\App\Models\Informe::class, 'fhir_document_reference_id');
    }
}
