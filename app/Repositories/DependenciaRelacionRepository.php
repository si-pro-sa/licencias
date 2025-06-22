<?php

namespace App\Repositories;

use App\Models\Dependencia;
use App\Models\DependenciaRelacion;
use App\Repositories\BaseRepository;

/**
 * Class DependenciaRelacionRepository
 * @package App\Repositories
 * @version August 16, 2019, 1:42 pm -03
 *
 * @method DependenciaRelacion findWithoutFail($id, $columns = ['*'])
 * @method DependenciaRelacion find($id, $columns = ['*'])
 * @method DependenciaRelacion first($columns = ['*'])
*/
class DependenciaRelacionRepository extends BaseRepository
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
        return DependenciaRelacion::class;
    }

    private $dependencias;
    /**
     * Find data by multiple values in one field
     *
     * @param       $dependencia
     *
     * @return mixed
     */
    public function dependencia($dependencia)
    {
        if ($dependencia != null){
            $this->dependencias = Dependencia::dependenciaLike($dependencia)->get()->toArray();
            $this->dependencias = array_column($this->dependencias,'iddependencia');
            $this->scopeQuery(function($query){
                return $query->whereIn('iddependenciapadre',$this->dependencias)
                    ->orWhereIn('iddependenciahija',$this->dependencias);
            });
        }
    }
    /**
     * Find data by multiple values in one field
     *
     * @param       $dependencia
     *
     * @return mixed
     */
    private $dependenciaRaiz;
    public function raiz($dependencia)
    {
        $this->dependenciaRaiz = $dependencia;
        if ($dependencia != null){
            $this->scopeQuery(function($query){
                return $query->where('iddependenciapadre',$this->dependenciaRaiz);
            });
        }
    }
    private $codigos;
    /**
     * Find data by multiple values in one field
     *
     * @param       $dependencia
     *
     * @return mixed
     */
    public function codigo($dependencia)
    {
        if ($dependencia != null){
            $this->dependencias = Dependencia::codigoLike($dependencia)->get()->toArray();
            $this->dependencias = array_column($this->dependencias,'iddependencia');
            $this->scopeQuery(function($query){
                return $query->whereIn('iddependenciapadre',$this->dependencias);
            });
        }
    }
}