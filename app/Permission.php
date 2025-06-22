<?php

namespace App;

use Laratrust\Models\LaratrustPermission;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Permission
 * @package App
 * @property integer id
 * @property string name
 * @property string display_name
 * @property string description
 */
class Permission extends LaratrustPermission
{
    use SoftDeletes;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $connection = 'pgsql_sistema';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'name',
        'display_name',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'display_name' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];
    public function imagen()
    {
        return $this->hasMany(imgPermission::class, 'permission'); // Le indicamos que se va relacionar con el atributo id
    }
}
