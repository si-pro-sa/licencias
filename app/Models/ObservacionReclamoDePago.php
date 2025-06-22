<?php

namespace App\Models;

use App\MasterModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObservacionReclamoDePago extends MasterModel
{
    public $table = 'observacion_reclamo_de_pago';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'idobservacion_reclamo_de_pago';

    protected $fillable = ['idreclamo_de_pago', 'observaciones'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function reclamoDePago()
    {
        return $this->belongsTo(ReclamoDePago::class, 'idreclamo_de_pago');
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
}
