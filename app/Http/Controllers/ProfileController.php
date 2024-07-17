<?php

namespace App\Http\Controllers;

use App\Events\BuyerNotificationEvent;
use App\Http\Requests\Buyer\RolesPermission\AddBuyerRoleRequest;
use App\Models\SystemRole;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\InviteBuyerActivationJob;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Support\Str;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Unit;
use App\Models\CompanyConsumption;
use App\Models\Currency;
use App\Models\Languages;
use App\Models\PaymentGroup;
use App\Models\PaymentTerms;
use App\Models\UserPaymentTerms;
use App\Models\CustomRoles;
use App;
use App\Jobs\buyer\user\SendEmailCustomUserJob;
use App\Mail\ApprovalInviteUserAdmin;
use App\Models\UserActivity;
use App\Models\UserApprovalConfig;
use App\Models\UserCompanies;
use App\Models\InviteBuyer;
use File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\AvailableBank;
use App\Models\BuyerBanks;
use App\Models\PreferredSupplier;
use App\Models\CustomPermission;
use App\Models\ModelHasCustomPermission;
use App\Models\Role;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\PermissionsGroup;
use App\Models\ProfessionalCategory;
use App\Models\Religion;
use App\Models\UserOtherInformation;
use App\Models\CompanyOtherInformation;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyUserController;
use App\Models\Order;
use App\Jobs\SendActivationMailToUserJob;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:publish buyer personal info|publish buyer company info|publish buyer side invite|publish buyer change password|publish buyer preferences|publish buyer payment term|publish buyer bank details|publish buyer users|publish buyer roles and permissions|publish buyer approval configurations', ['only' => ['index']]);
        $this->destinationPath1 = config('settings.koinworks_other_folder');
    }

    public function index()
    {
        $user = User::with(['companyUserDetails' => function($query){
            $query->where('company_id', Auth::user()->default_company);
        }])->find(Auth::user()->id);

        $designation = Designation::all()->where('is_deleted', 0);
        $department = Department::all()->where('is_deleted', 0);
        $category = Category::all()->where('is_deleted', 0);
        $language = Languages::all()->where('is_deleted', 0);
        $currencies = Currency::all()->where('is_deleted', 0);
        $PaymentGroups = PaymentGroup::all()->where('is_deleted', 0);
        $PaymentTerms = PaymentTerms::all()->where('is_deleted', 0);
        $professionalCategories = ProfessionalCategory::all();
        $religions = Religion::all();
        $langs = '';
        if($user->language_id){
            $langs = Languages::find($user->language_id);
        }
        if (session()->has('locale')) {
            // App::setLocale($langs->name);
        }else{
            App::setLocale(strtolower($langs->name));
        }
        $Unit = Unit::all()->where('is_deleted', 0);
        $company_consumptions = CompanyConsumption::all()->where('user_id', Auth::user()->id);
        $companyDetails = UserCompanies::join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->where('user_id', Auth::user()->id)
            ->where('companies.id', Auth::user()->default_company) // get company details by default company id
            ->first(['companies.name as company_name', 'companies.id as company_id','companies.background_logo as background_logo', 'companies.logo as company_logo', 'companies.registrantion_NIB', 'companies.nib_file', 'companies.npwp', 'companies.npwp_file', 'companies.termsconditions_file', 'companies.web_site', 'companies.company_email', 'companies.c_phone_code', 'companies.company_phone', 'companies.alternative_email', 'companies.a_phone_code', 'companies.alternative_phone', 'companies.address','companies.owner_user','background_colorpicker','establish_in','owner_full_name']);
        if($companyDetails == null){
            $companyDetails = UserCompanies::join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->where('companies.id', Auth::user()->default_company) // get company details by default company id
            ->first(['companies.name as company_name', 'companies.id as company_id','companies.background_logo as background_logo', 'companies.logo as company_logo', 'companies.registrantion_NIB', 'companies.nib_file', 'companies.npwp', 'companies.npwp_file', 'companies.termsconditions_file', 'companies.web_site', 'companies.company_email', 'companies.c_phone_code', 'companies.company_phone', 'companies.alternative_email', 'companies.a_phone_code', 'companies.alternative_phone', 'companies.address','companies.owner_user','background_colorpicker','establish_in','owner_full_name']);       
        }

        // invited user functionality
        $invited_users = User::getAllInvitedUsers($user->id, [$companyDetails->company_id]);
        // invited user functionality

        $userAppConfigUserIds = UserCompanies::leftJoin('user_approval_configs', 'user_companies.user_id', '=', 'user_approval_configs.user_id')->where('user_companies.company_id',$companyDetails->company_id)->where('user_approval_configs.user_id','<>',null)->pluck('user_approval_configs.user_id');
        $invited_active_users = User::join('user_companies','users.id','=','user_companies.user_id')
        ->where('users.id','!=',$user->id)->where('users.is_active',1)
        ->where('users.is_delete',0)->where('user_companies.company_id',$companyDetails->company_id)
        ->whereNotIn('user_companies.user_id',$userAppConfigUserIds)
        ->whereNotIn('user_companies.user_id', getUsersRolePermissionAttribute(Auth::user()->default_company))
        ->orderBy('users.id','DESC')
        ->get(['users.id as id','users.email as email']);

        $approvalConfigUsers = User::getApprovalConfigUsers($user->id, [$companyDetails->company_id]);

        $bankList = AvailableBank::all();
        $authUser = Auth::user();
        /**********begin:Buyer Bank details set permissions based on custom role******/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer bank details')){
            $buyerBanksList = BuyerBanks::where('company_id', $authUser->default_company)->get();
        }else {
            $buyerBanksList = BuyerBanks::where('user_id', $authUser->id)->where('company_id', $authUser->default_company)->get();
        }
        /**********end:Buyer Bank details  set permissions based on custom role******/
        //@ekta  for invite

        $inviteSupplier = InviteBuyer::all();
            /**********begin:Invite buyer/suppliers set permissions based on custom role******/
            $isOwner = User::checkCompanyOwner();
            if($isOwner == true || $authUser->hasPermissionTo('list-all buyer side invite')){
                $inviteSupplier = $inviteSupplier->where('company_id', $authUser->default_company);
            }else {
                $inviteSupplier = $inviteSupplier->where('user_id', $authUser->id)->where('company_id', $authUser->default_company);;
            }
            /**********end:Invite buyer/suppliers  set permissions based on custom role******/
            $inviteSupplier = $inviteSupplier->sortByDesc('id');

        $customRoles = CustomRoles::getRoles();

        //Get all preferred suppliers data (Ronak M - 21/06/2022)
        $preferredSuppliers = $this->getAllPreferredSuppliers(Auth::user()->id);

        /**
         * Company banner layout static number set -Mittal
         */
        $bannerNumber = 11;
        /********** Get company background color set in colorpicker ************/
        $backgroundColorPicker = "";
        if ($companyDetails->background_colorpicker) {
            $backgroundColorPicker = $companyDetails->background_colorpicker ;
        }
        return view('profile/index', ['user' => $user, 'category' => $category, 'companyDetails' => $companyDetails, 'designations' => $designation, 'departments' => $department, 'units' => $Unit, 'company_consumptions' => $company_consumptions, 'currencies' => $currencies, 'language' => $language,'PaymentTerms' => $PaymentTerms,'PaymentGroups' => $PaymentGroups, 'invited_users' => $invited_users, 'approvalConfigUsers' => $approvalConfigUsers, 'invited_active_users' => $invited_active_users, 'bank_list' => $bankList, 'buyer_bank_list' => $buyerBanksList,'inviteSupplier' => $inviteSupplier, 'preferredSuppliers' => $preferredSuppliers, 'customRoles' => $customRoles,'bannerNumber'=> $bannerNumber,'backgroundColorPicker'=>$backgroundColorPicker,'professionalCategories'=>$professionalCategories,'religions'=>$religions]);
    }

    //Preferred Suppliers Data (Ronak M - 21/06/2022)
    public function getAllPreferredSuppliers($authUserId) {
        $preferredSuppliers = [];
        $preferredSuppliers = PreferredSupplier::leftJoin('user_suppliers','preferred_suppliers.supplier_id','=','user_suppliers.supplier_id')
        ->leftJoin('user_companies','user_suppliers.user_id','=','user_companies.user_id')
        ->leftJoin('companies','user_companies.company_id','=','companies.id')
        ->leftJoin('suppliers','preferred_suppliers.supplier_id','=','suppliers.id');

        /**********begin:Preferred Suppliers set permissions based on custom role******/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer preferred supplier')){
            $preferredSuppliers = $preferredSuppliers->where('preferred_suppliers.company_id', Auth::user()->default_company);
        }else {
            $preferredSuppliers = $preferredSuppliers->where('preferred_suppliers.user_id', $authUserId)->where('preferred_suppliers.company_id', Auth::user()->default_company);;
        }
        /**********end:Preferred Suppliers set permissions based on custom role******/

        $preferredSuppliers=$preferredSuppliers->where('preferred_suppliers.deleted_at', null)
        ->orderBy('preferred_suppliers.id','DESC')
        ->get(['suppliers.name as companyName','suppliers.contact_person_email','suppliers.interested_in','preferred_suppliers.is_active','suppliers.id as preferredSuppId']);
        return $preferredSuppliers;

    }

    public function updatePersonalInfo(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if ($request->user_pic1) {
            $image = $request->user_pic1;
            $destinationPath = config('settings.profile_images_folder') . '/';
            if (empty(strpos($image,$destinationPath))) {
                if(!empty($user->profile_pic) && file_exists(storage_path( 'app/public/'.$user->profile_pic))) {
                    unlink(storage_path( 'app/public/'.$user->profile_pic));
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
        $user->designation = $request->designation;
        $user->department = $request->department;
        $user->save();

        $this->updateOtherInformation($user,$request);

        (new CompanyUserController)->companyUserCreate('', Auth::user(), $request);

        if (isset($request->changesUserNotification) && $request->changesUserNotification) {
            buyerNotificationInsert(Auth::user()->id, 'Personal Info', 'buyer_personal_info_change', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
            broadcast(new BuyerNotificationEvent());
        }
        addedByUpdatedByFun(Auth::user()->id,'',Auth::user()->id);

        $response['status'] = 'success';
       return response()->json($response);
    }

    /*user other information */
    public function updateOtherInformation ($user,$request){
        $path = $this->destinationPath1 . Auth::id();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0775, true, true);
        }

        if($request->file('family_card_image')!=NULL)
        {
            $user->userOtherInformation ? Storage::delete($user->userOtherInformation->ktp_image) : '';
            $familyCardImageFilename = $request->file('family_card_image')->getClientOriginalName();
            $familyCardImage = uploadAlldocs($request->file('family_card_image'),$path,'familyCardImage');
        }
        if($request->file('ktp_image')!=NULL)
        {
            $user->userOtherInformation ? Storage::delete($user->userOtherInformation->ktp_image) : '';
            $ktpImageFilename = $request->file('ktp_image')->getClientOriginalName();
            $ktpImagefile = uploadAlldocs($request->file('ktp_image'),$path,'ktpImage');
        }
        if($request->file('ktp_with_selfie_image')!=NULL)
        {
            $user->userOtherInformation ? Storage::delete($user->userOtherInformation->ktp_image):'';
            $ktpImageWithSelfiFileName = $request->file('ktp_with_selfie_image')->getClientOriginalName();
            $ktpImageWithSelfifile = uploadAlldocs($request->file('ktp_with_selfie_image'),$path,'ktpSelfiImage');
        }
        if($user->userOtherInformation == null)
        {
            $userInfo = UserOtherInformation::create([
                'user_id' => Auth::id(),
                'religion' => $request->religion,
                'marital_status' => $request->marital_status,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'family_card_image' => $request->family_card_image?$familyCardImage:null,
                'family_card_image_filename' => $request->family_card_image?$familyCardImageFilename:null,
                'ktp_image' => $request->ktp_image?$ktpImagefile:null,
                'ktp_image_filename' => $request->ktp_image?$ktpImageFilename:null,
                'ktp_with_selfie_image' => $request->ktp_with_selfie_image?$ktpImageWithSelfifile:null,
                'ktp_with_selfie_image_filename' => $request->ktp_with_selfie_image?$ktpImageWithSelfiFileName:null,
                'ktp_nik' => $request->ktp_nik,
                'gender' => $request->gender,
            ]);
        }
        else
        {
            $user->userOtherInformation->religion = $request->religion;
            $user->userOtherInformation->marital_status = $request->marital_status;
            $user->userOtherInformation->date_of_birth = $request->date_of_birth;
            $user->userOtherInformation->place_of_birth = $request->place_of_birth;
            $user->userOtherInformation->family_card_image = $request->family_card_image?$familyCardImage:$user->userOtherInformation->family_card_image;
            $user->userOtherInformation->family_card_image_filename = $request->family_card_image?$familyCardImageFilename:$user->userOtherInformation->family_card_image_filename;
            $user->userOtherInformation->ktp_image = $request->ktp_image?$ktpImagefile:$user->userOtherInformation->ktp_image;
            $user->userOtherInformation->ktp_image_filename = $request->ktp_image?$ktpImageFilename:$user->userOtherInformation->ktp_image_filename;
            $user->userOtherInformation->ktp_with_selfie_image = $request->ktp_with_selfie_image?$ktpImageWithSelfifile:$user->userOtherInformation->ktp_with_selfie_image;
            $user->userOtherInformation->ktp_with_selfie_image_filename = $request->ktp_with_selfie_image?$ktpImageWithSelfiFileName:$user->userOtherInformation->ktp_with_selfie_image_filename;
            $user->userOtherInformation->ktp_nik = $request->ktp_nik;
            $user->userOtherInformation->gender = $request->gender;
            $user->userOtherInformation->save();
        }
    }
    /*upadete compnies other information*/
    public function updateOtherCompanyInformation ($compnay,$request){
        $path = $this->destinationPath1 . Auth::id();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0775, true, true);
        }
        if($request->file('license_image')!=NULL)
        {
            $compnay->companyOtherInformation ? Storage::delete($compnay->companyOtherInformation->license_image): '';
            $licenseImageFilename = $request->file('license_image')->getClientOriginalName();
            $licenseImage = uploadAlldocs($request->file('license_image'),$path,'licenseImage');
        }
        if($request->file('bank_statement_image')!=NULL)
        {
            $compnay->companyOtherInformation ? Storage::delete($compnay->companyOtherInformation->bank_statement_image): '';
            $bankStatementImageFilename = $request->file('bank_statement_image')->getClientOriginalName();
            $bankStatementImage = uploadAlldocs($request->file('bank_statement_image'),$path,'bankStatementImage');
            $bankStatementImageDate = Carbon::now()->format('Y-m-d');
        }
        if($request->file('annual_financial_statement_image')!=NULL)
        {
            $compnay->companyOtherInformation ? Storage::delete($compnay->companyOtherInformation->annual_financial_statement_image) : '';
            $annualFinancialStatementImageFilename = $request->file('annual_financial_statement_image')->getClientOriginalName();
            $annualFinancialStatementImage = uploadAlldocs($request->file('annual_financial_statement_image'),$path,'annualFinancialStatementImage');
            $annualFinancialStatementImageDate = Carbon::now()->format('Y-m-d');
        }
         if($compnay->companyOtherInformation == null)
        {
            $userInfo = CompanyOtherInformation::create([
                'company_id' => $compnay->id,
                'number_of_employee' => $request->number_of_employee,
                'average_sales' => $request->average_sales,
                'annual_sales' => $request->annual_sales,
                'financial_target' => $request->financial_target,
                'type' => $request->type,
                'category' => $request->category,
                'description' => $request->description,
                'ownership_percentage' => $request->ownership_percentage,
                'license_image' => $request->license_image?$licenseImage:null,
                'license_image_filename' => $request->license_image? $licenseImageFilename :null,
                'bank_statement_image' => $request->bank_statement_image?$bankStatementImage:null,
                'bank_statement_image_filename' => $request->bank_statement_image? $bankStatementImageFilename:null,
                'annual_financial_statement_image' => $request->annual_financial_statement_image?$annualFinancialStatementImage:null,
                'annual_financial_statement_image_filename' => $request->annual_financial_statement_image? $annualFinancialStatementImageFilename :null,
                'bank_image_updated_at' => $request->bank_statement_image?$bankStatementImageDate:null,
                'annual_image_updated_at' => $request->annual_financial_statement_image?$annualFinancialStatementImageDate:null,
                'siup_number' => $request->siup_number,
            ]);

            /**begin: System activities */
            CompanyOtherInformation::bootSystemActivities();
            /**end: System activities */
        }
        else
        {
            $compnay->companyOtherInformation->number_of_employee = $request->number_of_employee;
            $compnay->companyOtherInformation->average_sales = $request->average_sales;
            $compnay->companyOtherInformation->annual_sales = $request->annual_sales;
            $compnay->companyOtherInformation->financial_target = $request->financial_target;
            $compnay->companyOtherInformation->type = $request->type;
            $compnay->companyOtherInformation->category = $request->category;
            $compnay->companyOtherInformation->description = $request->description;
            $compnay->companyOtherInformation->ownership_percentage = $request->ownership_percentage;
            $compnay->companyOtherInformation->license_image = $request->license_image?$licenseImage:$compnay->companyOtherInformation->license_image;
            $compnay->companyOtherInformation->license_image_filename = $request->license_image? $licenseImageFilename : $compnay->companyOtherInformation->license_image_filename;
            $compnay->companyOtherInformation->bank_statement_image = $request->bank_statement_image?$bankStatementImage:$compnay->companyOtherInformation->bank_statement_image;
            $compnay->companyOtherInformation->bank_statement_image_filename = $request->bank_statement_image?$bankStatementImageFilename:$compnay->companyOtherInformation->bank_statement_image_filename;
            $compnay->companyOtherInformation->bank_image_updated_at = $request->bank_statement_image?$bankStatementImageDate:$compnay->companyOtherInformation->bank_image_updated_at;
            $compnay->companyOtherInformation->annual_financial_statement_image = $request->annual_financial_statement_image?$annualFinancialStatementImage:$compnay->companyOtherInformation->annual_financial_statement_image;
            $compnay->companyOtherInformation->annual_financial_statement_image_filename = $request->annual_financial_statement_image?$annualFinancialStatementImageFilename:$compnay->companyOtherInformation->annual_financial_statement_image_filename;
            $compnay->companyOtherInformation->annual_image_updated_at = $request->annual_financial_statement_image?$annualFinancialStatementImageDate:$compnay->companyOtherInformation->annual_image_updated_at;
            $compnay->companyOtherInformation->siup_number = $request->siup_number;
            $compnay->companyOtherInformation->save();

            /**begin: System activities */
            $compnay->companyOtherInformation->bootSystemActivities();
            /**end: System activities */
        }
    }

    public function updatePaymentInfo(Request $request)
    {
        $user = User::find(Auth::user()->id);

        if(isset($request->payment_term_id) && count($request->payment_term_id)){
            $oldtermDetail = UserPaymentTerms::where('user_id',$user->id)->get();
                if(count($oldtermDetail) > 0){
                    foreach($oldtermDetail as $term){
                        if (!in_array($term->payment_term_id, $request->payment_term_id))
                        {
                            $res = UserPaymentTerms::where('user_id',$user->id)->where('payment_term_id', $term->payment_term_id)->delete();
                        }
                    }
                }
            for($i =0;$i< sizeof($request->payment_term_id);$i++){
                $payment_term_id = $request->payment_term_id[$i];

                $input['payment_term_id'] = $payment_term_id;
                $input['user_id'] = $user->id;

                $getData = UserPaymentTerms::where('user_id',$user->id)->where('payment_term_id',$payment_term_id)->first();

                if($getData){
                    $getData->update($input);
                }else{
                    $createdata = UserPaymentTerms::create($input);
                }
            }
        }else{
            UserPaymentTerms::where('user_id','=',$user->id)->delete();
        }

        if (isset($request->changesUserNotification)){
            buyerNotificationInsert(Auth::user()->id, 'Payment Term Info', 'buyer_payment_term_info_change', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
            broadcast(new BuyerNotificationEvent());
        }
        return response()->json(array('success' => true));
    }

    public function updatelangcurrency(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->language_id = $request->language_id;
        $user->currency_id = $request->currency_id;
        $user->save();

        $user->bootSystemActivities();

        if (isset($request->changesUserNotification)){
            buyerNotificationInsert(Auth::user()->id, 'Language Info', 'buyer_lang_info_change', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
            broadcast(new BuyerNotificationEvent());
        }
        $lang = Languages::where('id', $user->language_id)->first();
        App::setLocale(strtolower($lang->name));
        session()->put('locale', strtolower($lang->name));

        return response()->json(array('success' => true));
    }
    public function changePassword(Request $request)
    {
        $user = User::find(Auth::user()->id);

        if (Hash::check($request->currentPassword, $user->password)) {
            $user->password = Hash::make($request->newPassword);
            $user->save();
            if (isset($request->changesUserNotification)){
                buyerNotificationInsert(Auth::user()->id, 'Password Change', 'buyer_change_password', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
            }
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false, 'error' => 'current passoword is wrong'));
        }
    }

    /**
     * Get company list for switch company,
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyList(Request $request)
    {
        $assignedCompanies = Company::getUserAssignCompanyAttribute(json_decode(Auth::user()->assigned_companies));
        $companies = $assignedCompanies->get(['name','id','owner_user'])->toArray();
        if (count($companies)>0) {
            return response()->json([
                'success'   => true,
                'data' => ['company' => $companies, 'default_company' => Auth::user()->default_company,'user_id'=> Auth::user()->id]
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'message'   =>   __('admin.something_went_wrong')
            ]);
        }


    }

    /**
     * Change default company
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeDefaultCompanyId(Request $request){
        $user = Auth::user();
        $isOwner = User::checkOwnerByCompanyId(Auth::user()->default_company); // check owner of default company
        $isOwner2 = User::checkOwnerByCompanyId($request->cmpID); // check owner of default company to be
        //parent to child
        if ($isOwner == true && $isOwner2 == false ) {
            $user->revokePermissionTo($user->getAllPermissions()->pluck('name')->toArray());
            $user->removeRole('buyer');
        }
        // child to child
        if ($isOwner == false && $isOwner2 == false) {
            $user->revokePermissionTo($user->getAllPermissions()->pluck('name')->toArray());
        }
        // child to parent
        if ($isOwner == false && $isOwner2 == true){
            $user->revokePermissionTo($user->getAllPermissions()->pluck('name')->toArray());
            $user->removeRole('sub-buyer');
        }
        $data = User::where('id', $user->id)->update(['default_company' => $request->cmpID]);
        if($data){
            User::assignUserPermission($user->id);
        }
        return response()->json(["success" => true]);
    }

    public function changeCompanyDetail(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'company_logo' => 'image|mimes:jpeg,png,jpg',
            ],[
                'company_logo.image'=>__('profile.mime_type'),
                'company_logo.mimes'=>__('profile.mime_type')
            ]
        );
        if ($validator->fails()) {
            $response['status'] = "failed";
            $response['msg'] = "Something went wrong.";
            $response['data'] = array();
            $response['errors'] = $validator->errors();
            return response()->json($response);

        }
        $compnay = Company::find($request->company_id);
        if ($compnay) {
            if ($request->file('company_logo')) {
                $logoFileName = Str::random(10) . '_' . time() . 'logo_' . $request->file('company_logo')->getClientOriginalName();
                $logoFilePath = $request->file('company_logo')->storeAs('uploads/company', $logoFileName, 'public');
                if (!empty($compnay->logo)) {
                    Storage::delete('/public/' . $compnay->logo);
                }
                $compnay->logo = $logoFilePath;
            }

            if ($request->file('nib_file')) {
                $nibFileName = Str::random(10) . '_' . time() . 'nib_file_' . $request->file('nib_file')->getClientOriginalName();
                $nibFilePath = $request->file('nib_file')->storeAs('uploads/company', $nibFileName, 'public');
                if (!empty($compnay->nib_file)) {
                    Storage::delete('/public/' . $compnay->nib_file);
                }
                $compnay->nib_file = $nibFilePath;
            }

            if ($request->file('npwp_file')) {
                $npwpFileName = Str::random(10) . '_' . time() . 'npwp_file_' . $request->file('npwp_file')->getClientOriginalName();
                $npwpFilePath = $request->file('npwp_file')->storeAs('uploads/company', $npwpFileName, 'public');
                if (!empty($compnay->npwp_file)) {
                    Storage::delete('/public/' . $compnay->npwp_file);
                }
                $compnay->npwp_file = $npwpFilePath;
            }

            if ($request->file('termsconditions_file')) {
                $termsconditionsFileName = Str::random(10) . '_' . time() . 'termsconditions_file_' . $request->file('termsconditions_file')->getClientOriginalName();
                $termsconditionsFilePath = $request->file('termsconditions_file')->storeAs('uploads/company', $termsconditionsFileName, 'public');
                if (!empty($compnay->termsconditions_file)) {
                    Storage::delete('/public/' . $compnay->termsconditions_file);
                }
                $compnay->termsconditions_file = $termsconditionsFilePath;
            }

            CompanyConsumption::where('user_id', Auth::user()->id)->delete();

            foreach ($request->ProductCategory as $key => $ProductCategory) {
                if ($request->ProductAnnualConsumption[$key]) {
                    $companyConsumption = new CompanyConsumption;
                    $companyConsumption->product_cat_id = $ProductCategory;
                    $companyConsumption->unit_id = $request->ProductUnit[$key];
                    $companyConsumption->annual_consumption = $request->ProductAnnualConsumption[$key];
                    $companyConsumption->user_id = Auth::user()->id;
                    $companyConsumption->save();



                }
            }

            $compnay->name = $request->company_name;
            $compnay->registrantion_NIB = $request->registration_nib;
            $compnay->npwp = $request->npwp;
            $compnay->web_site = $request->website;
            $compnay->company_email = $request->company_email;
            $compnay->c_phone_code = $request->c_phone_code?'+'.$request->c_phone_code:'';
            $compnay->company_phone = $request->company_phone;
            $compnay->alternative_email = $request->alternative_email;
            $compnay->a_phone_code = $request->a_phone_code?'+'.$request->a_phone_code:'';
            $compnay->alternative_phone = $request->alternative_phone;
            $compnay->address = $request->address;
            $compnay->background_logo = $request->background_logo;
            $compnay->background_colorpicker = $request->background_colorPicker??null;
            $compnay->establish_in=$request->establish_in;
            $compnay->owner_full_name=$request->owner_full_name;
            $compnay->save();

            $this->updateOtherCompanyInformation($compnay,$request);
            if (isset($request->changesUserNotification)){
                buyerNotificationInsert(Auth::user()->id, 'Company Info', 'buyer_company_info_change', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
            }
            addedByUpdatedByFun(Auth::user()->id,'',Auth::user()->id);
            $response['status'] = "success";
        } else {
            $response['status'] = "success";
        }
        $percentage = getUserWisePendingProfilePercentage(Auth::user()->id,Auth::user()->default_company);
        $disableTopHeader = '';
        if($percentage == '100%'){
            $disableTopHeader = 'd-none';
        }
        $htmlTopHeader ='<div class="toast align-items-center show w-100 '.$disableTopHeader.'" id="profile_percentage_message_ajex" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body flex-fill text-center py-1">
                    <span id="profile_percentage_message_ajex2">'.__('dashboard.profile_percentage_message',['percentage' => $percentage]).'</span><a href="javascript:void(0);" class="btn btn-warning btn-sm py-1" id="profileUpdateBtn"><small>'.__('dashboard.click_here_to_update').'</small></a>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>';
        $response['data']['percentage'] = $htmlTopHeader;
        $response['data']['progressBar'] = '<div class="progress-bar" role="progressbar" id="progress_bar_ajax" aria-label="Example with label" style="width: '.$percentage.';" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">'.$percentage.'</div>';
        return response()->json($response);
    }

    //     return response()->json($response);
    // }

    //User Invitation Approval
    public function userInvitation(Request $request) {

        $user = Auth::user()->id;
        $defaultCompanyId = Auth::user()->default_company;

        //Below condition is for multicompany users
        $userExist = User::where('email', $request->email)
                    ->whereJsonContains('assigned_companies', [Auth::user()->default_company])
                    ->first();

        if (!empty($userExist)) {
            $msg =  __('validation.email_already_exist');
            return response()->json(['error' => "email_already_exist"]);
        }

        //Manage Approver Process and Role Management User
        if ((isset($request->role) && $request->role && $request->role=="Approver")){
            $mainUser = User::select('firstname','lastname')->where('id',$user)->first()->toArray();
            $otp = rand(100000, 999999);
            $differentUser = User::join('user_companies','users.id','=','user_companies.user_id')->where('users.email',$request->email)->where('user_companies.company_id','<>',$defaultCompanyId)->first();
            $unAssigned = User::leftjoin('user_companies','users.id','=','user_companies.user_id')->where('users.email',$request->email)->where('user_companies.company_id',null)->first(['users.id']);
            if (isset($differentUser)) {
                UserCompanies::insert([
                    'user_id' => $differentUser->user_id,
                    'company_id' => $defaultCompanyId
                ]);

                $userId = $differentUser->user_id;

                // Begin: Add other company owner as approver user in current company
                $childUser = User::where('id', $userId)->first();
                $assignedCompaniesArr = !empty($childUser->assigned_companies) ? json_decode($childUser->assigned_companies) : [];
                if (!in_array(Auth::user()->default_company, $assignedCompaniesArr)) {
                    array_push($assignedCompaniesArr, Auth::user()->default_company);
                }
                User::where('id', $userId)->update(['assigned_companies' => json_encode($assignedCompaniesArr)]);
                // End: Add other company owner as approver user in current company


                $msg =  __('validation.user_added');
                return response()->json(array('success' => true, 'msg' => $msg));
            } else if(isset($unAssigned)) {
                UserCompanies::insert([
                    'user_id' => $unAssigned->id,
                    'company_id' => $defaultCompanyId
                ]);
                $userId = $unAssigned->id;
            } else {
                $user = new User();
                $user->firstname = $request->firstName;
                $user->lastname = $request->lastName;
                $user->mobile = $request->mobile;
                $user->phone_code =  $request->phone_code?'+'.$request->phone_code:'';
                $user->email = $request->email;
                $user->role_id = Role::BUYER;
                $user->is_active = 1;
                $user->designation = $request->designation;
                $user->department = $request->department;
                $user->security_code = $otp;
                $user->default_company = Auth::user()->default_company;
                $user->assigned_companies = json_encode(array(Auth::user()->default_company));
                $user->added_by = Auth::id();
                if ((isset($request->role) && $request->role && $request->role=="Approver")) {
                    $user->approval_invite = 1;
                }
                $user->password = Hash::make(123456);
                $user->save();

                $userId =  $user->id;
                $userCompany = new UserCompanies;
                $userCompany->user_id = $userId;
                $userCompany->company_id = $defaultCompanyId;
                $userCompany->save();
                CustomRoles::setUserPersonalInfoPermissions($userId);
                $msg =  __('validation.user_added');
                return response()->json(array('success' => true, 'msg' => $msg));
            }

            //Store User Activity
            if ($userId) {
                buyerNotificationInsert(Auth::user()->id, 'Invite User Add', 'buyer_user_invite_add', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
                $useremail = [
                    'user' => $user,
                    'mainUser' => $mainUser,
                    'url' => Crypt::encrypt($userId),
                ];
                //Send Invitation mail to user (Due to change in requirement we are not sending invitation mail for now)
                try {
                    $ccUsers = \Config::get('static_arrays.bccusers');
                    // Mail::to($user->email)->bcc($ccUsers)->send(new ApprovalInviteUserAdmin($useremail));
                } catch (\Exception $e) {
                    //dd($e);
                }
            }
        } else if((isset($request->role) && $request->role && $request->role!="Approver")) {
            $assignUser = (new UserController)->assignUserWithRole($request);
            $msg =  $assignUser->getOriginalContent()['success'] == true ? __('validation.user_added') :  __('profile.something_went_wrong');
            return response()->json(array('success' => true, 'msg' => $msg));
        }
        buyerNotificationInsert(Auth::user()->id, 'Invite User', 'buyer_invite_added_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
        broadcast(new BuyerNotificationEvent());
        $msg =  __('validation.user_invited');
        return response()->json(array('success' => true, 'msg' => $msg));
    }

    //Accept User Invitation
    public function acceptUserInvitation($id) {
        $mainUser = User::select('firstname','lastname')->where('id',Auth::user()->id)->first();
        $userId = Crypt::decrypt($id);
        $userData = User::select('id','firstname','lastname','email','is_active')->where('id',$userId)->first();
        if($userData->is_active == 0) {
            return view('profile/invitation_accept', ['mainUser' => $mainUser,'userData' => $userData]);
        } else {
            return view('profile/already_accepted');
        }

    }

    //Verify user OTP
    function verifyUserOTP(Request $request){
        $user_details = User::find($request->user_id);
        if (!empty($user_details) && $user_details->security_code != $request->user_otp){
            return response()->json(array('ErrorOTP' => true, 'ErrorMessage' => 'Invalid Otp'));
        }
        if ($request->user_otp == $user_details->security_code && $user_details->is_active == 0) {
            $user_details->is_active = 1;
            $user_details->save();
            return response()->json(array('ErrorOTP' => false, 'return_url' => route('thank-you'), 'profileUrl' => route('profile')));
        }
    }

    public function thankYou() {
        return view('profile/thank_you');
    }

    public function alreadyAccepted() {
        return view('profile/already_accepted');
    }

    //Update details of invited User by user id
    function userDetails($id) {
        $id = Crypt::decrypt($id);
        $designations = Designation::all()->where('is_deleted', 0);
        $departments = Department::all()->where('is_deleted', 0);
        $user = User::leftjoin('departments','departments.id','=','users.department')
        ->leftjoin('designations','designations.id','=','users.designation')
        ->where('users.id',$id)
        ->first(['users.id','users.firstname','users.lastname','users.email','users.phone_code','users.mobile','users.designation','users.department']);
        //dd($user->toArray());
        //->get(['users.id','users.firstname','users.lastname','users.email','users.phone_code','users.mobile','users.designation','users.department']);

        $customRoles = CustomRoles::getRoles();

        $customPermissionIds = ModelHasCustomPermission::where('model_type',User::class)->where('model_id',$id)->get()->pluck('custom_permission_id')->toArray(); //->pluck('custom_permission_id')
        $customRoleIds = [];
        if(sizeof($customPermissionIds) > 0){
            $customRoleIds = CustomPermission::whereIn('id', $customPermissionIds)->pluck('value')->toArray();
        }
        $returnHTML = view('profile/invited_user_popup', ['user' => $user, 'designations' => $designations, 'departments' => $departments, 'customRoles' => $customRoles, 'customRoleIds' => $customRoleIds])->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    //Edit details of invited User by user id
    function updateInvitedUserInfo(Request $request) {
        $user = User::find($request->invitedUserId);

        if(isset($request->email) && ($user->email != $request->email) && $user->is_active == 1) {
            $emailExist = User::select('email')->where('email',$request->email)->where('is_delete',0)->first();
            if(isset($emailExist)) {
                $msg =  __('validation.email_already_exist');
                return response()->json(['msg' => $msg]);
            }
        }

        $mainUser = User::select('firstname','lastname')->where('id',Auth::user()->id)->first()->toArray();
        $otp = rand(100000, 999999);

        $user->firstname = $request->firstName;
        $user->lastname = $request->lastName;
        $user->email = $request->email;
        $user->phone_code =  $request->phone_code?'+'.$request->phone_code:'';
        $user->mobile = $request->mobile;
        $user->designation = $request->designation;
        $user->department = $request->department;
        $user->security_code = $otp;
        $user->save();

        assignCompanyToUserAttribute($user, Auth::user()->default_company);

        $response['status'] = 'success';

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

                // $childUser = User::findOrFail($user->id);
                $childUser = User::where('id',$user->id)->where('default_company',Auth::user()->default_company)->get()->first();
                if(!empty($childUser)){
                    !empty($oldCustomRolePermission['permissions']) ? $childUser->revokePermissionTo(CustomPermission::findByIds($oldCustomRolePermission['permissions'])) : '';
                }

                ModelHasCustomPermission::where('model_type', User::class)->where('model_id', $user->id)->where('custom_permission_id', getRolePermissionAttribute($user->id)['custom_permission']->first()->id)
                        ->update([
                            'custom_permission_id'  =>  json_encode($customPermissionId),
                            'custom_permissions'    =>  $rolePermissions
                        ]);
            }

            //Assign new permissions by role
            // $childUser = User::findOrFail($user->id);
            $childUser = User::where('id',$user->id)->where('default_company',Auth::user()->default_company)->get()->first();
            if(!empty($childUser)){
                $childUser->givePermissionTo(CustomPermission::findByIds(json_decode($rolePermissions)));
                CustomRoles::setUserPersonalInfoPermissions($user->id);
            }

        } else if ($request->role == "Approver") {
            //Get role and permissions
            $customPermissionId = CustomPermission::where('model_type',CustomRoles::class)->where('value',$request->role)->get()->pluck('id')->first();
            $rolePermissions = CustomRoles::where('id',$request->role)->get()->pluck('permissions')->first();

            if (!empty($rolePermissions)) {
                //Remove permissions
                $oldCustomRolePermission = getRolePermissionAttribute($user->id);
                // $childUser = User::findOrFail($user->id);
                $childUser = User::where('id',$user->id)->where('default_company',Auth::user()->default_company)->get()->first();
                if(!empty($childUser)){
                    $childUser->revokePermissionTo(CustomPermission::findByIds($oldCustomRolePermission['permissions']));

                }
                ModelHasCustomPermission::where('model_type', User::class)->where('model_id', $user->id)->where('custom_permission_id', getRolePermissionAttribute($user->id)['custom_permission']->first()->id)->delete();

            }

        }
        /********end: assign custom role and permission to user********/

        $userId =  $user->id;

        //Resend Verification mail if change in email id
        //(Due to change in requirement we are not sending invitation mail for now)
        // if(isset($request->email) && ($user->is_active == 0)) {
        //     $useremail = [
        //         'user' => $user,
        //         'mainUser' => $mainUser,
        //         'url' => Crypt::encrypt($userId),
        //     ];
        //     try {
        //         $ccUsers = \Config::get('static_arrays.bccusers');
        //         //Mail::to($user->email)->bcc($ccUsers)->send(new ApprovalInviteUserAdmin($useremail));
        //     } catch (\Exception $e) {
        //         //dd($e);
        //     }
        // }
        buyerNotificationInsert(Auth::user()->id, 'Invite User Update', 'buyer_user_invite_updated', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
        broadcast(new BuyerNotificationEvent());
        return response()->json($response);

    }

    //Delete Invited User
    function deleteInvitedUser($id) {
        $user = User::find(Auth::user()->id);
        $deletedUser = User::find($id);
        $company = UserCompanies::select('company_id')->where('user_id',$user->id)->first();
        $configUser = UserApprovalConfig::where('user_id',$id)->get();
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


        if(isset($configUser)) {
            UserApprovalConfig::where('user_id',$id)->delete();
        }
        if (Auth::user()->id && isset($userData)) {
            /*$userActivity = new UserActivity();
            $userActivity->user_id = Auth::user()->id;
            $userActivity->activity = $deletedUser->firstname . ' ' . $deletedUser->lastname . ' Has been deleted';
            $userActivity->type = 'user';
            $userActivity->record_id = $id;
            $userActivity->save();*/
            buyerNotificationInsert(Auth::user()->id, 'Invite User Delete', 'buyer_user_invite_deleted', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
            broadcast(new BuyerNotificationEvent());
        }
        $msg = __('validation.user_deleted');
        return response()->json(array('success' => true, 'msg' => $msg));
    }

    //User Approval configuration
    public function userApprovalConfiguration(Request $request) {
        $mainUser = User::select('firstname','lastname','name')->join('user_companies','user_companies.user_id','=','users.id')
        ->join('companies','companies.id','=','user_companies.company_id')->where('users.id',Auth::user()->id)->first()->toArray();
        $userData = User::select('firstname','lastname','email')->where('id',$request->member_id)->first();
        $userIdExist = UserApprovalConfig::select('user_id','user_type')->where('user_id',$request->member_id)->first();
        if(isset($userIdExist)) {
            $msg =  __('validation.user_data_exist');
            return response()->json(['msg' => $msg]);
        } else {
            $user = new UserApprovalConfig();
            $user->user_id = $request->member_id;
            $user->user_type = $request->user_type;
            $user->is_deleted = 0;
            $user->save();
            $msg = __('validation.user_added');

            //Send Invitation mail to user
            $useremail = [
                'user' => $user,
                'mainUser' => $mainUser,
                'userData' => $userData,
                'url' => Crypt::encrypt($request->member_id),
            ];
            try {
                $ccUsers = \Config::get('static_arrays.bccusers');
                Mail::to($userData->email)->bcc($ccUsers)->send(new ApprovalInviteUserAdmin($useremail));
            } catch (\Exception $e) {
                //dd($e);
            }
            //if (isset($request->userApprovalConfiguration)){
                buyerNotificationInsert(Auth::user()->id, 'Approve Config Add', 'buyer_approve_config_add', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
            //}
            return response()->json(array('success' => true, 'msg' => $msg));
        }
    }

    //Update details of invited User by user id
    function approvalUserDetails($id) {
        $user = User::join('user_approval_configs','user_approval_configs.user_id','=','users.id')
        ->where('users.id',$id)
        ->get(['users.id','users.email','user_approval_configs.user_type']);
        $returnHTML = view('profile/approval_user_popup', ['user' => $user])->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    //Edit details of invited User by user id
    function updateApprovalUserInfo(Request $request) {
        $user = User::find($request->configUserId);
        if(isset($request->email)){
            $user->email = $request->email;
            //send verification mail again
        }
        $user->save();

        $userType = UserApprovalConfig::where('user_id',$request->configUserId)->first();
        $userType->user_type = $request->user_type;
        $userType->save();
        if (isset($request->userApprovalConfiguration)){
            buyerNotificationInsert(Auth::user()->id, 'Approve Config Update', 'buyer_approve_config_update', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
            broadcast(new BuyerNotificationEvent());
        }
        $response['status'] = 'success';
        return response()->json($response);
    }

    //Delete Invited User
    function deleteApprovalUser($id) {
        $userData = UserApprovalConfig::where('user_id', $id)->first()->delete();
        $userData->save();
        buyerNotificationInsert(Auth::user()->id, 'Approve Config Delete', 'buyer_approve_config_delete', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
        broadcast(new BuyerNotificationEvent());
        return true;
    }
    function companyFileDelete(Request $request)
    {
        $columnName = $request->fileName;
        $columnName1 = $columnName.'_filename';
        if($request->type == 'other')
        {
            $table = CompanyOtherInformation::find($request->id);
            Storage::delete($table->$columnName);
            if($columnName == 'bank_statement_image'){
                $table->bank_image_updated_at =Carbon::now()->format('Y-m-d');
            }
            if($columnName == 'annual_financial_statement_image'){
                $table->annual_image_updated_at = Carbon::now()->format('Y-m-d');
            }
            $table->$columnName1 = null;
        }
        elseif($request->type == 'persnol_other'){
            $table = UserOtherInformation::find($request->id);
            Storage::delete($table->$columnName);
            $table->$columnName1 = null;
        }
        elseif($request->type == 'lo_doc'){
            $table = Order::find($request->id);
            Storage::delete($table->$columnName);
            $table->$columnName1 = null;
        }
        else{
            $table = Company::find($request->id);
            if (isset($table->$columnName) && !empty($table->$columnName)) {
                Storage::delete('/public/' . $table->$columnName);
            }
        }
        $table->$columnName = null;
        $table->save();
        return response()->json(array('success' => true,'compnay'=>$table->$columnName));
    }

    function companyFileDownload(Request $request){
        $image = Company::where('id', $request->id)->pluck($request->fieldName)->first();
        if (!empty($image)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $image, '', $headers);
            //return response()->download(public_path('storage/'.$image));
        }
        return response()->json(array('success' => false));
    }

    //@ekta 08/05/22 Invite aupplier and friend
    public function inviteSupplierFriend(Request $request) {
        $supplier_id = null;
        if (auth()->user()->role_id == 2) {
            $user_id = auth()->user()->id;
            $role_id = $request->role_id; //invite supplier or their friend
            $user_type = 2; //buyer

            $user = DB::table('users')
                ->join('user_companies','user_companies.user_id','=','users.id')
                ->join('companies','user_companies.company_id','=','companies.id')
                ->where('users.id',$user_id)
                ->selectRaw('CONCAT(users.firstname, " ", users.lastname) as contact_person_name, users.role_id as role_id, companies.name as name')
                ->first();

            //dd($supplier_id,$user_id);
            $token = Str::random(64);
            $inviteBuyer = new InviteBuyer();
            $inviteBuyer->supplier_id = $supplier_id;
            $inviteBuyer->user_id = $user_id;
            $inviteBuyer->company_id = Auth::user()->default_company; //company default id added
            $inviteBuyer->role_id = $role_id ;
            $inviteBuyer->user_type = $user_type ;
            $inviteBuyer->user_email = $request->user_email;
            $inviteBuyer->token = $token;
            $inviteBuyer->added_by =  Auth::id();
            $inviteBuyer->save();
            $user->buyer_supplier_mail = $role_id;
            if($role_id == 2){
                $useremail = [
                    'user' => $user,
                    'url' => route('signup-user', ['email' => Crypt::encryptString($request->user_email),'token' => $token ])
                ];
            }else{
                $useremail = [
                    'user' => $user,
                    'url' => route('signup-supplier-invited', ['email' => Crypt::encryptString($request->user_email),'token' => $token ])
                ];
            }
//            dd($useremail);
            try {
                dispatch(new InviteBuyerActivationJob($useremail, $request->user_email));
            } catch (\Exception $e) {
                //echo 'Error - ' . $e;
                dd($e);
            }
            buyerNotificationInsert(Auth::user()->id, 'Invite User', 'buyer_invite_added_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
            broadcast(new BuyerNotificationEvent());
            return response()->json(array('success' => true));
        }
        if(auth()->user()->role_id == 3){
            $user = User::find(auth()->user()->id);
            $register_supplier_flag = 1;
            $user->email = $request->user_email;
            $user->supplier->contact_person_email = $request->user_email;
            $user->save();
            $user->supplier->save();
            $useremail = [
                'user' => $user,
                'flag' => $register_supplier_flag,
                'url' => $request->getSchemeAndHttpHost() . '/activate-account?email=' . Crypt::encryptString($request->user_email)
            ];
            
            dispatch(new SendActivationMailToUserJob($useremail, $request->user_email));
        }
    }

    public function inviteSupplierFriendEdit($id) {

        $inviteBuyerEdit = InviteBuyer::find($id);
        return response()->json(array('success' => true, 'inviteBuyerEdit' => $inviteBuyerEdit));
    }

    public function inviteSupplierFriendUpdate(Request $request) {
        //dd($request->all());
        $supplier_id = null;
        if (auth()->user()->role_id == 2) {
            $user_id = auth()->user()->id;
            $role_id = $request->role_id; //invite supplier or their friend
            $user_type = 2; //buyer

            $checkRecords = InviteBuyer::where([['user_email', '=' ,$request->user_email], ['id',$request->id], ['user_id',$user_id], ['role_id',$request->role_id]])->count();
            //dd($checkRecords);
            if($checkRecords > 0){
                return response()->json(array('success' => true));
            }else{
                //for mail
                $user = DB::table('users')
                    ->join('user_companies','user_companies.user_id','=','users.id')
                    ->join('companies','user_companies.company_id','=','companies.id')
                    ->where('users.id',$user_id)
                    ->selectRaw('CONCAT(users.firstname, " ", users.lastname) as contact_person_name, companies.name as name')
                    ->first();

                //dd($supplier_id,$user_id);
                $token = Str::random(64);
                $inviteBuyer = InviteBuyer::find($request->id);
                $inviteBuyer->supplier_id = $supplier_id;
                $inviteBuyer->user_id = $user_id;
                $inviteBuyer->role_id = $role_id ;
                $inviteBuyer->user_type = $user_type ; //buyer login
                $inviteBuyer->user_email = $request->user_email;
                $inviteBuyer->token = $token;
                $inviteBuyer->added_by =  Auth::id();
                $inviteBuyer->save();
                $user->buyer_supplier_mail = $role_id ;
                if($role_id == 2){
                    $useremail = [
                        'user' => $user,
                        'url' => route('signup-user', ['email' => Crypt::encryptString($request->user_email),'token' => $token ])
                    ];
                }else{
                    $useremail = [
                        'user' => $user,
                        'url' => route('signup-supplier-invited', ['email' => Crypt::encryptString($request->user_email),'token' => $token ])
                    ];
                }
                try {
                    dispatch(new InviteBuyerActivationJob($useremail, $request->user_email));
                } catch (\Exception $e) {
                    //echo 'Error - ' . $e;
                    dd($e);
                }
                buyerNotificationInsert(Auth::user()->id, 'Invite User Update', 'buyer_invite_updated_notification', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
                broadcast(new BuyerNotificationEvent());
                return response()->json(array('success' => true));
                //$msg =  __('validation.user_invited');
                //return response()->json(array('success' => true, 'msg' => $msg));
            }
        }
    }

    /**
     * Get profile section redirection elements
     *
     * @return response
     */
    public function getProfileRedirectionElements(Request $request)
    {

        if ($request->ajax()) {

            try {

                $user       = Auth::user();
                $mainTab    = null;
                $secondTab  = null;

                $profileTabPermission = [
                    ['permission' => 'publish buyer personal info',     'tab'   => 'change_personal_info'],
                    ['permission' => 'publish buyer company info',      'tab'   => 'change_company_info'],
                    ['permission' => 'publish buyer side invite',       'tab'   => 'invite_supplier_list'],
                    ['permission' => 'publish buyer change password',   'tab'   => 'change_change_password']
                ];

                $settingsTabPermission = [
                    ['permission' => 'publish buyer preferences',               'tab'   => 'change_Preferences'],
                    ['permission' => 'publish buyer payment term',              'tab'   => 'change_Payment_Term'],
                    ['permission' => 'publish buyer bank details',              'tab'   => 'user_bank'],
                    ['permission' => 'publish buyer users',                     'tab'   => 'invite_user'],
                    ['permission' => 'publish buyer roles and permissions',     'tab'   => 'roles'],
                    ['permission' => 'publish buyer approval configurations',   'tab'   => 'approval_config']
                ];

                $tabPermission = [
                    ['permission' => 'publish buyer profile',   'tab' => 'personal-tab',    'sidebar' => $profileTabPermission],
                    ['permission' => 'publish buyer settings',  'tab' => 'company-tab',     'sidebar' => $settingsTabPermission]
                ];

                foreach ($tabPermission as $tab) {

                    if ($user->hasPermissionTo($tab['permission'])) {
                        $mainTab = $tab['tab'];
                        break;
                    }
                }

                foreach ($tabPermission as $tab) {

                    if (empty($secondTab)) {
                        foreach ($tab['sidebar'] as $subtab) {

                            if ($user->hasPermissionTo($subtab['permission']) && empty($secondTab)) {
                                $secondTab = $subtab['tab'];
                            }

                        }
                    }

                }

                return response()->json(['success' => true, 'message' => 'Success', 'tab' => ['mainTab' => $mainTab, 'secondTab' => $secondTab]]);


            } catch (\Exception $e) {

                return response()->json(['success' => false, 'message' => __('profile.something_went_wrong'), 'tab' => ['mainTab' => '', 'secondTab' => '']]);

            }
        }

    }

}
