<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\FhirGender;

class FhirProvider extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhir_providers';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_provider_id';

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
        'qualification',
        'telecom',
        'fhir_address_id',
        'fhir_facility_id',
        'communication',
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
        'qualification' => 'array',
        'telecom' => 'array',
        'communication' => 'array'
    ];

    /**
     * Get the address associated with the provider.
     */
    public function address()
    {
        return $this->belongsTo(FhirAddress::class, 'fhir_address_id');
    }

    /**
     * Get the facility associated with the provider.
     */
    public function facility()
    {
        return $this->belongsTo(FhirFacility::class, 'fhir_facility_id');
    }

    /**
     * Get the agente (personnel) associated with the provider.
     */
    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    /**
     * Get the encounters where this provider is the primary provider.
     */
    public function encounters()
    {
        return $this->hasMany(FhirEncounter::class, 'fhir_provider_id');
    }

    /**
     * Get the clinical notes authored by this provider.
     */
    public function clinicalNotes()
    {
        return $this->morphMany(FhirClinicalNote::class, 'author');
    }

    /**
     * Get the provider's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (!$this->name || !isset($this->name['text'])) {
            return 'Unknown Provider';
        }

        return $this->name['text'];
    }
}
