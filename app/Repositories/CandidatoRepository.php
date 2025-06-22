<?php

namespace App\Repositories;

use App\Models\Candidato;
use App\Repositories\BaseRepository;

/**
 * Class CandidatoRepository
 * @package App\Repositories
 * @version December 9, 2019, 7:57 pm UTC
*/

class CandidatoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento'
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
        return Candidato::class;
    }

    public function getCandidatoByDocumento($documento)
    {
        return $this->model->documento($documento)->first();
    }
}
