<?php

namespace App\Repositories;

use App\Models\TipoDia;
use App\Repositories\BaseRepository;

/**
 * Class TipoDiaRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:10 pm -03
 *
 * @method TipoDia findWithoutFail($id, $columns = ['*'])
 * @method TipoDia find($id, $columns = ['*'])
 * @method TipoDia first($columns = ['*'])
*/
class TipoDiaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tipodia',
        'nombre_corto'
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
        return TipoDia::class;
    }
}
