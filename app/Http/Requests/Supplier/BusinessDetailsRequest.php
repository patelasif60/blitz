<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class BusinessDetailsRequest extends FormRequest
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
            'net_income' => 'regex:/^[0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)*$/|nullable|min:0|not_in:0',
            'annual_sales' => 'regex:/^[0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)*$/|nullable|min:0|not_in:0',
            'financial_target' => 'regex:/^[0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)*$/|nullable|min:0|not_in:0',
            'company_description'   => 'max:5000|nullable'
        ];
    }
    public function messages()
    {
        return [
            'net_income.regex' => __("validation.numeric",['attribute'=>__('admin.profile_net_income')]),
            'net_income.not_in' => __("validation.not_in",['attribute'=>__('admin.profile_net_income')]),
            'net_income.min' => __("validation.gt.numeric",['attribute'=>__('admin.profile_net_income'), 'value'=>0]),
            'annual_sales.regex' => __("validation.numeric",['attribute'=>__('admin.annual_sales')]),
            'annual_sales.not_in' => __("validation.not_in",['attribute'=>__('admin.annual_sales')]),
            'annual_sales.min' => __("validation.gt.numeric",['attribute'=>__('admin.annual_sales'), 'value'=>0]),
            'financial_target.regex' => __("validation.numeric",['attribute'=>__('admin.financial_target')]),
            'financial_target.not_in' => __("validation.not_in",['attribute'=>__('admin.financial_target')]),
            'financial_target.min' => __("validation.gt.numeric",['attribute'=>__('admin.financial_target'), 'value'=>0]),
            'company_description.max' => __("validation.max.string",['attribute'=>__('admin.about_company'), 'max'=>5000]),
        ];
    }
}
