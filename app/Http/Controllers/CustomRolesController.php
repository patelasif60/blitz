<?php

namespace App\Http\Controllers;


use App\Models\CustomRoles;
use App\Models\ModelHasCustomPermission;
use App\Models\PermissionsGroup;
use App\Models\SystemActivity;
use App\Models\SystemRole;
use App\Models\CustomPermission;
use App\Models\User;
use App\Traits\SystemActivities;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Buyer\RolesPermission\AddBuyerRoleRequest;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Validator;


class CustomRolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:publish buyer roles and permissions', ['only' => ['index']]);
        $this->middleware('permission:create buyer roles and permissions', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit buyer roles and permissions', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete buyer roles and permissions', ['only' => ['delete']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();

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

            $isOwner = User::checkCompanyOwner();
            if ($isOwner == true) {
                $query = CustomRoles::with('users')->select('*')->where('company_id', Auth::user()->default_company)->where('system_role_id', SystemRole::FRONTOFFICE);
            } else {
                $query = CustomRoles::with('users')->select('*')->where('company_id', Auth::user()->default_company)->where('model_type', User::class)->where('system_role_id', SystemRole::FRONTOFFICE);
            }

            //Get approval toggle permisssion id
            $permissionId = Permission::findByName('toggle buyer approval configurations')->id;

            //show company wise added role for particular buyer.
            if ($authUser->default_company){
                $query->where('company_id', $authUser->default_company);
            }
            // Total records
            $totalRecords = $query->count();

            if ($search!="") {
                $query = $query->where('name', 'LIKE', "%$search%");
            }

            if ($column=='name') {
                $query = $query->orderBy('name', $sort);
            }

            if ($column=='created_by') {
                $query = $query->orderBy('name', $sort);
            }

            // Total Display records
            $totalDisplayRecords = $query->count();

            $customRoles = $query->skip($start)->take($length);

            $customRoles = $query->get();

            return response()->json([

                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $customRoles->map(function ($role) use ($permissionId) {
                if($role->system_default_role != 1) {
                    $editBtn    =   '<a href="javascript:void(0)" class="px-2 editRoles" data-id="'.Crypt::encrypt($role->id).'" data-toggle="tooltip" ata-placement="top" title="'.trans('admin.edit').'"><img height="12px" src="'.\URL::asset("front-assets/images/icons/icon_edit_add.png").'"></a>';
                    $deleteBtn  =   '<a href="javascript:void(0)" class="px-2 removeRoles" data-id="'.Crypt::encrypt($role->id).'" data-toggle="tooltip" ata-placement="top" title="'.trans('admin.delete').'"><img height="12px" src="'.\URL::asset("front-assets/images/icons/icon_delete_add.png").'"></a>';
                }else{
                    $editBtn    =   '';
                    $deleteBtn  =   '';
                }

                    $action     =   $editBtn.$deleteBtn;

                    $rolArray   =   $role->toArray();

                    $roleUser   =   array_key_exists(Str::snake(User::class), $rolArray) ? $rolArray[Str::snake(User::class)] : '';
                    $rolePermissionArr = json_decode($role->permissions, TRUE);

                    return [
                        'name'              =>  $role->name,
                        'created_by'        =>  array_key_exists('id', $roleUser) ? $roleUser['firstname']." ".$roleUser['lastname'] : '-',
                        'approval_process'  =>  in_array($permissionId,  $rolePermissionArr) == true ? 'ON' : '-',
                        'action'            =>  $action,
                    ];

                })
            ]);
        }

        return View::make('buyer.setting.roles.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissionGroup = "";
        $compIdArr = [];
        if( config('app.env') == 'production' || config('app.env') == 'live') {
            $compIdArr = ['3'];
            if(in_array(Auth::user()->default_company, $compIdArr)){ // Show permission with RFN
                $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->children()->sortBy('sort');
            }else{
                //Show permission without RFN
                $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->childrenWithoutRfn()->sortBy('sort');
            }
        } else {
            $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->children()->sortBy('sort');
        }



        return View::make('buyer.setting.roles.add')->with(compact(['permissionGroup']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBuyerRoleRequest $request)
    {
        if($request->ajax()){

            if ($request->isToggleOn == "true") {
                //call function to check approvers count
                $approvals = count(CustomRoles::companyApprovals());

                if ($approvals > 0) {
                    return response()->json(['success' => false, 'message' => 'Approvers Found', 'response' => true]);
                } else {
                    return response()->json(['success' => true, 'message' => 'No Approvers Found', 'response' => false]);
                }
            }

            $permissions = arraysToCollect($request->rolePermission);

            $customRole = CustomRoles::create([
                'name'              =>  $request->roleName,
                'permissions'       =>  json_encode($permissions),
                'guard'             =>  Auth::getDefaultDriver(),
                'model_type'        =>  User::class,
                'model_id'          =>  Auth::user()->id,
                'system_role_id'    =>  SystemRole::FRONTOFFICE,
                'company_id'        =>  Auth::user()->default_company,
            ]);

            if ($customRole) {

                $customPermission = CustomPermission::create([
                    'name'              =>  'Custom Role',
                    'model_type'        =>  CustomRoles::class,
                    'value'             =>  $customRole->id,
                    'system_role_id'    =>  SystemRole::FRONTOFFICE,
                    'guard'               =>  Auth::getDefaultDriver()
                ]);
            }

            if ($customPermission) {

                return response()->json(['success' => true, 'message' => __('profile.role_added_successfully')]);

            } else {

                return response()->json(['success' => false, 'message' => __('profile.something_went_wrong')]);

            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomRoles  $customRoles
     * @return \Illuminate\Http\Response
     */
    public function show(CustomRoles $customRoles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomRoles  $customRoles
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $encryptedRoleId    = $id;
            $id                 = Crypt::decrypt($id);
            $role               = CustomRoles::findOrFail($id);
            $role->permissions  = Arr::flatten(json_decode($role->permissions));

            /**begin: System activity */
            CustomRoles::bootSystemView(new CustomRoles(),'Buyer Role',SystemActivity::EDITVIEW,$id);
            /**end: System activity */

        } catch (\Exception $e) {

            abort('404');
        }

        //$permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->children()->sortBy('sort');

        $permissionGroup = "";
        $compIdArr = [];
        if( config('app.env') == 'production' || config('app.env') == 'live') {
            $compIdArr = ['3'];
            if(in_array(Auth::user()->default_company, $compIdArr)){ // Show permission with RFN
                $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->children()->sortBy('sort');
            }else{
                //Show permission without RFN
                $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->childrenWithoutRfn()->sortBy('sort');
            }
        } else {
            $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->children()->sortBy('sort');
        }




        return View::make('buyer.setting.roles.add')->with(compact(['permissionGroup', 'role', 'encryptedRoleId']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomRoles  $customRoles
     * @return \Illuminate\Http\Response
     */
    public function update(AddBuyerRoleRequest $request)
    {

        $buyerRoleId = request()->segment(count(request()->segments())) ? request()->segment(count(request()->segments())) : '';

        try {
            $buyerRoleId = Crypt::decrypt($buyerRoleId);

        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => __('profile.something_went_wrong')]);

        }

        if($request->ajax()){

            $permissions = collect();
            foreach ($request->rolePermission as $permission)
            {
                foreach (Arr::flatten(json_decode($permission, true)) as $permission) {

                    $permissions->push($permission);

                }
            }

            if ($request->isToggleOn == "true") {
                //call function to check approvers count
                $approvals = count(CustomRoles::companyApprovals());

                if ($approvals > 0) {
                    return response()->json(['success' => false, 'message' => 'Approvers Found', 'response' => true]);
                } else {
                    return response()->json(['success' => true, 'message' => 'No Approvers Found', 'response' => false]);
                }
            }

            $customRole = CustomRoles::where('id', $buyerRoleId)->update([
                'name'              =>  $request->roleName,
                'permissions'       =>  json_encode($permissions),
                'guard'             =>  \Auth::getDefaultDriver(),
                'model_type'        =>  User::class,
                'model_id'          =>  \Auth::user()->id,
                'system_role_id'    =>  SystemRole::FRONTOFFICE,
                'company_id'        =>  \Auth::user()->default_company
            ]);

            if ($customRole) {
                $customPermission = CustomPermission::where('model_type', CustomRoles::class)->where('value', $buyerRoleId)->first();

                if (!empty($customPermission->modelHasCustomPermission())) {

                    $assignedUsers = ModelHasCustomPermission::where('custom_permission_id', $customPermission->id)->get();
                    $assignedUsers->each(function ($assignedUser) use($permissions){
                        // $user = User::findOrFail($assignedUser->model_id);
                        $user = User::where('id',$assignedUser->model_id)->where('default_company',Auth::user()->default_company)->get()->first();

                        if(!empty($user)){
                            if(!empty($assignedUser) && $assignedUser->custom_permissions){
                                $user->revokePermissionTo(CustomPermission::findByIds(json_decode($assignedUser->custom_permissions)));
                            }
                        }


                    });

                    ModelHasCustomPermission::where('custom_permission_id', $customPermission->id)->update([
                        'custom_permissions' => json_encode($permissions)
                    ]);

                    $assignedUsers->each(function ($assignedUser) use($permissions){
                        $user = User::where('default_company', Auth::user()->default_company)->where('id',$assignedUser->model_id)->first();
                        if(!empty($user)){
                            $user->givePermissionTo(CustomPermission::findByIds(json_decode($permissions)));
                            CustomRoles::setUserPersonalInfoPermissions($assignedUser->model_id);
                        }

                    });

                }

                return response()->json(['success' => true, 'message' => __('profile.role_updated_successfully')]);

            } else {

                return response()->json(['success' => false, 'message' => __('profile.something_went_wrong')]);

            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomRoles  $customRoles
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomRoles $customRoles, $id)
    {
        try {

            $buyerRoleId = Crypt::decrypt($id);

            $checkRole = json_decode($this->checkRoleAssigend(new Request(), $id), true);

            if (count($checkRole) == 1) {

                $role = $customRoles->findOrFail($buyerRoleId);

                $role->delete();

                return json_encode(['success' => true, 'message' => __('profile.role_removed_successfully'), 'type' => 'success']);

            }

            return json_encode(['success' => false, 'message' => __('profile.something_went_wrong'), 'type' => 'error']);


        } catch (\Exception $e) {

            return json_encode(['success' => false, 'message' => __('profile.something_went_wrong'), 'type' => 'error']);

        }

    }

    /**
     * Check the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkRoleAssigend(Request $request, $id = null)
    {

        try {

            $buyerRoleId = empty($id) ? Crypt::decrypt($request->role) : Crypt::decrypt($id);

            $customPermission = CustomPermission::where('model_type', CustomRoles::class)->where('value', $buyerRoleId)->first();


            if (!empty($customPermission->modelHasCustomPermission())) {

                return json_encode(['success' => true, 'message' => __('profile.role_is_already_assigned'), 'type' => 'info']);

            } else {

                return json_encode(['success' => false]);

            }

        } catch (\Exception $e) {

            return json_encode(['success' => true, 'message' => " __('profile.something_went_wrong') ", 'type' => 'error']);

        }
    }

}
