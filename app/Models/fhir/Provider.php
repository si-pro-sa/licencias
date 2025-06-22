<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JuntaMedica;

class Provider extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_providers';
    protected $primaryKey = 'fhir_provider_id';

    protected $fillable = [
        'fhir_id',
        'npi',
        'active',
        'name',
        'gender',
        'birth_date',
        'fhir_address_id',
        'telecom',
        'qualification',
        'communication',
        'idagente',
        'fhir_facility_id'
    ];

    protected $casts = [
        'active' => 'boolean',
        'name' => 'array',
        'telecom' => 'array',
        'qualification' => 'array',
        'communication' => 'array',
        'birth_date' => 'date'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'fhir_address_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'fhir_facility_id');
    }

    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    public function encounters()
    {
        return $this->hasMany(Encounter::class, 'fhir_provider_id');
    }

    public function informes()
    {
        return $this->hasMany(\App\Models\Informe::class, 'fhir_provider_id');
    }

    public function juntasMedicas()
    {
        return $this->belongsToMany(JuntaMedica::class, 'fhir_provider_junta_medica', 'fhir_provider_id', 'junta_medica_id')
            ->withPivot('rol')
            ->withTimestamps();
    }
}
