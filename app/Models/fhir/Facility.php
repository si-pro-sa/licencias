<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_facilities';
    protected $primaryKey = 'fhir_facility_id';

    protected $fillable = [
        'fhir_id',
        'status',
        'name',
        'alias',
        'description',
        'type',
        'telecom',
        'fhir_address_id',
        'fhir_location_id'
    ];

    protected $casts = [
        'name' => 'array',
        'alias' => 'array',
        'type' => 'array',
        'telecom' => 'array'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'fhir_address_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'fhir_location_id');
    }

    public function providers()
    {
        return $this->hasMany(Provider::class, 'fhir_facility_id');
    }

    public function encounters()
    {
        return $this->hasMany(Encounter::class, 'fhir_facility_id');
    }

    public function juntasMedicas()
    {
        return $this->hasMany(JuntaMedica::class, 'fhir_facility_id');
    }
}
