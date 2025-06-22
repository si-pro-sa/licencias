<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoAreaTematica
 * @package App\Models
 * @version January 2, 2019, 7:00 pm UTC
 *
 * @property string tipoareatematica
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class TipoAreaTematica extends Model
{
    use SoftDeletes;

    public $table = 'tipo_areatematica';
    protected $primaryKey = 'idtipo_areatematica';
    protected $connection = 'pgsql_public';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'tipoareatematica',
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
        'idtipo_areatematica' => 'integer',
        'tipoareatematica' => 'string',
        'usuario' => 'string',
        'operacion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function tipoNiveleducativos()
    {
        return $this->belongsToMany(\App\Models\TipoNiveleducativo::class, 'titulo');
    }
}
