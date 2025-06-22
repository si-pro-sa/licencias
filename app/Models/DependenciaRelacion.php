<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DependenciaRelacion
 * @package App\Models
 * @version August 16, 2019, 1:00 pm -03
 *
 * @property integer iddependenciapadre
 * @property integer iddependenciahija
 * @property date fdesde
 * @property date fhasta
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 * @property boolean revisado
 */
class DependenciaRelacion extends Model
{
    public $table = 'dependenciarelacion';
    protected $connection = 'pgsql_public';
    protected $primaryKey = 'iddependenciarelacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'iddependenciapadre',
        'iddependenciahija',
        'fdesde',
        'fhasta',
        'usuario',
        'operacion',
        'foperacion',
        'revisado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'iddependenciarelacion' => 'integer',
        'iddependenciapadre' => 'integer',
        'iddependenciahija' => 'integer',
        'fdesde' => 'date',
        'fhasta' => 'date',
        'usuario' => 'string',
        'operacion' => 'string',
        'revisado' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public static function scopePadres($query)
    {
        $tmp = DependenciaRelacion::get(['iddependenciahija'])->toArray();
        $tmp = array_column($tmp, 'iddependenciahija');
        $query->whereIn('iddependenciapadre', $tmp);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciaPadre()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'iddependenciapadre', 'iddependencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciaHija()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'iddependenciahija', 'iddependencia');
    }
    public function dependenciasHijas()
    {
        return $this->hasMany(DependenciaRelacion::class, 'iddependenciapadre', 'iddependenciahija')->with('dependenciasHijas');
    }
    public function scopeSinHijos($q)
    {
        $q->has('dependenciasHijas', '=', 0);
    }
    public function allDependenciasHijas ()
    {
        $sections = new Collection();

        foreach ($this->dependenciasHijas() as $section) {
            $sections->push($section);
            $sections = $sections->merge($section->allDependenciasHijas());
        }

        return $sections;
    }
}
