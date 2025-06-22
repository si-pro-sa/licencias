<?php

namespace App\Repositories;

use App\Models\CargoTipoFirma;
use App\Repositories\BaseRepository;

/**
 * Class CargoTipoFirmaRepository
 * @package App\Repositories
 * @version January 28, 2020, 10:42 pm UTC
*/

class CargoTipoFirmaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cargotipo_firma'
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
        return CargoTipoFirma::class;
    }
}
