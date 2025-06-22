<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CesePuesto
 * @package App\Models
 * @version October 28, 2019, 7:33 am UTC
 *
 */
class CesePuesto extends Model
{
    public $table = 'cese_puesto';
    protected $primaryKey = 'idcese_puesto';
    protected $connection = 'pgsql_public';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcese_puesto' => 'integer',
        'idtipo_cese' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function tipoCese()
    {
        return $this->belongsTo(\App\Models\TipoCese::class, 'tipo_cese');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function puesto()
    {
        return $this->belongsTo(\App\Models\Puesto::class, 'puesto');
    }
}
