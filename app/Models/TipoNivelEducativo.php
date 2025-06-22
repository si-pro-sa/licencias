<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoNivelEducativo
 * @package App\Models
 * @version January 2, 2019, 7:00 pm UTC
 *
 * @property string tiponiveleducativo
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class TipoNivelEducativo extends Model
{
    use SoftDeletes;

    public $table = 'tipo_niveleducativo';
    protected $primaryKey = 'idtipo_niveleducativo';
    protected $connection = 'pgsql_public';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'tiponiveleducativo',
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
        'idtipo_niveleducativo' => 'integer',
        'tiponiveleducativo' => 'string',
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
    public function tipoAreatematicas()
    {
        return $this->belongsToMany(\App\Models\TipoAreatematica::class, 'titulo');
    }
}
