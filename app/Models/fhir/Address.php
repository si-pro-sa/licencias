<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_addresses';
    protected $primaryKey = 'fhir_address_id';

    protected $fillable = [
        'fhir_id',
        'use',
        'type',
        'line1',
        'line2',
        'city',
        'district',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function location()
    {
        return $this->hasOne(Location::class, 'fhir_address_id');
    }

    public function facility()
    {
        return $this->hasOne(Facility::class, 'fhir_address_id');
    }

    public function provider()
    {
        return $this->hasOne(Provider::class, 'fhir_address_id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'fhir_address_id');
    }
}
