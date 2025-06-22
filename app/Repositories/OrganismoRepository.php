<?php

namespace App\Repositories;

use App\Models\Organismo;
use App\Repositories\BaseRepository;

/**
 * Class OrganismoRepository
 * @package App\Repositories
 * @version July 3, 2020, 7:38 pm UTC
 */
class OrganismoRepository extends BaseRepository
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
        return Organismo::class;
    }
}
