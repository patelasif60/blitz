<?php

namespace App\Http\Controllers\Credit\Common;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Credit\KoinWorks\KoinWorkController;
use App\Http\Controllers\LoanApplicationsController;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\LoanApplicant;
use App\Models\LoanApplicantAddress;
use App\Models\LoanApplicantBusiness;
use App\Models\LoanApplicantBusinessAddress;
use App\Models\LoanApplicantSpouse;
use App\Models\LoanApplication;
use App\Models\LoanProvider;
use App\Models\LoanProviderApiResponse;
use App\Models\Rfq;
use App\Models\State;
use App\Models\SystemActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Models\CountryOne;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LimitController extends Controller
{
    /**
     * index: Limit list with datatable pagination, sorting and search
     */
    function index(Request $request)
    {
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


            $query = LoanApplication::with('applicant','loanApplicantBusiness');

            //Filters
            if (!empty($request->filterData)) {

                //Application Number
                if (Arr::exists($request->filterData, 'ApplicationNo')) {
                    $query->whereIn('loan_application_number', $request->filterData['ApplicationNo']);
                }

                //company name
                if (Arr::exists($request->filterData, 'company_name')) {
                    $query->whereHas('company', function($query) use($request){
                        $query->whereIn('name', $request->filterData['company_name']);
                    });
                }

                //email
                if (Arr::exists($request->filterData, 'email')) {
                    $query->whereHas('loanApplicantBusiness', function($query) use($request){
                        $query->whereIn('email', $request->filterData['email']);
                    });
                }

                //phone
                if (Arr::exists($request->filterData, 'mobile')) {
                    $query->whereHas('loanApplicantBusiness', function($query) use($request){
                        $query->whereIn('phone_number', $request->filterData['mobile']);
                    });
                }

                //Limit Amount
                if (Arr::exists($request->filterData, 'loan_limit')) {
                    $query->whereIn('senctioned_amount', $request->filterData['loan_limit']);
                }

                //limit Status
                if (Arr::exists($request->filterData, 'limit_status')) {
                    $query->whereIn('status_name', $request->filterData['limit_status']);
                }


                 //Buyer Name
                 if (Arr::exists($request->filterData, 'buyername')) {
                    $query->whereHas('applicant', function($query) use($request){
                    $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE',"%".implode('',$request->filterData['buyername'])."%");
                });
                }

            }

            // here will search query
            if ($search != "") {
                $query = $query->whereHas('applicant' , function($query) use($search){
                    $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE',"%".$search."%");

                    // Begin: Search based on company name
                    $companyId = Company::where('name', "LIKE", "%".$search."%")->pluck('id')->toArray();
                    if(sizeof($companyId) > 0){
                        $query->orWhereIn('company_id', $companyId);
                    }
                    // End: Search based on company name
                })
                ->orWhere('loan_application_number', 'LIKE', "%$search%")
                ->orWhere('senctioned_amount', 'LIKE', "%$search%")
                ->orWhere('status_name', 'LIKE', "%$search%")
                ->orWhereHas('loanApplicantBusiness', function($query) use($search){
                    $query->where('email', 'LIKE',"%$search%")
                        ->orWhere('phone_number', 'LIKE', "%$search%");
                });

            }
            // here will search query

            // sort
            if($column == 'loan_application_number' || $column == 'loan_limit' || $column == 'created_at') {
                if($column == 'loan_application_number'){
                    $query = $query->orderBy('id', $sort);
                }else{
                    $query = $query->orderBy($column, $sort);
                }
            }else{
                $query = $query->orderBy('id', 'desc');
            }
            // sort

            // Total Display records
            $totalRecords = $query->count();
            $totalDisplayRecords = $query->count();

            $limits = $query->skip($start)->take($length)->get();

            return response()->json([

                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $limits->map(function ($limit) {

                    $loanApplicationNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="limitModalView hover_underline" data-bs-toggle="modal" data-bs-target="#limitModal" data-id="'.Crypt::encrypt($limit->id).'">'.$limit->loan_application_number.'</a>';
                    $viewBtn = '<a class="ps-2 cursor-pointer limitModalView" data-id="'.Crypt::encrypt($limit->id).'" data-bs-toggle="modal" data-bs-target="#limitModal" data-toggle="tooltip" ata-placement="top" title="'.__('admin.view').'"><i class="fa fa-eye"></i></a>';
                    $editBtn  =   '<a href="'. route('limit-edit', ['id' => Crypt::encrypt($limit->id)]) .'" class="show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="'.__('admin.edit').'"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                    $resendCreateApi = '';
                    if($limit->provider_application_id == null){
                        $resendCreateApi = '<a href="javascript:void(0)" id="resendCreateApi_'. $limit->id .'" data-id="'.Crypt::encrypt($limit->id).'" class="resendCreateApi ps-2 me-1" data-toggle="tooltip" data-placement="top" title="Resend Create API"><i class="fa fa-send" aria-hidden="true"></i></a>';
                    }
                    $action = $resendCreateApi.$viewBtn.$editBtn;

                    $statusClass = '';
                    if($limit->status_name == 'Rejected'){
                        $statusClass = "danger";
                    }else{
                        $statusClass = "success";
                    }
                    $status = '<span class="badge badge-pill badge-'.$statusClass.'">'.$limit->status_name.'</span>';

                    if($limit->senctioned_amount!=''){
                        $senctioned_amount='Rp '.number_format($limit->senctioned_amount,2);
                    }
                    else{
                        $senctioned_amount=__('admin.no_approved_yet');
                    }
                    return [
                        'loan_application_number'  => $loanApplicationNumber,
                        'company_name' => $limit->applicant->company->name,
                        'buyer_name' => $limit->applicant->full_name,
                        'email' => $limit->loanApplicantBusiness->email,
                        'mobile' => '+'.$limit->loanApplicantBusiness->phone_code.' '.$limit->loanApplicantBusiness->phone_number,
                        'apllied_limit' => 'Rp '.number_format($limit->loan_limit,2),
                        'approved_limit' => $senctioned_amount,
                        'created_at' => date('d-m-Y H:i:s', strtotime($limit->created_at)),
                        'verify_otp' => $limit->verify_otp == 1 ? __('admin.yes'):__('admin.no'),
                        'status' => $status,
                        'action' =>  $action,
                    ];

                })
            ]);
        }
        $query = LoanApplication::with('applicant','loanApplicantBusiness');
        $limits = $query->get();

        $mobiles  = $limits;
        $mobiles  = $mobiles->unique('loanApplicantBusiness.phone_number');

        $buyerNames  = $limits;
        $buyerNames  = $buyerNames->unique('applicant.first_name');

        $companies  = $limits;
        $companies  = $companies->unique('company.name');

        $status  = $limits;
        $status = $status->unique('status');

        $amounts  = $limits;
        $amounts = $amounts->unique('senctioned_amount');

        /**begin: system log**/
        LoanApplication::bootSystemView(new LoanApplication(), 'Limit Application', SystemActivity::VIEW);
        /**end:  system log**/

        return View::make('admin.limit.index')->with(compact(['limits','mobiles','companies','status','amounts','buyerNames']));
    }

    public function resentCreditApplication(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $application = LoanApplication::where('id',$id)->first();
        if (empty($application)){
            return response()->json(['success' => false, 'message' => __('profile.limit_application_not_found')]);
        }
        $kwobj = new LoanApplicationsController;
        $returnData = $kwobj->createLimit($application->id);
        if ($returnData['status']==200 && isset($returnData['data'])){
            return response()->json(['success' => true, 'message' => __('profile.credit_applied')]);
        }
        return response()->json(['success' => false, 'message' => $returnData['message']['en']]);
    }

    public function limitApplicationDetailsView($id) {
        $id = Crypt::decrypt($id);
        $limitApplicantDetail = LoanApplication::getApplicationDatail($id);
        $limitView = view('admin/limit/limitApplicationViewModal',['limitApplicantDetail'=>$limitApplicantDetail])->render();
        return response()->json(array('success' => true, 'limitView' => $limitView));
   }

    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $applicant = LoanApplicant::where('id',$id)->first();
        $applications = LoanApplication::where('applicant_id',$id)->first();
        $applicantSpouses = LoanApplicantSpouse::where('applicant_id',$id)->first();
        $applicantAddress = LoanApplicantAddress::where('applicant_id',$id)->first();
        $applicantBusiness = LoanApplicantBusiness::where('applicant_id',$id)->first();
        $applicantBusinessAddress = LoanApplicantBusinessAddress::where('applicant_id',$id)->first();

        $applicantProvinces = State::where('id',$applicantAddress->provinces_id)->get();
        $applicantCity = City::where('id',$applicantAddress->city_id)->get();
        $applicantCountry = CountryOne::where('id',$applicantAddress->country_id)->get();

        $businessProvinces = State::where('id',$applicantBusinessAddress->provinces_id)->get();
        $businessCity = City::where('id',$applicantBusinessAddress->city_id)->get();
        $businessCountry =CountryOne::where('id',$applicantBusinessAddress->country_id)->get();
       $limitStatusValue='';
        $kwobj2 = new KoinWorkController;
        $getLimitResponse = $kwobj2->getLimit($applications->provider_application_id);

        $limitStatusValue= isset($getLimitResponse['data'][0]['limitStatusValue']) ? $getLimitResponse['data'][0]['limitStatusValue'] : '';
        $status= isset($getLimitResponse['data'][0]['status']) ? $getLimitResponse['data'][0]['status'] : '';
        $updateLoanApplication = LoanApplication::where('id', $id)->update([
            'status_name' => $limitStatusValue,
            'status' => $status
        ]);

        return view('admin/limit/limit_edit',['applicant' => $applicant, 'applications' => $applications, 'applicantSpouses' => $applicantSpouses, 'applicantAddress' => $applicantAddress, 'applicantBusiness' => $applicantBusiness, 'applicantBusinessAddress' => $applicantBusinessAddress, 'applicantProvinces' => $applicantProvinces, 'applicantCity' => $applicantCity, 'applicantCountry' => $applicantCountry , 'businessProvinces' => $businessProvinces, 'businessCity' => $businessCity, 'businessCountry' => $businessCountry,'limitStatusValue' => $limitStatusValue]);
    }

    /**
     * update: Limit recorde update only contract and document
     */
    function update(Request $request){
        $getUserIds= LoanApplicant::where('id', $request->id)->first(['company_id','user_id']);
        $companyId = $getUserIds->company_id;
        $userId = $getUserIds->user_id;

        $application = LoanApplication::where(['applicant_id'=>$request->id])->first();// check if status is return_to_user or waiting for documents

        // check if status is return_to_user
        if($application->status == '0ff092ed-86c8-49f2-b8bb-7384abbba233'){
            try {
                $destinationPath1 = config('settings.koinworks_confirm_folder');
                $folder = $destinationPath1 . $companyId;

                $applicant = LoanApplicant::reuploadDoucment($request->id,$request,$folder,$userId,$companyId);
                $loanApplicantBusiness = LoanApplicantBusiness::reuploadDoucment($request->id,$request,$folder,$userId,$companyId);
                $loanApplicantSpouse = LoanApplicantSpouse::reuploadDoucment($request->id,$request,$folder,$userId,$companyId);
                $spouseKtp = ($application->loan_limit>FIVE_HUNDRED_MILLION)?((string)url($loanApplicantSpouse->ktp_image)):'';
                $payload = [
                    "ktp"                  => (string)url($applicant->ktp_image),
                    "selfieKtp"            => (string)url($applicant->ktp_with_selfie_image),
                    "familyCard"           => (string)url($applicant->family_card_image),
                    "npwp"                 => (string)url($loanApplicantBusiness->npwp_image),
                    "bankStatement"        => (string)url($loanApplicantBusiness->bank_statement_image),
                    "businessLicense"      => (string)url($loanApplicantBusiness->license_image),
                    "spouseKtp"            => '',//$spouseKtp
                ];

                $kwobj = new KoinWorkController;

                /*****************begin: Call Credit Limit API*********/
                $loanSystemApi = LoanProviderApiResponse::bootApiActivity(LoanProvider::KOINWORKS, $applicant->user_id, $applicant->id, LoanProviderApiResponse::APIRequest, ['request_data' => json_encode($payload)]); //Boot API Request

                $returnData = $kwobj->reuploadDocument($application->provider_user_id,$payload);

                $loanSystemApi = LoanProviderApiResponse::bootApiActivity(LoanProvider::KOINWORKS, $applicant->user_id, $applicant->id, LoanProviderApiResponse::APIResponse, ['id' => $loanSystemApi->id,'request_data' => json_encode($returnData), 'response_code' => $returnData['status']]); //Boot API Response
                /*****************end: Call Credit Limit API*********/

                if ($returnData['status']==200){
                    return response()->json(array('success' => true, 'message' => __('admin.reupload_document_success'),'data'=>$request->id));
                }
                return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
            } catch (\Exception $e) {
                return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
            }
        }
        // waiting for documents
        elseif($application->status == 'badfa20d-3562-45eb-bf4a-79a1c59fd3e9'){
            try {

                //deleteFile(public_path($application->uploaded_contracts));
                Storage::delete('/public/' . $application->uploaded_contracts);
                $filepath = null;
                $filename = Str::random(10) . '_' . time() . '_contract_' . $request->file('uploadContract')->getClientOriginalName();
                $filepath = $request->file('uploadContract')->storeAs('credit_apply/buyers/companies'.$companyId.'/contract', $filename, 'public');
                LoanApplication::where(['id' => $application->id])->update(['uploaded_contracts'=>$filepath]);

                $contractURL = url('/').Storage::URL($filepath);

                $kwobj = new KoinWorkController;
                $returnData = $kwobj->limitContractUpload($application->provider_user_id,$contractURL);
                LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$application->applicant_id,'user_id'=>$application->user_id,'response_code'=>$returnData['status'],'response_data'=>json_encode($returnData)]);
                if ($returnData['status']==200){
                    return response()->json(array('success' => true, 'message' => __('admin.contract_upload_success')));
                }elseif ($returnData['status']==406){
                    return response()->json(array('success' => false, 'message' => $returnData['message']['en']));
                }else {
                    return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
                }
            } catch (\Exception $e) {
                return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
            }
        }
        else{
            return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
        }
    }

    /**
     * Limit application activity
     *
     * @param Request $request
     */
    public function limitActivity($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $loan = LoanApplication::where('id',$id)->first();

            $loanActivity = $loan->filter()->activities()->orderBy('created_at','desc')->get();

            return response()->json(['success' => true, 'message' => __('admin.success'), 'data' => ['activity' => $loanActivity, 'limit' => $loan]]);


        } Catch(\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B041 Limit application activity');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'), 'data' => '']);
        }

    }

}
