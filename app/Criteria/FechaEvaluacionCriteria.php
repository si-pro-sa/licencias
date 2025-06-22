<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FechaEvaluacionCriteria.
 *
 * @package namespace App\Criteria;
 */
class FechaEvaluacionCriteria implements CriteriaInterface
{
    private $fechainicio;
    private $fechafin;

    /**
     * FechaEvaluacionCriteria constructor.
     * @param $fechainicio string
     * @param $fechafin string
     */
    public function __construct($fechainicio,$fechafin)
    {
        $this->fechainicio = $fechainicio;
        $this->fechafin = $fechafin;
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
        if ($this->fechafin !== null && trim($this->fechafin) !== '') {
            $model = $model->where('fecha_evaluacion','<', $this->fechafin);
        }
        $model = $model->where('fecha_evaluacion','>', $this->fechainicio);
        return $model;
    }
}
