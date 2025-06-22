<?php

namespace App\Repositories;

use App\Models\Agente;
use App\Models\Candidato;
use App\Models\RecomendacionCandidato;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecomendacionCandidatoRepository
 * @package App\Repositories
 * @version January 15, 2020, 2:03 pm UTC
 */
class RecomendacionCandidatoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RecomendacionCandidato::class;
    }

    public function sinPsicotecnicos()
    {
        $recomendados = $this->all()->toArray();

        $candidatos = array();
        $agentes = array();
        /** @var RecomendacionCandidato $recomendado */
        foreach ($recomendados as $recomendado)
                {
                if (isset($recomendado['candidato']))
                   {
                    if (count($recomendado['candidato']['psicotecnicos']) == 0)
                       {
                       if ($recomendado['candidato_type'] === 'App\\Models\\Agente')
                          {$agentes[] = $recomendado['candidato'];}
                          else
                          {$candidatos[] = $recomendado['candidato'];};
                       };
                   };
                };
        $idagentes = array_column($agentes, 'idagente');
        $idcandidatos = array_column($candidatos, 'idcandidato');
        $this->scopeQuery(function ($query) use ($idagentes, $idcandidatos) {
            return $query->where(function ($query) use ($idagentes, $idcandidatos) {
                $query->where(function ($query) use ($idagentes) {
                    $query->whereIn('candidato_id', $idagentes)
                        ->where('candidato_type', 'App\\Models\\Agente');
                })->orWhere(function ($query) use ($idcandidatos) {
                    $query->whereIn('candidato_id', $idcandidatos)
                        ->where('candidato_type', 'App\\Models\\Candidato');
                });
            });
        });
    }
    /**
     * @param $recomendaciones
     * @return array
     */
    public function formatearDatosConsolidado($recomendaciones)
    {
        $array = $recomendaciones->toArray();
        $data = array();
        /** @var RecomendacionCandidato $recomendacion */
        foreach ($recomendaciones as $recomendacion) {
            $recomendacionTmp = array();
            if (isset($recomendacion->candidato))
               {
                /** @var Agente|Candidato $candidato */
                $candidato = $recomendacion->candidato;
                $recomendacionTmp['count'] = $candidato->psicotecnicos->count();
                $recomendacionTmp['apynom'] = strtoupper($candidato->apellido . ', ' . $candidato->nombre);
                $recomendacionTmp['documento'] = $candidato->documento;
                $recomendacionTmp['telefono'] = $candidato->telefono;
                $recomendacionTmp['celular'] = $candidato->celular;
                $recomendacionTmp['email'] = $candidato->email;
                if (isset($candidato->domicilio))
                   {
                    $domicilio = [
                        'direccion' => $candidato->domicilio->calle . ' ' . $candidato->domicilio->numero,
                        'departamento' => (isset($candidato->domicilio->departamentod)) ? $candidato->domicilio->departamentod->departamento : '',
                        'localidad' => (isset($candidato->domicilio->localidad)) ? $candidato->domicilio->localidad->localidad : '',
                        'cp' => $candidato->domicilio->codigo_postal,
                        ];
                   }
                   else
                   {$domicilio = ['direccion' => '','departamento' => '','localidad' => '','cp' => '',];};
               };
            $recomendacionTmp['domicilio'] = $domicilio;

            $recomendacionTmp['formacion'] = (isset($recomendacion->formacion)) ? $recomendacion->formacion->titulo : '';
            $recomendacionTmp['especialidad'] = (isset($recomendacion->especialidad)) ? $recomendacion->especialidad->tipofuncion : '';
            $recomendacionTmp['nivel'] = (isset($recomendacion->nivel)) ? $recomendacion->nivel->tiponivel : '';
            $recomendacionTmp['referido1'] = (isset($recomendacion->referidoInterno)) ? $recomendacion->referidoInterno->nombre : '';
            $recomendacionTmp['referido2'] = (isset($recomendacion->referidoPolitico)) ? $recomendacion->referidoPolitico->nombre : '';

            $data[] = $recomendacionTmp;
        }
        if (isset($array['data'])) {
            unset($array['data']);
            $array['data'] = $data;
        } else {
            $array = $data;
        }
        return $array;
    }

    /**
     * @param $dni integer
     * @return RecomendacionCandidatoRepository
     */
    public function documento($dni)
    {
        $this->scopeQuery(function ($query) use ($dni) {
            return $query->where(function ($query) use ($dni) {
                $query->whereHasMorph(
                    'candidato',
                    [Agente::class, Candidato::class],
                    function (Builder $q) use ($dni) {
                        $q->whereRaw("documento = ?", intval($dni));
                    }
                );
            });
        });
        return $this;
    }
}
