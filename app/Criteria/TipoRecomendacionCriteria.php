<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class TipoRecomendacionCriteria.
 *
 * @package namespace App\Criteria;
 */
class TipoRecomendacionCriteria implements CriteriaInterface
{
    private $idtipo_recomendacion;

    /**
     * TipoRecomendacionCriteria constructor.
     * @param $idtipo_recomendacion array
     */
    public function __construct(array $idtipo_recomendacion)
    {
        $this->idtipo_recomendacion = $idtipo_recomendacion;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (is_array($this->idtipo_recomendacion) && count($this->idtipo_recomendacion) > 0){
            $model = $model->whereIn('idtipo_recomendacion', $this->idtipo_recomendacion);
        }
        return $model;
    }
}
