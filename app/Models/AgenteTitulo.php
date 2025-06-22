<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AgenteTitulo
 * @package App\Models
 * @version January 3, 2019, 6:08 am UTC
 *
 * @property \App\Models\Agente agente
 * @property \App\Models\Titulo titulo
 * @property integer idagente
 * @property integer idtitulo
 * @property date falta
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class AgenteTitulo extends Model
{
    public $table = 'agentetitulo';
    protected $primaryKey = 'idagentetitulo';
    protected $connection = 'pgsql_public';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'idagente',
        'idtitulo',
        'falta',
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
        'idagentetitulo' => 'integer',
        'idagente' => 'integer',
        'idtitulo' => 'integer',
        'falta' => 'date',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class,'idagente','idagente');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function titulo()
    {
        return $this->belongsTo(\App\Models\Titulo::class,'idtitulo','idtitulo');
    }
}
