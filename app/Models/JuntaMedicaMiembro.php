<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class JuntaMedicaMiembro extends Pivot
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'junta_medica_miembros';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idjuntamedica',
        'idagente',
        'rol',
        'voto',
        'comentario',
        'asistio',
        'fecha_voto'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'asistio' => 'boolean',
        'fecha_voto' => 'datetime'
    ];

    // Constantes para los tipos de votos
    const VOTO_APROBADO = 'aprobado';
    const VOTO_RECHAZADO = 'rechazado';
    const VOTO_ABSTENCION = 'abstencion';

    // Constantes para los roles
    const ROL_PRESIDENTE = 'presidente';
    const ROL_SECRETARIO = 'secretario';
    const ROL_VOCAL = 'vocal';
    const ROL_ASESOR = 'asesor';

    /**
     * Get the junta médica that this miembro belongs to.
     */
    public function juntaMedica()
    {
        return $this->belongsTo(JuntaMedica::class, 'idjuntamedica');
    }

    /**
     * Get the agente that is a miembro of the junta.
     */
    public function agente()
    {
        return $this->belongsTo(Agente::class, 'idagente');
    }

    /**
     * Scope a query to only include asistentes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAsistentes($query)
    {
        return $query->where('asistio', true);
    }

    /**
     * Scope a query to only include miembros with a specific role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|array  $rol
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConRol($query, $rol)
    {
        if (is_array($rol)) {
            return $query->whereIn('rol', $rol);
        }

        return $query->where('rol', $rol);
    }

    /**
     * Scope a query to only include miembros with a specific vote.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|array  $voto
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConVoto($query, $voto)
    {
        if (is_array($voto)) {
            return $query->whereIn('voto', $voto);
        }

        return $query->where('voto', $voto);
    }

    /**
     * Scope a query to only include miembros that have voted.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHanVotado($query)
    {
        return $query->whereNotNull('voto');
    }

    /**
     * Scope a query to only include miembros that have not voted.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNoHanVotado($query)
    {
        return $query->whereNull('voto');
    }

    /**
     * Check if the miembro has voted.
     *
     * @return bool
     */
    public function haVotado()
    {
        return !is_null($this->voto);
    }

    /**
     * Register attendance for the member.
     *
     * @param bool $asistio
     * @return $this
     */
    public function registrarAsistencia($asistio = true)
    {
        $this->asistio = $asistio;
        $this->save();

        return $this;
    }

    /**
     * Cast vote for the member.
     *
     * @param string $voto
     * @param string|null $comentario
     * @return $this
     */
    public function votar($voto, $comentario = null)
    {
        if (!in_array($voto, [self::VOTO_APROBADO, self::VOTO_RECHAZADO, self::VOTO_ABSTENCION])) {
            throw new \InvalidArgumentException('Voto inválido. Debe ser: aprobado, rechazado o abstencion');
        }

        $this->voto = $voto;
        if ($comentario) {
            $this->comentario = $comentario;
        }
        $this->fecha_voto = now();
        $this->save();

        return $this;
    }

    /**
     * Get vote statistics for a specific junta médica.
     *
     * @param int $idjuntamedica
     * @return array
     */
    public static function getEstadisticasVotacion($idjuntamedica)
    {
        $stats = self::where('idjuntamedica', $idjuntamedica)
            ->select('voto', DB::raw('count(*) as total'))
            ->whereNotNull('voto')
            ->groupBy('voto')
            ->get()
            ->pluck('total', 'voto')
            ->toArray();

        $totalVotos = array_sum($stats);
        $aprobados = $stats[self::VOTO_APROBADO] ?? 0;
        $rechazados = $stats[self::VOTO_RECHAZADO] ?? 0;
        $abstenciones = $stats[self::VOTO_ABSTENCION] ?? 0;

        $pctAprobados = $totalVotos > 0 ? round(($aprobados / $totalVotos) * 100, 2) : 0;

        return [
            'aprobados' => $aprobados,
            'rechazados' => $rechazados,
            'abstenciones' => $abstenciones,
            'total' => $totalVotos,
            'porcentaje_aprobacion' => $pctAprobados
        ];
    }

    /**
     * Determine if a junta médica has quorum.
     *
     * @param int $idjuntamedica
     * @return bool
     */
    public static function tieneQuorum($idjuntamedica)
    {
        $junta = JuntaMedica::find($idjuntamedica);
        if (!$junta) {
            return false;
        }

        $asistentes = self::where('idjuntamedica', $idjuntamedica)
            ->where('asistio', true)
            ->count();

        return $asistentes >= $junta->quorum_minimo;
    }
}
