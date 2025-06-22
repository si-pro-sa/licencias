<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $table = 'fhir_locations';
    protected $primaryKey = 'fhir_location_id';

    protected $fillable = [
        'fhir_id',
        'status',
        'name',
        'alias',
        'description',
        'mode',
        'type',
        'telecom',
        'fhir_address_id',
        'physicalType',
        'position',
        'idlocalidad'
    ];

    protected $casts = [
        'name' => 'array',
        'alias' => 'array',
        'type' => 'array',
        'telecom' => 'array',
        'physicalType' => 'array',
        'position' => 'array'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'fhir_address_id');
    }

    public function facility()
    {
        return $this->hasOne(Facility::class, 'fhir_location_id');
    }

    public function encounters()
    {
        return $this->hasMany(Encounter::class, 'fhir_location_id');
    }

    public function juntasMedicas()
    {
        return $this->hasMany(JuntaMedica::class, 'fhir_location_id');
    }

    public function localidad()
    {
        return $this->belongsTo(\App\Models\Localidad::class, 'idlocalidad');
    }
}
