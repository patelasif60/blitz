<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginOtpRequest extends FormRequest
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
            'mobile' => 'required|string|min:9|max:16',
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
            'mobile.required'       =>  __('frontFormValidationMsg.mobile'),
            'mobile.numeric'        => __('frontFormValidationMsg.numeric'),
        ];
    }
}
