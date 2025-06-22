<?php

namespace App\Repositories;

use App\Models\Agente;
use App\Models\Candidato;
use App\Models\Domicilio;
use App\Models\EvaluacionPsicotecnica;
use App\Models\Puesto;
use App\Models\RecomendacionCandidato;
use App\Repositories\BaseRepository;
use App\User;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class EvaluacionPsicotecnicaRepository
 * @package App\Repositories
 * @version January 10, 2020, 2:04 pm UTC
 */
class EvaluacionPsicotecnicaRepository extends BaseRepository
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
        return EvaluacionPsicotecnica::class;
    }
}
