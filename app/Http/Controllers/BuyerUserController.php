<?php

namespace App\Http\Controllers;

use App\Events\BuyerNotificationEvent;
use App\Http\Requests\Buyer\User\AddUserRequest​;
use App\Models\Company;
use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\Department;
use App\Models\Designation;
use App\Models\ModelHasCustomPermission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserApprovalConfig;
use App\Models\UserCompanies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyUserController;

class BuyerUserController extends Controller
{
    /**
     * Display a listing of the resource. //Get all added users (Ronak M - 28/07/2022)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $authUser = $user = Auth::user();
        $authUserCompany = $authUser->defaultCompany()->first();

        $designation = Designation::all()->where('is_deleted', 0);
        $department = Department::all()->where('is_deleted', 0);

        $customRoles = CustomRoles::getRoles();

        //Get approval permisssions id
        $approverPersonId = Permission::findByName('approval buyer approval configurations')->id;


        if ($request->ajax()) {

            $draw = $request->get('draw');
            $start = $request->get("start");
            $length = $request->get("length");
            $sort = $request->get('order')[0]['dir'];
            $search = $request->get('search')['value'];

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $columnIndex = $columnIndex_arr[0]['column'];
            $column = $columnName_arr[$columnIndex]['data'];
            if (Auth::user()->buyer_admin == 1) {
                $query = User::with(['defaultCompany','user_designation','company'])->where('users.id','!=',$authUser->id)->whereJsonContains('users.assigned_companies',$authUser->default_company);
            } else {
                $query = User::with(['user_designation' => function($q) {
                    $q->select(['id','name']);
                }])->with(['company' => function($q1) {
                    $q1->select(['companies.id']);
                }])->where('users.id','!=',$authUser->id)->whereJsonContains('users.assigned_companies',$authUser->default_company);
            }

            // Total records
            $totalRecords = $query->count();

            if ($search!="") {
                $query = $query->where(function($query) use($search){
                     $query->where('firstname', 'LIKE', "%$search%")->orWhere('lastname', 'LIKE', "%$search%")->orWhere('email', 'LIKE', "%$search%");
                });
            }

            if ($column=='firstname') {
                $query = $query->orderBy('firstname', $sort);
            }

            $query = $query->orderBy('users.id', 'DESC')
            ->groupBy('users.id');

            // Total Display records
            $totalDisplayRecords = $query->count();

            $approverData = $query->skip($start)->take($length);

            $approverData = $query->get();

            $approverData = $query->get();
            return response()->json([
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $approverData->map(function ($userData) use ($approverPersonId,$authUserCompany) {

                    if($status_class = $userData->is_active == 1 ? 'badge bg-success' : 'badge bg-danger bg-opacity-25 text-dark');
                    if($status = $userData->is_active == 1 ? 'Active' : 'Verification Pending');
                    $showStatus = '<small class="'.$status_class.'">'.$status.'</small>';

                    $action = '<a class="rolecollapse collapsed" data-bs-toggle="collapse" href="#collapse'.$userData->id.'" role="button" aria-expanded="false" aria-controls="#collapse'.$userData->id.'">
                        <span class="px-1 py-0"><button type="button" class="btn rounded-3"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></span></a>

                    <div class="text-start shadow border collapsemoreaction collapse" id="collapse'.$userData->id.'">
                        <a href="javascript:void(0)" class="viewUserDetails" data-id="'.Crypt::encrypt($userData->id).'" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#rm-view" ata-placement="top" title="View">
                            <div class="editmenu px-2 py-2">
                                <span class="" style="min-width: 20px; display: inline-block;text-align: center;"><i class="fa fa-eye"></i></span>
                                <span class="ms-2" style="font-size: 14px;">'.trans('admin.view').'</span>
                            </div>
                        </a>

                        <a href="javascript:void(0)" class="editInvitedUserData" data-id="'.Crypt::encrypt($userData->id).'" data-toggle="tooltip" ata-placement="top" title="Edit">
                            <div class="editmenu px-2 py-2">
                                <span class="" style="min-width: 20px; display: inline-block;text-align: center;">
                                    <img height="12px" src="'.\URL::asset("front-assets/images/icons/icon_edit_add.png").'"></span>
                                <span class="ms-2" style="font-size: 14px;">'.trans('admin.edit').'</span>
                            </div>
                        </a>

                        <a href="javascript:void(0)" class="deleteInvitedUser" data-id="'.Crypt::encrypt($userData->id).'" data-toggle="tooltip" ata-placement="top" title="Delete">
                            <div class="editmenu px-2 py-2">
                                <span class="" style="min-width: 20px; display: inline-block;text-align: center;">
                                    <img height="12px" src="'.\URL::asset("front-assets/images/icons/icon_delete_add.png").'"></span>
                                <span class="ms-2" style="font-size: 14px;">'.trans('admin.delete').'</span>
                            </div>
                        </a>
                    </div>';

                    if ($authUserCompany->owner_user==$userData->id) { // child user can't do action on parent user
                        $action     =   '';
                    }


                    $customRoleId = getRolePermissionAttribute($userData->id)['custom_role_id'] != null ? getRolePermissionAttribute($userData->id)['custom_role_id'] : '';
                    $userRolePermission = getRolePermissionAttribute($userData->id)['role'] != null ? getRolePermissionAttribute($userData->id)['role'] : '';

                    $showPermissionPopup = '<a class="p-0 roleModalView text-black" data-bs-toggle="modal" data-bs-target="#rolePopUp" data-id="'.$customRoleId.'" title="'.trans('admin.view').'">'.$userRolePermission.'</a>';

                    $allPermissions = getRolePermissionAttribute($userData->id ?? null);

                    $rolePermissionsArr[] = !empty($allPermissions) ? $allPermissions['permissions'] : [];

                    return [
                        'firstname'     =>  $userData->firstname,
                        'lastname'      =>  $userData->lastname,
                        'email'         =>  $userData->email,
                        'status'        =>  $showStatus,
                        'role_id'       =>  $showPermissionPopup,
                        'permission_id' =>  !empty($rolePermissionsArr[0] && $approverPersonId) ? (in_array($approverPersonId, $rolePermissionsArr[0]) == true ? 'Approver' : '-') : '-',
                        'action'        =>  $action,
                    ];

                })
            ]);
        }
        return View::make('buyer.setting.users.index',['user' => $user, 'designations' => $designation ,'departments' => $department, 'customRoles' => $customRoles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddUserRequest​ $request) {
        if($request->ajax()) {
            $authUser = Auth::user();
            if (!empty($authUser)) {
                $user = User::find($authUser->id);
                $company_id = UserCompanies::select('company_id')->where('user_id',$user->id)->first();

                //Below condition is for multicompany users
                $userExist = User::where('email',$request->email)
                            ->whereJsonContains('assigned_companies', [Auth::user()->default_company])
                            ->first();

                if (!empty($userExist)) {
                    $msg =  __('validation.email_already_exist');
                    return response()->json(['error' => $msg]);
                } else if((isset($request->role) && $request->role && $request->role!="Approver")) {
                    $assignUser = (new UserController)->assignUserWithRole($request);
                    $msg =  $assignUser->getOriginalContent()['success'] == true ? __('validation.user_added') :  __('profile.something_went_wrong');
                    return response()->json(array('success' => true, 'msg' => $msg));
                }


                buyerNotificationInsert(Auth::user()->id, 'Invite User', 'buyer_invite_added_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
                return response()->json(array('success' => true));

                $msg =  __('validation.user_invited');
                return response()->json(array('success' => true, 'msg' => $msg));
            }
            $errorMsg = __('signup.something_went_wrong');
            return response()->json(['response' => 'error', 'message'=> $errorMsg, 'success' => 'false']);
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        if ($request->ajax()) {

            $userData = User::where('id',$id)->first();
            $companyUserDetails = $userData->companyUserDetails->where('company_id', Auth::user()->default_company)->first();
            $departmentDetails = '';
            $designationDetails = '';
            $branches = '';
            if(!empty($companyUserDetails)){
                $departmentDetails = $companyUserDetails->departmentDetails ?? '';
                $designationDetails = $companyUserDetails->designationDetails ?? '';
                $branches = $companyUserDetails->branches ?? '-';
            }
            $data = [
                'id' => $userData->id,
                'firstname' => $userData->firstname,
                'lastname' => $userData->lastname,
                'email' => $userData->email,
                'mobile' => $userData->mobile,
                'designationName' => $designationDetails->name ?? '',
                'departmentName' => $departmentDetails->name ?? '',
                'designation' => $designationDetails->id ?? '',
                'department' => $departmentDetails->id ?? '',
                'role' => getRolePermissionAttribute($userData->id)['role_id'] != null ? getRolePermissionAttribute($userData->id)['role_id'] : 'Approval / Consultant',
                'userRole' => getRolePermissionAttribute($userData->id)['role'] != null ? getRolePermissionAttribute($userData->id)['role'] : '',
                'branches' => $branches , // Branches for RFN
                'created_date' => changeDateFormat($userData->created_at)
            ];
            return response()->json(['response' => 'success', 'message' => 'Data fetched successfully', 'success' => 'true', 'successData' => $data]);

        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddUserRequest​ $request, $id)
    {
        $id = Crypt::decrypt($id);
        if ($request->ajax()) {
            $userId = Auth::user()->id;

            if (!empty($userId)) {
                $userData = User::where('id', $id)->update([
                    'id'          => $request->invitedUserId,
                    'firstname'   => $request->firstName,
                    'lastname'    => $request->lastName,
                    'email'       => $request->email,
                    'mobile'      => $request->mobile,
                    'designation' => $request->designation,
                    'department'  => $request->department,
                ]);

                $user = User::find($request->invitedUserId);

                /********begin: assign custom role and permission to user********/
                if ((isset($request->role) && $request->role && $request->role!="Approver")){
                    $user->updated_by = Auth::id();
                    $user->save();

                    //Get role and permissions
                    $customPermissionId = CustomPermission::where('model_type',CustomRoles::class)->where('value',$request->role)->get()->pluck('id')->first();
                    $rolePermissions = CustomRoles::where('id',$request->role)->get()->pluck('permissions')->first();

                    //Get old permissions and remove them
                    if (empty(getRolePermissionAttribute($user->id)['role'])) {

                        ModelHasCustomPermission::create(['custom_permission_id' => $customPermissionId, 'model_type' => User::class, 'model_id' => $user->id, 'custom_permissions' => $rolePermissions]);

                    } else {
                        $oldCustomRolePermission = getRolePermissionAttribute($user->id);

                        $childUser = User::findOrFail($user->id);
                        !empty($oldCustomRolePermission['permissions']) ? $childUser->revokePermissionTo(CustomPermission::findByIds($oldCustomRolePermission['permissions'])) : '';
                        ModelHasCustomPermission::where('model_type', User::class)->where('model_id', $user->id)->where('custom_permission_id', getRolePermissionAttribute($user->id)['custom_permission']->first()->id)
                                ->update([
                                    'custom_permission_id'  =>  json_encode($customPermissionId),
                                    'custom_permissions'    =>  $rolePermissions
                                ]);
                    }

                    //Assign new permissions by role
                    $childUser = User::findOrFail($user->id);
                    $childUser->givePermissionTo(CustomPermission::findByIds(json_decode($rolePermissions)));
                    CustomRoles::setUserPersonalInfoPermissions($user->id);

                } else if ($request->role == "Approver") {
                    //Get role and permissions
                    $customPermissionId = CustomPermission::where('model_type',CustomRoles::class)->where('value',$request->role)->get()->pluck('id')->first();
                    $rolePermissions = CustomRoles::where('id',$request->role)->get()->pluck('permissions')->first();

                    if (!empty($rolePermissions)) {
                        //Remove permissions
                        $oldCustomRolePermission = getRolePermissionAttribute($user->id);
                        $childUser = User::findOrFail($user->id);
                        $childUser->revokePermissionTo(CustomPermission::findByIds($oldCustomRolePermission['permissions']));
                        ModelHasCustomPermission::where('model_type', User::class)->where('model_id', $user->id)->where('custom_permission_id', getRolePermissionAttribute($user->id)['custom_permission']->first()->id)->delete();
                    }

                }
                /********end: assign custom role and permission to user********/

                (new CompanyUserController)->companyUserCreate($user, Auth::user(), $request);

                buyerNotificationInsert(Auth::user()->id, 'Buyer User Updated', 'buyer_user_updated_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
                return response()->json(['response' => 'success', 'message' => 'Buyer user updated successfully', 'success' => 'true']);
            }
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        if ($request->ajax()) {

            $user = User::find(Auth::user()->id);
            $deletedUser = User::find($id);

            $company = UserCompanies::select('company_id')->where('user_id',$user->id)->first();

            /**begin: Update default company and remove assigned company**/
            if($user->default_company == $deletedUser->default_company){
                $childUserDefaultCompany = Company::where('owner_user', $deletedUser->id)->first();
                if(!empty($childUserDefaultCompany)) {
                    $oldCustomRolePermission = getRolePermissionAttribute($deletedUser->id);
                    if(!empty($oldCustomRolePermission['custom_permission'])){
                        ModelHasCustomPermission::where('custom_permission_id',$oldCustomRolePermission['custom_permission']->first()->id)
                            ->where('model_type',User::class)
                            ->where('model_id',$deletedUser->id)
                            ->delete();
                    }

                    $deletedUser->default_company = $childUserDefaultCompany->id;
                    $deletedUser->save();

                    $deletedUser->revokePermissionTo($deletedUser->getAllPermissions()->pluck('name')->toArray()); //Old permission revoke

                    $buyerPermissions = SpatieRole::findByName('buyer')->permissions->pluck('name'); //Get new permission
                    $deletedUser->givePermissionTo($buyerPermissions); //New permission assign

                }

            }
            if($deletedUser->assigned_companies) {
                // added user and delete
                $oldCustomRolePermission = getRolePermissionAttribute($deletedUser->id);
                if(!empty($oldCustomRolePermission['custom_permission'])){
                    ModelHasCustomPermission::where('custom_permission_id',$oldCustomRolePermission['custom_permission']->first()->id)
                        ->where('model_type',User::class)
                        ->where('model_id',$deletedUser->id)
                        ->delete();
                }
                // added user and delete

                $deleteUserCompanyArr = json_decode($deletedUser->assigned_companies);

                $userDefaultCompany = $user->default_company;

                $diffCompany = array_filter($deleteUserCompanyArr, function ($company) use ($userDefaultCompany) {
                    return $company !== $userDefaultCompany;
                });

                $newCompany = [];

                foreach ($diffCompany as $company) {
                    array_push($newCompany, $company);
                }

                User::where('id', $id)->update(['assigned_companies' => json_encode($newCompany), 'is_active' => 1]);
            }
            /**end: Update default company and remove assigned company**/


            if (Auth::user()->id && isset($userData)) {
                buyerNotificationInsert(Auth::user()->id, 'Buyer User Deleted', 'buyer_user_deleted_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
            }
            return response()->json(['response' => 'success', 'message' => __('validation.user_deleted'), 'success' => true]);
        }

        abort(404);
    }
}
