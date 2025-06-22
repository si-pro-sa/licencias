<?php

namespace App\Repositories;

use App\Models\LicenciaFamiliar;
use App\Repositories\BaseRepository;

/**
 * Class LicenciaFamiliarRepository
 * @package App\Repositories
 * @version September 22, 2019, 11:08 pm -03
 *
 * @method LicenciaFamiliar findWithoutFail($id, $columns = ['*'])
 * @method LicenciaFamiliar find($id, $columns = ['*'])
 * @method LicenciaFamiliar first($columns = ['*'])
*/
class LicenciaFamiliarRepository extends BaseRepository
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
        return LicenciaFamiliar::class;
    }
}
