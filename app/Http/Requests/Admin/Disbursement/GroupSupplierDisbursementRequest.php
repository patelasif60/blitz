<?php

namespace App\Http\Requests\Admin\Disbursement;

use App\Models\Disbursements;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class GroupSupplierDisbursementRequest extends FormRequest
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
        $order = Order::find($this->order_id);
        return [
            'blitznet_commission_per' => 'numeric|min:0|max:100',
            'blitznet_commission_amount' => 'required_if:blitznet_commission,==,1|numeric',
            'final_disburse_amount' => 'required|numeric|gte:'.getMinDisbursementAmount().'|lte:'.Disbursements::getGroupPayableAmount($order,$this->request->all()),
        ];
    }

    public function messages()
    {
        return [
            'blitznet_commission_per.numeric' => __("validation.numeric"),
            'blitznet_commission_amount.required_if' => __("validation.required_if"),
            'blitznet_commission_amount.numeric' => __("validation.numeric"),
            'final_disburse_amount.numeric' => __("validation.numeric"),
            'final_disburse_amount.gte' => sprintf(__('admin.payable_amount_greater'),getMinDisbursementAmount()),
            'final_disburse_amount.lte' => __('validation.lte.numeric'),
        ];
    }
}
