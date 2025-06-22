<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class HorarioPuestoHistorico
 * @package App\Models
 * @version October 5, 2020, 10:08 pm -03
 *
 * @property \App\Models\Puesto idpuesto
 * @property \App\Models\TipoHorario idtipoHorario
 * @property integer idpuesto
 * @property integer idtipo_horario
 * @property string dias_guardia
 * @property time hora_desde
 * @property time hora_hasta
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class HorarioPuestoHistorico extends Model
{
    public $table = 'horario_puesto_historico';
    protected $primaryKey = 'idhorario_puesto';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];

    public $fillable = [
        'idpuesto',
        'idtipo_horario',
        'dias_guardia',
        'hora_desde',
        'hora_hasta',
        'usuario',
        'operacion',
        'foperacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idhorario_puesto' => 'integer',
        'idpuesto' => 'integer',
        'idtipo_horario' => 'integer',
        'dias_guardia' => 'string',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idpuesto' => 'required',
        'idtipo_horario' => 'required',
        'hora_desde' => 'required',
        'hora_hasta' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function puesto()
    {
        return $this->belongsTo(\App\Models\Puesto::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoHorario()
    {
        return $this->belongsTo(\App\Models\TipoHorario::class, 'idtipo_horario');
    }

    public function getHorarioHistoricoAttribute()
    {
        return $this->tipoHorario->tipohorario . ' - ' . $this->hora_desde . ' a ' . $this->hora_hasta;
    }

    public function scopeDocumento($query, $documento)
    {
        return $query->whereHas(
            'puesto',
            function ($que) use ($documento) {
                $que->where('fhasta', null)->whereHas(
                    'agente',
                    function ($q) use ($documento) {
                        $q->whereRaw("documento = ?", intval($documento));
                    }
                );
            }
        );
    }

    public function format()
    {
        $tipoDia = '';
        if (isset($this->tipoHorario->tipohorario)) {
            if ($this->idtipo_horario === 4) {
                $tipoDia = "{$this->tipoHorario->tipohorario}";
            } else {
                $tipoDia = "{$this->tipoHorario->tipohorario} {$this->dias_guardia}";
            }
        }
        return [
            'id' => $this->idhorario_puesto,
            'dependencia_padre' => (isset($this->puesto->dependencia) ? $this->puesto->dependencia->getPadre() : ''),
            'dependencia' => ($this->puesto->dependencia->dependencia) ?? '',
            'hora_desde' => $this->hora_desde,
            'hora_hasta' => $this->hora_hasta,
            'tipo_dia' => $tipoDia,
            'sistema_nuevo' => false
        ];
    }

    /**
     * Retorna el día que trabaja.
     * Si es 8, trabaja de lunes a viernes
     * Si es 9, es rotativo.
     * @return int|null
     */
    public function getNumeroDiaTrabajoAttribute(): int
    {
        if ($this->idtipo_horario === 4) {
            return 8;
        } else {
            if (isset($this->idtipo_horario)) {
                switch ($this->dias_guardia) {
                    case 'LUNES':
                        return 1;
                    case 'MARTES':
                        return 2;
                    case 'MIERCOLES':
                        return 3;
                    case 'JUEVES':
                        return 4;
                    case 'VIERNES':
                        return 5;
                    case 'SABADO':
                        return 6;
                    case 'DOMINGO':
                        return 7;
                    default:
                        return 9;
                }
            } else {
                return null;
            }
        }
    }
}
