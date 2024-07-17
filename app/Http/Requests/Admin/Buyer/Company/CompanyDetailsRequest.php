<?php

namespace App\Http\Requests\Admin\Buyer\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyDetailsRequest extends FormRequest
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
        if (request()->formType == "companyDetails") {
            return [
                'name' => 'required|max:255',
                'website' => 'nullable|max:255',
                'company_email' => 'nullable|email|max:255',
                'alternative_email' => 'nullable|email|max:255',
                'company_phone' => 'numeric|nullable|max:9999999999999999',
                'alternative_phone' => 'numeric|nullable|max:9999999999999999',
                'addressbuyer' => 'nullable|max:5000',
                'registration_nib' => 'nullable|min:13|max:13',
                'nib_file' => 'nullable|max:3000|mimes:jpeg,png,doc,docs,pdf',
                'npwp' => 'nullable|min:20|max:20',
                'npwp_file' => 'nullable|max:3000|mimes:jpeg,png,doc,docs,pdf',
                'termsconditions_file' => 'nullable|max:3000|mimes:jpeg,png,doc,docs,pdf',
            ];
        }else if (request()->formType == "buyerProfile"){
            return [
                'firstName' =>  'nullable|regex:/^[\pL\s\-]+$/u|max:255',
                'lastName'  =>  'nullable|regex:/^[\pL\s\-]+$/u|max:255',
                'mobile'    =>  'numeric|nullable|max:9999999999999999'
            ];
        }
    }
}
