<?php

namespace App\Repositories;

use App\Models\TipoSexo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoSexoRepository
 * @package App\Repositories
 * @version November 29, 2018, 9:02 am UTC
 *
 * @method TipoSexo[]|Collection|Model|null findWithoutFail($id, $columns = ['*'])
 * @method TipoSexo[]|Collection|Model|null find($id, $columns = ['*'])
 * @method TipoSexo|null first($columns = ['*'])
 */

class TipoSexoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tiposexo',
        'abreviatura',
        'usuario',
        'operacion',
        'foperacion'
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
        return TipoSexo::class;
    }
}
