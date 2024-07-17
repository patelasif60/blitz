<?php

namespace App\Http\Requests\Admin\Buyer;

use Illuminate\Foundation\Http\FormRequest;

class UserDetailsRequest extends FormRequest
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
            'firstName' =>  'required|regex:/^[\pL\s\-]+$/u|max:255',
            'lastName'  =>  'required|regex:/^[\pL\s\-]+$/u|max:255',
            'mobile'    =>  ['required', 'min:9', 'max:16'],
            'designation'   =>  'required',
            'department'    =>  'required',
            'role'  =>  'required'
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
            'firstName.required'    =>  __('frontFormValidationMsg.firstName'),
            'lastName.required'     =>  __('frontFormValidationMsg.lastName'),
            'mobile.required'       =>  __('frontFormValidationMsg.mobile'),
            'mobile.numeric'        => __('frontFormValidationMsg.numeric'),
            'designation.required'  => __('frontFormValidationMsg.designation'),
            'department.required'   => __('frontFormValidationMsg.department'),
            'role.required' => __('frontFormValidationMsg.role')
        ];
    }
}
