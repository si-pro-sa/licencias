<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\FhirResourceStatus;

class FhirLocation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhir_locations';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_location_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'array',
        'alias' => 'array',
        'type' => 'array',
        'telecom' => 'array',
        'physicalType' => 'array',
        'position' => 'array',
        'status' => FhirResourceStatus::class
    ];

    /**
     * Get the address associated with the location.
     *
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(FhirAddress::class, 'fhir_address_id');
    }

    /**
     * Get the facility associated with the location.
     *
     * @return HasOne
     */
    public function facility(): HasOne
    {
        return $this->hasOne(FhirFacility::class, 'fhir_location_id');
    }

    /**
     * Get the encounters associated with the location.
     *
     * @return HasMany
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(FhirEncounter::class, 'fhir_location_id');
    }

    /**
     * Get the associated localidad (local jurisdiction).
     *
     * @return BelongsTo
     */
    public function localidad(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Localidad::class, 'idlocalidad');
    }

    /**
     * Get the primary name of the location.
     *
     * @return string|null
     */
    public function getPrimaryNameAttribute(): ?string
    {
        if (!$this->name) {
            return null;
        }

        return $this->name['text'] ?? null;
    }
}
