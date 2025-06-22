<?php

namespace App\Models;

use App\MasterModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoFormularioReclamoDePago extends MasterModel
{
    use SoftDeletes;

    public $table = 'tipo_formulario_reclamo_de_pago';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'idtipo_formulario_reclamo_de_pago';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reclamoDePago()
    {
        return $this->hasMany(ReclamoDePago::class, 'idtipo_estado');
    }
}
