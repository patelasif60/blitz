<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Credit\KoinWorks\KoinWorkController;
use App\Http\Requests\Buyer\Credit\CreditRequest;
use App\Http\Requests\Buyer\Credit\ReuploadDocRequest;
use App\Jobs\CreditApplicationJob;
use App\Models\City;
use App\Models\LoanApplicantRevenue;
use App\Models\LoanApplication;
use App\Models\LoanApplicant;
use App\Models\LoanApplicantBusiness;
use App\Models\LoanApplicantSpouse;
use App\Models\LoanApplicantAddress;
use App\Models\LoanApplicantBusinessAddress;
use App\Models\LoanEmail;
use App\Models\LoanProvider;
use App\Models\LoanProviderApiResponse;
use Carbon\Carbon;
use Doctrine\DBAL\Cache\ArrayResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCompanies;
use \Illuminate\Http\Response;
use App\Models\State;
use App\Models\CountryOne;
use App\Models\OtherCharge;
use App\Models\Country;
use Illuminate\Support\Str;
use View;
use Log;
use App\Http\Controllers\DashboardController;


class LoanApplicationsController extends Controller
{
    protected $status = "";
    public function __construct()
    {
        $this->middleware('permission:create buyer company credits|edit buyer company credits|update buyer company credits|publish buyer company credits|unpublish buyer company credits', ['only' => ['index']]);
        $this->middleware('permission:create buyer company credits', ['only' => ['creditShow', 'requestLimitOtp', 'limitOtp']]);
        $this->status = array_flip(LoanApplication::STATUS);
    }

    /**
     * Display a listing of the credit form.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $existingApplication =  LoanApplication::where(['company_id'=>Auth::user()->default_company])->first();
        $fullFormData =  json_decode(Redis::get('data'.Auth::id()),true);
        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
        $maxCreditLimit = LoanApplication::MAX_CREDIT_LIMIT;

        $country = CountryOne::find(CountryOne::DEFAULTCOUNTRY);

        if (empty($existingApplication)) {
            return view('buyer.koinworks.index',compact('states','country','maxCreditLimit'));
        }elseif ((!empty($existingApplication) && empty($existingApplication->provider_application_id)) && (isset($fullFormData['form_status']) && $fullFormData['form_status'] == true)){
            return redirect('settings/credit-show');
        }elseif ($existingApplication->provider_application_id){//when koinworks api call and provider application id created
            $id = Crypt::encrypt($existingApplication->id);
            return redirect()->route('settings.credit-limit-status', [$id]);
        }else{
            return view('buyer.koinworks.index',compact('states','country','maxCreditLimit'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function creditShow()
    {
        try {
            $existingApplication =  LoanApplication::where(['company_id'=>Auth::user()->default_company])->first();
            if (!empty($existingApplication) && $existingApplication->provider_application_id){
                $id = Crypt::encrypt($existingApplication->id);
                return redirect()->route('settings.credit-limit-status', [$id]);
            }

            $fullFormData =  json_decode(Redis::get('data'.Auth::id()),true);

            if (!isset($fullFormData['form_status'])){
                return redirect('settings/credit-apply');

            } else {
                $fullFormData['business_provinces'] = $fullFormData['business_provinces_id'] > 0 ? State::findOrFail($fullFormData['business_provinces_id'])->name : $fullFormData['business_other_provinces'];
                $fullFormData['business_city'] = $fullFormData['business_city_id'] > 0 ? City::findOrFail($fullFormData['business_city_id'])->name : $fullFormData['business_other_city'];
                $fullFormData['business_country'] = !empty($fullFormData['business_country']) ? CountryOne::findOrFail($fullFormData['business_country'])->name : '';

                $fullFormData['provinces'] = $fullFormData['provinces_id'] > 0 ? State::findOrFail($fullFormData['provinces_id'])->name : $fullFormData['other_provinces'];
                $fullFormData['city'] = $fullFormData['city_id'] > 0 ? City::findOrFail($fullFormData['city_id'])->name : $fullFormData['other_city'];
                $fullFormData['country'] = !empty($fullFormData['country_id']) ? CountryOne::findOrFail($fullFormData['country_id'])->name : '';

            }

            return view('buyer.koinworks.creditshowindex',$fullFormData);

        } catch (\Exception $exception) {

            Log::critical('Code - 400 | ErrorCode:B018 - Credit Show');

            abort('404');
        }

    }

    /**
     * Credit tab dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditDashboard()
    {
        $creditDetail = LoanApplication::getCreditDatail();
        $returnHTML = view('buyer.koinworks.credit',['creditInfoDetail'=>$creditDetail])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
       $fullFormData =  Redis::get('data'.Auth::id());

       $fullFormData=json_decode($fullFormData,true);

       $company_id = Auth::user()->default_company;

        $newEntry = false;
        $application = LoanApplication::where(['user_id'=>Auth::id(),'company_id'=>$company_id])->first();

        if (empty($application)) {
            /*************************begin: Loan application transaction*******************/
            DB::transaction(function () use (&$application, &$newEntry, $fullFormData, $company_id) {
                $loanApplicantDetails = LoanApplicant::Create([
                    'company_id'                    =>  $company_id,
                    'user_id'                       =>  Auth::id(),
                    'loan_provider_id'              =>  LoanProvider::KOINWORKS,
                    'first_name'                    =>  $fullFormData['first_name'],
                    'last_name'                     =>  $fullFormData['last_name'],
                    'email'                         =>  $fullFormData['email'],
                    'phone_code'                    =>  $fullFormData['phone_code'],
                    'phone_number'                  =>  $fullFormData['phone_number'],
                    'ktp_nik'                       =>  $fullFormData['ktp_nik'],
                    'ktp_image'                     =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['ktp_image']),
                    'ktp_with_selfie_image'         =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['ktp_with_selfie_image']),
                    'gender'                        =>  $fullFormData['gender'],
                    'place_of_birth'                =>  $fullFormData['place_of_birth'],
                    'date_of_birth'                 =>  date('Y-m-d',strtotime($fullFormData['date_of_birth'])),
                    'marital_status'                =>  $fullFormData['marital_status'],
                    'religion'                      =>  $fullFormData['religion'],
                    'education'                     =>  $fullFormData['education'],
                    'occupation'                    =>  $fullFormData['occupation'],
                    'total_other_income'            =>  $fullFormData['total_other_income'],
                    'other_source_of_income'        =>  $fullFormData['other_source_of_income'],
                    'net_salary'                    =>  $fullFormData['net_salary'],
                    'my_position'                   =>  $fullFormData['myPosition'],
                    'family_card_image'             =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['family_card_image']),
                    'first_account_created_at'      =>  auth()->user()->created_at
                ]);

                $loanApplicantDetails1 = LoanApplicantSpouse::Create([
                    'applicant_id'                  =>  $loanApplicantDetails->id,
                    'relationship_with_borrower'    =>  $fullFormData['relationship_with_borrower'],
                    'ktp_image'                     =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['other_ktp_image']),
                    'phone_code'                    =>  $fullFormData['other_phone_code'],
                    'phone_number'                  =>  $fullFormData['other_phone_number'],
                    'ktp_nik'                       =>  $fullFormData['other_ktp_nik']??'',
                    'first_name'                    =>  $fullFormData['other_first_name'],
                    'last_name'                     =>  $fullFormData['other_last_name'],
                    'email'                         =>  $fullFormData['other_email']??'',
                ]);

                $loanApplicantAddress = LoanApplicantAddress::Create([
                    'applicant_id'                  =>  $loanApplicantDetails->id,
                    'name'                          =>  $fullFormData['loanApplicantAddressName'],
                    'address_line1'                 =>  $fullFormData['address_line1'],
                    'address_line2'                 =>  $fullFormData['address_line2'],
                    'sub_district'                  =>  $fullFormData['sub_district'],
                    'district'                      =>  $fullFormData['district'],
                    'country_id'                    =>  $fullFormData['country'],
                    'provinces_id'                  =>  $fullFormData['provinces_id'],
                    'other_provinces'               =>  $fullFormData['other_provinces'],
                    'city_id'                       =>  $fullFormData['city_id'],
                    'other_city'                    =>  $fullFormData['other_city'],
                    'has_live_here'                 =>  $fullFormData['has_live_here'],
                    'home_ownership_status'         =>  $fullFormData['home_ownership_status'],
                    'duration_of_stay'              =>  $fullFormData['duration_of_stay'],
                    'postal_code'                   =>  $fullFormData['loanApplicantPostalCode'],

                ]);

                $loanApplicantBusiness = LoanApplicantBusiness::Create([
                    'applicant_id'                  =>  $loanApplicantDetails->id,
                    'type'                          =>  $fullFormData['type'],
                    'name'                          =>  $fullFormData['business_name'],
                    'description'                   =>  $fullFormData['description'],
                    'phone_code'                    =>  $fullFormData['loanApplicantBusinessCode'],
                    'phone_number'                  =>  $fullFormData['loanApplicantBusinessPhone'],
                    'website'                       =>  $fullFormData['website'],
                    'email'                         =>  $fullFormData['loanApplicantBusinessEmail'],
                    'owner_first_name'              =>  $fullFormData['owner_first_name'],
                    'owner_last_name'               =>  $fullFormData['owner_last_name'],
                    'npwp_number'                   =>  $fullFormData['business_npwp_number']??'',
                    'npwp_image'                    =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['npwp_image']),// $fullFormData['loanApplicantBusinessNpwpImage'],
                    'average_sales'                 =>  $fullFormData['average_sales'],
                    'establish_in'                  =>  date('Y-m-d',strtotime($fullFormData['establish_in'])),
                    'number_of_employee'            =>  $fullFormData['number_of_employee'],
                    'bank_statement_image'          =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['bank_statement_image']), //$fullFormData['loanApplicantBankStatement'],
                    'ownership_percentage'          =>  $fullFormData['ownership_percentage'],//' $fullFormData['ownership_percentage'],
                    'category'                      =>  $fullFormData['category'],
                    'siup_number'                   =>  $fullFormData['siup_number'],
                    'license_image'                 =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['license_image']), //$fullFormData['loanApplicantBusinessLicenceImage'],

                ]);

                $loanApplicantBusinessAddress = LoanApplicantBusinessAddress::Create([
                    'applicant_id'                  =>  $loanApplicantDetails->id,
                    'applicant_business_id'         =>  $loanApplicantBusiness->id,
                    'address1'                      =>  $fullFormData['business_address1'],
                    'address2'                      =>  $fullFormData['business_address2'],
                    'district'                      =>  $fullFormData['business_district'],
                    'sub_district'                  =>  $fullFormData['business_sub_district'],
                    'provinces_id'                  =>  $fullFormData['business_provinces_id'],
                    'other_provinces'               =>  $fullFormData['business_other_provinces'],
                    'city_id'                       =>  $fullFormData['business_city_id'],
                    'other_city'                    =>  $fullFormData['business_other_city'],
                    'country_id'                    =>  $fullFormData['business_country'],
                    'postal_code'                   =>  $fullFormData['business_postal_code'],
                ]);

                $loanApplications = LoanApplication::Create([
                    'user_id'                       =>  Auth::id(),
                    'loan_provider_id'              =>  LoanProvider::KOINWORKS,
                    'company_id'                    =>  $company_id,
                    'applicant_id'                  =>  $loanApplicantDetails->id,
                    'loan_limit'                    =>  str_replace(',','',$fullFormData['loanAmount']),
                ]);

                $updateLoanApplication = LoanApplication::where('id', $loanApplications->id)->update([
                    'loan_application_number' => 'BLIMIT-' . $loanApplications->id,
                ]);

                $application    = $loanApplications;
                $newEntry       = true;
            });
            /*************************end: Loan application transaction*******************/

        }else{
            $applicationId = LoanApplicant::where(['user_id'=>Auth::id(),'company_id'=>$company_id])->first();
             $loanApplicantDetails = ([
                    'company_id'                    =>  $company_id,
                    'user_id'                       =>  Auth::id(),
                    'loan_provider_id'              =>  LoanProvider::KOINWORKS,
                    'first_name'                    =>  $fullFormData['first_name'],
                    'last_name'                     =>  $fullFormData['last_name'],
                    'email'                         =>  $fullFormData['email'],
                    'phone_code'                    =>  $fullFormData['phone_code'],
                    'phone_number'                  =>  $fullFormData['phone_number'],
                    'ktp_nik'                       =>  $fullFormData['ktp_nik'],
                    'ktp_image'                     =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['ktp_image']),
                    'ktp_with_selfie_image'         =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['ktp_with_selfie_image']),
                    'gender'                        =>  $fullFormData['gender'],
                    'place_of_birth'                =>  $fullFormData['place_of_birth'],
                    'date_of_birth'                 =>  date('Y-m-d',strtotime($fullFormData['date_of_birth'])),
                    'marital_status'                =>  $fullFormData['marital_status'],
                    'religion'                      =>  $fullFormData['religion'],
                    'education'                     =>  $fullFormData['education'],
                    'occupation'                    =>  $fullFormData['occupation'],
                    'total_other_income'            =>  $fullFormData['total_other_income'],
                    'other_source_of_income'        =>  $fullFormData['other_source_of_income'],
                    'net_salary'                    =>  $fullFormData['net_salary'],
                    'my_position'                   =>  $fullFormData['myPosition'],
                    'family_card_image'             =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['family_card_image']),
                    'first_account_created_at'      =>  auth()->user()->created_at
                ]);
                LoanApplicant::whereId($applicationId->id)->update($loanApplicantDetails);

                $loanApplicantDetails1 = ([
                    'relationship_with_borrower'    =>  $fullFormData['relationship_with_borrower'],
                    'ktp_image'                     =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['other_ktp_image']),
                    'phone_code'                    =>  $fullFormData['other_phone_code'],
                    'phone_number'                  =>  $fullFormData['other_phone_number'],
                    'ktp_nik'                       =>  $fullFormData['other_ktp_nik']??'',
                    'first_name'                    =>  $fullFormData['other_first_name'],
                    'last_name'                     =>  $fullFormData['other_last_name'],
                    'email'                         =>  $fullFormData['other_email']??'',
                ]);
                LoanApplicantSpouse::where('applicant_id',$applicationId->id)->update($loanApplicantDetails1);

                $loanApplicantAddress = ([
                    'name'                          =>  $fullFormData['loanApplicantAddressName'],
                    'address_line1'                 =>  $fullFormData['address_line1'],
                    'address_line2'                 =>  $fullFormData['address_line2'],
                    'sub_district'                  =>  $fullFormData['sub_district'],
                    'district'                      =>  $fullFormData['district'],
                    'country_id'                    =>  $fullFormData['country'],
                    'provinces_id'                  =>  $fullFormData['provinces_id'],
                    'other_provinces'               =>  $fullFormData['other_provinces'],
                    'city_id'                       =>  $fullFormData['city_id'],
                    'other_city'                    =>  $fullFormData['other_city'],
                    'has_live_here'                 =>  $fullFormData['has_live_here'],
                    'home_ownership_status'         =>  $fullFormData['home_ownership_status'],
                    'duration_of_stay'              =>  $fullFormData['duration_of_stay'],
                    'postal_code'                   =>  $fullFormData['loanApplicantPostalCode'],

                ]);
                LoanApplicantAddress::where('applicant_id',$applicationId->id)->update($loanApplicantAddress);

                $loanApplicantBusiness = ([
                    'type'                          =>  $fullFormData['type'],
                    'name'                          =>  $fullFormData['business_name'],
                    'description'                   =>  $fullFormData['description'],
                    'phone_code'                    =>  $fullFormData['loanApplicantBusinessCode'],
                    'phone_number'                  =>  $fullFormData['loanApplicantBusinessPhone'],
                    'website'                       =>  $fullFormData['website'],
                    'email'                         =>  $fullFormData['loanApplicantBusinessEmail'],
                    'owner_first_name'              =>  $fullFormData['owner_first_name'],
                    'owner_last_name'               =>  $fullFormData['owner_last_name'],
                    'npwp_number'                   =>  $fullFormData['business_npwp_number']??'',
                    'npwp_image'                    =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['npwp_image']),// $fullFormData['loanApplicantBusinessNpwpImage'],
                    'average_sales'                 =>  $fullFormData['average_sales'],
                    'establish_in'                  =>  date('Y-m-d',strtotime($fullFormData['establish_in'])),
                    'number_of_employee'            =>  $fullFormData['number_of_employee'],
                    'bank_statement_image'          =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['bank_statement_image']), //$fullFormData['loanApplicantBankStatement'],
                    'ownership_percentage'          =>  $fullFormData['ownership_percentage'],//' $fullFormData['ownership_percentage'],
                    'category'                      =>  $fullFormData['category'],
                    'siup_number'                   =>  $fullFormData['siup_number'],
                    'license_image'                 =>  setFinalPathForLoanApplication(Auth::id(),$company_id,$fullFormData['license_image']), //$fullFormData['loanApplicantBusinessLicenceImage'],

                ]);
                LoanApplicantBusiness::where('applicant_id',$applicationId->id)->update($loanApplicantBusiness);

                $loanApplicantBusinessAddress = ([
                    'address1'                      =>  $fullFormData['business_address1'],
                    'address2'                      =>  $fullFormData['business_address2'],
                    'district'                      =>  $fullFormData['business_district'],
                    'sub_district'                  =>  $fullFormData['business_sub_district'],
                    'provinces_id'                  =>  $fullFormData['business_provinces_id'],
                    'other_provinces'               =>  $fullFormData['business_other_provinces'],
                    'city_id'                       =>  $fullFormData['business_city_id'],
                    'other_city'                    =>  $fullFormData['business_other_city'],
                    'country_id'                    =>  $fullFormData['business_country'],
                    'postal_code'                   =>  $fullFormData['business_postal_code'],
                ]);
                LoanApplicantBusinessAddress::where('applicant_id',$applicationId->id)->update($loanApplicantBusinessAddress);

                $updateLoanApplication = LoanApplication::where('id', $applicationId->id)->update([
                    'loan_limit' => str_replace(',','',$fullFormData['loanAmount']),
                ]);
        }
        if (!empty($application)) {
            $destinationPath1 = config('settings.koinworks_temp_folder');
            $temp_folder = $destinationPath1 . Auth::id();

            $destinationPathFinal= config('settings.koinworks_confirm_folder');
            $final_folder = $destinationPathFinal .$company_id;
            if ($newEntry) {

                //move files to final folder
                Storage::move($temp_folder, $final_folder);

            }
            $result = $this->createLimit($application->id);

            if ($result['status']==200 && isset($result['data'])){
                //Redis::del('data'.Auth::id());
                /**
                credit mail send process
                 */
                $email = $fullFormData['loanApplicantBusinessEmail'];

                $data = LoanApplication::with('user:id,firstname,lastname')->where('id', $application->id)->first(['id','loan_application_number','user_id','senctioned_amount','loan_limit','created_at']);
                dispatch(new CreditApplicationJob($data,$email,'Requested',Auth::user()->default_company));

                /** Begin: buyer and admin loan apply notification */
                buyerNotificationInsert(Auth::user()->id, "Limit Apply", "limit_apply", 'limit', $data->id, ['limit_number' => $data->loan_application_number, 'status' => 'Pending', 'updated_by' => Auth::user()->full_name ?? '', 'icons' => 'fa-gear']);
                $limitData = [
                    'user_activity' => "Limit Apply",
                    'translation_key' => "limit_apply",
                    'type_id' => $data->id,
                    'limit_number' => $data->loan_application_number,
                    'status' => 'Pending',
                    'updated_by' => Auth::user()->full_name ?? '',
                    'user_id' => Auth::user()->id
                ];
                (new NotificationController)->addLimitNotification($limitData);
                /** End: buyer and admin loan apply notification */


                return response()->json(['success' => true, 'message' => __('profile.credit_applied'), 'data' => Crypt::encrypt($application->id)]);
            }
        }


        return response()->json(['success' => false, 'message' => __('profile.something_went_wrong')]);
    }

    /**
     * Create Limit Koinworks API call
     *
     * @param $id
     * @return array|Response
     */
    public function createLimit($id)
    {

        $application = LoanApplication::find($id);

        if (!empty($application->provider_application_id)){
            return [
                "status"=>200,
                "message"=> [
                        "en"=> "Already limit created",
                        "id"=> "Sudah batas dibuat"
                ],
                "data"  =>  $application->provider_application_id
            ];
        }
        $applicant = $application->applicant()->first();
        $applicantAddress = $applicant->loanApplicantAddress()->first();
        $applicantSpouse = $applicant->loanApplicantSpouse()->first();
        $applicantBusiness = $applicant->loanApplicantBusiness()->first();
        $applicantBusinessAddress = $applicantBusiness->loanApplicantBusinessAddress()->first();
        $payload = [
            "limit" => (int)$application->loan_limit,
            "personal" => [
                "fullname"                  => (string)$applicant->full_name,
                "ktpNIK"                    => (string)$applicant->ktp_nik,
                "ktpImage"                  => (string)url($applicant->ktp_image),
                "ktpWithSelfieImage"        => (string)url($applicant->ktp_with_selfie_image),
                "familyCardImage"           => (string)url($applicant->family_card_image),
                "gender"                    => (string)$applicant->gender_name,
                "placeOfBirth"              => (string)$applicant->place_of_birth,
                "dateOfBirth"               => (string)$applicant->date_of_birth,
                "maritalStatus"             => (string)$applicant->marital_status_name,
                "religion"                  => (string)$applicant->religion_name,
                "education"                 => (string)$applicant->education,
                "ocupation"                 => (string)$applicant->occupation,
                "totalOtherIncome"          => (string)$applicant->total_other_income,
                "otherSourceOfIncome"       => "",//$applicant->other_source_income_name,
                "netSalary"                 => (int)$applicant->net_salary,
                "myPosition"                => (string)$applicant->my_position_name,
                /* applicant addresses */
                "address" => [
                    "address"               => (string)($applicantAddress->address_line1.' '.$applicantAddress->address_line2),
                    "postalCode"            => (string)$applicantAddress->postal_code,
                    "subDistrict"           => (string)$applicantAddress->sub_district,
                    "district"              => (string)$applicantAddress->district,
                    "city"                  => (string)($applicantAddress->city_id > 0 ? $applicantAddress->city->name : $applicantAddress->other_city),
                    "province"              => (string)($applicantAddress->provinces_id > 0 ? $applicantAddress->state->name : $applicantAddress->other_provinces),
                    "country"               => (string)($applicantAddress->country->name??'Indonesia'),
                    "hasLiveHere"           => ($applicantAddress->has_live_here==1)?true:false,
                    "homeOwnershipStatus"   => (string)$applicantAddress->home_ownership_status_name,
                    "durationOfStay"        => (string)$applicantAddress->duration_of_stay
                ],
                /* applicant spouse */
                "spouse" => [
                    "fullName"                  => (string)$applicantSpouse->full_name,
                    "email"                     => (string)$applicantSpouse->email,
                    "relationshipWithBorrower"  => (string)$applicantSpouse->relationship_with_borrower_name,
                    "KtpImage"                  => (string)url($applicantSpouse->ktp_image),
                    "phoneArea"                 => (string)$applicantSpouse->phone_code,
                    "phoneNumber"               => (string)$applicantSpouse->phone_number
                ]
            ],
            /* applicant business */
            "business" => [
                "type"                      => (string)$applicantBusiness->type_name,
                "name"                      => (string)$applicantBusiness->name,
                "description"               => (string)$applicantBusiness->description,
                "website"                   => (string)$applicantBusiness->website,
                "email"                     => (string)$applicantBusiness->email,
                "phoneArea"                 => (string)$applicantBusiness->phone_code,
                "phoneNumber"               => (string)$applicantBusiness->phone_number,
                "ownerFullname"             => (string)($applicantBusiness->owner_first_name.' '.$applicantBusiness->owner_last_name),
                "npwpImage"                 => (string)url($applicantBusiness->npwp_image),
                "licenseImage"              => (string)url($applicantBusiness->license_image),
                "averageSales"              => (int)$applicantBusiness->average_sales,
                "establisedIn"              => (string)$applicantBusiness->establish_in,
                "numberOfEmployee"          => (string)'1 - 50',//$applicantBusiness->number_of_employee_name,
                "bankStatementImage"        => (string)url($applicantBusiness->bank_statement_image),
                "ownershipPercentage"       => (int)$applicantBusiness->ownership_percentage,
                "category"                  => (string)$applicantBusiness->loanBusinessCategory->name,
                "siupNumber"                => (string)$applicantBusiness->siup_number,
                "address" => [
                    "address"               => (string)($applicantBusinessAddress->address1.' '.$applicantBusinessAddress->address2),
                    "postalCode"            => (string)$applicantBusinessAddress->postal_code,
                    "subDistrict"           => (string)$applicantBusinessAddress->sub_district,
                    "district"              => (string)$applicantBusinessAddress->district
                ]
            ],
        ];


        $firstDayOfMonth = strtotime('first day of this month', time());

        $payload['monthlyRevenues'] = $this->generateRevenue($applicant,$firstDayOfMonth);

        $previous3Month = strtotime("-3 month",$firstDayOfMonth);
        if ($previous3Month<strtotime($applicant->first_account_created_at)) {
            $payload["firstAccountCreatedAt"] = (string)date('Y-m-d',$previous3Month);
        }else{
            $payload["firstAccountCreatedAt"] = (string)date('Y-m-d',strtotime($applicant->first_account_created_at));
        }

        $kwobj = new KoinWorkController;

        /*****************begin: Call Credit Limit API*********/
        $loanSystemApi = LoanProviderApiResponse::bootApiActivity(LoanProvider::KOINWORKS, $applicant->user_id, $applicant->id, LoanProviderApiResponse::APIRequest, ['request_data' => json_encode($payload)]); //Boot API Request

        $returnData = $kwobj->createLimit($payload);


        $loanSystemApi = LoanProviderApiResponse::bootApiActivity(LoanProvider::KOINWORKS, $applicant->user_id, $applicant->id, LoanProviderApiResponse::APIResponse, ['id' => $loanSystemApi->id,'request_data' => json_encode($returnData), 'response_code' => $returnData['status']]); //Boot API Response
        /*****************end: Call Credit Limit API*********/

        if ($returnData['status']==200 && isset($returnData['data'])){
            $application->provider_user_id = $returnData['data']['userID'];
            $application->provider_application_id = $returnData['data']['id'];
            $application->reserved_amount = $returnData['data']['reserveAmount'];
            
            if (isset($returnData['data']['isExpired']) && $returnData['data']['isExpired']==true) {
                $application->expire_date = date('Y-m-d', strtotime("+6 months"));
            }
            $application->status = $returnData['data']['status'];
            $application->save();


        }
        return $returnData;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreditRequest $request)
    {
        $fullFormData =  json_decode(Redis::get('data'.Auth::id()),true);
       if($request->ajax()){

           $authId = Auth::id();
           $redisData = 'data'.$authId;
           if(!empty( $fullFormData)){
            $prevData=Redis::set($redisData, json_encode($fullFormData));
           }
            if(request()->form_type=="loanApplicantLimit"){
                $data=[
                    'loanAmount' => $request->loanAmount,

                ];
                if(!empty($fullFormData)){
                    $currentData=array_merge($fullFormData,$data);
                    Redis::set($redisData, json_encode($currentData));
                }else{
                    Redis::set($redisData, json_encode($data));
                }

            }elseif(request()->form_type=="loanApplicantDetails"){
                $prevData =  Redis::get($redisData);

                $destinationPath1 = config('settings.koinworks_temp_folder');
                $folder = $destinationPath1 . Auth::id();

               if (!Storage::exists($folder)) {
                    Storage::makeDirectory($folder, 0775, true, true);
                }

                if(!empty($fullFormData) && $request->file('ktpImage')==NULL){
                    $ktpImagefile = $fullFormData['ktp_image'];
                }else{
                    if(isset($fullFormData['ktp_image']) && $fullFormData['ktp_image']!='') {
                    Storage::delete($fullFormData['ktp_image']);
                    }
                    if (Storage::exists($request->file('ktpImage')->storeAs($folder,'ktpImage'))) {
                        Storage::delete($request->file('ktpImage')->storeAs($folder,'ktpImage'));
                    }

                    $ktpImagefileExt = $request->file('ktpImage')->extension();
                    $ktpImagefile = $request->file('ktpImage')->storeAs($folder,'ktpImage'.Carbon::now()->format('ymdhis').'.'.$ktpImagefileExt);
                    //$ktpImageWithSelfifile='check';
                }

                if(!empty($fullFormData['ktp_with_selfie_image']) && $request->file('ktpSelfiImage')==NULL){
                    $ktpImageWithSelfifile = $fullFormData['ktp_with_selfie_image'];
                }else{
                    if(isset($fullFormData['ktpSelfiImage']) && $fullFormData['ktpSelfiImage']!='') {
                        Storage::delete($fullFormData['ktpSelfiImage']);
                    }
                    if(Storage::exists($request->file('ktpSelfiImage')->storeAs($folder,'ktpSelfiImage'))) {
                        Storage::delete($request->file('ktpSelfiImage')->storeAs($folder,'ktpSelfiImage'));
                    }

                    $ktpImageWithSelfifileExt = $request->file('ktpSelfiImage')->extension();
                    $ktpImageWithSelfifile = $request->file('ktpSelfiImage')->storeAs($folder,'ktpSelfiImage'.Carbon::now()->format('ymdhis').'.'.$ktpImageWithSelfifileExt);
                }

                if(!empty($fullFormData['other_ktp_image']) && $request->file('otherKtpImage')==NULL){
                        $otherKtpImage = $fullFormData['other_ktp_image'];
                }else{
                        if(isset($fullFormData['otherKtpImage']) && $fullFormData['otherKtpImage']!='') {
                            Storage::delete($fullFormData['otherKtpImage']);
                        }
                        if(Storage::exists($request->file('otherKtpImage')->storeAs($folder,'otherKtpImage'))) {
                            Storage::delete($request->file('otherKtpImage')->storeAs($folder,'otherKtpImage'));
                        }
                        $otherKtpImageExt = $request->file('otherKtpImage')->extension();
                        $otherKtpImage = $request->file('otherKtpImage')->storeAs($folder,'otherKtpImage'.Carbon::now()->format('ymdhis').'.'.$otherKtpImageExt);
                }
                if(!empty($fullFormData['family_card_image']) && $request->file('familyCardImage')==NULL){
                    $familyCardImage = $fullFormData['family_card_image'];
                }else{
                    if(isset($fullFormData['familyCardImage']) && $fullFormData['familyCardImage']!='') {
                        Storage::delete($fullFormData['familyCardImage']);
                    }
                    if (Storage::exists($request->file('familyCardImage')->storeAs($folder,'familyCardImage'))) {
                        Storage::delete($request->file('familyCardImage')->storeAs($folder,'familyCardImage'));
                    }

                    $familyCardImageExt = $request->file('familyCardImage')->extension();
                    $familyCardImage = $request->file('familyCardImage')->storeAs($folder,'familyCardImage'.Carbon::now()->format('ymdhis').'.'.$familyCardImageExt);
                }

                $data=[
                    'company_name' => $request->company_name,
                    'loan_provider_name' => $request->loan_provider_name,
                    'first_name' => $request->firstName,
                    'last_name' =>$request->lastName,
                    'email' => $request->email,
                    'phone_code' =>$request->phoneCode,
                    'phone_number' =>$request->phoneNumber,
                    'ktp_nik'=> $request->ktpNik,
                    'ktp_image' => $ktpImagefile,//$request->ktp_image,
                    'ktp_with_selfie_image' => $ktpImageWithSelfifile,//$request->ktp_with_selfie_image,
                    'gender' => $request->gender,
                    'place_of_birth' => $request->placeOfBirth,
                    'date_of_birth' => $request->dateOfBirth,
                    'marital_status' => $request->maritalStatus,
                    'religion' => $request->religion,
                    'education' => $request->education,
                    'occupation' => $request->occupation,
                    'total_other_income' => $request->otherIncome,
                    'other_source_of_income' =>$request->otherSourceOfIncome,
                    'net_salary' => $request->netSalary,
                    'myPosition' => $request->myPosition,
                    'relationship_with_borrower' => $request->relationshipWithBorrower,
                    'other_ktp_nik' => $request->otherKtpNik,
                    'other_ktp_image' => $otherKtpImage,//$request->otherKtpImage,
                    'other_phone_code' => $request->otherMemberCode,
                    'other_phone_number' => $request->otherMemberPhone,
                    'other_first_name' => $request->otherFirstName,
                    'other_last_name' => $request->otherLastName,
                    'other_email' => $request->otherMemberEmail,
                    'family_card_image' =>$familyCardImage,
                ];
                if(!empty($fullFormData)){
                $currentData=array_merge(json_decode($prevData,true),$data);
                }
                else{
                    $currentData=array_merge($data,json_decode($prevData,true));
                }
                $data= Redis::set($redisData, json_encode($currentData));
            }elseif(request()->form_type=='loanApplicantAddress'){
                $prevData =  Redis::get($redisData);
                $data=[
                    'loanApplicantAddressName' => $request->loanApplicantAddressName,
                    'address_line1' => $request->loanApplicantAddressLine1,
                    'address_line2' => $request->loanApplicantAddressLine2,
                    'loanApplicantPostalCode' => $request->loanApplicantPostalCode,
                    'sub_district' => $request->subDistrict,
                    'district' => $request->district,
                    'city_id' =>  $request->cityId,
                    'other_city' =>  $request->city,
                    'country' => $request->loanApplicantCountryId,
                    'provinces_id' => $request->provincesId,
                    'other_provinces' => $request->state,
                    'has_live_here' => $request->loanApplicantHasLivedHere,
                    'home_ownership_status' => $request->loanApplicanthomeOwnershipStatus,
                    'duration_of_stay' => $request->loanApplicantDurationOfStay,
                ];
                    if(!empty($fullFormData)){
                    $currentData=array_merge(json_decode($prevData,true),$data);
                    }
                    else{
                        $currentData=array_merge($data,json_decode($prevData,true));
                    }
                $data= Redis::set($redisData, json_encode($currentData));
            }elseif(request()->form_type=='loanApplicantBusiness'){


                $destinationPath1 = config('settings.koinworks_temp_folder');
                $folder = $destinationPath1 . Auth::id();

                if (!Storage::exists($folder)) {
                    Storage::makeDirectory($folder, 0775, true, true);
                }
                if(!empty($fullFormData['npwp_image']) && $request->file('loanApplicantBusinessNpwpImage')==NULL){
                    $loanApplicantBusinessNpwpImage = $fullFormData['npwp_image'];
                }else{
                    if(isset($fullFormData['loanApplicantBusinessNpwpImage']) && $fullFormData['loanApplicantBusinessNpwpImage']!='') {
                        Storage::delete($fullFormData['loanApplicantBusinessNpwpImage']);
                    }
                    if (Storage::exists($request->file('loanApplicantBusinessNpwpImage')->storeAs($folder,'loanApplicantBusinessNpwpImage'))) {
                    Storage::delete($request->file('loanApplicantBusinessNpwpImage')->storeAs($folder,'loanApplicantBusinessNpwpImage'));
                    }

                    $loanApplicantBusinessNpwpExt = $request->file('loanApplicantBusinessNpwpImage')->extension();
                    $loanApplicantBusinessNpwpImage = $request->file('loanApplicantBusinessNpwpImage')->storeAs($folder,'loanApplicantBusinessNpwpImage'.Carbon::now()->format('ymdhis').'.'.$loanApplicantBusinessNpwpExt);
                }
                if(!empty($fullFormData['bank_statement_image']) && $request->file('loanApplicantBankStatement')==NULL){
                    $loanApplicantBankStatement = $fullFormData['bank_statement_image'];
                }else{
                    if(isset($fullFormData['loanApplicantBankStatement']) && $fullFormData['loanApplicantBankStatement']!='') {
                        Storage::delete($fullFormData['loanApplicantBankStatement']);
                    }
                    if(Storage::exists($request->file('loanApplicantBankStatement')->storeAs($folder,'loanApplicantBankStatement'))) {
                    Storage::delete($request->file('loanApplicantBankStatement')->storeAs($folder,'loanApplicantBankStatement'));
                    }
                    $loanApplicantBankStatementExt = $request->file('loanApplicantBankStatement')->extension();
                    $loanApplicantBankStatement = $request->file('loanApplicantBankStatement')->storeAs($folder,'loanApplicantBankStatement'.Carbon::now()->format('ymdhis').'.'.$loanApplicantBankStatementExt);
                }
                if(!empty($fullFormData['license_image']) && $request->file('loanApplicantBusinessLicenceImage')==NULL){
                    $loanApplicantBusinessLicenceImage = $fullFormData['license_image'];
                }else{
                    if(isset($fullFormData['loanApplicantBusinessLicenceImage']) && $fullFormData['loanApplicantBusinessLicenceImage']!='') {
                        Storage::delete($fullFormData['loanApplicantBusinessLicenceImage']);
                    }
                    if (Storage::exists($request->file('loanApplicantBusinessLicenceImage')->storeAs($folder,'loanApplicantBusinessLicenceImage'))) {
                        Storage::delete($request->file('loanApplicantBusinessLicenceImage')->storeAs($folder,'loanApplicantBusinessLicenceImage'));
                    }
                    $loanApplicantBusinessLicenceImageExt = $request->file('loanApplicantBusinessLicenceImage')->extension();
                    $loanApplicantBusinessLicenceImage = $request->file('loanApplicantBusinessLicenceImage')->storeAs($folder,'loanApplicantBusinessLicenceImage'.Carbon::now()->format('ymdhis').'.'.$loanApplicantBusinessLicenceImageExt);
                }
                $prevData =  Redis::get($redisData);
                $data=[

                    'type'                          => $request->loanApplicantBusinessType,
                    'business_name'                 => $request->loanApplicantBusinessName,
                    'description'                   => $request->loanApplicantBusinessDescription,
                    'website'                       => $request->loanApplicantBusinessWebsite,
                    'loanApplicantBusinessEmail'    => $request->loanApplicantBusinessEmail,
                    'loanApplicantBusinessCode'     => $request->loanApplicantBusinessCode,
                    'loanApplicantBusinessPhone'    => $request->loanApplicantBusinessPhone,
                    'owner_first_name'              => $request->loanApplicantBusinessFirstName,
                    'owner_last_name'               => $request->loanApplicantBusinessLastName,
                    'business_npwp_number'          => Auth::user()->company->npwp,
                    'npwp_image'                    => $loanApplicantBusinessNpwpImage,
                    'average_sales'                 => $request->loanApplicantBusinessAverageSales,
                    'establish_in'                  => $request->loanApplicantBusinessEstablish,
                    'number_of_employee'            => $request->loanApplicantBusinessNoOfEmployee,
                    'bank_statement_image'          => $loanApplicantBankStatement, //$request->loanApplicantBankStatement,
                    'ownership_percentage'          => $request->loanApplicantOwnership,
                    'category'                      => $request->loanApplicantCategory,
                    //'relationship_with_borrower'    => $request->loanApplicantRelationshipWithBorrower,
                    'siup_number'                   => Auth::user()->companies->registrantion_NIB,
                    'license_image'                 => $loanApplicantBusinessLicenceImage, //$request->loanApplicantBusinessLicenceImage,

                ];

                    if(!empty($fullFormData)){
                    $currentData=array_merge(json_decode($prevData,true),$data);
                    }
                    else{
                    $currentData=array_merge($data,json_decode($prevData,true));
                    }
                $data= Redis::set($redisData, json_encode($currentData));
            }elseif(request()->form_type=='loanApplicantBusinessAddress'){

                $prevData =  Redis::get($redisData);
                $data=[
                    'Loan_Applicant_Business_Id'=>$request->loanApplicantBusinessId,
                    'branch_name'=>$request->branchName,
                    'business_address1' => $request->loanBusinessAddressLine1,
                    'business_address2' => $request->loanBusinessAddressLine2,
                    'business_district' => $request->loanBusinessAddressDistrict,
                    'business_sub_district' => $request->loanBusinessAddressSubDistrict,
                    'business_provinces_id' => $request->loanBusinessAddressProvinces,
                    'business_other_provinces' => $request->state_business,
                    'business_city_id' => $request->loanBusinessAddressCity,
                    'business_other_city' => $request->city_business,
                    'business_country' => $request->loanBusinessAddressCountry,
                    'business_postal_code' => $request->loanBusinessAddressPostalCode,
                    'form_status' => true
                ];

                    if(!empty($fullFormData)){
                    $currentData=array_merge(json_decode($prevData,true),$data);
                    }
                    else{
                    $currentData=array_merge($data,json_decode($prevData,true));
                    }
                $data= Redis::set($redisData, json_encode($currentData));
            }

       }
    }
       /**
     *Downlaod images
     *
     * @param  \App\Models\LoanApplication  $loanApplications
     * @return \Illuminate\Http\Response
     */

    public function downloadAttachmentFile(Request $request) {

        $headers = array('Content-Type: image/*, application/pdf');
        $file = 'credit_apply/buyer/temp496/ktpImage';
        $headers = array('Content-Type: image/*, application/pdf');

        return Storage::download('/app/' . $file, '', $headers);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestLimitOTP(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $application = LoanApplication::where(['id'=>$id,'company_id'=>Auth::user()->default_company])->first(['applicant_id','user_id','provider_user_id']);
            if (empty($application)){
                return response()->json(array('success' => false, 'message' => __('profile.limit_application_not_found')));
            }
            $kwobj = new KoinWorkController;
            $returnData = $kwobj->requestLimitOTP($application->provider_user_id);
            LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$application->applicant_id,'user_id'=>$application->user_id,'response_code'=>$returnData['status'],'response_data'=>json_encode($returnData)]);

            if ($returnData['status']==200){
                return response()->json(array('success' => true, 'message' => __('profile.otp_sent_successfully')));
            }
            return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
        } catch (\Exception $e) {
            Log::critical('Code - 400 | ErrorCode:B011 - Credit Limit OTP Request');

            return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoanApplication  $loanApplications
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyLimitOTP(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $application = LoanApplication::where(['id'=>$id,'user_id'=>Auth::id()])->first();
            if (empty($application)){
                return response()->json(array('success' => false, 'message' => __('profile.limit_application_not_found')));
            }
            $code = implode('', $request->code);

            if (strlen($code)<6){
                return response()->json(array('success' => false, 'message' => __('admin.enter_valid_otp')));
            }
            $kwobj = new KoinWorkController;
            $returnData = $kwobj->verifyLimitOTP($application->provider_user_id,$code);
            LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$application->applicant_id,'user_id'=>$application->user_id,'response_code'=>$returnData['status'],'response_data'=>json_encode($returnData)]);

            if ($returnData['status']==200){
                $application->verify_otp = 1;
                $application->save();
                return response()->json(array('success' => true));
            }elseif ($returnData['status']==400){
                return response()->json(array('success' => false, 'message' => $returnData['message']['en']));
            }elseif ($returnData['status']==406){
                return response()->json(array('success' => false, 'message' => $returnData['message']['en']));
            }else {
                return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
            }
        } catch (\Exception $e) {
            Log::critical('Code - 400 | ErrorCode:B012 - Credit Limit OTP Verify');

            return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
        }
    }

    /**
     * Generate last 3 month revenue of company
     *
     * @param LoanApplicant $applicant
     * @return array
     */
    public function generateRevenue(LoanApplicant $applicant,$firstDayOfMonth)
    {
        $data = [];
        for ($i = -3; $i <= -1; $i++){
            $month = date('m', strtotime("$i month",$firstDayOfMonth));
            //get company wise orders
            $revenue = $applicant->company->orders()->join('order_tracks', function($join)
            {
                $join->on('orders.id', '=', 'order_tracks.order_id');
                $join->where('order_tracks.status_id',3);

            })->where('payment_status','!=',0)->whereMonth('order_tracks.created_at',$month)->sum('payment_amount');

            $monthlyRevenues = (int)(empty($revenue)?1:$revenue);

            $data[date('Ym', strtotime("$i month",$firstDayOfMonth))] = $monthlyRevenues;
            //save or update applicant revenue
            LoanApplicantRevenue::createOrUpdateApplicantRevenue([
                'applicant_id'=>$applicant->id,
                'company_id'=>$applicant->company_id,
                'monthly_date'=>date('Y-m-t', strtotime("$i month",$firstDayOfMonth)),
                'revenue'=>$monthlyRevenues
            ]);
        }
        return $data;
    }


    /**
     * Edit the specified resource in storage.

     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoanApplication  $loanApplications
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, LoanApplication $loanApplications)
    {
        $company_id = Auth::user()->default_company;
        $applicantId='';
        $application = LoanApplication::where(['user_id'=>Auth::id(),'company_id'=>$company_id])->first();
        if(!empty($application))
        {
            $applicantId=$application->id;
        }

        $fullFormData =  json_decode(Redis::get('data'.Auth::id()),true);
        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
        $country = CountryOne::find(CountryOne::DEFAULTCOUNTRY);
        Redis::set('data'.Auth::id(), json_encode($fullFormData));
        $maxCreditLimit = LoanApplication::MAX_CREDIT_LIMIT;
        $form_type=$request['f'];
        //return view('buyer.koinworks.index',$fullFormData);
        return view('buyer.koinworks.index',compact('states','country','maxCreditLimit','form_type','applicantId'),$fullFormData);
    }


    /**
     * after completed limit Rejection
     */
    public function creditReject(Request $request){
        $id = Crypt::decrypt($request->id);
        $loanApplication = LoanApplication::where('id',$id)->select('loan_application_number','loan_limit')->first();
        if (empty($loanApplication)){
            return redirect('dashboard');
        }
        return view('buyer/koinworks/creditlimit_reject',['loanApplication' => $loanApplication]);
    }

    /**
     * after completed limit process generate thanks page.
     */
    public function thankYou(Request $request){
        $id = Crypt::decrypt($request->id);
        $loanApplication = LoanApplication::where('id',$id)->select('loan_application_number','loan_limit','senctioned_amount')->first();
        if (empty($loanApplication)){
            return redirect('dashboard');
        }
        return view('buyer/koinworks/thankyou',['loanApplication' => $loanApplication]);
    }

    /**
     * after conform redirect otp page.
     */
    public function limitOtp(Request $request){

        $creditDetail = LoanApplication::getCreditDatail();

        if(!empty($creditDetail) && isset($creditDetail->provider_application_id)){

            return redirect()->route('dashboard');

        } else {
            $existingApplication = LoanApplication::where('company_id', Auth::user()->default_company);

            if ($existingApplication->count()==0 && $existingApplication->count()==null ) {
                return redirect()->route('settings.credit.apply.step1');
            }
            $application = LoanApplication::find($existingApplication->first()->id);
            $businessNumber = '+'.$application->loanApplicantBusiness()->first()->phone_code.' '.$application->loanApplicantBusiness()->first()->phone_number;
            return View::make('buyer.koinworks.limit_otp')->with(compact('businessNumber'));
        }
    }

    /**
     * Show the form for uploade contract when limit is morethen 50m.
     * @param  $ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function limitContract(Request $request){
        $creditDetail = LoanApplication::getCreditDatail();

        if(!empty($creditDetail) && isset($creditDetail->provider_application_id)){

            return redirect()->route('dashboard');

        } else {
            $existingApplication = LoanApplication::where('company_id', Auth::user()->default_company);
            if ($existingApplication->count()==0 && $existingApplication->count()==null ) {
                return redirect()->route('settings.credit.apply.step1');
            }
            $applicant = LoanApplicant::find($existingApplication->first()->applicant_id);
            $contracts = $applicant->contracts;
            return View::make('buyer.koinworks.contract')->with(compact('contracts'));
        }
    }

    /*
     * reupload document view
     * */
    public function reuploadDocument(Request $request){
        try {
            $id = Crypt::decrypt($request->id);
            $loanApplication = LoanApplication::where(['id'=>$id,'status'=>'0ff092ed-86c8-49f2-b8bb-7384abbba233'])->first();// check if status is return_to_user
            if (empty($loanApplication)){
                return redirect('dashboard');
            }
            return view('buyer.koinworks.reupload_documents',['loanApplication' => $loanApplication]);
        } catch (\Exception $e) {
            Log::critical('Code - 400 | ErrorCode:B014 - Credit Limit Document Reupload');

            return redirect('dashboard');
        }
    }

    /*
     * reupload document
     * */
    public function limitReuploadDocument(ReuploadDocRequest $request){
        try {
            $id = Crypt::decrypt($request->id);
            $application = LoanApplication::where(['id'=>$id,'status'=>'0ff092ed-86c8-49f2-b8bb-7384abbba233'])->first();// check if status is return_to_user
            if (empty($application)){
                return response()->json(array('success' => false, 'message' => __('profile.limit_application_not_found')));
            }
            $destinationPath1 = config('settings.koinworks_confirm_folder');
            $companyId = Auth::user()->default_company;
            $userId = Auth::user()->id;
            $folder = $destinationPath1 . $companyId;

            $applicant = LoanApplicant::reuploadDoucment($application->applicant_id,$request,$folder,$userId,$companyId);
            $loanApplicantBusiness = LoanApplicantBusiness::reuploadDoucment($application->applicant_id,$request,$folder,$userId,$companyId);
            $loanApplicantSpouse = LoanApplicantSpouse::reuploadDoucment($application->applicant_id,$request,$folder,$userId,$companyId);
            //Spouse ktp is only required for loan amount >500 m
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
            Log::critical('Code - 400 | ErrorCode:B013 - Credit Limit OTP Request');

            return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
        }
    }

    public function creditLimitStatus($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $existingApplication = LoanApplication::select('id', 'loan_application_number','user_id', 'loan_limit', 'senctioned_amount','status', 'provider_user_id')->findOrFail($id);
            $limitStatus = LoanApplication::STATUS[$existingApplication->status];
            return view('buyer.koinworks.credit_limit_status')->with(compact('existingApplication', 'limitStatus'));

        } catch (\Exception $th) {
            Log::critical('Code - 400 | ErrorCode:B015 - Credit Limit Status');

            abort(404);
        }
    }

    /**
     * Upload signed contract.
     *
     * @param  \App\Models\LoanApplication  $loanApplications
     * @return \Illuminate\Http\JsonResponse
     */
    public function limitContractUpload(Request $request)
    {
        try {
            $applicationId = Crypt::decrypt($request->applicationId);

            if($request->hasfile('uploadContract'))
            {
                $filepath = null;
                $filename = Str::random(10) . '_' . time() . '_contract_' . $request->file('uploadContract')->getClientOriginalName();
                $filepath = $request->file('uploadContract')->storeAs('credit_apply/buyers/companies'.Auth::user()->default_company.'/contract', $filename, 'public');
                LoanApplication::where(['id' => $applicationId])->update(['uploaded_contracts'=>$filepath]);

                $application = LoanApplication::where(['id'=>$applicationId,'user_id'=>Auth::id()])->first();

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
            }
            else{
                return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
            }
        } catch (\Exception $e) {
            Log::critical('Code - 400 | ErrorCode:B016 - Credit Limit Contract Upload');

            return response()->json(['success' => false,'msgType'=>'error','message'=>__('admin.something_error_message')]);
        }
    }

    public function getUpdateUserLimit($applicationId)
    {
        try {
            $id = $applicationId;
            $applicationId = Crypt::decrypt($applicationId);

            $application = LoanApplication::where('id', $applicationId)->first(['user_id','provider_user_id','status','status_name','verify_otp','senctioned_amount','loan_limit','applicant_id','provider_application_id']);
            /*****************begin: get user Credit Limit API*********/
            $kwobj = new KoinWorkController;
            $limitResponse = $kwobj->userLimit($application->provider_user_id);

            LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$application->applicant_id,'user_id'=>$application->user_id,'response_code'=>$limitResponse['status'],'response_data'=>json_encode($limitResponse)]);
            /*****************begin: get user Credit Limit API*********/

            if(isset($limitResponse['status']) && isset($limitResponse['data']['status']) && $limitResponse['status'] == 200){
                $statusName = $limitResponse['data']['limitStatusValue'];
                $update = LoanApplication::where('id', $applicationId)->update(['status' => $limitResponse['data']['status'],'status_name'=>$statusName]);
                $url = null;
                $msgType = '';
                $message = '';
                $data = LoanApplication::with('user:id,firstname,lastname')->where('id', $applicationId)->first(['id','loan_application_number','user_id','loan_limit','senctioned_amount','created_at','applicant_id','reserved_amount','remaining_amount']);
                $email = $data->loanApplicantBusiness->email;
                if ($statusName == "Waiting For Documents"){
                    //Update contractURL
                    $kwobj2 = new KoinWorkController;
                    $getLimitResponse = $kwobj2->getLimit($application->provider_application_id);
                    if(isset($getLimitResponse['data'][0]['contractURL'])){
                        LoanApplicant::where('id', $application->applicant_id)->whereNull('contracts')->update(['contracts' => $getLimitResponse['data'][0]['contractURL']]);
                    }
                    //Update contractURL

                    if ($application->verify_otp!=1) {
                        $kwobj3 = new KoinWorkController;
                        $returnData = $kwobj3->requestLimitOTP($application->provider_user_id);
                        LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$application->applicant_id,'user_id'=>$application->user_id,'response_code'=>$returnData['status'],'response_data'=>json_encode($returnData)]);
                        $url = route('settings.limit-otp',$id);
                    }elseif ($application->loan_limit>FIFTY_MILLION){// && $limitResponse['data']['isNeedReuploadContract']==true
                        $url = route('settings.credit-limit-contract',$id);
                    }
                }elseif ($statusName == "Return to User"){
                    $url = route('settings.limit-reupload-document',$id);
                }elseif ($statusName == "Rejected"){
                    DB::enableQueryLog();
                    $checkMailSend = LoanEmail::where(['application_id'=>$applicationId,'status'=>'Rejected','type'=>'CREDIT'])->get();
                    $emailCount = $checkMailSend->count();
                    if($emailCount==0){
                        dispatch(new CreditApplicationJob($data,$email,'Rejected',Auth::user()->default_company));
                    }
                    $url = route('settings.credit-reject',$id);
                }elseif ($statusName == "Approved"){
                     //save reserved amount
                     $data->senctioned_amount = $limitResponse['data']['amount'];
                     $data->remaining_amount = $limitResponse['data']['remainingAmount'];
                    if ($data->loan_limit>FIFTY_MILLION) {
                        $data->expire_date = $limitResponse['data']['expiredAt'];
                    }
                    $data->save();
                    $checkMailSend = LoanEmail::where(['application_id'=>$id,'status'=>'Approved','type'=>'CREDIT'])->get();
                    $emailCount = $checkMailSend->count();
                    if($emailCount==0){
                        dispatch(new CreditApplicationJob($data,$email,'Approved',Auth::user()->default_company));
                        /** Begin: buyer and admin limit approved notification */
                            buyerNotificationInsert(Auth::user()->id, "Limit Approved", "limit_approved", 'limit', $data->id, ['limit_number' => $data->loan_application_number, 'status' => 'Approved', 'updated_by' => Auth::user()->full_name ?? '', 'icons' => 'fa-gear']);
                            $limitData = [
                                'user_activity' => "Limit Approved",
                                'translation_key' => "limit_approved",
                                'type_id' => $data->id,
                                'limit_number' => $data->loan_application_number,
                                'status' => 'Approved',
                                'updated_by' => Auth::user()->full_name ?? '',
                                'user_id' => Auth::user()->id
                            ];
                            (new NotificationController)->addLimitNotification($limitData);
                        /** End: buyer and admin limit approved notification */
                    }
                    if($application->loan_limit>FIFTY_MILLION){
                        //credit page
                        $url = route('dashboard').'?section=creditSection';
                    }else{
                        $url = route('settings.credit-thank-you',$id);
                    }
                }
                return response()->json(['success' => true,'msgType'=>$msgType,'message'=>$message,'data'=>$limitResponse,'url'=>$url,'limit_status' => $statusName]);
            }
            return response()->json(['success' => false,'msgType'=>'error','message'=>__('admin.something_error_message')]);
        } catch (\Exception $e) {

            Log::critical('Code - 400 | ErrorCode:B017 - Update User Limit');

            return response()->json(['success' => false,'msgType'=>'error','message'=>__('admin.something_error_message')]);
        }
    }



}
