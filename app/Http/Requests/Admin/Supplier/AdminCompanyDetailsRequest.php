<?php

namespace App\Http\Requests\Admin\Supplier;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class AdminCompanyDetailsRequest extends FormRequest
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

        $supplier = isset(request()->id) ? Supplier::with('user')->where('id','=',request()->id)->first() : '';

        $userId = (!empty($supplier->user) && isset(request()->id)) ? $supplier->user->id : '';
        $supplierId = (!empty($supplier->id)) ? $supplier->id : '';
        return [
            'name'                  =>  'required|max:255',
            'email'                 =>  'nullable|string|email:rfc,dns|max:255',
            'mobile'                =>  'nullable|string|min:9|max:16',
            'contactPersonName'     =>  'nullable|string|max:255',
            'contactPersonLastName' =>  'nullable|string|max:255',
            'contactPersonEmail'    =>  'required|max:255|unique:suppliers,contact_person_email,'.$supplierId.'|unique:users,email,'.$userId,
            'alternate_email'       =>  'nullable|email|max:255',
            'company_alternative_phone'=>'nullable|string|min:9|max:16',
            'license'               =>  'nullable|max:255',
            'facebook'              =>  'nullable|max:255',
            'twitter'               =>  'nullable|max:255',
            'linkedIn'              =>  'nullable|max:255',
            'youtube'               =>  'nullable|max:255',
            'instagram'             =>  'nullable|max:255',
            'contactPersonMobile'   =>  'nullable|string|min:9|max:16',
            'nib'                   =>  'nullable|max:13',
            'npwp'                  =>  'nullable',
            'website'               =>  'nullable|max:255',
            'catalog'               =>  'nullable|max:20600|mimes:jpeg,png,doc,docs,pdf',
            'pricing'               =>  'nullable|max:3072|mimes:jpeg,png,doc,docs,pdf',
            'product'               =>  'nullable|max:3072|mimes:jpeg,png,doc,docs,pdf',
            'commercialCondition'   =>  'nullable|max:3072|mimes:jpeg,png,doc,docs,pdf',
            'pkp_file'              =>  ($this->companyType==1 && $this->oldpkp_file == '')?'required|max:3000|mimes:jpeg,png,doc,docs,pdf':'nullable|max:3000|mimes:jpeg,png,doc,docs,pdf',
            'profile_username'      =>  'required|unique:suppliers,profile_username,'.$supplierId
        ];
    }
    public function messages()
    {
        return [
            'name.required' => __("validation.custom.company_name.required"),
            'profile_username.required' => __("validation.custom.profile_username.required"),
            'email.email' => __("validation.custom.email.email"),
            'contactPersonName.max' => __("validation.custom.contactPersonName.max"),
            'contactPersonLastName.max' => __("validation.custom.contactPersonLastName.max"),
            'contactPersonEmail.max' => __("validation.custom.contactPersonEmail.required"),
            'contactPersonEmail.unique' => __("validation.custom.contactPersonEmail.unique"),
            'alternate_email.email' => __("validation.custom.alternate_email.email"),
            'company_alternative_phone.min' => __("validation.min.numeric",['attribute'=>__('admin.company_alternative_phone'), 'min'=>9]),
            'company_alternative_phone.max' => __("validation.max.numeric",['attribute'=>__('admin.company_alternative_phone'), 'max'=>16]),
            'license.max' => __("validation.custom.license.max"),
            'facebook.url' => __("validation.custom.facebook.url"),
            'twitter.url' => __("validation.custom.twitter.url"),
            'linkedIn.url' => __("validation.custom.linkedIn.url"),
            'youtube.url' => __("validation.custom.youtube.url"),
            'instagram.url' => __("validation.custom.instagram.url"),
            'nib.max' => __("validation.custom.nib.max"),
            'website.url' => __("validation.custom.website.url"),
            'catalog.mimes' => __("validation.mimes",['attribute'=>__('admin.catalog'), 'values'=>'jpeg,png,doc,docs,pdf']),
            'pricing.mimes' => __("validation.mimes",['attribute'=>__('admin.pricing'), 'values'=>'jpeg,png,doc,docs,pdf']),
            'product.mimes' => __("validation.mimes",['attribute'=>__('admin.product'), 'values'=>'jpeg,png,doc,docs,pdf']),
            'commercialCondition.mimes' => __("validation.mimes",['attribute'=>__('admin.commercial_conditions'), 'values'=>'jpeg,png,doc,docs,pdf']),
            'catalog.max' => __("validation.max.file",['attribute'=>__('admin.catalog'), 'max'=>'20600']),
            'pricing.max' => __("validation.max.file",['attribute'=>__('admin.pricing'), 'max'=>'3072']),
            'product.max' => __("validation.max.file",['attribute'=>__('admin.product'), 'max'=>'3072']),
            'commercialCondition.max' => __("validation.max.file",['attribute'=>__('admin.commercial_conditions'), 'max'=>'3072']),
            'pkp_file.required' => __("validation.custom.pkp_file.required"),
            'pkp_file.mimes' => __("validation.custom.pkp_file.mimes"),
            'contactPersonMobile.min' => __("validation.min.numeric",['attribute'=>__('admin.contact_person_phone'), 'min'=>9]),
            'contactPersonMobile.max' => __("validation.max.numeric",['attribute'=>__('admin.contact_person_phone'), 'max'=>16]),
            'mobile.min' => __("validation.min.numeric",['attribute'=>__('admin.company_mobile'), 'min'=>9]),
            'mobile.max' => __("validation.max.numeric",['attribute'=>__('admin.company_mobile'), 'max'=>16])
        ];

    }
}
