<?php


namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Casts\Attribute;



class CustomRoles extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    const ADMINNAME = 'Admin';

    /**
     * @var SystemActivity string
     */
    protected $tagname = "Buyer Roles";

    /**
     * @var Table string[]
     */
    protected $fillable = [
        'name',
        'permissions',
        'guard',
        'model_type',
        'model_id',
        'system_role_id',
        'company_id',
        'system_default_role'
    ];

    /**
     * Equolent relationship b/n CustomRoles and Company
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Equolent relationship b/n CustomRoles and SystemRole
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function systemRole()
    {
        return $this->belongsTo(SystemRole::class);
    }

    /**
     * Equolent relationship b/n CustomRoles and CustomPermission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customPermission()
    {
        return $this->belongsTo(CustomPermission::class);
    }

    /**
     *
     * Equolent polymorphic relationship b/n User and CustomRole
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function users()
    {
        return $this->morphTo(User::class, 'model_type','model_id');
    }

    /**
     * Roles list by companies
     *
     * @return mixed
     */
    public static function getRoles(User $user = null, $systemRole = null,$ownerCompanyId = null)
    {
        $systemRole = $systemRole ?? SystemRole::FRONTOFFICE;

        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $defaultcompanyId = $user ? $user->default_company : Auth::user()->default_company; // Default company ID

        $companyId = $ownerCompanyId ? $ownerCompanyId : $defaultcompanyId; // If Owner company id is passed otherwise default company Id

        $query = CustomRoles::with('users')->select('*')->where('company_id',$companyId )->where('system_role_id', $systemRole)->where('guard',$guardName);

        if (!Auth::user()->hasPermissionTo('list-all buyer users')) {
           //$query->where('model_id', \Auth::user()->id);
        }

        $roles = $query->get();
        return $roles;
    }
    /**
     *
     * Set default permissions to user Admin => user
     *
     * @param $id
     * @return bool
     */
    public static function setUserPersonalInfoPermissions($id)
    {
        $user = User::findOrFail($id);
        $defaultPermissions = [
            'publish buyer settings',
            'publish buyer profile',
            'create buyer personal info',
            'edit buyer personal info',
            'publish buyer personal info',
            'delete buyer personal info',
            'create buyer change password',
            'edit buyer change password',
            'publish buyer change password',
            'delete buyer change password',
            'create buyer chat',
            'edit buyer chat',
            'delete buyer chat',
            'publish buyer chat',
            'unpublish buyer chat'
        ];
        $user->givePermissionTo($defaultPermissions);
        return true;
    }

    // Default Role "ADMIN" Permissions list.

    public static function getAdminUserPermission(){

        $adminDefaultPermissions = [
            'create buyer rfqs',
            'publish buyer rfqs',
            'publish-only buyer rfqs',
            'edit buyer rfqs',
            'delete buyer rfqs',
            'create buyer orders',
            'publish buyer orders',
            'publish-only buyer orders',
            'edit buyer orders',
            'create buyer quotes',
            'publish buyer quotes',
            'edit buyer quotes',
            'publish-only buyer quotes',
            'create buyer join group',
            'edit buyer join group',
            'publish buyer join group',
            'delete buyer join group',
            'create buyer payments',
            'edit buyer payments',
            'publish buyer payments',
            'delete buyer payments',
            'create buyer address',
            'publish buyer address',
            'edit buyer address',
            'publish-only buyer address',
            'delete buyer address',
            'create buyer preferences',
            'edit buyer preferences',
            'publish buyer preferences',
            'delete buyer preferences',
            'publish buyer settings',
            'create buyer payment term',
            'edit buyer payment term',
            'publish buyer payment term',
            'delete buyer payment term',
            'create buyer company info',
            'edit buyer company info',
            'publish buyer company info',
            'delete buyer company info',
            'publish buyer profile',
            'create buyer roles and permissions',
            'edit buyer roles and permissions',
            'publish buyer roles and permissions',
            'delete buyer roles and permissions',
            'create buyer users',
            'edit buyer users',
            'publish buyer users',
            'delete buyer users'
        ];

        $permissionId = DB::table('permissions')->whereIn('name', $adminDefaultPermissions)->pluck('id')->toArray();

        return $permissionId;

    }
    //Get Custom Permission Value By Role Id
    public function custom_permissions() {
        return $this->belongsTo(Designation::class, 'designation');
    }

    /**
     *
     * Get count of users who has Role as "Approver"
     *
     * @param $id
     * @return bool
     */
    public static function getUserByCustomRole($buyerDefaultCompany, $approverRole) {
        $approvalConfigUsers = CustomRoles::join('custom_permissions','custom_roles.id','=','custom_permissions.value')
        ->join('model_has_custom_permissions','custom_permissions.id','=','model_has_custom_permissions.custom_permission_id')
        ->where('custom_roles.name','=',$approverRole)
        ->where('custom_roles.company_id','=',$buyerDefaultCompany)
        ->get(['model_has_custom_permissions.model_id']);
        return $approvalConfigUsers;
    }

    /**
     *
     */
    public function companyApprovals()
    {
        try {
            $approvalUsers = collect();
            $isOwner = User::checkOwnerByCompanyId(Auth::user()->default_company);
            $approvalUsersByCompany = User::whereJsonContains('assigned_companies',Auth::user()->default_company)->get();
            foreach($approvalUsersByCompany as $cmpUser) {
                $approverPermissionId = Permission::findByName('approval buyer approval configurations')->id;       //Get permission id by permission name
                $permissions = getRolePermissionAttribute($cmpUser->id ?? null)['permissions'];                     //Get Role & permission by user id

                if (!empty($permissions)) {
                    if (in_array($approverPermissionId, $permissions)) {
                        $approvalUsers->push($cmpUser);
                    }
                }
            }
            return $approvalUsers;

        } catch (\Exception $exception) {
            return null;
        }

    }


}
