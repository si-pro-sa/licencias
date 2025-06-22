<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScorePerLicExcluido extends Model
{
    public $table = 'score_per_lic_excluido';
    public $timestamps = false;

    protected $primaryKey = 'idscore_per_lic_excluido';
    protected $dates = ['fdesde', 'fhasta', 'fdesde_vigencia', 'fhasta_vigencia'];

    public $fillable = [
        'fdesde', 
        'fhasta', 
        'fdesde_vigencia', 
        'fhasta_vigencia'
    ];

    protected $casts = [
        'idscore_per_lic_excluido' => 'integer',
        'fdesde' => 'date:Y-m-d',
        'fhasta' => 'date:Y-m-d',
        'fdesde_vigencia' => 'date:Y-m-d',
        'fhasta_vigencia' => 'date:Y-m-d',
    ];

    protected $rules = [
        'idscore_per_lic_excluido' => 'required',
        'fdesde' => 'required',
        'fhasta' => 'required',
        'fdesde_vigencia' => 'required',
        'fhasta_vigencia' => 'required',
    ];

    use HasFactory;
}
