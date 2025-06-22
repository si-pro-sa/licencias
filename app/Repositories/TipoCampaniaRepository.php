<?php

namespace App\Repositories;

use App\Models\TipoCampania;
use App\Repositories\BaseRepository;

/**
 * Class TipoCampaniaRepository
 * @package App\Repositories
 * @version June 7, 2020, 3:07 am -03
*/

class TipoCampaniaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tipocampania',
        'created_by',
        'updated_by',
        'deleted_by'
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
        return TipoCampania::class;
    }
}
