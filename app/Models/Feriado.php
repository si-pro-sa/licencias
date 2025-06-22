<?php
namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Periodo;
use App\Models\Fecha;

/**
 * Class Feriado
 * @package App\Models
 * @version October 7, 2020, 5:59 pm -03
 *
 * @property \App\Models\Periodo idperiodo
 * @property string fecha
 * @property string descripcion
 * @property integer idperiodo
 * @property string created_by
 * @property string updated_by
 * @property string deleted_by
 */
class Feriado extends Model
{
    use SoftDeletes;

    public $table = 'feriado';
    protected $primaryKey = 'idferiado';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'fecha',
        'descripcion',
        'idperiodo',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idferiado' => 'integer',
        'fecha' => 'datetime:Y-m-d',
        'descripcion' => 'string',
        'idperiodo' => 'integer',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha' => 'required',
        'descripcion' => 'required',
        'idperiodo' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function periodo()
    {
        return $this->belongsTo(\App\Models\Periodo::class, 'idperiodo');
    }

    /**
     * Determina si una fecha es Feriado
     * @param $fecha
     * @return bool
     */
    public static function isFeriado(string $fecha): bool
    {
        $fecha = Fecha::parse($fecha);
        $periodo = Periodo::getPeriodoConFecha($fecha);
        $feriados = Feriado::where(['idperiodo' => $periodo->idperiodo])->pluck('fecha')->map(function ($date) {
            return $date->format('Y-m-d');
        });

        if (in_array($fecha, $feriados->all())) {
            return true;
        }
        return false;
    }
}
