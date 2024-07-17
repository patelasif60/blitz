<?php

namespace App\Rules\Frontend;

use App\Models\BuyerBanks;
use Illuminate\Contracts\Validation\Rule;

class BuyerBankUnique implements Rule
{
    protected $bank;
    protected $buyerBankId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($bankId, $buyerBankId = '')
    {
        $this->bank         = $bankId;
        $this->buyerBankId  = $buyerBankId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $bank = BuyerBanks::where('bank_id',$this->bank)->where('account_number', $value)->whereNotIn('id', [$this->buyerBankId])->get()->count();

        return $bank > 0 ? false : true ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.bankAccountNumber.BuyerBankunique');
    }
}
