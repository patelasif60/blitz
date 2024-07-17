<?php

namespace App\Http\Requests\Buyer\User;

use App\Rules\Frontend\ApproverPasswordMatch;
use Illuminate\Foundation\Http\FormRequest;

class StoreApproverFeedbackRequest extends FormRequest
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
        if(request()->accept_feedback == 1){
            return [
                'approveMobile' => ['required','digits_between:9,16' ,new ApproverPasswordMatch(request())]
            ];
        }else{
            if(request()->reasonForReject == "other_reason" && !isset(request()->other_reason_text)){
                return [
                    'other_reason_text' => 'required',
                    'rejectMobile' => ['required','digits_between:9,16' ,new ApproverPasswordMatch(request())]
                ];
            }else{
                return [
                    'reasonForReject' => 'required',
                    'rejectMobile' => ['required','digits_between:9,16' ,new ApproverPasswordMatch(request())]
                ];
            }
        }
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'other_reason_text.required' => __('validation.required'),
            'rejectMobile.required' =>  __('frontFormValidationMsg.mobile'),
            'rejectMobile.digits_between' =>  __('profile.required_phone_number_error'),
            'approveMobile.required' =>  __('frontFormValidationMsg.mobile'),
            'approveMobile.digits_between' =>  __('profile.required_phone_number_error'),
        ];
    }
}
