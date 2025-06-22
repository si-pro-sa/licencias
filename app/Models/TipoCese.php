<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class TipoCese
 * @package App\Models
 * @version January 12, 2019, 7:33 am UTC
 *
 * @property string tipocese
 */
class TipoCese extends Model
{
    public $table = 'tipo_cese';
    protected $primaryKey = 'idtipo_cese';
    protected $connection = 'pgsql_public';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'tipocese'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_cese' => 'integer',
        'tipocese' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function scopeTipoCese($query, $tipocese){
        if (trim($tipocese) != "" && $tipocese != '...') {
            $raw = DB::raw('LOWER(CONCAT(idtipo_cese,tipo_cese))');
            $query->where($raw, 'like', '%' . strtolower($tipocese) . '%');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function cesePuestos()
    {
        return $this->hasMany(\App\Models\CesePuesto::class, 'cese_puesto');
    }
}
