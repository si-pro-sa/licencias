<?php

namespace App\Models;

use App\MasterModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEstadoReclamoDePago extends MasterModel
{
    use SoftDeletes;

    public $table = 'tipo_estado_reclamo_de_pago';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'idtipo_estado_reclamo_de_pago';


     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Sistema.usuario::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Sistema.usuario::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function deletedBy()
    {
        return $this->belongsTo(\App\Models\Sistema.usuario::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reclamoDePago()
    {
        return $this->hasMany(ReclamoDePago::class,'idtipo_estado');
    }


}
