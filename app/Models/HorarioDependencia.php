<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class HorarioDependencia
 * @package App\Models
 * @version October 28, 2019, 8:09 pm -03
 *
 * @property \App\Models\TipoDia idtipoDia
 * @property \App\Models\Dependencia iddependencia
 * @property integer idtipo_dia
 * @property integer iddependencia
 * @property time hora_desde
 * @property time hora_hasta
 */
class HorarioDependencia extends Model
{
    public $table = 'horario_dependencia';
    protected $primaryKey = 'idhorario_dependencia';
    private static $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'idtipo_dia',
        'iddependencia',
        'hora_desde',
        'hora_hasta',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idhorario_dependencia' => 'integer',
        'idtipo_dia' => 'integer',
        'iddependencia' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idtipo_dia' => 'required',
        'iddependencia' => 'required',
        'hora_desde' => 'required',
        'hora_hasta' => 'required',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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

    public static function getHorarioDependencia(object $puesto): array
    {
        $dependencia = $puesto->dependencia;
        $horarioHistorico = $puesto->horario_historico ?? [];

        $horarios = $puesto->horarios;

        $horarioUnico = $horarios[0] ?? null;

        if (isset($horarioUnico->idtipo_dia) && ($horarioUnico->idtipo_dia < 8)) {
            $tipoHorario = 'p';
        } elseif (isset($horarioUnico->idtipo_dia) && ($horarioUnico->idtipo_dia === 8)) {
            $tipoHorario = 'lv';
        } elseif (isset($horarioUnico->idtipo_dia) && ($horarioUnico->idtipo_dia === 9)) {
            $tipoHorario = 'rot';
        } elseif (isset($horarioUnico->idtipo_dia) && ($horarioUnico->idtipo_dia === 10)) {
            $tipoHorario = 'ld';
        } else {
            $tipoHorario = 'pg';
        }

        $sitemaNuevo = count($horarios) > 1 ? true : false;

        $horarioDependencia = [
            'puesto_id' => $puesto->idpuesto_adicional ?? $puesto->idpuesto,
            'puesto_type' => get_class($puesto),
            'idhorario_puesto' => null,
            'idpuesto' => $puesto->idpuesto,
            'sistema_viejo' => $horarioHistorico->horario_historico ?? '',
            'efector' => $dependencia->getPadre(),
            'idefector' => $dependencia->getIdPadre(),
            'servicio' => $dependencia->dependencia,
            'idservicio' => $puesto->iddependencia,
            'diagramacionHabitual' => ['efector' => '', 'servicio' => ''],
            'agentes' => ['efector' => ['mismo_horario' => 0, 'diferente_horario' => 0, 'total' => 0], 'servicio' => ['mismo_horario' => 0, 'diferente_Horario' => 0, 'total' => 0]],
            'tipoHorario' => $tipoHorario,
            'hora_desde' => $horarioUnico->hora_desde ?? '',
            'hora_hasta' => $horarioUnico->hora_hasta ?? '',
            'cantidad_mensual' => $horarioUnico->cantidad_mensual ?? '',
            'sistema_nuevo' => $sitemaNuevo,
            'dias' => [],
        ];
        for ($i = 0; $i < 7; $i++) {
            $horarioDependencia['dias'][$i] = [
                'isChecked' => false,
                'nombre' => self::$diasSemana[$i],
                'hora_desde' => '',
                'hora_hasta' => '',
                'efector' => [
                    'cantidad_horario' => 0,
                    'cantidad_diferente_horario' => 0,
                    'cantidad_total' => 0
                ],
                'servicio' => [
                    'cantidad_horario' => 0,
                    'cantidad_diferente_horario' => 0,
                    'cantidad_total' => 0
                ]
            ];
        }

        if ($sitemaNuevo && isset($horarioUnico->idtipo_dia) && in_array($horarioUnico->idtipo_dia, [8, 9, 10])) {
            $horarioDependencia['idhorario_puesto'] = $horarioUnico->idhorario_puesto;
            $horarioDependencia['tipoHorario'] = ($horarioUnico->idtipo_dia === 8 ? 'lv' : ($horarioUnico->idtipo_dia === 9 ? 'rot' : 'ld'));
            $horarioDependencia['hora_desde'] = $horarioUnico->hora_desde;
            $horarioDependencia['hora_hasta'] = $horarioUnico->hora_hasta;
            $horarioDependencia['cantidad_mensual'] = $horarioUnico->cantidad_mensual;
        } else {
            for ($i = 0; $i < 7; $i++) {
                foreach ($horarios as $hsn) {
                    if ($hsn->idtipo_dia === ($i + 1) || $hsn->idtipo_dia === ($i + 11)) {
                        $horarioDependencia['dias'][$i]['isChecked'] = true;
                        $horarioDependencia['dias'][$i]['idhorario_puesto'] = $hsn->idhorario_puesto;
                        $horarioDependencia['dias'][$i]['hora_desde'] = $hsn->hora_desde;
                        $horarioDependencia['dias'][$i]['hora_hasta'] = $hsn->hora_hasta;
                    }
                }
            }
        }

        return $horarioDependencia;
    }
}
