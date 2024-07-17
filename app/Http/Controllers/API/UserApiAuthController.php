<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserApiAuthController extends Controller
{
    /**
     * login user to our application
     */
    public function login(Request $request){
        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];

        if(auth()->attempt($login_credentials)){
            //generate the token for the user
            $user_login_token= auth()->user()->createToken('blitznetapi')->accessToken;
            //now return this token on success login attempt
            return response()->json(['user' => auth()->user(), 'token' => $user_login_token], 200);
        }
        else{
            //wrong login credentials, return, user not authorised to our system, return error code 401
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
    }

    /**
     * This method returns authenticated user details
     */
    public function authenticatedUserDetails(){
        //returns details
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }
}
