<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class PsicoEvaluadorCriteria.
 *
 * @package namespace App\Criteria;
 */
class PsicoEvaluadorCriteria implements CriteriaInterface
{
    private $evaluador;

    /**
     * PsicoEvaluadorCriteria constructor.
     * @param $evaluador array
     */
    public function __construct(array $evaluador)
    {
        $this->evaluador = $evaluador;
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
        if (is_array($this->evaluador) && count($this->evaluador) > 0){
            $model = $model->whereIn('idpsicoevaluador', $this->evaluador);
        }
        return $model;
    }
}
