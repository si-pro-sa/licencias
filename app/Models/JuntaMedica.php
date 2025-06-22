<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Fhir\Provider;
use Illuminate\Support\Facades\DB;

class JuntaMedica extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juntas_medicas';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idjuntamedica';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'tipo',
        'descripcion',
        'fecha',
        'estado',
        'fhir_facility_id',
        'idlicencia_salud_ocupacional',
        'fecha_creacion',
        'fecha_deliberacion',
        'fecha_finalizacion',
        'observaciones',
        'diagnostico',
        'recomendaciones',
        'resolucion',
        'quorum_minimo',
        'idagente_presidente'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
        'fecha_creacion' => 'datetime',
        'fecha_deliberacion' => 'datetime',
        'fecha_finalizacion' => 'datetime',
        'quorum_minimo' => 'integer'
    ];

    // Constantes para los estados de la junta médica
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_EN_DELIBERACION = 'en_deliberacion';
    const ESTADO_FINALIZADA = 'finalizada';
    const ESTADO_CANCELADA = 'cancelada';

    // Constantes para los tipos de junta médica
    const TIPO_ORDINARIA = 'ordinaria';
    const TIPO_EXTRAORDINARIA = 'extraordinaria';
    const TIPO_URGENCIA = 'urgencia';

    // Constantes para las resoluciones
    const RESOLUCION_APROBADA = 'aprobada';
    const RESOLUCION_RECHAZADA = 'rechazada';
    const RESOLUCION_EMPATE = 'empate';
    const RESOLUCION_SIN_QUORUM = 'sin_quorum';
    const RESOLUCION_PENDIENTE = 'pendiente';

    /**
     * Get the facility associated with the junta médica.
     *
     * @return BelongsTo
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(FhirFacility::class, 'fhir_facility_id');
    }

    /**
     * Get the licencia de salud ocupacional associated with the junta médica.
     *
     * @return BelongsTo
     */
    public function licenciaSaludOcupacional(): BelongsTo
    {
        return $this->belongsTo(LicenciaSaludOcupacional::class, 'idlicencia_salud_ocupacional');
    }

    /**
     * Get the encounters associated with the junta médica.
     *
     * @return HasMany
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(FhirEncounter::class, 'idjuntamedica');
    }

    /**
     * Get the FHIR providers associated with the junta médica.
     *
     * @return BelongsToMany
     */
    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class, 'fhir_provider_junta_medica');
    }

    /**
     * Get the presidente de la junta médica.
     *
     * @return BelongsTo
     */
    public function presidente(): BelongsTo
    {
        return $this->belongsTo(Agente::class, 'idagente_presidente');
    }

    /**
     * Get the miembros de la junta médica.
     *
     * @return BelongsToMany
     */
    public function miembros(): BelongsToMany
    {
        return $this->belongsToMany(Agente::class, 'junta_medica_miembros', 'idjuntamedica', 'idagente')
            ->withPivot(['rol', 'voto', 'comentario', 'asistio', 'fecha_voto'])
            ->withTimestamps()
            ->using(JuntaMedicaMiembro::class);
    }

    /**
     * Get the pivot relationship entries for the junta médica miembros.
     *
     * @return HasMany
     */
    public function miembrosPivot(): HasMany
    {
        return $this->hasMany(JuntaMedicaMiembro::class, 'idjuntamedica');
    }

    /**
     * Get the miembros de la junta médica que asistieron.
     *
     * @return BelongsToMany
     */
    public function miembrosAsistentes(): BelongsToMany
    {
        return $this->miembros()->wherePivot('asistio', true);
    }

    /**
     * Get the miembros de la junta médica con voto registrado.
     *
     * @return BelongsToMany
     */
    public function miembrosConVoto(): BelongsToMany
    {
        return $this->miembros()->whereNotNull('junta_medica_miembros.voto');
    }

    /**
     * Get the miembros de la junta médica sin voto registrado.
     *
     * @return BelongsToMany
     */
    public function miembrosSinVoto(): BelongsToMany
    {
        return $this->miembros()->whereNull('junta_medica_miembros.voto');
    }

    /**
     * Get the miembros de la junta médica por rol.
     *
     * @param string|array $rol
     * @return BelongsToMany
     */
    public function miembrosPorRol($rol): BelongsToMany
    {
        if (is_array($rol)) {
            return $this->miembros()->wherePivotIn('rol', $rol);
        }

        return $this->miembros()->wherePivot('rol', $rol);
    }

    /**
     * Get the votos de la junta médica.
     *
     * @param string|null $tipo
     * @return BelongsToMany
     */
    public function votos(?string $tipo = null): BelongsToMany
    {
        $query = $this->miembros()->whereNotNull('junta_medica_miembros.voto');

        if ($tipo) {
            $query->wherePivot('voto', $tipo);
        }

        return $query;
    }

    /**
     * Check if the junta médica has quorum.
     *
     * @return bool
     */
    public function tieneQuorum(): bool
    {
        return $this->miembrosAsistentes()->count() >= $this->quorum_minimo;
    }

    /**
     * Get the resultado de la votación.
     *
     * @return array
     */
    public function resultadoVotacion(): array
    {
        $aprobados = $this->votos(JuntaMedicaMiembro::VOTO_APROBADO)->count();
        $rechazados = $this->votos(JuntaMedicaMiembro::VOTO_RECHAZADO)->count();
        $abstenciones = $this->votos(JuntaMedicaMiembro::VOTO_ABSTENCION)->count();
        $total = $aprobados + $rechazados + $abstenciones;

        return [
            'aprobados' => $aprobados,
            'rechazados' => $rechazados,
            'abstenciones' => $abstenciones,
            'total' => $total,
            'porcentaje_aprobacion' => $total > 0 ? round(($aprobados / $total) * 100, 2) : 0
        ];
    }

    /**
     * Add a miembro to the junta médica.
     *
     * @param int|Agente $agente
     * @param string $rol
     * @param bool $asistio
     * @return JuntaMedicaMiembro
     */
    public function agregarMiembro($agente, string $rol, bool $asistio = false)
    {
        $idagente = $agente instanceof Agente ? $agente->idagente : $agente;

        $pivot = $this->miembros()->attach($idagente, [
            'rol' => $rol,
            'asistio' => $asistio,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $this->miembrosPivot()
            ->where('idagente', $idagente)
            ->first();
    }

    /**
     * Remove a miembro from the junta médica.
     *
     * @param int|Agente $agente
     * @return int
     */
    public function eliminarMiembro($agente)
    {
        $idagente = $agente instanceof Agente ? $agente->idagente : $agente;

        return $this->miembros()->detach($idagente);
    }

    /**
     * Register attendance for a miembro.
     *
     * @param int|Agente $agente
     * @param bool $asistio
     * @return JuntaMedicaMiembro|null
     */
    public function registrarAsistencia($agente, bool $asistio = true)
    {
        $idagente = $agente instanceof Agente ? $agente->idagente : $agente;

        $miembro = $this->miembrosPivot()->where('idagente', $idagente)->first();

        if ($miembro) {
            $miembro->registrarAsistencia($asistio);
            return $miembro;
        }

        return null;
    }

    /**
     * Register vote for a miembro.
     *
     * @param int|Agente $agente
     * @param string $voto
     * @param string|null $comentario
     * @return JuntaMedicaMiembro|null
     */
    public function registrarVoto($agente, string $voto, ?string $comentario = null)
    {
        $idagente = $agente instanceof Agente ? $agente->idagente : $agente;

        $miembro = $this->miembrosPivot()->where('idagente', $idagente)->first();

        if ($miembro) {
            $miembro->votar($voto, $comentario);
            return $miembro;
        }

        return null;
    }

    /**
     * Determine the resolution based on votes.
     *
     * @return string
     */
    public function determinarResolucion(): string
    {
        if (!$this->tieneQuorum()) {
            return self::RESOLUCION_SIN_QUORUM;
        }

        $resultado = $this->resultadoVotacion();

        if ($resultado['total'] === 0) {
            return self::RESOLUCION_PENDIENTE;
        }

        if ($resultado['aprobados'] > $resultado['rechazados']) {
            return self::RESOLUCION_APROBADA;
        } else if ($resultado['aprobados'] < $resultado['rechazados']) {
            return self::RESOLUCION_RECHAZADA;
        } else {
            return self::RESOLUCION_EMPATE;
        }
    }

    /**
     * Finalizar la junta médica y registrar su resolución.
     *
     * @param string|null $observaciones
     * @return $this
     */
    public function finalizar(?string $observaciones = null)
    {
        $this->estado = self::ESTADO_FINALIZADA;
        $this->fecha_finalizacion = now();
        $this->resolucion = $this->determinarResolucion();

        if ($observaciones) {
            $this->observaciones = $observaciones;
        }

        $this->save();

        return $this;
    }

    /**
     * Iniciar deliberación de la junta médica.
     *
     * @return $this
     */
    public function iniciarDeliberacion()
    {
        $this->estado = self::ESTADO_EN_DELIBERACION;
        $this->fecha_deliberacion = now();
        $this->save();

        return $this;
    }

    /**
     * Cancelar la junta médica.
     *
     * @param string|null $motivo
     * @return $this
     */
    public function cancelar(?string $motivo = null)
    {
        $this->estado = self::ESTADO_CANCELADA;

        if ($motivo) {
            $this->observaciones = $motivo;
        }

        $this->save();

        return $this;
    }

    /**
     * Check if all members have voted.
     *
     * @return bool
     */
    public function todosHanVotado(): bool
    {
        $totalAsistentes = $this->miembrosAsistentes()->count();
        $totalVotados = $this->miembrosConVoto()->count();

        return $totalAsistentes > 0 && $totalAsistentes === $totalVotados;
    }

    /**
     * Set the diagnóstico for the junta médica.
     *
     * @param string $diagnostico
     * @return $this
     */
    public function setDiagnostico(string $diagnostico)
    {
        $this->diagnostico = $diagnostico;
        $this->save();

        return $this;
    }

    /**
     * Set the recomendaciones for the junta médica.
     *
     * @param string $recomendaciones
     * @return $this
     */
    public function setRecomendaciones(string $recomendaciones)
    {
        $this->recomendaciones = $recomendaciones;
        $this->save();

        return $this;
    }

    /**
     * Check if the junta médica is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return in_array($this->estado, [self::ESTADO_PENDIENTE, self::ESTADO_EN_DELIBERACION]);
    }
}
