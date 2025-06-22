<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreParamSubnivel extends Model
{

    public $table = 'score_param_subnivel';
    
    protected $primaryKey = 'idparam_subnivel';

    public $fillable = [
        'iddependencia',
        'idtipo_funcion',
        'critico',
        'subnivel',
    ];

    protected $casts = [
        'idparam_subnivel' => 'integer',
        'iddependencia' => 'integer',
        'idtipo_funcion' => 'integer',
        'critico' => 'integer',
        'subnivel' => 'string',
    ];

    public $rules = [
        'idparam_subnivel' => 'required',
        'iddependencia' => 'required',
        'idtipo_funcion' => 'required',
        'critico' => 'required',
        'subnivel' => 'required',
    ];

    use HasFactory;
}
