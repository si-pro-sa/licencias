<?php

namespace App\Repositories;

use App\Models\TipoLicencia;
use App\Repositories\BaseRepository;

/**
 * Class TipoLicenciaRepository
 * @package App\Repositories
 * @version September 22, 2019, 11:00 pm -03
 *
 * @method TipoLicencia findWithoutFail($id, $columns = ['*'])
 * @method TipoLicencia find($id, $columns = ['*'])
 * @method TipoLicencia first($columns = ['*'])
*/
class TipoLicenciaRepository extends BaseRepository
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
        return TipoLicencia::class;
    }
}
