<?php

namespace App\Models;

use App\MasterModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class NovedadCargo extends MasterModel
{
    use SoftDeletes;

    public $table = 'novedad_cargo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    protected $primaryKey = 'idnovedad_cargo';

    public $fillable = [
        'idefectiva_prestacion_cargo',
        'idtipo_visado_novedad',
        'idtipo_observacion_novedad',
        'comentarios',
        'liquidado',
    ];

    protected $casts = [
        'idefectiva_prestacion_cargo' => 'integer',
        'idtipo_visado_novedad' => 'integer',
        'idtipo_observacion_novedad' => 'integer',
        'comentarios' => 'string',
        'liquidado' => 'boolean',
    ];


    public function efectivaPrestacion()
    {
        return $this->belongsTo(\App\Models\EfectivaPrestacionCargo::class,'idefectiva_prestacion_cargo');
    }

    public function tipoVisado()
    {
        return $this->belongsTo(\App\Models\TipoVisadoNovedad::class,'idtipo_visado_novedad','idtipovisadonovedad');
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

    public function scopeEfector($query, Dependencia $efector=null)
    {
        if (isset($efector)) {
            return $query->whereHas('efectivaPrestacion.cargo', function($query) use ($efector) {
                $query->where('idefector', $efector->iddependencia);
            });
        }
    }

    public function scopeTipoVisadoNovedad($query, $idtipo_visado_novedad)
    {
        if (isset($idtipo_visado_novedad)) {
            return $query->where('idtipo_visado_novedad', $idtipo_visado_novedad);
        }
        return $query;
    }

    public function scopePdf($query, $pdf)
    {
        if (isset($pdf)) {
            return $query->whereHas('efectivaPrestacion', function($query) {
                $query->where('rechazado',false);
            });
        }
        return $query;

    }

    public function scopePeriodoLiq($query, Periodo $periodo_liq)
    {
        return $query->whereHas('efectivaPrestacion', function($query) use ($periodo_liq) {
            $query->where('idperiodo_liq', $periodo_liq->idperiodo);
        });
    }

    public function scopeRetroactivo($query, $retroactivo=null)
    {
        if (isset($retroactivo)) {
            $query->whereHas('efectivaPrestacion', function ($query) use ($retroactivo) {
                $query->where('retroactivo',$retroactivo);
            });
        }
    }




}
