<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'companyName' => 'required',
            'mobile' => 'required|string|min:9|max:16',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'firstname.required'    =>  __('frontFormValidationMsg.firstName'),
            'lastname.required'     =>  __('frontFormValidationMsg.lastName'),
            'companyName.required'  =>  __('frontFormValidationMsg.companyName'),
            //'mobile.required'       =>  __('frontFormValidationMsg.mobile'),
            //'mobile.numeric'        => __('frontFormValidationMsg.numeric'),
            'email.required'        =>  __('frontFormValidationMsg.required'),
            'email.unique'        =>  __('frontFormValidationMsg.emailunique'),
            'email.email'          => __('frontFormValidationMsg.email'),
            'password.required'     =>  __('frontFormValidationMsg.password'),
        ];
    }
}
