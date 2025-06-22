<?php

namespace App\Models;

use App\Permission;
use App\Role;
use App\Team;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PermissionRole
 * @package App\Models
 * @version March 9, 2020, 4:05 pm UTC
 *
 * @property BelongsTo|Permission permission
 * @property BelongsTo|Role role
 * @property BelongsTo|Team team
 * @property integer role_id
 * @property integer team_id
 */
class PermissionRole extends Model
{
    public $table = 'permission_role';
    public $primaryKey = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $connection = "pgsql_sistema";

    public $fillable = [
        'role_id',
        'team_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'permission_id' => 'integer',
        'role_id' => 'integer',
        'team_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'role_id' => 'required',
        'team_id' => 'required'
    ];

    /**
     * @return BelongsTo
     **/
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * @return BelongsTo
     **/
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * @return BelongsTo
     **/
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
