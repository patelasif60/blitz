<?php

namespace App\Http\Controllers\Admin\Buyer;

use App\Http\Controllers\Controller;
use App\Export\BuyerExport;
use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\ModelHasCustomPermission;
use App\Models\OtherCharge;
use App\Models\SystemRole;
use App\Models\XenditCommisionFee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserRfq;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\UserCompanies;
use App\Models\Unit;
use App\Models\Department;
use App\Models\Designation;
use App\Models\CompanyConsumption;
use App\Models\Category;
use App\Models\SystemActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Admin\Buyer\UserDetailsRequest;

class AdminBuyerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create buyer list|edit buyer list|delete buyer list|publish buyer list|unpublish buyer list', ['only'=> ['list', 'buyerDetailsView']]);
        $this->middleware('permission:create buyer list', ['only' => ['create']]);
        $this->middleware('permission:edit buyer list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete buyer list', ['only' => ['delete']]);
    }

    function list() {

        /**begin: system log**/
        User::bootSystemView(new User(), 'Buyer');
        /**end:  system log**/
        //return view('admin/buyer/buyerList', ['buyers' => $buyers,'companyConsumption' => $companyConsumption]);
        return View::make('admin.buyer.company.list');
    }


    function changeStatus(Request $request) {
        $buyer_rfq = UserRfq::where('user_id', $request->id)->get();
        if ($buyer_rfq->count() > 0 && $request->status == 0) {
            return response()->json(array('success' => false));
        } else {
            $user = User::find($request->id);
            $user->is_active = $request->status;
            $user->save();
            addedByUpdatedByFun($request->id,'',Auth::user()->id);
            return response()->json(array('success' => true));
        }
    }
    function delete(Request $request) {
        $buyer_rfq = UserRfq::where('user_id', $request->id)->get();
        if ($buyer_rfq->count() > 0 ) {
            return response()->json(array('success' => false));
        } else {
            $user = User::find($request->id);
            $user->is_delete = 1;
            $user->save();
            $user->delete();
            if(!empty($request->companyId)) {
                $usercompany = UserCompanies::where('user_id',$request->id)->where('company_id',$request->companyId)->first();
                $usercompany->is_deleted=1;
                $usercompany->save();
            }

            /**begin: system log**/
            User::bootSystemActivities('Buyer');
            /**end:  system log**/
            return response()->json(array('success' => true));
        }
    }
    function downloadBuyerImageAdmin(Request $request) {
        $image = User::leftjoin('user_companies', 'user_companies.user_id', '=', 'users.id')
            ->leftjoin('companies', 'companies.id', '=', 'user_companies.company_id')
            ->where('users.id',$request->id)
            ->pluck($request->fieldName)
            ->first();

        if (!empty($image)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $image, '', $headers);
        }
        return response()->json(array('success' => false));
    }
    function edit($ids = '') {
        if (!empty($ids)){
            $id = Crypt::decrypt($ids);
            $buyer_id = User::find($id);
        } else {
            $id = User::where('id', Auth::id())->first()->id;
        }

        $buyer = User::leftjoin('user_companies', 'user_companies.user_id', '=', 'users.id')
            ->leftjoin('companies', 'companies.id', '=', 'user_companies.company_id')
            ->leftjoin('designations','users.designation','=','designations.id')
            ->leftjoin('departments','users.department','=','departments.id')
            ->where('users.is_delete',0)
            ->where('users.role_id',2)
            ->where('users.id',$id)
            ->orderBy('users.id', 'desc')
            ->selectRaw('users.id,users.phone_code, users.mobile,companies.c_phone_code, companies.company_phone,users.profile_pic,users.salutation,companies.id as company_id,
            companies.a_phone_code, companies.alternative_phone,users.id,users.firstname,users.lastname,users.email,designations.id as designation,companies.address,
            departments.id as department,companies.name as company_name,companies.company_email as company_email,companies.alternative_email as compy_alt_email,companies.company_email as company_email,
            companies.alternative_email as compy_alt_email,companies.company_email as company_email,companies.registrantion_NIB ,companies.nib_file,companies.npwp,
            companies.npwp_file,companies.termsconditions_file,companies.web_site,companies.address as cmp_address,companies.logo as company_logo,companies.background_logo as background_logo,companies.background_colorpicker as background_colorpicker')
            ->first();
        $designation = Designation::all()->where('is_deleted', 0);
        $department = Department::all()->where('is_deleted', 0);
        $company_consumptions = CompanyConsumption::all()->where('user_id', $id);
        $category = Category::all()->where('is_deleted', 0);
        $Unit = Unit::all()->where('is_deleted', 0);
        $company_id = $buyer->company_id;
        $allOtherCharges = OtherCharge::with(['company' => function ($q) use($company_id) {$q->where(['is_delete' => 0, 'company_id' => $company_id])->select(['charge_id', 'company_id']);}])->where(['charges_type' => 2, 'is_deleted' => 0, 'status' => 1])->get();
        $getCountCharegs = collect($allOtherCharges->toArray())->where('company','!=',null);
        $customRoles = CustomRoles::getRoles(User::find($id)); // get roles name
        if($buyer) {
            /**begin: system log**/
            $buyer_id->bootSystemView(new User(), 'Buyer', SystemActivity::EDITVIEW, $buyer_id->id);
            /**end: system log**/
            return view('admin/buyer/company/companyEdit', ['buyer'=>$buyer, 'designations' => $designation, 'departments' => $department,'company_consumptions' => $company_consumptions, 'category' => $category, 'units' => $Unit, 'platform_charges' => $allOtherCharges, 'count_other_charges' => count($getCountCharegs),'customRoles'=>$customRoles]);
        }
    }
    function buyerCompanyFileDelete(Request $request)
    {
        $compnay = Company::find($request->id);
        $columnName = $request->fileName;
        if (isset($compnay->$columnName) && !empty($compnay->$columnName)) {
            Storage::delete('/public/' . $compnay->$columnName);
            $compnay->$columnName = '';
            $compnay->save();
        }
        return response()->json(array('success' => true,'$compnay'=>$compnay->$columnName));
    }
    function update(Request $request)
    {
        ///For company Profile Update
        if($request->company_id) {

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
                $this->addXenditFee($request->platform_charges, $request->company_id);
                $response['status'] = "success";
            } else {
                $response['status'] = "success";
            }
            return response()->json($response);
        }
        // For Buyer Profile Update
        if($request->id) {

            $user = User::where('id', $request->id)->first();
            if ($request->cropImageSrc && $request->user_pic) {
                $image = $request->cropImageSrc;
                $destinationPath = config('settings.profile_images_folder') . '/';
                if (empty(strpos($image,$destinationPath))) {
                    if(!empty($user->profile_pic) && file_exists(storage_path($user->profile_pic))) {
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
            $user->phone_code = $request->phone_code?'+'.$request->phone_code:'';
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->department = $request->department;
            $user->save();

            /**begin: system log**/
            $user->bootSystemActivities();
            /**end: system log**/
            addedByUpdatedByFun($request->id,'',Auth::user()->id);
            $response['status'] = 'success';
            return response()->json($response);
        }

    }
    // buyer excel export -Vrutika
    function buyerExportExxcel()
    {
        ob_end_clean();
        ob_start();

        return Excel::download(new BuyerExport, 'buyers.xlsx');

        ob_flush();

    }

    function addXenditFee($ids, $company_id){
        if (!empty($ids)){
            foreach ($ids as $id){
                if ($id == 10){
                    continue;
                }
                $feeAddedOrNotCheck = XenditCommisionFee::where(['charge_id' => $id, 'company_id' => $company_id] )->first();
                if (empty($feeAddedOrNotCheck)){
                    $data = ['charge_id' => $id, 'company_id' => $company_id, 'is_delete' => 0 ];
                } else if(!empty($feeAddedOrNotCheck) && $feeAddedOrNotCheck->is_delete == 1) {
                    $data = ['id' => $feeAddedOrNotCheck->id,'charge_id' => $id, 'company_id' => $company_id, 'is_delete' => 0 ];
                } else {
                    $data = ['id' => $feeAddedOrNotCheck->id,'charge_id' => $id, 'company_id' => $company_id, 'is_delete' => $feeAddedOrNotCheck->is_delete ];
                }
                XenditCommisionFee::updateOrCreate(['charge_id' => $id, 'company_id' => $company_id], $data);
            }
            $getAllXenditFees = XenditCommisionFee::where(['company_id' => $company_id])->where('charge_id', '<>', 10)->whereNotIn('charge_id', $ids)->update(['is_delete' => 1]);
        } else {
            $getAllXenditFees = XenditCommisionFee::where(['company_id' => $company_id])->where('charge_id', '<>', 10)->update(['is_delete' => 1]);
        }

    }


}
