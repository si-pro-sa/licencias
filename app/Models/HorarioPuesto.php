<?php

namespace App\Models;

use App\MasterModel;
use App\Models\RangoTiempo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class HorarioPuesto
 * @package App\Models
 * @version October 6, 2020, 7:07 am -03
 *
 * @property \App\Models\TipoDium idtipoDia
 * @property \App\Models\Dependencium iddependencia
 * @property \App\Models\Sistema.usuario createdBy
 * @property \App\Models\Sistema.usuario updatedBy
 * @property integer puesto_id
 * @property string puesto_type
 * @property integer idtipo_dia
 * @property integer iddependencia
 * @property time hora_desde
 * @property time hora_hasta
 * @property integer cantidad_mensual
 * @property integer cantidad_horas
 * @property integer created_by
 * @property integer updated_by
 */
class HorarioPuesto extends MasterModel
{
    public $table = 'horario_puesto';
    protected $primaryKey = 'idhorario_puesto';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = [];

    protected $appends = ['multiplicador_mensual', 'diff_horas'];

    private $horarioPersonalizado = [1, 2, 3, 4, 5, 6, 7];
    private $horarioPersonalizadoGuardia = [11, 12, 13, 14, 15, 16, 17];
    private $horarioRotativoLaV = [8, 9, 10];

    public $fillable = [
        'puesto_id',
        'puesto_type',
        'idtipo_dia',
        'iddependencia',
        'hora_desde',
        'hora_hasta',
        'cantidad_mensual',
        'cantidad_horas',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idhorario_puesto' => 'integer',
        'puesto_id' => 'integer',
        'puesto_type' => 'string',
        'idtipo_dia' => 'integer',
        'iddependencia' => 'integer',
        'cantidad_mensual' => 'integer',
        'cantidad_horas' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'efectores.*.idpuesto' => 'required',
        'efectores.*.puesto_type' => 'required',
        'efectores.*.tipoHorario' => 'required',
        'efectores.*.hora_desde' => '',
        'efectores.*.hora_hasta' => '',
        'efectores.*.dias' => 'array'
    ];

    /**
     * Get the owning commentable model.
     */
    public function puesto()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoDia()
    {
        return $this->belongsTo(\App\Models\TipoDia::class, 'idtipo_dia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencia()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'iddependencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'updated_by');
    }

    /**
     * Documento Agente
     *
     * @param integer $documento
     * @return void
     */
    public static function buscarDocumentoPuesto(int $documento)
    {
        $agente = Agente::where('documento', $documento)->first();
        $puesto = $agente->puestoActual();
        return self::where('puesto_id', $puesto->idpuesto)->where('puesto_type', get_class($puesto));
    }

    public function format()
    {
        return [
            'id' => $this->idhorario_puesto,
            'dependencia_padre' => (isset($this->dependencia) ? $this->dependencia->getPadre() : ''),
            'dependencia' => ($this->dependencia->dependencia) ?? '',
            'tipo_dia' => ($this->tipoDia->tipodia ?? ''),
            'tipo_dia_corto' => ($this->tipoDia->tipodia_corto ?? ''),
            'hora_desde' => $this->hora_desde,
            'hora_hasta' => $this->hora_hasta,
            'cantidad_mensual' => $this->cantidad_mensual,
            'sistema_nuevo' => true
        ];
    }

    public static function getTipoDia(string $tipoHorario, ?int $cantidad = 0)
    {
        $tipoDia = collect([
            ['tipoHorario' => 'p', 'idtipo_dia' => $cantidad + 1, 'cantidad_mensual' => 4],
            ['tipoHorario' => 'pg', 'idtipo_dia' => $cantidad + 11, 'cantidad_mensual' => 4],
            ['tipoHorario' => 'lv', 'idtipo_dia' => 8, 'cantidad_mensual' => 4],
            ['tipoHorario' => 'rot', 'idtipo_dia' => 9, 'cantidad_mensual' => $cantidad],
            ['tipoHorario' => 'ld', 'idtipo_dia' => 10, 'cantidad_mensual' => 4],
        ]);

        return $tipoDia->firstWhere('tipoHorario', $tipoHorario);
    }

    public function scopePuestoAdicional($query, int $idpuesto_adicional)
    {
        return $query->where('puesto_id', $idpuesto_adicional)
            ->where('puesto_type', 'App\Models\PuestoAdicional');
    }

    public function scopePuestoPrincipal($query, int $idpuesto)
    {
        return $query->where('puesto_id', $idpuesto)
            ->where('puesto_type', 'App\Models\Puesto');
    }

    public function scopePersonalizado($query)
    {
        return $query->whereIn('idtipo_dia', $this->horarioPersonalizado);
    }

    public function scopePersonalizadoGuardia($query)
    {
        return $query->whereIn('idtipo_dia', $this->horarioPersonalizadoGuardia);
    }

    public function scopeLunesViernesOrRotativo($query)
    {
        return $query->whereIn('idtipo_dia', [8, 9]);
    }

    public function scopeLunesViernes($query)
    {
        return $query->where('idtipo_dia', 8);
    }

    public function scopeRotativo($query)
    {
        return $query->where('idtipo_dia', 9);
    }

    public function scopeLunesDomingo($query)
    {
        return $query->where('idtipo_dia', 10);
    }

    /**
     * Busca horario utilizando el Documento del Agente
     *
     * @param integer $documento
     * @return void
     */
    public function scopeDocumento($query, $documento)
    {
        return $query->whereHasMorph(
            'puesto',
            [Puesto::class, PuestoAdicional::class],
            function ($que, $type) use ($documento) {
                if ($type === 'App\Models\Puesto') {
                    $que->where('fhasta', null)->whereHas(
                        'agente',
                        function ($q) use ($documento) {
                            $q->whereRaw('documento = ?', intval($documento));
                        }
                    );
                } else {
                    $que->whereHas(
                        'puesto',
                        function ($qu) use ($documento) {
                            $qu->where('fhasta', null)->whereHas(
                                'agente',
                                function ($q) use ($documento) {
                                    $q->whereRaw('documento = ?', intval($documento));
                                }
                            );
                        }
                    );
                }
            }
        );
    }

    /**
     * Horario Personalizado
     *
     * @param integer $documento
     * @return void
     */
    public function scopeSoloHorarioPersonalizado($query)
    {
        return $query->whereIn('idtipo_dia', [1, 2, 3, 4, 5, 6, 7]);
    }

    public function puestoActivo($query)
    {
        return $query->whereHasMorph(
            'puesto',
            Puesto::class,
            function (Builder $q) {
                $q->whereNull('fhasta');
            }
        );
    }

    /**
     * Retorna Hora desde sin los Segundos
     *
     * @return string
     */
    public function getHoraDesdeAttribute($value)
    {
        return date('H:i', strtotime('2020-01-01 ' . $value));
    }

    /**
     * Retorna Hora hasta sin los Segundos
     *
     * @return string
     */
    public function getHoraHastaAttribute($value)
    {
        return date('H:i', strtotime('2020-01-01 ' . $value));
    }

    /**
     * Retorna Horas Mensuales
     *
     */
    public function getHorasMensualesAttribute($value)
    {
        $rango = new RangoTiempo($this->hora_desde->format('H:i'), $this->hora_hasta->format('H:i'));
        return $rango->getDiffHoras() * $this->multiplicador_mensual;
    }

    public function getMultiplicadorMensualAttribute(): int
    {
        switch ($this->idtipo_dia) {
            case 8: //lunes a viernes
                return 20;
            case 9: //rotativo
                return $this->cantidad_mensual;
            case 10: //lunes a domingo
                return 30;
            default:
                return 4;
        }
    }

    /**
     * Retorna Horas Mensuales
     *
     */
    public function getDiffHorasAttribute($value)
    {
        $rango = new RangoTiempo($this->hora_desde, $this->hora_hasta);
        return $rango->getDiffHoras();
    }

    /**
     * Horario Distinto a Personalizado
     *
     * @param integer $documento
     * @return void
     */
    public function scopeSoloHorarioDistintoPersonalizado($query)
    {
        return $query->whereIn('idtipo_dia', [8, 9, 10]);
    }

    public static function createOrUpdateHorario(int $puesto_id, string $puesto_type, $tipoHorario, int $idservicio, string $hora_desde, string $hora_hasta, ?int $keyOrCantidadMensual, bool $isTipoDia = false): bool
    {
        if (isset($puesto_id, $puesto_type, $tipoHorario, $idservicio, $hora_desde, $hora_hasta)) {
            $tipoDia = $isTipoDia ? (int) $tipoHorario : self::getTipoDia($tipoHorario, $keyOrCantidadMensual);

            if (isset($tipoDia)) {
                $horario = new HorarioPuesto;
                $horario->puesto_id = $puesto_id;
                $horario->puesto_type = $puesto_type;
                $horario->idtipo_dia = $isTipoDia ? $tipoDia : $tipoDia['idtipo_dia'];
                $horario->iddependencia = $idservicio;
                $horario->hora_desde = $hora_desde;
                $horario->hora_hasta = $hora_hasta;
                $horario->cantidad_mensual = $isTipoDia ? $keyOrCantidadMensual : $tipoDia['cantidad_mensual'];

                return $horario->save();
            }
        }
        return false;
    }
}
