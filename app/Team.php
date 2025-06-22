<?php

namespace App;

use Laratrust\Models\LaratrustTeam;

/**
 * Class Team
 * @package App
 * @property interger id
 * @property string name
 * @property string display_name
 * @property string description
 */
class Team extends LaratrustTeam
{
    protected $table = 'teams';

    protected $primaryKey = 'id';
    protected $connection = 'pgsql_sistema';

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    public static $rules = [
        'name' => 'required'
    ];
}
