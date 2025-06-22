<?php

namespace App\Repositories;

use App\Models\Agente;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class ReferenteDependenciaRepository
 * @package App\Repositories
 * @version December 9, 2018, 2:21 pm UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
*/
class UserRepository extends BaseRepository
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
        return User::class;
    }

    /**
     * @param string|integer $usuario
     * @return mixed
     */
    public function getUsuario($usuario){
        $this->applyCriteria();
        $this->applyScope();

        if (is_numeric($usuario)){
            $agentes = Agente::dni($usuario)->get(['idagente']);
            $idagentes = array();
            foreach ($agentes as $agente){
                array_push($idagentes,$agente->idagente);
            }
            $results = $this->model->whereIn('idagente',$idagentes)
                ->orderBy('nombreusuario')
                ->get();
        }else{
            $results = $this->model->usuario($usuario)
                ->orderBy('nombreusuario')
                ->get();
        }

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
