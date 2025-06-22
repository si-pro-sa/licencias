<?php

namespace App\Models;

use App\Role;
use App\Team;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Boolean;
use PHPUnit\Exception;

trait User
{
    public static function userAuth()
    {
        /** @var \App\User $user */
        $user = Auth::user();
        return $user;
    }

    /**
     * @return Team
     */
    public static function team()
    {
        return Team::where('id', 4)->first();
    }

    /**
     * @return Role|null
     */
    public static function roleTeam()
    {
        $user = self::userAuth();
        try {
            $role = $user->rolesTeams()->where('team_id', self::team()->id)->first()->pivot;
            return Role::where('id', $role->role_id)->first();
        } catch (\Exception $e) {
            Log::debug('El usuario no tiene un perfil asociado a siarhu_v2 ----' . $e->getMessage());
            return null;
        }
    }

    /**
     * $permission string
     * @param $permission
     * @return bool
     */
    public static function tienePermiso($permission): bool
    {
        if (env('CONTROL_PERMISOS', false)) {
            $role = self::roleTeam();
            if ($role !== null) {
                return $role->hasPermissions(self::team(), $permission) || self::userAuth()->hasPermission(self::team(), $permission);
            }
            return false;
        }
        return true;
    }

    /**
     * @return Role|null
     */
    public static function roleSiarhuV2()
    {
        $user = self::userAuth();
        $role = $user->roles()->where('team_id', 4)->first();
        if (isset($role) && !empty($role)) {
            return $user->roles()->where('team_id', 4)->first()->role();
        }
        return null;
    }

    /**
     * @return Role|null
     */
    public static function rolePorModulo($team = 5)
    {
        $user = self::userAuth();
        $role = $user->roles()->where('team_id', $team)->first();
        return $role;
    }

    /**
     * @param string $role
     * @return bool
     */
    public static function isRole($role = ''): bool
    {
        $isRole = false;
        $roleName = null;
        try {
            $roleName = self::roleTeam()->name;
        } catch (Exception $exception) {
        } finally {
            ($roleName == $role) ? $isRole = true : $isRole = false;
        }

        return $isRole;
    }
}
