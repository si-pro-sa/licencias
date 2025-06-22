<?php

namespace App\Models;

use App\MasterModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\TipoVisadoReclamoDePago;

class ReclamoDePago extends MasterModel
{
    use SoftDeletes;

    public $table = 'reclamo_de_pago';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'idreclamo_de_pago';

    protected $fillable = [
        'idagente',
        'idefector',
        'idservicio',
        'idperiodo_ep',
        'idtipo_formulario_reclamo_de_pago',
        'fdesde_ep',
        'fhasta_ep',
        'expediente',
        'observacion_efector',
        'observacion_visado',
        'tipo_formulario_reclamo',
    ];

    protected $casts = [
        'idagente' => 'integer',
        'idefector' => 'integer',
        'idservicio' => 'integer',
        'idperiodo_ep' => 'integer',
        'idperiodo_liq' => 'integer',
        'idtipo_visado' => 'integer',
        'fdesde_ep' => 'datetime',
        'fhasta_ep' => 'datetime',
        'observacion_efector' => 'string',
        'observacion_ampliada' => 'string',
    ];

    public static $rules = [
        'idefector' => 'required|integer',
        'idservicio' => 'required|integer',
        'idperiodo_ep' => 'required|integer',
        'idtipo_formulario_reclamo_de_pago' => 'required|integer',
        'expediente' => 'required|string',
        'observacion_efector' => 'string',
    ];


    public function getTipoAttribute()
    {
        return $this->tipoFormulario->tipo_formulario;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoFormulario()
    {
        return $this->belongsTo(TipoFormularioReclamoDePago::class, 'idtipo_formulario_reclamo_de_pago');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function deletedBy()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agente()
    {
        return $this->belongsTo(Agente::class, 'idagente');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function periodoEP()
    {
        return $this->belongsTo(Periodo::class, 'idperiodo_ep');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function periodoLiquidacion()
    {
        return $this->belongsTo(Periodo::class, 'idperiodo_ep');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function efector()
    {
        return $this->belongsTo(Dependencia::class, 'idefector');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicio()
    {
        return $this->belongsTo(Dependencia::class, 'idservicio');
    }

    public function tipoVisado()
    {
        return $this->belongsTo(
            TipoVisadoReclamoDePago::class,
            'idtipo_visado',
            'idtipo_visado_reclamo_de_pago'
        );
    }

    public function getDiasAttribute()
    {
        return $this->fhasta_ep->diffInDays($this->fdesde_ep)+1;
    }

    public function scopeEfector($query, $idefector = null)
    {
        if ($idefector) {
            return $query->where('idefector', $idefector);
        }
    }

    public function scopeServicio($query, $idservicio = null)
    {
        if ($idservicio) {
            return $query->where('idservicio', $idservicio);
        }
    }

    public function scopePeriodoEP($query, $idperiodo = null)
    {
        if ($idperiodo) {
            return $query->where('idperiodo_ep', $idperiodo);
        }
    }

    public function scopeFDesdeCreado($query, $fdesde)
    {
        if ($fdesde) {
            return $query->where('created_at', '>=', $fdesde);
        }
    }

    public function scopeFHastaCreado($query, $fhasta)
    {
        if ($fhasta) {
            return $query->where('created_at', '<=', $fhasta);

        }
    }
    public function scopeRangoCreacion($query, $fdesde, $fhasta)
    {
        if ($fdesde && $fhasta) {
            return $query->whereBetween('created_at',[$fdesde,$fhasta]);
        } elseif ($fdesde && !isset($fhasta)) {
            return $query->where('created_at', '>=', $fdesde);
        } elseif ($fhasta && !isset($fdesde)) {
            return $query->where('created_at', '<=', $fhasta);
        }
    }

    public function scopeAgente($query, $apellido_nombre = null)
    {
        if ($apellido_nombre) {
            return $query->whereHas('agente', function ($query) use ($apellido_nombre) {
                $query->whereRaw("apellido || ' ' || nombre ILIKE ?", ["%{$apellido_nombre}%"]);
            });
        }
    }
}
