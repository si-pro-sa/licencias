<?php

namespace App\Models;

use App\Permission;
use App\Team;
use App\User;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PermissionUser
 * @package App\Models
 * @version March 10, 2020, 1:07 pm UTC
 *
 * @property BelongsTo|Permission permission
 * @property BelongsTo|Team team
 * @property BelongsTo|User user
 * @property integer user_id
 * @property string user_type
 * @property integer team_id
 */
class PermissionUser extends Model
{
    public $table = 'permission_user';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $connection = "pgsql_sistema";

    public $fillable = [
        'user_id',
        'user_type',
        'team_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'permission_id' => 'integer',
        'user_id' => 'integer',
        'user_type' => 'string',
        'team_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'user_type' => 'required',
        'team_id' => 'required'
    ];

    /**
     * @return BelongsTo|Permission
     **/
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * @return BelongsTo|Team
     **/
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * @return BelongsTo|User
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
