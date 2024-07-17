<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyerForgetPassword;
use App\Http\Requests\BuyerLogInFormRequest;
use App\Http\Requests\BuyerResetPassRequest;
use App\Models\Role;
use App\Models\SystemActivity;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Session;
use Hash;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AdminLoginController extends Controller
{

    function index()
    {
        if (Auth::check() && (auth()->user()->role_id == 1 || auth()->user()->role_id == 5)) {
            return redirect('admin/dashboard');
        }
        return view('/admin/login');
    }

    function login(BuyerLogInFormRequest $request)
    {

        $credentials = $request->only('email', 'password');
        $userData = User::withTrashed()->where('email', $request->email)
            ->get()
            ->first();

        if ($userData) {
            $role_name = ($userData->role_id == 1) ? 'Admin' : 'Supplier';
            $role_name = ($userData->role_id == 5) ? 'Agent' : $role_name;

            if ($userData->role_id == 1 || $userData->role_id == 3 || $userData->role_id == 5 || $userData->role_id == Role::JNE || $userData->role_id == Role::FINANCE) {
                if (Auth::attempt($credentials)) {
                    if ($userData->is_active == 0 && $userData->role_id != 3 ){
                        $request->session()->flash('status', 'Your account is inactive please contact to Blitznet Team.');
                        // START :: System log activity.
                            User::bootSystemView(new User(), 'Supplier - LogiIn', SystemActivity::LOGIN);
                        // START :: System log activity.
                        return redirect("admin/login");
                    }
                    if($userData->role_id == 3){
                        if($userData->first_login == 0){
                            return redirect('/admin/changemobile');    
                        }
                        else if($userData->mobile_verified == 0){
                            return redirect('/admin/changemobile');   
                        }
                        return redirect('admin/dashboard');
                        
                    } else {
                        if($userData->mobile_verified == 0 && $userData->role_id != 1){    
                            return redirect('/admin/changemobile');     
                        }
                        return redirect('admin/dashboard');
                    }
                }
                if(isset($request->frmtypo) && $request->frmtypo == 'multiple_type')
                {
                    $request->session()->flash('status',  __('signup.password_not_valid'));
                    return redirect("/chooseemail/".$request->id);
                }
                $request->session()->flash('status', 'Email and password not matched');
                return redirect("admin/login");
            } else {
                $request->session()->flash('status', 'Please login with '.$role_name.' credentials');
                return redirect("admin/login");
            }
        } else {
            $request->session()->flash('status', 'Not found user with this email');
            return redirect("admin/login");
        }
        // $userData = User::where('email', $request->email)
        //        ->where('password', md5($request->password))
        //        ->get();
        // if(count($userData)) {
        //     return redirect('/admin/dashboard');
        // } else {
        //     $request->session()->flash('status', 'Email and password not matched');
        //     return view('/admin/login');
        // }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return Redirect('admin/login');
    }

    public function showForgetPasswordForm()
    {
        return view('/admin/forgetPassword');
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
            return redirect("admin/forget-password");
        }
        if (!in_array($user->role_id, [1,3])){
            $request->session()->flash('status', __('signup.email_error'));
            return redirect("admin/forget-password");
        }
        DB::table('password_resets')->insert(['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]);
        Mail::send('emails.admin.forgetPasswordMail', ['token' => $token,'full_name' => $user->firstname .' '. $user->lastname], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        $request->session()->flash('success', 'Please check your email for reset password.');
        return redirect("admin/login");
    }

    public function showResetPasswordForm($token) {
        $updatePassword = DB::table('password_resets')->where('token', $token)->first();
        if (empty($updatePassword)){
            Session::flash('status', 'Link is expired.');
            return redirect("admin/login");
        }
        return view('/admin/resetAdminPassword', ['token' => $token]);
    }

    public function submitResetPasswordForm(BuyerResetPassRequest $request)
    {
        if($request->ajax()) {
            return true;
        }
        $updatePassword = DB::table('password_resets')->where('token', $request->token)->first();

        if(empty($updatePassword)){
            $request->session()->flash('status', 'Invalid token!');
            return redirect()->back();
        } else {
            $user = User::where('email', $updatePassword->email)->update(['password' => Hash::make($request->password), 'first_login' => 1]);

            DB::table('password_resets')->where('email', $updatePassword->email)->delete();
            $request->session()->flash('success', 'Your password has been changed!');
            return redirect("admin/login");
        }
    }
    // check mobile exits or not
    public function checkMobileExist(Request $request)
    {
        if(config('app.env') == 'live')
        {
            $result = User::withTrashed()->where('mobile', $request->mobile)->where('email',$request->email)->where('role_id',3);
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
}
