<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LicenciaSaludOcupacional extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'licencias_salud_ocupacional';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idlicencia_salud_ocupacional';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'motivo',
        'tipo',
        'idagente',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Get the agent (patient) associated with the licencia.
     *
     * @return BelongsTo
     */
    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class, 'idagente');
    }

    /**
     * Get the juntas médicas associated with the licencia.
     *
     * @return HasMany
     */
    public function juntasMedicas(): HasMany
    {
        return $this->hasMany(JuntaMedica::class, 'idlicencia_salud_ocupacional');
    }

    /**
     * Get the patient FHIR resource associated with the agent.
     *
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(FhirPatient::class, 'idagente', 'idagente');
    }

    /**
     * Check if the license is active.
     *
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->estado === 'activa' &&
            ($this->fecha_fin === null || $this->fecha_fin->isFuture());
    }

    /**
     * Get the duration of the license in days.
     *
     * @return int|null
     */
    public function getDurationDaysAttribute(): ?int
    {
        if (!$this->fecha_fin) {
            return null;
        }

        return $this->fecha_inicio->diffInDays($this->fecha_fin);
    }
}
