<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActividad extends Model
{
    public $table = 'tipo_actividad';
    public $timestamps = false;

    protected $primaryKey = 'idtipo_actividad';

    public $fillable = [
        'actividad'
    ];

    protected $casts = [
        'idtipo_actividad' => 'integer',
        'actividad' => 'string'
    ];

    public static $rules = [
        'idtipo_actividad' => 'required',
        'actividad' => 'required'
    ];
    
    use HasFactory;
}
