<?php

namespace App\Http\Controllers\Admin\Buyer;

use App\Events\BuyerNotificationEvent;
use App\Export\BuyerExport;
use App\Exports\Admin\Buyer\Company\BuyerCompaniesExport;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Buyer\Company\CompanyDetailsRequest;
use App\Http\Requests\Admin\Buyer\UserDetailsRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyConsumption;
use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\Department;
use App\Models\Designation;
use App\Models\ModelHasCustomPermission;
use App\Models\OtherCharge;
use App\Models\SystemActivity;
use App\Models\SystemRole;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserCompanies;
use App\Models\UserRfq;
use App\Models\XenditCommisionFee;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

class AdminBuyerCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create buyer list|edit buyer list|delete buyer list|publish buyer list|unpublish buyer list', ['only' => ['list', 'buyerDetailsView']]);
        $this->middleware('permission:create buyer list', ['only' => ['create']]);
        $this->middleware('permission:edit buyer list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete buyer list', ['only' => ['delete']]);
    }

    function list()
    {
        /**begin: system log**/
        User::bootSystemView(new User(), 'Buyer');
        /**end:  system log**/
        return View::make('admin.buyer.company.index');
    }

    /**
     * Display a datatable listing of resource
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listJson(Request $request)
    {
        try {
            $authUser = \Auth::user();
            if ($request->ajax() && $request->get('draw')) {

                $draw = $request->get('draw');
                $start = $request->get("start");
                $length = $request->get("length");
                $sort = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
                $search = !empty($request->get('search')) ? $request->get('search')['value'] : '';

                $columnIndex_arr = $request->get('order');
                $columnName_arr = $request->get('columns');
                $columnIndex = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
                $column = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';

                $query = Company::with('user')->whereHas('user', function ($query) {
                    return $query->where('users.role_id', 2)->where('users.is_delete', 0)->where('users.approval_invite', 0);
                });

                //Agent category permission
                if (Auth::user()->hasRole('agent')) {
                    $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
                    $query = $query->with('companyConsumption')->whereHas('companyConsumption', function ($query) use ($assignedCategory) {
                        $query->whereIn('company_consumptions.product_cat_id', $assignedCategory)
                            ->orWhereHas('user', function ($query) {
                                return $query->where('users.added_by', Auth::user()->id);
                            });
                    });
                }


                $query = $query->where('is_deleted', 0)->where('owner_user', '!=', '');
                // Sorting
                $query = $this->sorting($column, $sort, $query, $authUser);
                // Server side search
                if ($search != "") {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%")->orWhere('company_email', 'LIKE', "%$search%")->orWhere('c_phone_code', 'LIKE', "%$search%")->orWhere('company_phone', 'LIKE', "%$search%")
                            ->orWhereHas('user', function ($query) use ($search) {
                                $query->where('firstname', 'LIKE', "%$search%")->orWhere('company_email', 'LIKE', "%$search%")->orWhere('email', 'LIKE', "%$search%")->orWhere('phone_code', 'LIKE', "%$search%")->orWhere('mobile', 'LIKE', "%$search%");
                            });

                    });

                }
                $totalRecords = $query->count();

                // Total Display records
                $totalDisplayRecords = $query->count();


                $companys = $query->skip($start)->take($length)->get();

                //Company Consumption data
                $companyConsumption = CompanyConsumption::join('categories', 'company_consumptions.product_cat_id', '=', 'categories.id')
                    ->selectRaw('GROUP_CONCAT(categories.name) as category,company_consumptions.id,company_consumptions.user_id')
                    ->groupBy('company_consumptions.user_id')
                    ->get();
                // company mapping
                $companyData = $companys->map(function ($company) use ($authUser, $request, $companyConsumption) {

                    $active = !empty($company->user) ? $company->user->is_active : '';
                    $companyUserid = !empty($company->user) ? $company->user->id : '';
                    if ($active == 0) {
                        $status = '<a href="javascript:void(0)" data-id="' . $companyUserid . '" data-status="' . $active . '" class="color-gray changeStatus" id="buyer' . $companyUserid . '" data-toggle="tooltip" ata-placement="top" title="' . __('admin.inactive') . '"><i class="fa fa-user-circle"
                                                                                                                                                                  aria-hidden="true"></i></a>';
                    } else {
                        $status = '<a href="javascript:void(0)" data-id="' . $companyUserid . '" data-status="' . $active . '" class="changeStatus" id="buyer' . $companyUserid . '" data-toggle="tooltip" ata-placement="top" title="' . __('admin.active') . '"><i class="fa fa-user-circle"

                                                                                                                                                               aria-hidden="true"></i></a>';
                    }
                    // company consumption value
                    $category = "";
                    foreach ($companyConsumption as $key => $val) {
                        if ($val->user_id == $companyUserid) {

                            $category = $val->category;
                        }
                    }


                    $viewBtn = '<a class="ps-2 cursor-pointer buyerModalView" data-id="' . $companyUserid . '" data-bs-toggle="modal" data-bs-target="#buyerModal" data-toggle="tooltip" ata-placement="top" title="' . __('admin.view') . '"><i class="fa fa-eye"></i></a>';
                    $editBtn = '<a  href="' . route('admin.buyer.company.list.company.edit', ['id' => Crypt::encrypt($companyUserid)]) . '"class="show-icon ps-2" data-id="' . $company->id . '"  data-toggle="tooltip" ata-placement="top" title="' . __('admin.edit') . '"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                    $deleteBtn = '<a class="deleteBuyer show-icon ps-2"  href="javascript:void(0)" id="deleteBuyer_' . $companyUserid . '_' . $company->id . '"   data-toggle="tooltip" ata-placement="top" title="' . __('admin.delete') . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';


                    $action = "";
                    if (Auth::user()->hasAnyPermission(['edit buyer list', 'publish buyer list', 'delete buyer list'])) {
                        if (Auth::user()->hasPermissionTo('publish buyer list')) {
                            $action .= $viewBtn;
                        }
                        if (Auth::user()->hasPermissionTo('edit buyer list')) {
                            $action .= $editBtn;
                        }
                        if (Auth::user()->hasPermissionTo('delete buyer list')) {
                            $action .= $deleteBtn;
                        }
                    }

                    return [
                        'user_name' => (!empty($company->user->firstname) && !empty($company->user->lastname)) ? ($company->user->firstname.' '.$company->user->lastname) : '-',
                        'company_name' => $company->name,
                        'company_email' => !empty($company->company_email) ? $company->company_email : '-',
                        'company_phone' => !empty($company->company_phone) ? $company->c_phone_code . ' ' . $company->company_phone : '-',
                        'contact_person_email' => !empty($company->user) ? $company->user->email : '-',
                        'category' => (!empty($category)) ? $category : '-',
                        'status' => $status,
                        'register_on' => Carbon::parse($company->created_at)->format('d-m-Y'),
                        'updated_by' => (!empty($company->user) && !empty($company->user->userUpdateData())) ? $company->user->userUpdateData()->firstname.' '.$company->user->userUpdateData()->lastname : '-',

                        'actions' => $action
                    ];
                });

                return response()->json([

                    "draw" => intval($draw),
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalDisplayRecords,
                    "aaData" => $companyData
                ]);

            }
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B101 - Admin Buyer Company List');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

        }
    }

    /**
     * Get sorting query for List AJAX of resource
     *
     * @param $column
     * @param $sort
     * @param $query
     * @param $authUser
     * @return mixed
     */
    public function sorting($column, $sort, $query, $authUser)
    {
        if (!empty($column)) {

            // Buyer name
            if ($column == 'buyer_name') {
                $query->orderBy('users.firstname', $sort);
            }
            if ($column == 'user_name') {
                $query->orderBy('id', $sort);
            }

            // Company Name
            if ($column == 'company_name') {
                $query = $query->orderBy('name', $sort);
            }

            // Company email
            if ($column == 'company_email') {
                $query->orderBy('company_email', $sort);
            }

            // Company phone
            if ($column == 'company_phone') {
                $query->orderBy('company_phone', $sort);
            }
            //Buyer designation
            if ($column == 'buyer_designation') {
                $query = $query->orderBy(Designation::select('name')
                    ->whereColumn('designations.id', 'users.designation')->limit(1), $sort);
            }
            //Buyer Roles
            if ($column == "buyer_role") {
                $query = $query->orderBy(CustomRoles::with('users')->select('name')
                    ->whereColumn('custom_roles.company_id', 'users.default_company')->where('custom_roles.system_role_id', SystemRole::FRONTOFFICE)->limit(1), $sort);
            }

        } else {
            $query->orderBy('id', $sort);
        }

        return $query;
    }

    /**
     * Company view popup
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buyerDetailsView(Request $request)
    {
        try {
            $user = User::with('CompanyOwner')->where('id', $request->id)->first();
            $companyId = $user->CompanyOwner->id;

           $buyers =  User::with(['company','companyUserDetails' => function($q) use($companyId) {
                        $q->where('company_id', $companyId);
                    },
                    'companyUserDetails.departmentDetails','companyUserDetails.designationDetails'])
                ->where('is_delete', 0)
                ->where('role_id', 2)
                ->where('id', $request->id)
                ->orderBy('id', 'desc')->first();

            $buyerView = view('admin/buyer/company/companyViewModal', ['buyer' => $buyers])->render();

            /**begin: system log**/
            $user->bootSystemView(new User(), 'Buyer', SystemActivity::RECORDVIEW, $user->id);
            /**end: system log**/

            return response()->json(array('success' => true, 'buyerView' => $buyerView));
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B102 - Admin Buyer Company viewModal');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Company edit view
     * @param string $ids
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */

    function edit($ids = '')
    {
        try {
            if (!empty($ids)) {
                $id = Crypt::decrypt($ids);
            } else {
                $id = User::where('id', Auth::id())->first()->id;
            }
            $user = User::with('CompanyOwner')->where('id', $id)->first();

            $companyId = !empty($user->CompanyOwner) ? $user->CompanyOwner->id : '';


            $buyer =  User::with('company')->with(['companyUserDetails' => function($query) use($companyId){
                $query->where('company_id', $companyId);
            }])->where('users.is_delete', 0)
                ->where('users.role_id', 2)
                ->where('users.id', $id)
                ->orderBy('users.id', 'desc')->first();
            $ownerCompanyId = !empty($buyer) ? $buyer->company->id : null;

            $designation = Designation::where('is_deleted', 0)->get();
            $department = Department::where('is_deleted', 0)->get();
            $company_consumptions = CompanyConsumption::where('user_id', $id)->get();
            $category = Category::where('is_deleted', 0)->get();
            $Unit = Unit::where('is_deleted', 0)->get();

            /** Flat fee code start */
            $allOtherCharges = OtherCharge::with(['company' => function ($q) use ($ownerCompanyId) {
                $q->where(['is_delete' => 0, 'company_id' => $ownerCompanyId])->select(['charge_id', 'company_id']);
            }])
                ->with(['xenditCommisionFee' => function ($q) use ($ownerCompanyId) {
                    $q->where(['company_id' => $ownerCompanyId]);
                }])
                ->where(['charges_type' => 2, 'is_deleted' => 0, 'status' => 1])->get();
            $getCountCharegs = collect($allOtherCharges->toArray())->where('company', '!=', null);
            /** Flat fee code end  */

            /** @var  $customRoles - get roles name based on owner company id */
            $customRoles = CustomRoles::getRoles(null,null,$ownerCompanyId);

            /**begin: system log**/
            User::bootSystemView(new User(), 'Buyer', SystemActivity::EDITVIEW, isset($id) ? $id : '');
            /**end: system log**/

            if ($buyer) {

                return view('admin/buyer/company/companyEdit', ['buyer' => $buyer, 'designations' => $designation, 'departments' => $department, 'company_consumptions' => $company_consumptions, 'category' => $category, 'units' => $Unit, 'platform_charges' => $allOtherCharges, 'count_other_charges' => count($getCountCharegs), 'customRoles' => $customRoles]);
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B103 - Admin Buyer Company List Edit');
            abort('404');
        }
        Log::critical('Code - 403 | ErrorCode:B104 - Admin Buyer Company List Edit');
        abort('404');

    }

    /**
     * Company change status
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function changeStatus(Request $request)
    {
        try {
            $buyer_rfq = UserRfq::where('user_id', $request->id)->get();
            if ($buyer_rfq->count() > 0 && $request->status == 0) {
                return response()->json(array('success' => false));
            } else {
                $user = User::find($request->id);
                $user->is_active = $request->status;
                $user->save();
                addedByUpdatedByFun($request->id, '', Auth::user()->id);
                return response()->json(array('success' => true));
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B105 - Admin Buyer Company change status');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Company details update
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function update(CompanyDetailsRequest $request)
    {

        try {
            ///For company Profile Update
            if ($request->company_id) {

                $company = Company::where('id', $request->company_id)->first();
                if ($company) {
                    if ($request->file('logo')) {
                        $logoFileName = Str::random(10) . '_' . time() . 'logo_' . $request->file('logo')->getClientOriginalName();
                        $logoFilePath = $request->file('logo')->storeAs('uploads/company', $logoFileName, 'public');
                        if (!empty($compnay->logo)) {
                            Storage::delete('/public/' . $compnay->logo);
                        }
                        $company->logo = $logoFilePath;
                    }
                    if ($request->file('nib_file')) {
                        $nibFileName = Str::random(10) . '_' . time() . 'nib_file_' . $request->file('nib_file')->getClientOriginalName();
                        $nibFilePath = $request->file('nib_file')->storeAs('uploads/company', $nibFileName, 'public');
                        if (!empty($compnay->nib_file)) {
                            Storage::delete('/public/' . $compnay->nib_file);
                        }
                        $company->nib_file = $nibFilePath;
                    }
                    if ($request->file('npwp_file')) {
                        $npwpFileName = Str::random(10) . '_' . time() . 'npwp_file_' . $request->file('npwp_file')->getClientOriginalName();
                        $npwpFilePath = $request->file('npwp_file')->storeAs('uploads/company', $npwpFileName, 'public');
                        if (!empty($compnay->npwp_file)) {
                            Storage::delete('/public/' . $compnay->npwp_file);
                        }
                        $company->npwp_file = $npwpFilePath;
                    }
                    if ($request->file('termsconditions_file')) {
                        $termsconditionsFileName = Str::random(10) . '_' . time() . '$termsconditions_file_' . $request->file('termsconditions_file')->getClientOriginalName();
                        $termsconditionsFilePath = $request->file('termsconditions_file')->storeAs('uploads/company', $termsconditionsFileName, 'public');
                        if (!empty($compnay->termsconditions_file)) {
                            Storage::delete('/public/' . $compnay->termsconditions_file);
                        }
                        $company->termsconditions_file = $termsconditionsFilePath;
                    }
                    DB::table('company_consumptions')
                        ->where('user_id', $request->user_id)
                        ->delete();

                    foreach ($request->ProductCategory as $key => $ProductCategory) {
                        if ($request->ProductAnnualConsumption[$key]) {
                            $companyConsumption = new CompanyConsumption;
                            $companyConsumption->product_cat_id = $ProductCategory;
                            $companyConsumption->unit_id = $request->ProductUnit[$key];
                            $companyConsumption->annual_consumption = $request->ProductAnnualConsumption[$key];
                            $companyConsumption->user_id = $request->user_id;
                            $companyConsumption->save();
                        }
                    }
                    $company->name = $request->name;
                    $company->registrantion_NIB = $request->registration_nib;
                    $company->npwp = $request->npwp;
                    $company->web_site = $request->website;
                    $company->company_email = $request->company_email;
                    $company->c_phone_code = (!empty($request->company_phone) && $request->c_phone_code) ? '+' . $request->c_phone_code : '';
                    $company->company_phone = $request->company_phone;
                    $company->alternative_email = $request->alternative_email;
                    $company->a_phone_code = (!empty($request->alternative_phone) && $request->a_phone_code) ? '+' . $request->a_phone_code : '';
                    $company->alternative_phone = $request->alternative_phone;
                    $company->address = $request->addressbuyer;
                    //$company->background_logo = $request->background_logo;
                    $company->save();
                    /**begin: system log**/
                    $company->bootSystemActivities("Buyer Company");
                    /**end: system log**/
                    addedByUpdatedByFun($request->user_id, '', Auth::user()->id);
                    //Add in Xendit Commison Fee Code
                    $this->addXenditFee($request->platform_charges, $request->company_id, $request->type, $request->chargeValue);
                    $response['status'] = "success";
                } else {
                    $response['status'] = "success";
                }
                return response()->json($response);
            }
            // For Buyer Profile Update
            if ($request->id) {

                $user = User::with('CompanyOwner')->where('id', $request->id)->first();
                $companyOwnerId = $user->CompanyOwner->id;
                if ($request->cropImageSrc && $request->user_pic) {
                    $image = $request->cropImageSrc;
                    $destinationPath = config('settings.profile_images_folder') . '/';
                    if (empty(strpos($image, $destinationPath))) {
                        if (!empty($user->profile_pic) && file_exists(storage_path($user->profile_pic))) {
                            unlink(storage_path($user->profile_pic));
                        }
                        list($type, $image) = explode(';', $image);
                        list(, $image) = explode(',', $image);
                        $image = base64_decode($image);
                        $image_name = Str::random(10) . '_' . time() . 'pic_' . '.png';
                        $filename = $image_name;
                        Storage::disk('public')->put($destinationPath . $filename, $image, 'public');
                        $user->profile_pic = $destinationPath . $filename;
                    }
                }
                $user->salutation = $request->salutation;
                $user->firstname = $request->firstName;
                $user->lastname = $request->lastName;
                $user->phone_code = $request->phone_code ? '+' . $request->phone_code : '';
                $user->mobile = $request->mobile;
                $user->designation = $request->designation;
                $user->department = $request->department;
                $user->save();
                /** User's Designation and department entry store  **/
                (new CompanyUserController)->companyUserCreate('', $user, $request,$companyOwnerId);

                /**begin: system log**/
                $user->bootSystemActivities();
                /**end: system log**/
                addedByUpdatedByFun($request->id, '', Auth::user()->id);
                $response['status'] = 'success';
                return response()->json($response);
            }
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B106 - Admin Buyer Company update details');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }


    }

    /**
     * Company delete
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function delete(Request $request)
    {
        try {
            $buyer_rfq = UserRfq::where('user_id', $request->id)->get();
            if ($buyer_rfq->count() > 0) {
                return response()->json(array('success' => false));
            } else {
                $user = User::find($request->id);
                $user->is_delete = 1;
                $user->save();
                $user->delete();
                if (!empty($request->companyId)) {
                    $usercompany = UserCompanies::where('user_id', $request->id)->where('company_id', $request->companyId)->first();
                    $usercompany->is_deleted = 1;
                    $usercompany->save();
                }

                /**begin: system log**/
                User::bootSystemActivities('Buyer');
                /**end:  system log**/
                return response()->json(array('success' => true));
            }
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B107 - Admin Buyer Company delete');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }
    /**
     *company file delete
     */

    function buyerCompanyFileDelete(Request $request)
    {
        try {
            $compnay = Company::find($request->id);
            $columnName = $request->fileName;
            if (isset($compnay->$columnName) && !empty($compnay->$columnName)) {
                Storage::delete('/public/' . $compnay->$columnName);
                $compnay->$columnName = '';
                $compnay->save();
            }
            return response()->json(array('success' => true, '$compnay' => $compnay->$columnName));
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B108 - Admin Buyer Company file delete');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Company admin images download
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */

    function downloadBuyerImageAdmin(Request $request)
    {
        try {
        $image = User::leftjoin('companies', 'companies.owner_user', '=', 'users.id')
                ->where('users.id', $request->id)
                ->pluck($request->fieldName)
                ->first();

            if (!empty($image)) {
                ob_end_clean();
                $headers = array('Content-Type: image/*, application/pdf');
                return Storage::download('/public/' . $image, '', $headers);
            }
            return response()->json(array('success' => false));
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B109 - Admin Buyer Company file download image');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Display User Details datatable
     */
    function userListJson(Request $request)
    {
        try {
            $buyerId = $request->buyerId;

            $compnayOwner = Company::where('owner_user', $buyerId)->first();
            $companyId = $compnayOwner->id;
            if ($request->ajax() && $request->get('draw')) {
                $draw = $request->get('draw');
                $start = $request->get("start");
                $length = $request->get("length");
                $sort = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
                $search = !empty($request->get('search')) ? $request->get('search')['value'] : '';

                $columnIndex_arr = $request->get('order');
                $columnName_arr = $request->get('columns');
                $columnIndex = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
                $column = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';

                $query = User::with(['defaultCompany','companyUserDetails' => function($query) use($companyId){
                    $query->where('company_id', $companyId);
                },'companyUserDetails.designationDetails', 'company', 'company.customRoles.customPermission.modelHasCustomPermissionOne'])->where('users.id', '!=', $buyerId)
                    ->whereJsonContains('users.assigned_companies', $companyId);



                //Sorting
                $query = $this->sorting($column, $sort, $query, Auth::user());

                //Server side search
                if ($search != "") {
                    /*if (Carbon::createFromFormat('d-m-Y', $search) !== false) {
                        $newDate = \Illuminate\Support\Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    ->orwhereDate('users.created_at', '=', $search)
                    }*/

                    $query = $query->where(function ($query) use ($search) {
                        $query->where('users.firstname', 'LIKE', "%$search%")->orWhere('users.lastname', 'LIKE', "%$search%")->orWhere('users.email', 'LIKE', "%$search%")->orWhere('users.mobile', 'LIKE', "%$search%")->orwhereDate('users.created_at', '=', $search)
                            ->orWhereHas('user_designation', function ($query) use ($search) {
                                $query->where('name', 'LIKE', "%$search%");
                            });
                    });
                }

                // Total records
                $totalRecords = $query->count();
                // Total Display records
                $totalDisplayRecords = $query->count();

                $userData = $query->skip($start)->take($length);

                $userData = $query->get();
                // company mapping
                $userDetailsData = $userData->map(function ($user) use ($request, $companyId, $buyerId) {

                    $editBtn = '<a class="companyRolePopup show-icon ps-2" data-owner="' . $buyerId . '"  data-id="' . $user->id . '"  data-toggle="tooltip" ata-placement="top" title="' . __('admin.edit') . '"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                    $deleteBtn = '<a class="deleteUser show-icon ps-2" data-owner="' . $buyerId . '" href="javascript:void(0)" data-id="' . Crypt::encrypt($user->id) . '"   data-toggle="tooltip" ata-placement="top" title="' . __('admin.delete') . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';


                    return [
                        'buyer_name' => $user->firstname.' '.$user->lastname,
                        'buyer_email' => $user->email,
                        'buyer_phone' => $user->phone_code . " " . $user->mobile,
                        'buyer_designation' => (!empty($user->companyUserDetails) && !empty($user->companyUserDetails[0]->designationDetails)) ? $user->companyUserDetails[0]->designationDetails->name : '-',
                        'buyer_role' => getRolePermissionAttribute($user->id, $companyId)['role'] != null ? getRolePermissionAttribute($user->id, $companyId)['role'] : '',
                        'buyer_joining' => Carbon::parse($user->created_at)->format('d-m-Y'),
                        'actions' => $editBtn . $deleteBtn
                    ];
                });

                return response()->json([

                    "draw" => intval($draw),
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalDisplayRecords,
                    "aaData" => $userDetailsData
                ]);

            }
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B110 - Admin Buyer Company User listing');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }


    }

    /**
     * Display User Role Popup
     */
    function viewCompanyRolePopup(Request $request)
    {
        try {
            $buyer = User::find($request->id);
            $compnayOwner = Company::where('owner_user', $request->ownerUserId)->first();
            $companyId = $compnayOwner->id;

            $userDetails = User::with(['company','companyUserDetails' => function($q) use($companyId) {
                $q->where('company_id', $companyId);
            },
                'companyUserDetails.departmentDetails','companyUserDetails.designationDetails','companies'])
                ->where('users.id', '=', $buyer->id)->whereJsonContains('users.assigned_companies', $buyer->default_company)->first();

            $role = getRolePermissionAttribute($buyer->id, $companyId)['role_id'] != null ? getRolePermissionAttribute($buyer->id, $companyId)['role_id'] : '';
            $userDetails = [
                'companyName' => $userDetails->companies->name,
                'firstname' => $userDetails->firstname,
                'lastname' => $userDetails->lastname,
                'email' => $userDetails->email,
                'mobile' => $userDetails->mobile,
                'designation' => (!empty($userDetails->companyUserDetails) && !empty($userDetails->companyUserDetails[0]->designationDetails)) ? $userDetails->companyUserDetails[0]->designationDetails->id : '',
                'department' => (!empty($userDetails->companyUserDetails) && !empty($userDetails->companyUserDetails[0]->departmentDetails)) ?  $userDetails->companyUserDetails[0]->departmentDetails->id : '',
                'role' => $role,
                'companyUserId' => $buyer->id,
                'defaultCompanyId' => $companyId ,// Buyer admin company id
                'branches' => (!empty($userDetails->companyUserDetails) && !empty($userDetails->companyUserDetails[0])) ? $userDetails->companyUserDetails[0]->branches : '' // branches for RFN
            ];
            return response()->json(array('success' => true, 'message' => 'Data fetched successfully', 'userDetails' => $userDetails));

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B111 - Admin Buyer Company User Edit popup');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Update User roles details
     */
    function updateUserRolesDetails(UserDetailsRequest $request)
    {
        try {
            if ($request->ajax()) {
                $userId = $request->companyUserId;
                if (!empty($userId)) {
                    $userData = User::where('id', $userId)->update([
                        'firstname' => $request->firstName,
                        'lastname' => $request->lastName,
                        'mobile' => $request->mobile,
                        'designation' => $request->designation,
                        'department' => $request->department,
                    ]);
                    /********begin: assign custom role and permission to user********/
                    $user = User::find($userId);
                    $user->updated_by = Auth::id();
                    $user->save();
                    //Get role and permissions
                    $customPermissionId = CustomPermission::where('model_type', CustomRoles::class)->where('value', $request->role)->get()->pluck('id')->first();
                    $rolePermissions = CustomRoles::where('id', $request->role)->get()->pluck('permissions')->first();
                    //Get old permissions and remove them

                    if (empty(getRolePermissionAttribute($user->id, $request->defaultCompanyId)['role'])) {

                        ModelHasCustomPermission::create(['custom_permission_id' => $customPermissionId, 'model_type' => User::class, 'model_id' => $user->id, 'custom_permissions' => $rolePermissions]);

                    } else {
                        $oldCustomRolePermission = getRolePermissionAttribute($user->id);

                        $childUser = User::findOrFail($user->id);
                        !empty($oldCustomRolePermission['permissions']) ? $childUser->revokePermissionTo(CustomPermission::findByIds($oldCustomRolePermission['permissions'])) : '';
                        ModelHasCustomPermission::where('model_type', User::class)->where('model_id', $user->id)->where('custom_permission_id', getRolePermissionAttribute($user->id, $request->defaultCompanyId)['custom_permission']->first()->id)
                            ->update([
                                'custom_permission_id' => json_encode($customPermissionId),
                                'custom_permissions' => $rolePermissions
                            ]);
                    }
                    //Assign new permissions by role
                    $childUser = User::findOrFail($user->id);

                    $childUser->givePermissionTo(CustomPermission::findByIds(json_decode($rolePermissions)));
                    CustomRoles::setUserPersonalInfoPermissions($user->id);
                    /********end: assign custom role and permission to user********/

                    /** User's Designation and department entry store  **/
                    (new CompanyUserController)->companyUserCreate($user, null, $request,$request->defaultCompanyId);

                    /**begin: system log**/
                    User::bootSystemActivities('Buyer');
                    /**end:  system log**/
                    return response()->json(['response' => 'success', 'message' => __('admin.user_updated'), 'success' => 'true']);
                }
            }
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B112 - Admin Buyer Company User update details');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Company user delete
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function companyUsersDelete(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            if ($request->ajax()) {

                $user = User::find($request->ownerUserId);
                $deletedUser = User::find($id);

                $company = UserCompanies::select('company_id')->where('user_id', $user->id)->first();

                /**begin: Update default company and remove assigned company**/
                if ($user->default_company == $deletedUser->default_company) {
                    $childUserDefaultCompany = Company::where('owner_user', $deletedUser->id)->first();
                    if (!empty($childUserDefaultCompany)) {
                        $oldCustomRolePermission = getRolePermissionAttribute($deletedUser->id);
                        if (!empty($oldCustomRolePermission['custom_permission'])) {
                            ModelHasCustomPermission::where('custom_permission_id', $oldCustomRolePermission['custom_permission']->first()->id)
                                ->where('model_type', User::class)
                                ->where('model_id', $deletedUser->id)
                                ->delete();
                        }

                        $deletedUser->default_company = $childUserDefaultCompany->id;
                        $deletedUser->save();

                        $deletedUser->revokePermissionTo($deletedUser->getAllPermissions()->pluck('name')->toArray()); //Old permission revoke

                        $buyerPermissions = SpatieRole::findByName('buyer')->permissions->pluck('name'); //Get new permission
                        $deletedUser->givePermissionTo($buyerPermissions); //New permission assign

                    }

                }
                if ($deletedUser->assigned_companies) {
                    // added user and delete
                    $oldCustomRolePermission = getRolePermissionAttribute($deletedUser->id);
                    if (!empty($oldCustomRolePermission['custom_permission'])) {
                        ModelHasCustomPermission::where('custom_permission_id', $oldCustomRolePermission['custom_permission']->first()->id)
                            ->where('model_type', User::class)
                            ->where('model_id', $deletedUser->id)
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
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B113 - Admin Buyer Company User Delete');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }


    }

    /**
     * Company export excel
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function CompanyExportExxcel()
    {
        ob_end_clean();
        ob_start();

        return Excel::download(new BuyerCompaniesExport, 'Companies.xlsx');

        ob_flush();

    }

    /**
     * @ekta
     * Flat fees Update function
     */
    function flatFeeUpdate(Request $request)
    {
        $data = OtherCharge::where('id', $request->charge_id)->first();
        if ($request->check == false) {
            XenditCommisionFee::updateOrCreate(['charge_id' => $request->charge_id, 'company_id' => $request->company_id], ['type' => $data['type'], 'charges_value' => $data['charge_values']]);
        }

        return response()->json(array('success' => true, 'data' => $data));
    }

    /**
     * Add XenditFee
     * @param $ids
     * @param $company_id
     * @param $types
     * @param $charge_values
     */
    function addXenditFee($ids, $company_id, $types, $charge_values)
    {
        $otherCharges = OtherCharge::where(['charges_type' => 2, 'editable' => 0])->pluck('id')->toArray();
        if (!empty($ids)) {
            foreach ($ids as $id) {
                if ($id == 10) {
                    continue;
                }
                $feeAddedOrNotCheck = XenditCommisionFee::where(['charge_id' => $id, 'company_id' => $company_id])->orderBy('id', 'DESC')->first();

                if (empty($feeAddedOrNotCheck)) {
                    $data = ['charge_id' => $id, 'company_id' => $company_id, 'is_delete' => 0];
                } else if (!empty($feeAddedOrNotCheck) && $feeAddedOrNotCheck->is_delete == 1) {
                    $data = ['id' => $feeAddedOrNotCheck->id, 'charge_id' => $id, 'company_id' => $company_id, 'is_delete' => 0];
                } else {
                    $data = ['id' => $feeAddedOrNotCheck->id, 'charge_id' => $id, 'company_id' => $company_id, 'is_delete' => $feeAddedOrNotCheck->is_delete];
                }
                if (isset($types[$id]) && isset($charge_values[$id])) {
                    $data = array_merge($data, ['type' => $types[$id], 'charges_value' => $charge_values[$id]]);
                }
                XenditCommisionFee::updateOrCreate(['charge_id' => $id, 'company_id' => $company_id], $data);
            }

            $getAllXenditFees = XenditCommisionFee::where(['company_id' => $company_id])->where('charge_id', '<>', 10)->whereNotIn('charge_id', $otherCharges)->whereNotIn('charge_id', $ids)->update(['is_delete' => 1]);
        } else {
            $getAllXenditFees = XenditCommisionFee::where(['company_id' => $company_id])->where('charge_id', '<>', 10)->whereNotIn('charge_id', $otherCharges)->update(['is_delete' => 1]);
        }

    }


}
