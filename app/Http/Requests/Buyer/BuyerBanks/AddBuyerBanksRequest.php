<?php

namespace App\Http\Requests\Buyer\BuyerBanks;

use App\Rules\Frontend\BuyerBankPrimaryExist;
use App\Rules\Frontend\BuyerBankUnique;
use Illuminate\Foundation\Http\FormRequest;

class AddBuyerBanksRequest extends FormRequest
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

        $buyerBankId = is_numeric(request()->segment(count(request()->segments()))) ? request()->segment(count(request()->segments())) : '';


        return [
            'bankName'                  =>  'required',
            'bankAccountHolderName'     =>  'required|regex:/^[\pL\s\-]+$/u|max:255',
            'bankAccountNumber'         =>  ['required','string','min:8', new BuyerBankUnique(request()->bankName, $buyerBankId)],
            'isPrimary'                 =>  [new BuyerBankPrimaryExist($buyerBankId)]

        ];
    }
}
