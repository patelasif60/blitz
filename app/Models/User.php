<?php

namespace App\Models;

use App\Models\MongoDB\GroupChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Collection;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\SystemActivities;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Self_;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens,SoftDeletes, HasRoles, SystemActivities, HybridRelations;

    const BUYERADMIN = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = 'mysql';
    protected $guard_name = 'web';

    protected $tagname = "User";

    protected $fillable = [
        'salutation',
        'firstname',
        'lastname',
        'email',
        'phone_code',
        'mobile',
        'password',
        'role_id',
        'profile_pic',
        'is_active',
        'currency_id',
        'assigned_companies',
        'language_id','designation','department',
        'approval_invite',
        'google_id',
        'fb_id',
        'linkedin_id',
        'added_by',
        'updated_by',
        'default_company',
        'buyer_admin',
        'mobile_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get the user's full name.
     *
     * @return string
     */

    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getMobileNumberAttribute($value)
    {
        return $this->phone_code.' '.$this->mobile;
    }

    /**
     * User active de-active status
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getActiveStatusAttribute()
    {
        return $this->is_active == 1 ? __('admin.active') : __('admin.deactive');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function company()
    {
        return $this->hasOneThrough(Company::class,UserCompanies::class,'user_id','id','','company_id');
    }

    /**
     * User relationship b/n Company table by default_company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|\Jenssegers\Mongodb\Relations\HasOne
     */
    public function companies()
    {
        return $this->hasOne(Company::class, 'id', 'default_company');
    }

    public function order()
    {
        return $this->morphMany(QuoteActivity::class, 'user');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function supplierId(){
        return UserSupplier::where('user_id', auth()->user()->id)->first();
    }

    public function userAddData()
    {
        return $this->hasOne(User::class,'id','added_by')->first();
    }

    public function userUpdateData()
    {
        return $this->hasMany(User::class,'id','updated_by')->first();
    }

    public function buyerBank()
    {
        return $this->hasOne(BuyerBanks::class);
    }

    public function rfq()
    {
        return $this->hasOneThrough(Rfq::class,UserRfq::class,'user_id','id','','rfq_id');
    }

    public function quote()
    {
        return $this->hasManyThrough(Quote::class,UserRfq::class,'user_id','rfq_id','','rfq_id');
    }

    public function rfqs()
    {
        return $this->hasManyThrough(Rfq::class,UserRfq::class,'user_id','id','','rfq_id');
    }

    public function supplier()
    {
        return $this->hasOneThrough(Supplier::class,UserSupplier::class,'user_id','id','','supplier_id');
    }

    /**
     * Get all Buyer Banks by User id
     *
     * @return mixed
     */
    public function BuyerBanks() {

        return $this->hasMany(BuyerBanks::class,'user_id','id');

    }

    /**
     * Get all custom permissions
     *
     * @return mixed
     */
    public function getCustomPermission(string $type, $role = null, $guardName = null, $systemRole = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = $role ?? Auth::user()->roles->pluck('name')->first();

        $systemRole = $systemRole ?? SystemRole::findByName('back office')->id;

        if ($role != null)
        {

            $assignedPermissions = CustomPermission::with('modelHasCustomPermissionMulti')->where('name', $type)->where('system_role_id', $systemRole)->where('model_type', 'App/Models/'.$type)
            ->has('modelHasCustomPermissionMulti')->get();


            $userPermissions = static::getUserCustomPermission($assignedPermissions);

            return $userPermissions;
        }

        return null;
    }

    /**
     * Get Agents by custom permission
     *
     * @param string $type Module name e.g category
     * @param array $params Module values or data
     * @param null $role User role name
     * @param null $guardName Guard name
     * @param null $systemRole System Role Id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAgentsByCustomPermissions(string $type, $params = null, $role = null, $guardName = null, $systemRole = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = $role ?? Auth::user()->roles->pluck('name')->first();

        $systemRole = $systemRole ?? SystemRole::findByName('back office')->id;

        if ($role != null) {

            $agents = CustomPermission::with('modelHasCustomPermissionMulti')->where('name', $type)->where('system_role_id', $systemRole)->where('model_type', 'App/Models/' . $type)
                ->where('guard_name', $guardName)->where('value', $params)
                ->has('modelHasCustomPermissionMulti')->get();

            return $agents;
        }

        return collect();

    }

    /**
     * Get custom permission by user.
     */
    protected static function getUserCustomPermission($params = null)
    {
        $value = $params->each(function($param){
            return $param->modelHasCustomPermissionMulti->where('model_id', Auth::user()->id);
        });

        return $value;
    }

    /**
     * Get default company by user.
     */
    public function defaultCompany()
    {
        return $this->hasOne(Company::class,'id','default_company');
    }

    /**
     *
     * Parent user relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentUser()
    {
        return $this->belongsTo(User::class, 'added_by','id');
    }

    /**
     *
     * Child user relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(User::class, 'added_by');
    }

    /**
     * User relation b/n designation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\Jenssegers\Mongodb\Relations\BelongsTo
     */
    public function user_designation() {
        return $this->belongsTo(Designation::class, 'designation');
    }

    //User designation
    public function user_department() {
        return $this->belongsTo(Department::class, 'department');
    }

    //Get Approval Config Users
    public function approval_config_user() {
        return $this->hasOne(UserApprovalConfig::class, 'user_id');
    }

    //Get All Invited Users for Approval Configuration Process (Ronak M - 21-07-2022)
    public static function getAllInvitedUsers($userId, $companyDetails) {
        $invited_users = self::with(['user_designation' => function($q) {
            $q->select(['id','name']);
        }])->with(['company' => function($q1) {
            $q1->select(['companies.id']);
        }])->where('users.id','!=',$userId)->whereJsonContains('users.assigned_companies',$companyDetails)
        ->orderBy('users.id', 'DESC')
        ->groupBy('users.id')
        ->get(['users.id','firstname','lastname','email','mobile','users.role_id as role_id','is_active','created_at','designation']);
        return $invited_users;
    }

    //Get All Invited Users for Approval Configuration Process (Ronak M - 21-07-2022)
    public static function getApprovalConfigUsers($userId, $companyDetails) {
        $approvalConfigUsers = User::join('user_companies','users.id','=','user_companies.user_id')
        ->join('user_approval_configs', 'users.id', '=', 'user_approval_configs.user_id')
        ->leftjoin('designations', 'users.designation', '=', 'designations.id')
        ->where('users.id','!=',$userId)
        ->where('users.is_delete',0)->where('user_companies.company_id',$companyDetails)
        ->orderBy('users.id','DESC')
        ->get(['users.id AS id','users.firstname AS firstname','users.lastname AS lastname','users.email AS email','users.mobile AS mobile','users.role_id AS role_id','users.is_active AS is_active','users.created_at AS created_at','user_approval_configs.user_type AS user_type','user_approval_configs.created_at AS app_created_at','designations.name AS designation']);
        return $approvalConfigUsers;
    }

    /**
     * Purpose - Is user is a default company owner
     * Impact - Buyer Module
     *
     * @param null $id User ID
     * @return bool
     */
    public static function checkCompanyOwner($id = null){

        $user = User::with('defaultCompany')->where('id',empty($id) ? Auth::user()->id : $id)->first();

        $defaultCompanyOwner = $user->defaultCompany->owner_user ?? '';

        if (!empty($defaultCompanyOwner) && ($defaultCompanyOwner == (empty($id) ? Auth::user()->id : $id))) {
            return true;
        }

        return false;
    }

    /**
     * checkCompanyOwner: Check logged in user is owner or not of requested company
     * @param $companyId
     * @return bool
     */
    public static function checkOwnerByCompanyId($companyId){
        $ownerUserId = Company::where('id' ,$companyId)->pluck('owner_user')->first();
        return Auth::user()->id == $ownerUserId ? true : false;
    }

    // get user id based on company id.
    public static function getOwnerUserIdByCompanyId($companyId){
        $ownerUserId = Company::where('id' ,$companyId)->pluck('owner_user')->first();
        return $ownerUserId;
    }

    /**
     * revokeUserPermission: remove old user role and user permissions
     * get current role name => Auth::user()->roles->pluck('name')[0]
     * get all current permission name => Auth::user()->getAllPermissions()->pluck('name')
     * @param $userId
     * @return bool
     */
    public static function revokeUserPermission($userId)
    {
        $user = self::findOrFail($userId);
        $user->revokePermissionTo($user->getAllPermissions()->pluck('name')->toArray());
        return true;
        if(isset(Auth::user()->roles->pluck('name')[0]) == true){
            $user->removeRole(Auth::user()->roles->pluck('name')[0]);
        }
    }

    /**
     * assignUserPermission: update new user role and user permissions
     *
     * get current role name => Auth::user()->roles->pluck('name')[0]
     * get all current permission name => Auth::user()->getAllPermissions()->pluck('name')
     *
     * @param $userId
     * @return bool
     */
    public static function assignUserPermission($userId)
    {
        $user = self::findOrFail($userId);
        $rolePermisisonObj = getRolePermissionAttribute($userId, $user->default_company); // get role and permission based on default company
        $isOwner = self::checkCompanyOwner(); // Check current user is owner or not of default company
        if($isOwner == true){
            /** syncRoles: Assign new role and permission */
            $user->syncRoles('buyer');
            $buyerPermissions = SpatieRole::findByName('buyer')->permissions->pluck('name');
            $user->givePermissionTo($buyerPermissions);

        }else{
            $user->syncRoles('sub-buyer');
            if(!empty($rolePermisisonObj['permissions'])){
                /** give permission to user by permission names */
                $user->givePermissionTo(CustomPermission::findByIds($rolePermisisonObj['permissions']));
            }
        }
        /** assign default permission to user by user id */
        CustomRoles::setUserPersonalInfoPermissions($userId); // Assign default permission to user
        return true;
    }
    public function userOtherInformation()
    {
        return $this->hasOne(UserOtherInformation::class);
    }

    // return company name from company array ID
    public function assignCompany()
    {
        $arr = [];
        $datas = $this->assigned_companies;
        if (!empty($datas)) {
            foreach($datas as $values) {
                $singleCompanyName = Company::where('id', $values)->pluck('name')->first();
                array_push($arr,$singleCompanyName);
            }

            $this->assigned_companies = $arr;
            return $this;
        }

        return $this->assigned_companies;
    }

    /**
     * Company onwer relationalship
     */
    public function CompanyOwner() {
            return $this->hasOne(Company::class,'owner_user','id');
    }
    /**
     * function for company User details
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Jenssegers\Mongodb\Relations\HasMany
     */
    public function companyUserDetails()
    {
        return $this->hasMany(CompanyUser::class,'users_id');
    }


}
