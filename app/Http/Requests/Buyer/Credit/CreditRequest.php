<?php

namespace App\Http\Requests\Buyer\Credit;

use Illuminate\Foundation\Http\FormRequest;

class CreditRequest extends FormRequest
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

        if( request()->form_type=="loanApplicantLimit" ){

            return [
                'loanAmount'    =>  'required|string'
            ];

        }elseif(request()->form_type=="loanApplicantDetails"){
            return [
                'email'                     =>  'required|string|email:rfc,dns|max:255',
                'firstName'                 =>  'required|string|max:255',
                'lastName'                  =>  'required|string|max:255',
                'otherMemberCode'           =>  'required|string|max:255',
                'phoneNumber'               =>  'required|string|min:9|max:16',
                'gender'                    =>  'required|string|max:255',
                'maritalStatus'             =>  'required|string|max:255',
                'religion'                  =>  'required|string|max:255',
                'education'                 =>  'required|string|max:255',
                'occupation'                =>  'required|string|max:255',
                'otherIncome'               =>  'required|string|max:255',
                'netSalary'                 =>  'required|string|max:255',
                'otherSourceOfIncome'       =>  'required|string|max:255',
                'phoneCode'                 =>  'required|string',
                 'ktpImage'                  =>  'required_if:old_ktpImage,==,null',
                'ktpSelfiImage'             =>  'required_if:old_ktpSelfiImage,==,null',
                'familyCardImage'           =>  'required_if:old_familyCardImage,==,null',
                'otherKtpImage'             =>  'required_if:old_otherKtpImage,==,null',
                'dateOfBirth'               =>  'required|date|date_format:d-m-Y',
                'placeOfBirth'              =>  'required|string',
                'myPosition'                =>  'required',
                'otherFirstName'            =>  'required',
                'otherLastName'             =>  'required',
                'otherMemberEmail'          =>  'required|string|email:rfc,dns|max:255',
                'otherMemberPhone'          =>  'required|min:9|max:16',
                'otherKtpNik'               =>  'required|min:16|different:ktpNik',
                'relationshipWithBorrower'  =>  'required',
                'ktpNik'                    =>  'required|min:16',

            ];


        }elseif(request()->form_type=='loanApplicantAddress'){

            return [
                'loanApplicantAddressLine1'         =>  'required|string|max:255',
                'loanApplicantAddressLine2'         =>  'required|string|max:255',
                'subDistrict'                       =>  'required|string|max:255',
                'district'                          =>  'required|string|max:255',
                'loanApplicantPostalCode'           =>  'required|string|min:5|max:8',
                'loanApplicantHasLivedHere'         =>  'required|string|max:255',
                'loanApplicantDurationOfStay'       =>  'required|string|gt:0|lte:99',
                'loanApplicanthomeOwnershipStatus'  =>  'required|string|max:255',
                'city'                              =>  'required_if:cityId,==,-1|nullable',
                'state'                             =>  'required_if:provincesId,==,-1|nullable',
                'cityId'                            =>  'required|max:255',
                'provincesId'                       =>  'required|max:255',
                'loanApplicantCountryId'            =>  'required|max:255',
                'loanApplicantAddressName'          =>  'required',
            ];

        }elseif(request()->form_type=='loanApplicantBusiness'){
            return [
                'loanApplicantBusinessType'             =>  'required|string|max:255',
                'loanApplicantBusinessName'             =>  'required|string|max:255',
                'loanApplicantBusinessEmail'            =>  'required|string|max:255|email:rfc,dns|unique:loan_applicant_businesses,email,'.request()->applicantId,
                'loanApplicantBusinessPhone'            =>  'required|string|min:9|max:16',
                'loanApplicantBusinessFirstName'        =>  'required|string|max:255',
                'loanApplicantBusinessLastName'         =>  'required|string|max:255',
                'loanApplicantBusinessAverageSales'     =>  'required|string|max:255',
                'loanApplicantBusinessEstablish'        =>  'required|date|max:255',
                'loanApplicantBusinessNoOfEmployee'     =>  'required|string|max:255',
                'loanApplicantSiupNumber'               =>  'required|string|max:255',
                'loanApplicantCategory'                 =>  'required|string|max:255',
                'loanApplicantOwnership'                =>  'required|numeric|gt:0|lte:100',
                //'loanApplicantRelationshipWithBorrower' =>  'required|string|max:255',
                'loanApplicantBusinessNpwpImage'        =>  'required_if:old_loanApplicantBusinessNpwpImage_file,==,null',
                'loanApplicantBusinessLicenceImage'     =>  'required_if:old_LoanApplicantBusinessLicenceImage_file,==,null',
                'loanApplicantBankStatement'            =>  'required_if:old_loanApplicantBankStatement_file,==,null',
                'loanApplicantBusinessDescription'      =>  'required',
            ];

        }elseif(request()->form_type=='loanApplicantBusinessAddress'){

            return [
                'loanBusinessAddressLine1'          =>  'required|string|max:255',
                'loanBusinessAddressLine2'          =>  'required|string|max:255',
                'loanBusinessAddressSubDistrict'    =>  'required|string|max:255',
                'loanBusinessAddressDistrict'       =>  'required|string|max:255',
                'loanBusinessAddressProvinces'      =>  'required|string|max:255',
                'state_business'                    =>  'required_if:loanBusinessAddressProvinces,==,-1|nullable',
                'loanBusinessAddressCity'           =>  'required',
                'city_business'                     =>  'required_if:loanBusinessAddressCity,==,-1|nullable',
                'loanBusinessAddressCountry'        =>  'required|string|max:255',
                'loanBusinessAddressPostalCode'     =>  'required|string|min:5|max:8',
             ];

        }



    }
    public function messages()
    {
        return [
            'ktpImage.required_if'                           => __("validation.ktpImage"),
            'ktpSelfiImage.required_if'                      => __("validation.ktpSelfiImage"),
            'otherKtpImage.required_if'                      => __("validation.otherKtpImage"),
            'familyCardImage.required_if'                    => __("validation.familyCardImage"),
            'loanApplicantBusinessNpwpImage.required_if'     => __("validation.loanApplicantBusinessNpwpImage"),
            'loanApplicantBusinessLicenceImage.required_if'  => __("validation.loanApplicantBusinessLicenceImage"),
            'loanApplicantBankStatement.required_if'         => __("validation.loanApplicantBankStatement"),
           'loanApplicantOwnership.required'                 => __("validation.loanApplicantOwnership.required"),
           'loanApplicantOwnership.numeric'                  => __("validation.loanApplicantOwnership.numeric"),
           'loanApplicantOwnership.gt'                       => __("validation.loanApplicantOwnership.gt"),
           'loanApplicantOwnership.lt'                       => __("validation.loanApplicantOwnership.lt"),
           'loanApplicantDurationOfStay.gt'                  => __("validation.loanApplicantDurationOfStay.gt"),
           'loanApplicantDurationOfStay.lt'                  => __("validation.loanApplicantDurationOfStay.lt"),
           'city.required_if'                                => __("validation.city.required"),
           'state.required_if'                               => __("validation.state.required"),
           'city_business.required_if'                       => __("validation.city_business.required"),
           'state_business.required_if'                      => __("validation.state_business.required"),
        ];

    }
}
