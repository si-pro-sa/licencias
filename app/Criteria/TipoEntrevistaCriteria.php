<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class TipoEntrevistaCriteria.
 *
 * @package namespace App\Criteria;
 */
class TipoEntrevistaCriteria implements CriteriaInterface
{
    private $idtipo_entrevista;

    /**
     * TipoEntrevistaCriteria constructor.
     * @param $idtipo_entrevista
     */
    public function __construct($idtipo_entrevista)
    {
        $this->idtipo_entrevista = $idtipo_entrevista;
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
        if ($this->idtipo_entrevista != null && trim($this->idtipo_entrevista) != '') {
            $model = $model->where('idtipo_entrevista','=', $this->idtipo_entrevista);
        }
        return $model;
    }
}
