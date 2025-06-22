<?php

namespace App\Models;

use App\Role;
use App\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoleUser
 * @package App\Models
 * @version April 22, 2020, 6:06 pm -03
 *
 * @property Role role
 * @property Team team
 * @property \App\User user
 * @property integer user_id
 * @property string user_type
 * @property integer team_id
 * @property string data
 * @property string bizrule
 */
class RoleUser extends Model
{
    use SoftDeletes;

    public $table = 'role_user';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'user_id',
        'user_type',
        'team_id',
        'data',
        'bizrule'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'role_id' => 'integer',
        'user_id' => 'integer',
        'user_type' => 'string',
        'team_id' => 'integer',
        'data' => 'string',
        'bizrule' => 'string'
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

    /**
     * @return BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
