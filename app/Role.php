<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Laratrust\Models\LaratrustRole;
use App\Models\PermissionRole;

class Role extends LaratrustRole
{
    protected $table = 'roles';

    protected $primaryKey = 'id';
    protected $connection = 'pgsql_sistema';

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];
    public static $rules = [
        'name' => 'required',
        'display_name' => 'required',
        'description' => 'required',
    ];

    public function permisos()
    {
        return $this->hasMany(Permission::class, 'role_id'); // Le indicamos que se va relacionar con el atributo id
    }

    /**
     * @return HasMany|PermissionRole[]
     */
    public function permisosRol()
    {
        return $this->hasMany(PermissionRole::class, 'role_id', 'id')->with('permission'); // Le indicamos que se va relacionar con el atributo id
    }

    /**
     * @param $team Team
     * @param string|array $permission Permission string or array of permissions.
     * @return bool
     */
    public function hasPermissions($team, $permission)
    {
        $permissions = $this->permisosRol->where('team_id', $team->id);
        foreach ($permissions as $permissionTmp) {
            /** PermissionRole $permissionTmp */
            if (isset($permissionTmp->permission->name) && !empty($permissionTmp->permission->name) && $permissionTmp->permission->name === $permission) {
                return true;
            }
        }
        return false;
    }


    /**
     * Many-to-Many relations with the permission model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permisosLicencias()
    {
        $permisos = $this->belongsToMany(
            Config::get('laratrust.models.permission'),
            Config::get('laratrust.tables.permission_role'),
            Config::get('laratrust.foreign_keys.role'),
            Config::get('laratrust.foreign_keys.permission')
        );
        return $permisos->withPivot(Config::get('laratrust.foreign_keys.team'))
            ->wherePivot('team_id', 5);
    }
}
