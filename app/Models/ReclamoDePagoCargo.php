<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReclamoDePagoCargo extends Model
{

    public $table = 'reclamo_de_pago_cargo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'idreclamo_de_pago_cargo';

    public $fillable = ['idagente', 'agente_type', 'idreclamo_de_pago'];

    public $cast = [];
}
