<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class TipoPuesto
 * @package App\Models
 * @version Febrary 6, 2023, 15:00 pm UTC
 *
 * @property string tipopuesto
 */
class TipoPuesto extends Model
{
    public $table = 'tipo_puesto';
    protected $primaryKey = 'idtipo_puesto';
    protected $connection = 'pgsql_public';

    public $timestamps = false;

    public $fillable = [
        'tipo_puesto'
    ];

    /**
     * The attributes that should be casted to native types.
     * @var array
     */
    protected $casts = [
        'idtipo_puesto' => 'integer',
        'tipo_puesto' => 'string'
    ];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestos()
    {
        return $this->hasMany(\App\Models\Puesto::class);
    }

    /**
     * @var Request $puesto
     * @return json
     **/
    public function crearPuesto($puesto)
    {
        $this->idtipo_puesto = $puesto->idtipo_puesto;
        $this->tipo_puesto = $puesto->tipo_puesto;
        $this->saveOrFail();
    }

    /**
     * @var Request $puesto
     * @return json
     **/
    public function actualizarPuesto($puesto)
    {
        $this->tipo_puesto = $puesto->tipo_puesto;
        $this->updateOrFail();
    }
}