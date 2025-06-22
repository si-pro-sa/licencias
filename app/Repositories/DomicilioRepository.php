<?php

namespace App\Repositories;

use App\Models\Domicilio;
use App\Repositories\BaseRepository;

/**
 * Class DomicilioRepository
 * @package App\Repositories
 * @version December 9, 2019, 8:01 pm UTC
*/

class DomicilioRepository extends BaseRepository
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
        return Domicilio::class;
    }

    /**
     * @param $dni integer
     * @return DomicilioRepository
     */
    public function documento($dni) : DomicilioRepository
    {
        $this->scopeQuery(function ($query) use ($dni) {
            return $query->where(function ($query) use ($dni) {
                $query->whereHas('candidato', function ($query) use ($dni) {
                    $query->where('documento', $dni);
                })
                    ->orWhereHas('agente', function ($query) use ($dni) {
                        $query->where('documento', $dni);
                    });
            });
        });
        return $this;
    }
}
