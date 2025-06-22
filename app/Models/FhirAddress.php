<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\FhirAddressType;
use App\Enums\FhirAddressUse;

class FhirAddress extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'fhir_addresses';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_address_id';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
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
        'longitude',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'use' => 'string',
        'type' => FhirAddressType::class,
    ];

    /**
     * Obtener el tipo de uso de la dirección como enum
     *
     * @return \App\Enums\FhirAddressUse|null
     */
    public function getUseEnumAttribute()
    {
        return $this->use ? FhirAddressUse::from($this->use) : null;
    }

    /**
     * Obtiene las ubicaciones asociadas a esta dirección.
     */
    public function locations()
    {
        return $this->hasMany(FhirLocation::class, 'fhir_address_id');
    }

    /**
     * Obtiene los establecimientos asociados a esta dirección.
     */
    public function facilities()
    {
        return $this->hasMany(FhirFacility::class, 'fhir_address_id');
    }

    /**
     * Obtiene los proveedores asociados a esta dirección.
     */
    public function providers()
    {
        return $this->hasMany(FhirProvider::class, 'fhir_address_id');
    }

    /**
     * Obtiene los pacientes asociados a esta dirección.
     */
    public function patients()
    {
        return $this->hasMany(FhirPatient::class, 'fhir_address_id');
    }

    /**
     * Obtiene la dirección formateada
     *
     * @return string
     */
    public function getFormattedAddressAttribute()
    {
        $parts = [
            $this->line1,
            $this->line2,
            $this->city,
            $this->district,
            $this->state,
            $this->postal_code,
            $this->country
        ];

        // Filtrar partes vacías
        $parts = array_filter($parts);

        return implode(', ', $parts);
    }
}
