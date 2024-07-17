<?php

namespace App\Http\Requests\Supplier;

use App\Models\CompanyHighlights;
use App\Models\CompanyMembers;
use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class SupplierProfessionalProfileRequest extends FormRequest
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
        $imageValidateHighlight = true;
        $imageValidate = true;
        if (!empty(request()->id)) {
            $id = Crypt::decrypt(request()->id);
            if(request()->formType == "highlights"){
                $data = CompanyHighlights::where('id',$id)->first();
                if(isset($data) && !empty($data->image)){
                    if(empty(\request()->oldhighlightImage)){
                        $imageValidateHighlight = true;
                    }else{
                        $imageValidateHighlight = false;
                    }
                }
            }else{
                $data = CompanyMembers::where('id',$id)->first();
                if(isset($data) && !empty($data->image)){
                    if(empty(\request()->oldmemberImage)){
                        $imageValidate = true;
                    }else{
                        $imageValidate = false;
                    }
                }
            }
        }

        if (request()->formType == "coreteam"){
            return [
                'firstname'     => 'required|max:255',
                'lastname'      => 'required|max:255',
                'email'         => 'nullable|email|max:255',
                'phone'         => 'numeric|nullable|max:9999999999999999',
                'designation'   => 'nullable|string|max:255',
                'description'   => 'required',
                'coreTeamImage' => 'nullable|max:3000|mimes:jpeg,png,svg'
            ];
        }else if (request()->formType == "portfolio"){
            if ($imageValidate) {
                return [
                    'company_name' => 'required|max:255',
                    'sector' => 'required|max:255',
                    'position' => 'nullable|max:255',
                    'registration_NIB' => 'nullable|digits:13',
                    'portfolio_type' => 'required|max:255',
                    'portfolioImage' => 'required|max:3000|mimes:jpeg,png,svg'
                ];
            } else {
                return [
                    'company_name' => 'required|max:255',
                    'sector' => 'required|max:255',
                    'position' => 'nullable|max:255',
                    'registration_NIB' => 'nullable|digits:13',
                    'portfolio_type' => 'required|max:255',
                    'portfolioImage' => 'nullable|max:3000|mimes:jpeg,png,svg'
                ];
            }

        }else if (request()->formType == "testimonial"){
            if ($imageValidate) {
                return [
                    'firstname' => 'required|max:255',
                    'lastname' => 'required|max:255',
                    'company_name' => 'required|max:255',
                    'testimonialImage' => 'required|max:3000|mimes:jpeg,png,svg'
                ];
            }else{
                return [
                    'firstname' => 'required|max:255',
                    'lastname' => 'required|max:255',
                    'company_name' => 'required|max:255',
                    'testimonialImage' => 'nullable|max:3000|mimes:jpeg,png,svg'
                ];
            }
        }else if (request()->formType == "companyPartner"){
            if($imageValidate){
                return [
                    'company_name' => 'required|max:255',
                    'partnerImage' => 'required|max:3000|mimes:jpeg,png,svg'
                ];
            }else{
                return [
                    'company_name' => 'required|max:255',
                    'partnerImage' => 'nullable|max:3000|mimes:jpeg,png,svg'
                ];
            }

        } else if(request()->formType == "highlights") {
            if ($imageValidateHighlight) {
                return [
                    'category' => 'required',
                    'name' => 'required|max:255',
                    'highlightImage' => 'required|max:3000|mimes:jpeg,png,svg'
                ];
            }else{
                return [
                    'category' => 'required',
                    'name' => 'required|max:255',
                    'highlightImage' => 'nullable|max:3000|mimes:jpeg,png,svg'
                ];
            }
        }

        return [
            //
        ];


    }

    public function messages()
    {
        return [
            'firstname.required' => __("validation.required",['attribute'=>__('admin.first_name')]),
            'firstname.max' => __("validation.max.string",['attribute'=>__('admin.first_name'), 'max'=>255]),
            'lastname.required' => __("validation.required",['attribute'=>__('admin.last_name')]),
            'lastname.max' => __("validation.max.string",['attribute'=>__('admin.last_name'), 'max'=>255]),
            'email.email' => __("validation.custom.email.email"),
            'designation.required' => __("validation.required",['attribute'=>__('admin.designation')]),
            'description.required' => __("validation.required",['attribute'=>__('admin.about')]),
            'coreTeamImage.required' => __("validation.required",['attribute'=>__('admin.photo')]),
            'coreTeamImage.max' => __("validation.lt.file",['attribute'=>__('admin.photo'), 'value'=>3000]),
            'coreTeamImage.mimes' => __("validation.mimes",['attribute'=>__('admin.photo'), 'values'=>'jpeg,png,svg']),
            'company_name.required' => __("validation.required",['attribute'=>__('admin.company_name')]),
            'sector.required' => __("validation.required",['attribute'=>__('admin.sector')]),
            'registration_NIB.digits' => __("validation.digits",['attribute'=>__('admin.registation_nib'), 'digits'=>13]),
            'portfolioImage.required' => __("validation.required",['attribute'=>__('admin.logo')]),
            'portfolioImage.max' => __("validation.lt.file",['attribute'=>__('admin.logo'), 'value'=>3000]),
            'portfolioImage.mimes' => __("validation.mimes",['attribute'=>__('admin.logo'), 'values'=>'jpeg,png,svg']),
            'testimonialImage.required' => __("validation.required",['attribute'=>__('admin.photo')]),
            'testimonialImage.max' => __("validation.lt.file",['attribute'=>__('admin.photo'), 'value'=>3000]),
            'testimonialImage.mimes' => __("validation.mimes",['attribute'=>__('admin.photo'), 'values'=>'jpeg,png,svg']),
            'partnerImage.required' => __("validation.required",['attribute'=>__('admin.logo')]),
            'partnerImage.max' => __("validation.lt.file",['attribute'=>__('admin.logo'), 'value'=>3000]),
            'partnerImage.mimes' => __("validation.mimes",['attribute'=>__('admin.logo'), 'values'=>'jpeg,png,svg']),
            'highlightImage.required' => __("validation.required",['attribute'=>__('admin.photo')]),
            'highlightImage.max' => __("validation.lt.file",['attribute'=>__('admin.photo'), 'value'=>3000]),
            'highlightImage.mimes' => __("validation.mimes",['attribute'=>__('admin.photo'), 'values'=>'jpeg,png,svg']),
            'category.required' => __("validation.required",['attribute'=>__('admin.achievement_category')]),
            'name.required' => __("validation.required",['attribute'=>__('admin.name')]),
            'portfolio_type.required' => __("validation.required",['attribute'=>__('admin.type')]),
        ];
    }
}
