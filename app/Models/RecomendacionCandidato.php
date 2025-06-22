<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class RecomendacionCandidato
 * @package App\Models
 *
 * @property integer idrecomendacion_candidato
 * @property integer candidato_id
 * @property string candidato_type
 * @property integer idtipo_referido_interno
 * @property integer idtipo_referido_politico
 * @property integer idtipo_funcion
 * @property integer idtitulo
 * @property integer idtipo_nivel
 *
 */

class RecomendacionCandidato extends Model
{
    use SoftDeletes;

    public $table = 'recomendacion_candidato';

    public $primaryKey = 'idrecomendacion_candidato';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'candidato_id',
        'candidato_type',
        'idtipo_referido_interno',
        'idtipo_referido_politico',
        'idtipo_funcion',
        'idtitulo',
        'idtipo_nivel',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'candidato_id' => 'integer',
        'candidato_type' => 'string',
        'idtipo_referido_interno' => 'integer',
        'idtipo_referido_politico' => 'integer',
        'idtipo_funcion' => 'integer',
        'idtitulo' => 'integer',
        'idtipo_nivel' => 'integer',
    ];

    /**
     * Get the owning candidato model.
     */
    public function candidato()
    {
        return $this->morphTo();
    }

    public function formacion()
    {
        return $this->belongsTo('App\Models\Titulo', 'idtitulo');
    }

    public function referidoInterno()
    {
        return $this->belongsTo('App\Models\TipoReferido', 'idtipo_referido_interno');
    }

    public function referidoPolitico()
    {
        return $this->belongsTo('App\Models\TipoReferido', 'idtipo_referido_politico');
    }

    public function especialidad()
    {
        return $this->belongsTo('App\Models\TipoFuncion', 'idtipo_funcion');
    }

    public function nivel()
    {
        return $this->belongsTo('App\Models\TipoNivel', 'idtipo_nivel');
    }

    public static function exportarExcel()
    {
        $recomendaciones = RecomendacionCandidato::all()->sortBy('candidato.apellido');

        $data = [];

        foreach($recomendaciones as $key => $recomendacion) {

            if(isset($recomendacion->candidato->iddomicilio)) {
                $domicilio = [
                    'direccion' => $recomendacion->candidato->domicilio->calle.' '.$recomendacion->candidato->domicilio->numero,
                    'departamento' => $recomendacion->candidato->domicilio->localidad->departamento->departamento,
                    'localidad' => $recomendacion->candidato->domicilio->localidad->localidad,
                    'cp' => $recomendacion->candidato->domicilio->codigo_postal,
                ];
            }else {
                $domicilio = [
                    'direccion' => '',
                    'departamento' => '',
                    'localidad' => '',
                    'cp' => '',
                ];
            }
            $data[$key] = [
                'apellido_y_nombre' => $recomendacion->candidato->apellido.", ".$recomendacion->candidato->nombre,
                'documento' => $recomendacion->candidato->documento,
                'telefono' => $recomendacion->candidato->telefono,
                'celular' => $recomendacion->candidato->celular,
                'email' => $recomendacion->candidato->email,
                'formacion' => $recomendacion->formacion->titulo,
                'especialidad' => $recomendacion->especialidad->tipofuncion,
                'nivel' => $recomendacion->nivel->tiponivel,
                'referido1' => $recomendacion->referidoInterno->nombre,
                'referido2' => $recomendacion->referidoPolitico->nombre,
                'direccion' => $domicilio['direccion'],
                'departamento' => $domicilio['departamento'],
                'localidad' => $domicilio['localidad'],
                'cp' => $domicilio['cp'],
            ];
        }

        return $data;
    }
}
