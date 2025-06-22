<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\FhirResourceStatus;

class FhirFacility extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fhir_facilities';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fhir_facility_id';

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
        'type',
        'telecom',
        'fhir_address_id',
        'fhir_location_id'
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
        'status' => FhirResourceStatus::class
    ];

    /**
     * Get the address associated with the facility.
     *
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(FhirAddress::class, 'fhir_address_id');
    }

    /**
     * Get the location associated with the facility.
     *
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(FhirLocation::class, 'fhir_location_id');
    }

    /**
     * Get the providers associated with the facility.
     *
     * @return HasMany
     */
    public function providers(): HasMany
    {
        return $this->hasMany(FhirProvider::class, 'fhir_facility_id');
    }

    /**
     * Get the encounters associated with the facility.
     *
     * @return HasMany
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(FhirEncounter::class, 'fhir_facility_id');
    }

    /**
     * Get the primary name of the facility.
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
