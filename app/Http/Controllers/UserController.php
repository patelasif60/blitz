<?php

namespace App\Http\Controllers;

use App\Http\Requests\Buyer\RolesPermission\EditUserPermissionRequest;
use App\Http\Requests\BuyerForgetPassword;
use App\Http\Requests\BuyerFormRequest;
use App\Http\Requests\BuyerLogInFormRequest;
use App\Http\Requests\BuyerResetPassRequest;
use App\Jobs\buyer\user\SendEmailCustomUserJob;
use App\Jobs\SendActivationMailToUserJob;
use App\Jobs\UserLoginWithSocialiteJob;
use App\Models\BlockedContact;
use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\InviteBuyer;
use App\Models\ModelHasCustomPermission;
use App\Models\OtherCharge;
use App\Models\PermissionsGroup;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\UserSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\ContactUsMailJob;
use App\Http\Requests\LoginOtpRequest;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Session;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivity;
use App\Models\SubscribedUser;
use App\Mail\SendActivationMailToUser;
use App\Models\CompanyUser;
use App\Models\UserCompanies;
use App\Models\ContactUs;
use App\Models\PreferredSupplier;
use App\Models\Settings;
use App\Models\SystemActivity;
use App\Models\SystemRole;
use Mail;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Http\Controllers\CompanyUserController;
use App\Twilloverify\TwilloService;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{
    protected $verify;
    /**
     * Create a new controller instance.
     *
     * @param Service $verify
    */
    public function __construct()
    {
        $this->verify = app('App\Twilloverify\TwilloService');
    }
    //login with otp frontend
    public function loginWithOtp(LoginOtpRequest $request)
    {
        return $this->loginwitmobile($request,2);
    }
    //login with otp backend
    public function supplierloginWithOtp(LoginOtpRequest $request)
    {
        return $this->loginwitmobile($request,3);
    }
    //login with otp
    public function loginwitmobile($request,$usertype)
    {

        $userData = User::where('mobile', $request->mobile)->where('is_delete',0);
        if($usertype == 2){
            $userData = $userData->where('role_id',$usertype)->get();
        }
        else{
            $userData = $userData->where('role_id','<>',2)->get();
        }
        if(count($userData) == 0){
            $request->session()->flash('status', __('signup.user_mobile_not_found'));
            if($usertype == 2){
                return redirect("/signin");
            }
            return redirect("/admin/login");
        }
        else{

            $ids = $userData->where('mobile_verified',0)->pluck('id')->toArray();
            User::whereIn('id',$ids)->update(['mobile_verified'=>1]);

            if(count($userData) == 1){
                Auth::login($userData->first());
                if($usertype == 2){
                    return redirect("/dashboard");
                }
                return redirect("/admin/dashboard");
            }
            if(count($userData) > 1){

                session()->put('userData', $userData);
                session()->put('usertype', $usertype);
                if($usertype == 2){
                   return redirect()->route('getbuyeremails');
                }
                return redirect()->route('getemails');
            }
        }
    }
    public function getEmails(Request $request){
         $userData = Session::get('userData');
         $usertype=Session::get('usertype');
        return view("/emails",compact('userData','usertype'));
    }
    //login with choosing email vai otp
    public function chooseemail($id){
        $encyId = $id;
        $id = Crypt::decrypt($id);
        $userData = User::find($id);
        return view("/password",compact('userData','encyId'));
    }
    public function login(BuyerLogInFormRequest $request)
    {
        if($request->ajax()){
            return true;
        }
        $credentials = $request->only('email', 'password');
        $userData = User::where('email', $request->email)->withTrashed()->first();

        if ($userData) {
            if ($userData->is_delete == 0 && !$userData->trashed()) {
                if ($userData->role_id == Role::BUYER) {
                        if (Auth::attempt($credentials)) {
                            if(!isset($request->group_id) && empty($request->group_id)) {
                                // START :: System log activity.
                                    User::bootSystemView(new User(), 'Buyer - SignIn', SystemActivity::LOGIN);
                                // END :: System log activity.
                                if(Auth::user()->hasPermissionTo('create buyer rfqs') || Auth::user()->hasPermissionTo('publish buyer orders') || Auth::user()->hasPermissionTo('publish buyer join group') || Auth::user()->hasPermissionTo('delete buyer join group') || Auth::user()->hasPermissionTo('publish buyer payments') || Auth::user()->hasPermissionTo('publish buyer address')) {
                                    return redirect('/dashboard');
                                } else if(Auth::user()->hasPermissionTo('approval buyer approval configurations')) {
                                   return redirect('/approvals');
                                } else if (Auth::user()->hasPermissionTo('buyer rfn publish') || Auth::user()->hasPermissionTo('buyer global rfn publish') ) {
                                    return redirect('/rfn');
                                }else if(Auth::user()->hasPermissionTo('utilize buyer company credit')) {
                                    return redirect('/dashboard');
                                    //return  redirect('/credit/wallet');
                                }
                            } else {
                                return redirect('/get-a-quote');
                            }
                        }else{
                            if(isset($request->frmtypo) && $request->frmtypo == 'multiple_type')
                            {
                                $request->session()->flash('status',  __('signup.password_not_valid'));
                                return redirect("/chooseemail/".$request->id);
                            }
                            $request->session()->flash('status',  __('signup.credential_not_valid'));
                            return redirect("/signin");
                        }
                } else if ($userData->role_id == Role::ADMIN || $userData->role_id == Role::SUPPLIER) {
                    $request->session()->flash('status', __('signup.credential_not_valid'));
                    return redirect("/signin");
                }
            } else if($userData->is_delete == 1 || $userData->trashed()) {
                $request->session()->flash('status',  __('signup.contact_to_blitznet_team'));
                return redirect("/signin");
            }

        } else {
            $request->session()->flash('status', __('signup.user_not_found'));
        }
        return redirect("/signin");
    }

    public function checkEmailExist(Request $request)
    {
        $result = User::withTrashed()->where('email', $request->email)->count();
        if ($result > 0){
            return response()->json(false);
        }
        return response()->json(true);

    }
    // check mobile exits or not
    public function checkMobileExist(Request $request)
    {
        if(config('app.env') == 'live')
        {
            $result = User::withTrashed()->where('mobile', $request->mobile)->where('email',$request->email)->where('role_id',2);
            if(Auth::user()){
                $result = $result->where('id','<>',Auth::user()->id);
            }
            if ($result->count() > 0){
                return response()->json(false);
            }
            return response()->json(true);
       }
        return response()->json(true);
    }

    public function addUser(BuyerFormRequest $request)
    {
        $userData = User::withTrashed()->where('email', $request->email)->first();

        if (empty($userData)) {
            if($request->validated()){
                $data = $request->all();
                $check = $this->create($data, $request);
            }
            return response()->json(['inserted' => true]);
        }else if(!empty($userData) && $userData->is_delete == 1){
                $message =  __('signup.contact_to_blitznet_team');
                return response()->json(['emailExist' => true,'message'=>$message]);
            }
    }

    public function create(array $data, $request)
    {

        $user = new User();
        $user->salutation = $data['salutation'];
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->phone_code = $data['phone_code']?'+'.$data['phone_code']:'';
        $user->mobile = $data['mobile'];
        $user->email = $data['email'];
        $user->role_id = 2;
        $user->is_active = 0;
        $user->mobile_verified = 1;
        $user->password = Hash::make($data['password']);
        $user->save();
        $userId =  $user->id;

        // START :: System Activity
            User::bootSystemView(new User(), 'Buyer - Register', SystemActivity::CREATED);
        // END :: System Activity

        // Assign User to Buyer Role.
        $agentPermissions = SpatieRole::findByName('buyer')->permissions->pluck('name');
        $user->assignRole('buyer');
        $user->givePermissionTo($agentPermissions);

        $company = new Company();
        $company->name = $data['companyName'];
        $company->owner_user = $userId;
        $company->created_by = $userId;
        $company->save();


        // START :: call Default role function for assign ADMIN role
        createDefaultAdminRole($user->id, $company->id);
        // END :: call Default role function for assign ADMIN role

        // Set Default company and make buyer admin
        $user->default_company = $company->id;
        $cmpIdArr = (array) $company->id;
        $user->assigned_companies = json_encode($cmpIdArr);
        $user->buyer_admin = User::BUYERADMIN;
        $user->save();

        $companyId = $company->id;

        DB::table('user_companies')->insert([
            'user_id' => $userId,
            'company_id' => $companyId
        ]);

        //add Xendit commision fee
        addXenditCommisionFee($companyId);
        $sendMsg = $this->verify->sendMsg($data['firstname'],$data['lastname'],'buyer_welcome_msg',$data['phone_code'],$data['mobile']);
        if ($userId) {
            if (config('app.env')=='live') {
                $salesAccountsUrl = "https://buyer-blitznet.myfreshworks.com/crm/sales/api/sales_accounts";
                $contactsUrl = "https://buyer-blitznet.myfreshworks.com/crm/sales/api/contacts";

                $salesAccountsRequest = \Illuminate\Support\Facades\Http::withBasicAuth('juan.caldera@blitznet.co.id', 'Barbozablitz.123$%^')
                    ->withHeaders([
                        'Authorization' => 'Authorization: Token token=w5zQdIUkZZrc0j5BNC2E_Q',
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])->post($salesAccountsUrl, [
                        'name' => $company->name,
                        'owner_id' => 70000034301,
                    ]);

                $requestJsonDecode = json_decode($salesAccountsRequest->body(), true);

                if (isset($requestJsonDecode['sales_account'])) {
                    $salesAccount = $requestJsonDecode['sales_account']['id'];

                    $contactsRequest = \Illuminate\Support\Facades\Http::withBasicAuth('juan.caldera@blitznet.co.id', 'Barbozablitz.123$%^')
                        ->withHeaders([
                            'Authorization' => 'Authorization: Token token=w5zQdIUkZZrc0j5BNC2E_Q',
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json'
                        ])->post($contactsUrl, [
                            // 'name' => $user->name,
                            'first_name' => $user->firstname,
                            'last_name' => $user->lastname,
                            'mobile_number' => $user->phone_code . $user->mobile,
                            'lead_source_id' => 70000387207,
                            'owner_id' => 70000034301,
                            'sales_accounts' => [
                                [
                                    'id' => $salesAccount,
                                    'is_primary' => true
                                ]
                            ],
                            'emails' => $user->email
                        ]);
                }
            }

            $userActivity = new UserActivity();
            $userActivity->user_id = $userId;
            $userActivity->activity = 'Account Created';
            $userActivity->type = 'account';
            $userActivity->record_id = $user->id;
            $userActivity->save();

            if(empty($data['token'])){
                $useremail = [
                    'user' => $user,
                    'url' => $request->getSchemeAndHttpHost() . '/activate-account?email=' . Crypt::encryptString($user->email)
                    // 'pdf' => public_path($path),
                ];
                try {
                    dispatch(new SendActivationMailToUserJob($useremail, $user->email));
                } catch (\Exception $e) {
                    //echo 'Error - ' . $e;
                }
            }else{
                $updateInviteBuyer = DB::table('invite_buyer')->where('token', $data['token'])->first();
                if(!empty($updateInviteBuyer)){
                    $buyer = InviteBuyer::where('id', $updateInviteBuyer->id)->update(['status' => "1"]);
                    $userUpdate = User::where('id', $userId)->update(['is_active' => "1"]);
                    session()->flash('success', __('signup.activate_successfully'));
                    return redirect("signin");
                }
            }
        }
        
        return true;
    }

    public function logout()
    {
        try {
            if (Auth::check()) {
                $data = UserCompanies::join('users','user_companies.user_id','=','users.id')
                    ->join('companies','user_companies.company_id','=','companies.id')
                    ->where('user_id',Auth::user()->id)->first(['companies.id','companies.approval_process']);

                $approvalConfigUsers = User::join('user_companies','users.id','=','user_companies.user_id')
                    ->join('user_approval_configs', 'users.id', '=', 'user_approval_configs.user_id')
                    ->leftjoin('designations', 'users.designation', '=', 'designations.id')
                    ->where('users.id','!=',Auth::user()->id)
                    ->where('users.is_delete',0)->where('user_companies.company_id',$data->id)
                    ->orderBy('users.id','DESC')->get()->count();

                if(isset($approvalConfigUsers) || isset($data->approval_process)) {
                    if($approvalConfigUsers == 0 && $data->approval_process == 1) {
                        Company::where('id',$data->id)->update(['approval_process' => 0]);
                    }
                }
            }

            Session::flush();
            Auth::logout();
            return Redirect('/signin');

        } catch (\Exception $exception) {
            Session::flush();
            Auth::logout();
            return Redirect('/signin');
        }


    }

    public function addSubsribeUser(Request $request)
    {
        $subscribedUser = new SubscribedUser();
        $subscribedUser->firstname = $request->firstname;
        $subscribedUser->lastname = $request->lastname;
        $subscribedUser->company_name = $request->company;
        $subscribedUser->email = $request->email;
        if ($request->is_buyer) {
            $subscribedUser->is_buyer = $request->is_buyer;
        }
        if ($request->is_supplier) {
            $subscribedUser->is_supplier = $request->is_supplier;
        }
        $subscribedUser->save();
        return response()->json(['inserted' => true]);
    }

    /*
     * Vrutika 11-05-2022
     * subsribeUser: Insert subscribe user data from website
     * */
    public function subsribeUser(Request $request)
    {
        $subscribedUser = SubscribedUser::firstOrCreate([
            'email'     => $request->email
        ],[
            'fullname'      =>  $request->fullname,
            'company_name'  =>  $request->company_name,
            'mobile'        =>  $request->mobile,
            'is_buyer'      =>  $request->is_buyer ?? '',
            'is_supplier'   =>  $request->is_supplier ?? ''
        ]);

        if ($subscribedUser->wasRecentlyCreated) {
            return response()->json(['success' => true, 'message' => __("home_latest.subscribe_success")]);
        } else {
            return response()->json(['success' => false, 'message' => __("home_latest.already_subscribed")]);
        }

    }

    /*
     * Vrutika 11-05-2022
     * addContactUs: add contact details to databse table
     * */
    public function addContactUs(Request $request)
    {
        if( strtolower($request->company_name) == 'crytobon' || strtolower($request->company_name) == 'bonasync' || strtolower($request->fullname) == 'crytobon' || strtolower($request->fullname) == 'bonasync' ){
             return response()->json(['inserted' => false]);
        }
        $blockedContact = BlockedContact::where('contact_email',$request->email)->where('deleted_at',null)->orderBy('id', 'desc')->first();
        if (isset($blockedContact) && $blockedContact->contact_email == $request->email){
            return response()->json(['inserted' => false]);
        }else{
            $data = $request->toArray();
            $settings = Settings::where('key',"contact_receive_mail")->get()->first();
            $blitznetContactEmail = $settings->value;
            dispatch(new ContactUsMailJob($data, $blitznetContactEmail));

            $contactUs = new ContactUs();
            $contactUs->fullname = $request->fullname;
            $contactUs->company_name = $request->company_name;
            $contactUs->email = $request->email;
            $contactUs->mobile = $request->mobile;
            $contactUs->message = $request->message;
            $contactUs['user_type'] = implode(', ', $request->user_type);
            $contactUs->save();
            return response()->json(['inserted' => true]);
        }
    }

    public function activateAccount(Request $request)
    {
        $email = Crypt::decryptString($_GET['email']);
        if ($email) {
            $userData = User::withTrashed()->where('email', $email)
                ->get()
                ->first();
            if ($userData) {
                $userData->is_active = 1;
                $userData->language_id = 1;
                $userData->currency_id = 1;
                $userData->save();

                /* @ekta new register user send successful registration mail */
                $registerUsers = new static;
                $registerUsers->firstname = $userData['firstname'];
                $registerUsers->lastname = $userData['lastname'];
                $registerUsers->url = route('signin');
                $useremail = [
                    'user' => $registerUsers
                ];

                try {
                    dispatch(new UserLoginWithSocialiteJob($useremail, $email));
                } catch (\Exception $e) {
                    dd($e);
                }
                /* End mail*/

                $request->session()->flash('success', __('signup.activate_successfully'));
                if($userData->role_id==3) {
                    if(Auth::user())
                    {
                        return redirect("admin/dashboard");
                    }
                    return redirect("admin/login");
                } else {
                    $request->session()->flash('status','Email varified');
                    return redirect("/profile");
                }
            }
        }
        return redirect("/link-expired");
    }

    public function showForgetPasswordForm()
    {
        $user = Auth::user();
        if($user){
            return view('/forgetPassword',['user'=>$user]);
        }else{
            return view('/forgetPassword');
        }
    }

    public function submitForgetPasswordForm(BuyerForgetPassword $request)
    {
        if ($request->ajax()) {
            return true;
        }

        $token = Str::random(64);
        $user = User::where('email', $request->email)->first();
        if (empty($user)){
            $request->session()->flash('status', __('signup.email_error'));
            return redirect("/forget-password");
        }
        if (!in_array($user->role_id, [2])){
            $request->session()->flash('status', __('signup.email_error'));
            return redirect("/forget-password");
        }
        DB::table('password_resets')->insert(['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]);
        Mail::send('emails.customer.forgetPasswordUserMail', ['token' => $token, 'full_name' => $user->firstname .' '. $user->lastname], function($message) use($request){
            $message->to($request->email);
            $message->subject(__('passwords.password_reset'));
        });
        $request->session()->flash('success', __('signup.email_success'));
        return redirect("/signin");
    }

    public function showResetPasswordForm($token) {
        $updatePassword = DB::table('password_resets')->where('token', $token)->first();
        if (empty($updatePassword)){
            Session::flash('status', __('signup.link_is_expired'));
            return redirect("/signin");
        }
        return view('/resetPassword', ['token' => $token]);
    }

    public function submitResetPasswordForm(BuyerResetPassRequest $request)
    {
        if($request->ajax()){
            return true;
        }
        $updatePassword = DB::table('password_resets')->where('token', $request->token)->first();

        if(empty($updatePassword)){
            $request->session()->flash('status', __('signup.email_error'));
            return redirect()->back();
        } else {
            $user = User::where('email', $updatePassword->email)->update(['password' => Hash::make($request->password), 'first_login' => 1]);

            DB::table('password_resets')->where('email', $updatePassword->email)->delete();
            $request->session()->flash('success', __('passwords.reset'));
            return redirect("/signin");
        }
    }
    //added by ekta
    public function showSignupUser($email,$token)
    {

        //when user is active
        $user = User::where(['email'=>Crypt::decryptString($email),'is_active'=>1])->count();
        if (!empty($user)){
            Session::flash('status', __('signup.link_is_expired'));
            return redirect("/link-expired");
        }

        $checkInviteUser = InviteBuyer::where(['user_email'=>Crypt::decryptString($email),'token'=>$token])->count();
        if (empty($checkInviteUser)){
            Session::flash('status', __('signup.link_is_expired'));
            //return redirect("/signup");
            return redirect("/link-expired");
        } else{
            return redirect()->route('signup',['email'=> $email,'token'=> $token]);
        }

    }

    public function socialLogin($social)
    {
        return Socialite::driver($social)->redirect();

    }

    public function handleProviderCallback($social)
    {

        try {
            if(isset($_GET['error'])){
                return redirect('/signin#');
            }

            $user = Socialite::driver($social)->stateless()->user();

            if($social == 'google'){
                $finduser = User::where(['google_id' => $user->id,'email' => $user->email])->first();
            }elseif ($social == 'facebook'){
                $finduser = User::where(['fb_id' => $user->id,'email' => $user->email])->first();
            }else{
                $finduser = User::where(['linkedin_id' => $user->id,'email' => $user->email])->first();
            }
            if($finduser){
                if ($finduser->is_active == 1) { // check for user is active  - Mittal
                    Auth::login($finduser);
                    if(!$finduser->mobile_verified)
                    {
                        return redirect('/mobileverification');
                    }
                    return redirect('/dashboard');
                }else{
                    return redirect('/signin#')->with('register', __('signup.account_not_activated'));
                }

            }else{
                $existuser = User::withTrashed()->where('email', $user->email)->get(['id','email','role_id','is_delete','mobile_verified'])->first();
                if($existuser) {

                    if($existuser->role_id == 2){
                        if($social == 'google'){
                            $updateUser  = User::where('id',$existuser->id)->update(['google_id'=>$user->id,'is_active'=>1]);
                        }elseif ($social == 'facebook'){
                            $updateUser  = User::where('id',$existuser->id)->update(['fb_id'=>$user->id,'is_active'=>1]);
                        }else{
                            $updateUser  = User::where('id',$existuser->id)->update(['linkedin_id'=>$user->id,'is_active'=>1]);
                        }
                        if($existuser->is_delete == 0) { // check for user delete -Mittal
                            Auth::login($existuser);
                            if(!$existuser->mobile_verified)
                            {
                                return redirect('/mobileverification');
                            }
                            return redirect('/dashboard');
                        }else {
                            return redirect('/signin#')->with('register', __('signup.contact_to_blitznet_team'));
                        }
                    }else{
                        Session::flash('register', __('signup.register_as_supplier'));
                        return redirect('/signin#');
                    }

                }else {
                    if(empty($user->email)){
                        Session::flash('register',__('signup.login_with_email'));
                        return redirect('/signin#');
                    }
                    $firstname=''; $lastname='';
                    if($social == 'google'){
                        $firstname=$user->user['given_name'];
                        $lastname=$user->user['family_name'];
                    }
                    if($social == 'facebook'){
                        $fb_user_name = explode(" ", $user->name);
                        $firstname=$fb_user_name[0]??'';
                        $lastname=$fb_user_name[1]??'';
                    }
                    if($social == 'linkedin'){
                        $firstname=$user->first_name;
                        $lastname=$user->last_name;
                    }
                    $newUser = User::create([
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $user->email,
                        'mobile' => '',
                        'google_id' => $social == 'google' ? $user->id : null,
                        'linkedin_id' => $social == 'linkedin' ? $user->id : null,
                        'fb_id' => $social == 'facebook' ? $user->id : null,
                        'role_id' => 2,
                        'is_active' => 1,
                        'password' => ''
                    ]);
                    $userId = $newUser->id;

                    $asssignUserRole    = User::findOrFail($newUser->id);
                    $buyerPermissions   = SpatieRole::findByName('buyer')->permissions->pluck('name');
                    $asssignUserRole->assignRole('buyer');
                    $asssignUserRole->givePermissionTo($buyerPermissions);

                    $company = new Company();
                    $company->name          = $user->name;
                    $company->owner_user    = $userId;
                    $company->created_by    = $userId;
                    $company->save();

                    // Set Default company and make buyer admin
                    $cmpIdArr                               = (array) $company->id;
                    $asssignUserRole->default_company       = $company->id;
                    $asssignUserRole->assigned_companies    = json_encode($cmpIdArr);
                    $asssignUserRole->buyer_admin           = User::BUYERADMIN;
                    $asssignUserRole->save();

                    $companyId = $company->id;

                    DB::table('user_companies')->insert([
                        'user_id' => $userId,
                        'company_id' => $companyId
                    ]);
                    $userActivity = new UserActivity();
                    $userActivity->user_id = $userId;
                    $userActivity->activity = 'Account Created';
                    $userActivity->type = 'account';
                    $userActivity->record_id = $userId;
                    $userActivity->save();
                    /* @ekta new socialite user send successful registration mail */
                    $socialiteUsers = new static;
                    $socialiteUsers->firstname = $firstname;
                    $socialiteUsers->lastname =$lastname;
                    $socialiteUsers->url = route('signin');
                    $useremail = [
                        'user' => $socialiteUsers
                    ];

                    try {
                        dispatch(new UserLoginWithSocialiteJob($useremail, $user->email));
                    } catch (\Exception $e) {
                        dd($e);
                    }
                    /* End mail*/

                    Auth::login($newUser);
                    if(!$newUser->mobile_verified)
                    {
                        return redirect('/mobileverification');
                    }
                    return redirect('/dashboard');
                }
            }
        } catch (Exception $e){
            return redirect ('/signin');
            dd($e->getMessage());
        }
    }

    public function checkSupplierEmailExist(Request $request)
    {
        /*$data = User::where('email', $request->email)->count();
        return response()->json(['found' => $data]);*/

        $result = User::where('email', $request->email)->count();
        $supplier = Supplier::where('email', $request->email)->orWhere('contact_person_email',$request->email)->count();
        if (!empty($result)) {
            return response()->json(false);
        }
        if (!empty($supplier)){
            return response()->json(false);
        }
        return response()->json(true);
    }

    public function signupSupplier($email,$token)
    {
        //when supplier is active
        $user = User::where(['email'=>Crypt::decryptString($email),'is_active'=>1])->count();
        if (!empty($user)){
            Session::flash('status', __('signup.link_is_expired'));
            return redirect("/link-expired");
        }

        $checkInviteUser = InviteBuyer::where(['user_email'=>Crypt::decryptString($email),'token'=>$token])->count();
        if (empty($checkInviteUser)){
            Session::flash('status', __('signup.link_is_expired'));
            //return redirect("/signup");
            return redirect("/link-expired");
        } else{
            return redirect()->route('signup-supplier',['email'=> $email,'token'=> $token]);
        }

    }


    public function addSupplier(BuyerFormRequest $request)
    {
        $supplier = new Supplier();
        $supplier->salutation = $request->salutation;
        $supplier->name = $request->companyName;
        $supplier->contact_person_name = $request->firstname;
        $supplier->contact_person_last_name = $request->lastname;
        $supplier->contact_person_email = trim($request->email);
        $supplier->cp_phone_code = $request->phone_code?'+'.$request->phone_code:'';
        $supplier->contact_person_phone = $request->mobile;
        $supplier->save();
        $supplierID = $supplier->id;
        // START :: System Activity
        User::bootSystemView(new User(), 'Supplier - Register', SystemActivity::CREATED);
        // END :: System Activity

        //effect on user table
        $user = new User();
        $user->salutation = $request->salutation;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->mobile_verified = 1;
        $user->phone_code = $request->phone_code?'+'.$request->phone_code:'';
        $user->password = Hash::make($request['password']);
        $user->role_id = 3;
        $user->first_login = 1;
        $user->is_active = 0;
        $user->save();
        $userId =  $user->id;

        $userSupplier = new UserSupplier();
        $userSupplier->user_id = $userId;
        $userSupplier->supplier_id = $supplierID;
        $userSupplier->save();

        // Assign user to Supplier Role
        $agentPermissions = SpatieRole::findByName('supplier')->permissions->pluck('name');
        $user->assignRole('supplier');
        $user->givePermissionTo($agentPermissions);

        //check register from third party or invited
        if($request->register_supplier_flag == 1){
            $register_supplier_flag = $request->register_supplier_flag;
        }

        if (config('app.env')=='live') {
            $salesAccountsUrl = "https://supplier-blitznet.myfreshworks.com/crm/sales/api/sales_accounts";
            $contactsUrl = "https://supplier-blitznet.myfreshworks.com/crm/sales/api/contacts";

            $salesAccountsRequest = \Illuminate\Support\Facades\Http::withBasicAuth('juan.caldera@blitznet.co.id', 'Barbozablitz.123')
                ->withHeaders([
                    'Authorization' => 'Authorization: Token token=yfrfjObR-CPsOVZBglvUNQ',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->post($salesAccountsUrl, [
                    'name' => $supplier->name,
                    'owner_id' => 70000066313,
                ]);

            $requestJsonDecode = json_decode($salesAccountsRequest->body(), true);

            if (isset($requestJsonDecode['sales_account'])) {
                $salesAccount = $requestJsonDecode['sales_account']['id'];

                $contactsRequest = \Illuminate\Support\Facades\Http::withBasicAuth('juan.caldera@blitznet.co.id', 'Barbozablitz.123')
                    ->withHeaders([
                        'Authorization' => 'Authorization: Token token=w5zQdIUkZZrc0j5BNC2E_Q',
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])->post($contactsUrl, [
                        //'name' => $supplier->contact_person_name,
                        'first_name' => $supplier->contact_person_name,
                        'last_name' => $supplier->contact_person_last_name,
                        'mobile_number' => $supplier->cp_phone_code . $supplier->contact_person_phone,
                        'lead_source_id' => 70000707343,
                        'owner_id' => 70000066313,
                        'sales_accounts' => [
                            [
                                'id' => $salesAccount,
                                'is_primary' => true
                            ]
                        ],
                        'emails' => $supplier->contact_person_email
                    ]);
            }
        }
        $sendMsg = $this->verify->sendMsg( $request->firstname, $request->lastname,'supplier_welcome_msg', $request->phone_code, $request->mobile);
        if(empty($request['token'])){ //third party
            $useremail = [
                'user' => $user,
                'flag' => $register_supplier_flag,
                'url' => $request->getSchemeAndHttpHost() . '/activate-account?email=' . Crypt::encryptString($user->email)
                // 'pdf' => public_path($path),
            ];
            try {
                //Mail::to($user->email)->bcc($ccUsers)->send(new SendActivationMailToUser($useremail));
                dispatch(new SendActivationMailToUserJob($useremail, $user->email));
            } catch (\Exception $e) {
                dd($e);
            }
            return true;
        }else{ //invited
            $updateInviteBuyer = DB::table('invite_buyer')->where('token', $request['token'])->first();
            if(!empty($updateInviteBuyer)){
                $buyer = InviteBuyer::where('id', $updateInviteBuyer->id)->update(['status' => "1"]);
                $userUpdate = User::where('id', $userId)->update(['is_active' => "1"]);

                //Insert user_id and supplier id in "preferred_suppliers" table (Ronak M - 22/06/2022)
                $PreferredSupplier = new PreferredSupplier();
                $PreferredSupplier->user_id = $updateInviteBuyer->user_id;
                $PreferredSupplier->supplier_id = $supplierID;
                $PreferredSupplier->is_active = 1;
                $PreferredSupplier->save();
                //End
                session()->flash('success', __('signup.activate_successfully'));
                return true;
            }
        }
    }

    /**
     *
     * Resource user edit permissions - Admin Settings => Users
     *
     * @param $id
     * @return mixed
     */
    public function editPermission($id)
    {
        try {
            $userCompanyRole = collect();
            $userPermission = collect();
            $userCustomPermission = collect(); //Model has permission data store
            $encryptedUserId = $id;
            $id = Crypt::decrypt($id);
            $user = User::where('id', $id)->first();


            $userAssignedRoles = ModelHasCustomPermission::with('customPermission')
                ->whereHas('customPermission', function ($query) {
                    $query->where('model_type', '=', CustomRoles::class);
                })
                ->where('model_type', User::class)
                ->where('model_id', $id)->get();

            $userAssignedRoles->each(function ($role) use ($userCompanyRole, $userPermission, $user, $userCustomPermission) {

                $companyRole = CustomRoles::where('company_id', $user->default_company)->where('id', $role->customPermission->value)->first();

                if (!empty($companyRole)) {
                    $userPermission->push($role->custom_permissions);
                    $userCompanyRole->push($companyRole);
                    $userCustomPermission->push($role);
                }

            });

            $role = $userCompanyRole->first();
            $customPermission = $userCustomPermission->first();
            $permissions = !empty($userPermission->first()) ? Arr::flatten(json_decode($userPermission->first())) : '';

            $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->children();
            $roleGroup = CustomRoles::where('company_id', Auth::user()->default_company)->get();

        } catch (\Exception $e) {
            abort('404');
        }


        return View::make('buyer.setting.users.permissions')->with(compact(['permissionGroup', 'role', 'encryptedUserId', 'permissions', 'roleGroup', 'user', 'customPermission']));
    }

    /**
     *
     * Resource user update permissions - Admin Settings => Users
     *
     * @param Request $request
     * @return mixed
     */
    public function updatePermission(EditUserPermissionRequest $request)
    {
        try {
            $roleId = Crypt::decrypt($request->roleSegment);
            $customPermissionId = Crypt::decrypt($request->permissionSegment); //Model Has Custom Permission Id
            $userId = Crypt::decrypt($request->segment);
            $user = User::findOrFail($userId);

            //Old Permissions
            $modelHasCustomPermission = ModelHasCustomPermission::where('id', $customPermissionId)->first();
            $oldPermission = $modelHasCustomPermission->custom_permissions;

            $oldPermissionArr = !empty($oldPermission) ? Arr::flatten(json_decode($oldPermission)) : '';


            //New Permissions
            $permissions = collect();
            foreach ($request->rolePermission as $permission) {
                foreach (Arr::flatten(json_decode($permission, true)) as $permission) {

                    $permissions->push($permission);

                }
            }

            //Remove old permissions and assign new permission to user
            !empty($oldPermissionArr) ? $user->revokePermissionTo(CustomPermission::findByIds($oldPermissionArr)) : '';

            $user->givePermissionTo(CustomPermission::findByIds($permissions->toArray()));

            $modelHasCustomPermission->custom_permissions = json_encode($permissions);
            $modelHasCustomPermission->save();

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => __('profile.something_went_wrong'), 'type' => 'error']);
        }

        return response()->json(['success' => true, 'message' => __('profile.permission_updated'), 'type' => 'success']);

    }

    /**
     *
     * Resource user create with role - Admin Settings => Users
     *
     * @param Request $request
     * @return mixed
     */
    public function assignUserWithRole($request)
    {
        try {
            $parentUser = Auth::user();

            $childUser = User::where('email', $request->email)->first();

            //Exist user then assign company
            if (!empty($childUser)) {

                // START :: Check USer company if not then create new company and return company ID:
                    $companyId = Company::setCompanyOwner($childUser->id);
                // END :: Check USer company if not then create new company and return company ID:

                assignCompanyToUserAttribute($childUser, $parentUser->default_company);

                $customPermissionId = CustomPermission::where('model_type',CustomRoles::class)->where('value',$request->role)->get()->pluck('id')->first();
                $rolePermissions = CustomRoles::where('id',$request->role)->get()->pluck('permissions')->first();
                ModelHasCustomPermission::insert(['custom_permission_id' => $customPermissionId, 'model_type' => User::class, 'model_id' => $childUser->id, 'custom_permissions' => $rolePermissions]);

                //Create a new object instance to apply new role and permission to a child user.
                $childUser = User::findOrFail($childUser->id);

                //For maintain approval process functionality
                UserCompanies::updateOrCreate([
                    'user_id'       =>  $childUser->id,
                    'company_id'    =>  $parentUser->default_company
                ]);
                //Add Xendit Commission Fee
                addXenditCommisionFee($parentUser->default_company);
                (new CompanyUserController)->companyUserCreate($childUser, $parentUser, $request);

                return response(['success' => true, 'message' => 'Company assigned successfully']);

            } else {

                $newPassword = Str::random(8);

                //Create child user
                $childUser = User::Create([
                    'firstname'         =>  $request->firstName,
                    'lastname'          =>  $request->lastName,
                    'mobile'            =>  $request->mobile,
                    'phone_code'        =>  $request->phone_code?'+'.$request->phone_code:'',
                    'email'             =>  $request->email,
                    'role_id'           =>  Role::BUYER,
                    'is_active'         =>  1,
                    'designation'       =>  $request->designation,
                    'department'        =>  $request->department,
                    'added_by'          =>  $parentUser->id,
                    'password'          =>  Hash::make($newPassword)
                ]);

                if (!empty($childUser)) {

                    //Create child user company
                    $childUserCompany = Company::create([
                        'name'          => $request->firstName,
                        'owner_user'    => $childUser->id,
                        'created_by'    => $childUser->id
                    ]);

                    // START :: call Default role function for assign ADMIN role
                    createDefaultAdminRole($childUser->id, $childUserCompany->id);
                    // END :: call Default role function for assign ADMIN role
                    //Assign companies
                    $childUser->assigned_companies    =   json_encode(array($childUserCompany->id));
                    $childUser->save();

                    assignCompanyToUserAttribute($childUser, $parentUser->default_company, true);

                    if (!empty($childUserCompany)) {

                        UserCompanies::create([
                            'user_id'       =>  $childUser->id,
                            'company_id'    =>  $childUserCompany->id
                        ]);
                        //Add Xendit Commission Fee
                        addXenditCommisionFee($childUserCompany->id);
                        //For maintain approval process functionality with parent user default company
                        UserCompanies::updateOrCreate([
                            'user_id'       =>  $childUser->id,
                            'company_id'    =>  $parentUser->default_company
                        ]);
                        //Add Xendit Commission Fee
                        addXenditCommisionFee($parentUser->default_company);
                        (new CompanyUserController)->companyUserCreate($childUser, $parentUser, $request);

                    }

                    $customPermissionId = CustomPermission::where('model_type',CustomRoles::class)->where('value',$request->role)->get()->pluck('id')->first();
                    $rolePermissions = CustomRoles::where('id',$request->role)->get()->pluck('permissions')->first();
                    ModelHasCustomPermission::insert(['custom_permission_id' => $customPermissionId, 'model_type' => User::class, 'model_id' => $childUser->id, 'custom_permissions' => $rolePermissions]);

                    //Create a new object instance to apply new role and permission to a child user.
                    $childUser = User::findOrFail($childUser->id);
                    $childUser->assignRole('sub-buyer');
                    $childUser->givePermissionTo(CustomPermission::findByIds(json_decode($rolePermissions)));
                    CustomRoles::setUserPersonalInfoPermissions($childUser->id);

                    //Send user credentials to new child user
                    dispatch(new SendEmailCustomUserJob($childUser, $newPassword));

                    return response(['success' => true, 'message' => 'User created and assigned company']);

                }

                return response(['success' => false, 'message' => 'Something went wrong']);

            }

        } catch (\Exception $exception) {
            return response(['success' => false, 'message' => 'Something went wrong']);
        }

    }


    /**
     *
     * Get logged user permission resource
     *
     * @param string $param
     * @return array|mixed
     */
    public function getLoggeUserPermission($param = '')
    {
        $userCustomPermission = collect();
        $id = Auth::user()->id;
        $user = User::where('id', $id)->first();

        $userAssignedRoles = ModelHasCustomPermission::with('customPermission')
            ->whereHas('customPermission', function ($query) {
                $query->where('model_type', '=', CustomRoles::class);
            })
            ->where('model_type', User::class)
            ->where('model_id', $id)->get();

        $userAssignedRoles->each(function ($role) use ($user, $userCustomPermission) {
            $companyRole = CustomRoles::where('company_id', $user->default_company)->where('id', $role->customPermission->value)->first();
            if (!empty($companyRole)) {
                $userCustomPermission->push($role);
            }
        });

        $customPermission = $userCustomPermission->first();
        if(empty($customPermission)){
            return [];
        }
        $permissionIds = json_decode($customPermission->custom_permissions);
        $permissionNames = DB::table('permissions')->whereIn('id', $permissionIds)->get()->pluck('name')->toArray();

        if($param == "name"){
            return $permissionNames;
        }else{
            return $permissionIds;
        }
    }

    /**
     *
     * Get redirect tabs by permission resource
     *
     * @return string|null
     */
    public function getRedirectRoute()
    {
        $sectionName = null;
        $permissionNames = $this->getLoggeUserPermission('name');
        $sectionPermissionArr = [
            ['permissionName' => 'create buyer rfqs', 'sectionName' => 'postRequirementSection'],
            ['permissionName' => 'publish buyer rfqs', 'sectionName' => 'rfqSection'],
            ['permissionName' => 'publish buyer orders', 'sectionName' => 'orderSection'],
            ['permissionName' => 'publish buyer join group', 'sectionName' => 'myGroupsSection'],
            ['permissionName' => 'publish buyer payments', 'sectionName' => 'paymentSection'],
            ['permissionName' => 'publish buyer address', 'sectionName' => 'addressSection']
        ];

        if(sizeof($permissionNames) > 0){
            foreach ($sectionPermissionArr as $row) {
                if(in_array($row['permissionName'], $permissionNames)){
                    $sectionName = $row['sectionName'];
                    break;
                }
            }
        }
        return $sectionName;
    }

    /**
     * Role & permission POPUP
     */
    public function rolePermissionPopup($id)
    {
        try {
            $id                 = $id;
            $role               = CustomRoles::findOrFail($id);
            $role->permissions  = Arr::flatten(json_decode($role->permissions));
        } catch (\Exception $e) {
            abort('404');
        }
        $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->children();
        $rolePopupView = view('buyer.setting.users.rolePopup', ['permissionGroup' => $permissionGroup,'role'=> $role])->render();

        return response()->json(array('success' => true, 'rolePopupView' => $rolePopupView));

    }
    /**
     * send mail for varification
     */
    public function sendEmailVarification(Request $request){
        $user = User::find(Auth::user()->id);
        $useremail = [
            'user' => $user,
            'url' => $request->getSchemeAndHttpHost() . '/activate-account?email=' . Crypt::encryptString($request['email'])
            // 'pdf' => public_path($path),
        ];
        try {
            dispatch(new SendActivationMailToUserJob($useremail, $request['email']));
        } catch (\Exception $e) {
            //echo 'Error - ' . $e;
        }

        $user->email = $request['email'];
        $user->save();
       return response()->json(array('success' => true));
    }
    /**
     * change mobile number
     */
    public function changemobile(){
        $userData = User::find(Auth::user()->id);
        $phoneCode = $userData->phone_code ? str_replace('+','',$userData->phone_code):62;
        $country = strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1));
        return view('mobileotp/mobileVarification',compact('userData','country','phoneCode'));
    }
    /**
     * redirect if mobile is varified else mobile page
     */
    public function mobileVarification(){
        if(Auth::user()->mobile_verified){
            return redirect('/dashboard');
        }
        $userData = User::find(Auth::user()->id);
        $phoneCode = $userData->phone_code ? str_replace('+','',$userData->phone_code):62;
        $country = strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1));
        return view('mobileotp/mobileVarification',compact('userData','country','phoneCode'));
    }
    /**
     * mobile varified entry in db
     */
    public function mobilevarify(Request $request){
        $phone_code = $request['phone_code']?'+'.$request['phone_code']:'';
        $mobile = $request['phone_number'];
        $user = User::find(auth()->user()->id);
        $user->mobile_verified = 1;
        $user->phone_code = $phone_code;
        $user->mobile = $mobile;
        $user->save();
        if(Auth::user()->role_id == 3)
        {
            $user->supplier->cp_phone_code = $phone_code;
            $user->supplier->contact_person_phone = $mobile;
            $user->supplier->save();
        }
       return response()->json(array('success' => true));
    }
    /**
     * send otp and look up mobile no
     */
    public function getOtpModel(Request $request){
        $phone_number = '+'.$request['phone_code'].$request['phone_number'];
        $lookup = $this->verify->lookupPhonenumber($phone_number);
        if (!$lookup->isValid()) {
            $errors = new MessageBag();
            foreach($lookup->getErrors() as $error) {
                $errors->add('verification', $error);
            }
            return response()->json(array('success' => false,'response' => $errors));
        }
        $verification = $this->verify->startVerification($phone_number,'sms');
        if (!$verification->isValid()) {
            $errors = new MessageBag();
            foreach($verification->getErrors() as $error) {
                $errors->add('verification', $error);
            }
            return response()->json(array('success' => false,'response' => $errors));
        }
        else{
             $returnHTML = view('mobileotp/otp',compact(['request']))->render();
            $attempts =$verification->getSid();
            return response()->json(array('success' => true,'response' => $returnHTML,'attempts'=>$verification->getSid(),'resend'=>__('admin.resendcode'),'second'=>__('admin.second')));
        }
    }
    /**
     * varify mobile
     */
    public function verifyOtp(Request $request)
    {
        $phone_number = '+'.$request['phone_code'].$request['phone_number'];
        $verification = $this->verify->checkVerification($phone_number,$request['code']);
        if ($verification->isValid()) {
            return response()->json(array('success' => true,'response' =>''));
        }

        $errors = new MessageBag();
        foreach ($verification->getErrors() as $error) {
            $errors->add('verification', $error);
        }
        return response()->json(array('success' => false,'response' =>$errors));
    }
    // check mobile exits or not
    public function checkmobilenotexist(Request $request)
    {
        if(config('app.env') == 'live')
        {
            if($request->frmType == 'frontend'){
                $result = User::where('mobile', $request->mobile)->where('is_delete',0)->where('role_id',2);
            }else{
                $result = User::where('mobile', $request->mobile)->where('is_delete',0)->where('role_id','<>',2);
            }
            if ($result->count() == 0){
                return response()->json(false);
            }
            return response()->json(true);
       }
        return response()->json(true);
    }
}
