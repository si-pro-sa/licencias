<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoReferido
 * @package App\Models
 *
 * @property string organismo
 * @property string nombre
 * @property string telefono
 * @property boolean interno
 */

class TipoReferido extends Model
{
    use SoftDeletes;

    public $table = 'tipo_referido';
    protected $primaryKey = 'idtipo_referido';

    protected $fillable = [
        'organismo',
        'nombre',
        'telefono',
        'interno',
    ];

    /**
     * Casteo de los atributos a tipos nativos
     *
     * @var array
     */
    protected $casts = [
        'idctipo_referido' => 'integer',
        'organismo' => 'string',
        'nombre' => 'string',
        'documento' => 'integer',
        'interno' => 'boolean',
    ];

    public function recomendacionInterna()
    {
        return $this->hasMany('App\Models\RecomendacionCandidato','idtipo_referido_interno');
    }

    public function recomendacionPolitica()
    {
        return $this->hasMany('App\Models\RecomendacionCandidato','idtipo_referido_politico');
    }
}
