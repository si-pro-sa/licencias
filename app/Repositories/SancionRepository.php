<?php

namespace App\Repositories;

use App\Models\Sancion;
use App\Repositories\BaseRepository;

/**
 * Class SancionRepository
 * @package App\Repositories
 * @version November 4, 2019, 12:27 am -03
 *
 * @method Sancion findWithoutFail($id, $columns = ['*'])
 * @method Sancion find($id, $columns = ['*'])
 * @method Sancion first($columns = ['*'])
*/
class SancionRepository extends BaseRepository
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
        return Sancion::class;
    }
}
