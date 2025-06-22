<?php

namespace App;

use App\Models\Agente;
use App\Models\DependenciaUsuario;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Laratrust\Traits\LaratrustUserTrait;
use App\Models\PermissionUser;
use App\Models\RoleUser;
use App\Models\Cronograma;
use App\Models\Dependencia;
use App\Models\Util;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;



/**
 * Class usuario
 * @package App\Models
 * @version November 26, 2018, 5:48 pm UTC
 *
 * @property string nombreusuario
 * @property string clave
 * @property string email
 * @property boolean activo
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 * @property integer idagente
 * @property integer idperfil
 * @property string password
 * @property string remember_token
 * @property string api_token
 * @property HasMany|PermissionUser[] permisosUsuarios
 * @mixin LaratrustUserTrait
 */
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;
    use \App\Models\User;
    protected $primaryKey = 'idusuario';
    protected $table = 'sistema.usuario';
    protected $connection = 'pgsql_sistema';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'nombreusuario',
        'clave',
        'email',
        'activo',
        'usuario',
        'operacion',
        'foperacion',
        'idagente',
        'idperfil',
        'password',
        'remember_token',
        'api_token'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idusuario' => 'integer',
        'nombreusuario' => 'string',
        'clave' => 'string',
        'email' => 'string',
        'activo' => 'boolean',
        'usuario' => 'string',
        'operacion' => 'string',
        'idagente' => 'integer',
        'idperfil' => 'integer',
        'password' => 'string',
        'remember_token' => 'string',
        'api_token' => 'string'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return HasMany|PermissionUser[]
     */
    public function permisosUsuarios()
    {
        return $this->hasMany(PermissionUser::class, 'user_id', 'idusuario'); // Le indicamos que se va relacionar con el atributo id
    }

    public function generateToken()
    {
        $this->api_token = Str::random(60);
        $this->save();

        return $this->api_token;
    }

    public function getPasswordAttribute()
    {
        Log::info($this->clave);
        return $this->clave;
    }

    public function getAuthPassword()
    {
        Log::info($this->clave);
        return $this->clave;
    }

    public function scopeUsuario($query, $usuario)
    {
        if (trim($usuario) != "") {
            $query->where('nombreusuario', 'like', "%" . $usuario . "%");
        }
    }

    public function agente()
    {
        return $this->belongsTo(Agente::class, "idagente", "idagente");
    }


    /**
     * Many-to-Many relations with Permission from user in team 5 = licencia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function permisosLicenciaParticular()
    {

        $permisos = $this->belongsToMany(
            Config::get('laratrust.models.permission'),
            Config::get('laratrust.tables.permission_user'),
            Config::get('laratrust.foreign_keys.user'),
            Config::get('laratrust.foreign_keys.permission')
        );
        return $permisos->withPivot(Config::get('laratrust.foreign_keys.team'))
            ->wherePivot('team_id', 5);
    }

    /**
     * Return all the user permissions from team 5 = licencia.
     *
     * @return \Illuminate\Support\Collection|static
     */
    public function permisosLicencias()
    {

        $roles = $this->roles()->with('permisosLicencias')->get();

        $roles = $roles->flatMap(function ($role) {
            return $role->permisosLicencias;
        });
        return $this->permisosLicenciaParticular()->get()->merge($roles)->unique('name');
    }

    /**
     * Check if user has a permission by its name.
     *
     * @param  string|array  $permission Permission string or array of permissions.
     * @param  Team   $team
     * @return bool
     */
    public function hasPermission($team, $permission)
    {
        $permissions = $this->permisosUsuarios->where('team_id', $team->id);
        foreach ($permissions as $permissionTmp) {
            /** PermissionUser $permissionTmp */
            if ($permissionTmp->permission->name === $permission) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function roleSiarhuV2()
    {
        $role = $this->roles()->where('team_id', 4)->first();
        if (isset($role) && !empty($role)) {
            return $this->roles()->where('team_id', 4)->first()->role();
        }
        return null;
    }

    public function getRoleNames(): array
    {
        $roles = [];
        $userRoles = $this->roles()->with('role')->get();
        foreach ($userRoles as $userRole) {
            array_push($roles, $userRole->role->name);
        }
        return $roles;
    }

    public function getEfectoresVisibles(): array
    {
        $dependencias = Dependenciausuario::where('idusuario', $this->idusuario)->pluck('iddependencia_hija')->all();
        return count($dependencias) > 0 ? $dependencias : [];
    }

    public function getDependenciasVisibles(): array
    {
        $efectoresVisibles = DependenciaUsuario::has('dependenciaHija')
            ->where('idusuario', $this->idusuario)
            ->get();
        $dependenciasVisibles = [];
        foreach ($efectoresVisibles as $dependencia) {
            $dependenciasVisibles = array_merge($dependenciasVisibles, $dependencia->dependenciaHija->getIdsDescendencia());
        }

        return $dependenciasVisibles;
    }

    /**
     * Busca información de un usuario logueado, la formatea y devuelve un array o error
     * Busca información del cronograma
     * Busca información del puesto
     * @return array
     */
    public function getInformacionUsuario(): array
    {
        $datosUsuario = [
            'cronogramas' => Cronograma::getCronogramas($this->getEfectoresVisibles()),
            'roles' => $this->getRoleNames(),
            'permissions' => $this->getPermissions()
        ];

        return $datosUsuario;
    }

    public function getPermissions()
    {
        $perms = [];
        $permissions = $this->permisosUsuarios->where('team_id', 1);
        foreach ($permissions as $permissionTmp) {
            array_push($perms, $permissionTmp->permission->name);
        }
        return $perms;
    }

    public function isRRHH(): bool
    {
        $roles = $this->getRoleNames();
        $rrhhRoles = ['gerencia', 'jefedptoseleccion', 'departamentoseleccion', 'jefedptoplanificacion', 'departamentoplanificación', 'informesdgrhs'];
        foreach ($roles as $role) {
            if (in_array($role, $rrhhRoles)) {
                return true;
            }
        }

        return false;
    }

    public function isGerencia(): bool
    {
        $roles = $this->getRoleNames();
        foreach ($roles as $role) {
            if ($role === 'gerencia') {
                return true;
            }
        }

        return false;
    }

    /**
     * Retorna la descendencia del efector al cual pertenece el usuario logueado.
     * @param bool $returnString
     * @return array|string
     */
    public function getDescendencia(bool $returnString = false): array
    {
        $idsHijas = [];
        $dependencias = $this->getEfectoresVisibles();

        foreach ($dependencias as $dep) {
            $dependencia = Dependencia::firstWhere('iddependencia', $dep);
            $idsHijas = array_merge($idsHijas, $dependencia->getIdsDescendencia());
        }

        if ($returnString) {
            return Util::convertArrayToString($idsHijas);
        }

        return $idsHijas;
    }

    /**
     * Get visible dependencies for the user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDependenciasVisible()
    {
        if (!$this) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $dependenciasVisiblesIds = $this->getDependenciasVisibles();

        $dependenciasVisibles = [];

        foreach ($dependenciasVisiblesIds as $dependenciaId) {
            $dependencia = Dependencia::find($dependenciaId);
            if ($dependencia) {
                $dependenciasVisibles[] = [
                    'iddependencia' => $dependencia->iddependencia,
                    'dependencia' => $dependencia->dependencia,
                ];
            }
        }

        $agente = $this->agente;

        if (!$agente) {
            return response()->json(['message' => 'Agente no encontrado para el usuario'], 404);
        }

        $departmentDetails = $agente->getDepartmentDetails();
        $allChildDependencies = $departmentDetails['IdsDependenciasHijas'];

        foreach ($dependenciasVisibles as $dependencia) {
            $dep = Dependencia::find($dependencia['iddependencia']);
            if ($dep) {
                $childDependencies = $dep->getIdsDescendencia();
                $allChildDependencies = array_unique(array_merge($allChildDependencies, $childDependencies));
            }
        }

        // now foreach element in $allChildDependencies, get the name of the dependency and store it in a new array
        $allChildDependencies = array_map(function ($id) {
            $dep = Dependencia::find($id);
            return [
                'iddependencia' => $dep->iddependencia,
                'dependencia' => $dep->dependencia,
            ];
        }, $allChildDependencies);


        return response()->json([
            'idusuario' => $this->idusuario,
            'nombreusuario' => $this->nombreusuario,
            'dependenciasVisibles' => $dependenciasVisibles,
            'agente' => [
                'idagente' => $agente->idagente,
                'nombre' => $agente->nombre,
                'apellido' => $agente->apellido,
                'departmentDetails' => $departmentDetails,
            ],
            'dependenciasHijas' => $allChildDependencies,
        ]);
    }
}
