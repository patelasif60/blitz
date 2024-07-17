<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerResetPassRequest extends FormRequest
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
            'password' => 'required',
            'password_confirmation' => 'required|required_with:password|same:password'
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
            'password.required'                 =>  __('frontFormValidationMsg.password'),
            'password_confirmation.password_confirmation'    => __('frontFormValidationMsg.password'),
            'password_confirmation.required' => __('frontFormValidationMsg.required'),
            'password_confirmation.same' => __('frontFormValidationMsg.same'),
        ];
    }
}
