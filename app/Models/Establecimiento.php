<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model for healthcare facilities
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $codigo_sisa
 * @property string $tipo_establecimiento
 * @property int $nivel_atencion
 * @property string $direccion
 * @property string $provincia
 * @property string $localidad
 * @property string|null $codigo_postal
 * @property string|null $telefono
 * @property string|null $email
 * @property string|null $responsable
 * @property string|null $servicios
 * @property string|null $observaciones
 * @property boolean $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Establecimiento extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'establecimientos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'codigo_sisa',
        'tipo_establecimiento',
        'nivel_atencion',
        'direccion',
        'provincia',
        'localidad',
        'codigo_postal',
        'telefono',
        'email',
        'responsable',
        'servicios',
        'observaciones',
        'activo'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nivel_atencion' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['servicios_array'];

    /**
     * Get the services as an array
     *
     * @return array
     */
    public function getServiciosArrayAttribute()
    {
        if ($this->servicios) {
            return json_decode($this->servicios, true) ?? [];
        }
        return [];
    }

    /**
     * Set the services from an array
     *
     * @param array $value
     * @return void
     */
    public function setServiciosAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['servicios'] = json_encode($value);
        } else {
            $this->attributes['servicios'] = $value;
        }
    }

    /**
     * Scope a query to only include active facilities
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope a query to filter by facility type
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $tipo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_establecimiento', $tipo);
    }

    /**
     * Scope a query to filter by attention level
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $nivel
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNivel($query, $nivel)
    {
        return $query->where('nivel_atencion', $nivel);
    }

    /**
     * Scope a query to filter by province
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $provincia
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProvincia($query, $provincia)
    {
        return $query->where('provincia', $provincia);
    }
}
