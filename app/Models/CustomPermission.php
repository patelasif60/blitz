<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\Array_;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission;
use App\Models\ModelHasCustomPermission;
use Illuminate\Support\Facades\DB;

class CustomPermission extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    const CATAGEORY = 'category';

    protected $fillable = [
        'name',
        'model_type',
        'value',
        'guard_name',
        'system_role_id'
    ];

    /**
     * Custom Permission relation Model Has Custom Permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelHasCustomPermission()
    {
        return $this->hasMany(ModelHasCustomPermission::class,'custom_permission_id', 'id')->first();
    }

    /**
     * Custom Permission relation Model Has Custom Permission Single
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelHasCustomPermissionOne()
    {
        return $this->hasOne(ModelHasCustomPermission::class,'custom_permission_id', 'id');
    }

    /**
     * Custom Permission relation Model Has Custom Permission Multi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelHasCustomPermissionMulti()
    {
        return $this->hasMany(ModelHasCustomPermission::class,'custom_permission_id', 'id');
    }

    /**
     * Get custom permission by name.
     */
    public static function findByName(string $name, $guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $customPermission = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        return $customPermission;
    }

    /**
     * Get custom permission by params.
     */
    protected static function findByParam(array $params = [])
    {
        $query = CustomPermission::where('name', $params['name'])->where('guard_name', $params['guard_name'])->first();

        return $query;
    }

    /**
     * Get permissions by id.
     */
    public static function findByIds(Array $permissions = [], $guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $customPermission = static::findByKey(['id' => $permissions, 'guard_name' => $guardName]);

        return $customPermission;
    }

    /**
     * Get permissions by key.
     */
    protected static function findByKey(array $params = [])
    {

        $query = Permission::whereIn('id', $params['id'])->where('guard_name', $params['guard_name'])->pluck('name')->toArray();

        return $query;
    }

    public static function getUserByCustomPermissionId($buyerDefaultCompany, $permissionId) {
        $approvalConfigUsers = CustomPermission::join('custom_roles','custom_permissions.value','=','custom_roles.id')
        ->join('model_has_custom_permissions','custom_permissions.id','=','model_has_custom_permissions.custom_permission_id')
        ->join('users','model_has_custom_permissions.model_id','=','users.id')      //New added line
        ->whereJsonContains('model_has_custom_permissions.custom_permissions',$permissionId)
        ->whereJsonContains('users.assigned_companies',$buyerDefaultCompany)
        ->get(['model_has_custom_permissions.model_id']);
        return $approvalConfigUsers;
    }

    public function customRoles()
    {
        return $this->morphTo();
    }



}
