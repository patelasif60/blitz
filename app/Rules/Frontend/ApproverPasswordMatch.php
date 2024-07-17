<?php

namespace App\Rules\Frontend;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Auth;
use Illuminate\Support\Facades\Hash;
class ApproverPasswordMatch implements Rule
{
    protected $password;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        if (request()->accept_feedback == 1) {
            $this->mobileNumber = $request->approveMobile;
        }  else{
            $this->mobileNumber = $request->rejectMobile;
        }
        $this->approverPhoneCode    = $request->phone_code;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::where('id',Auth::user()->id)->first();
        if ($this->approverPhoneCode == $user->phone_code && $this->mobileNumber == $user->mobile){ // check for compare password
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.approverMobileMatch');
    }
}
