<?php

namespace App\Rules\Frontend;

use App\Models\BuyerBanks;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class BuyerBankPrimaryExist implements Rule
{
    protected $buyerBankId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($buyerBankId = '')
    {
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
        if ($value == 0 || $value == '' || $value == null) {
            $authUser = Auth::user();
            /**********begin:Buyer Bank details set permissions based on custom role******/
            $isOwner = User::checkCompanyOwner();
            if($isOwner == true || $authUser->hasPermissionTo('list-all buyer bank details')){
                $primaryBank = BuyerBanks::where('company_id', $authUser->default_company)->where('is_primary',1)->get()->count();
            }else {
                $primaryBank = BuyerBanks::where('user_id', $authUser->id)->where('company_id', $authUser->default_company)->where('is_primary',1)->get()->count();
            }
            /**********end:Buyer Bank details  set permissions based on custom role******/
            if ($primaryBank == null || $primaryBank == 0) {
                return false ;
            }

            $isPrimaryBank = BuyerBanks::where('id', $this->buyerBankId)->where('is_primary', 1)->get()->count();

            if (($isPrimaryBank != null || $isPrimaryBank != 0) && ($value == 0 || $value == '' || $value == null)) {
                return false ;

            }

        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.isPrimary.BuyerBankPrimaryExist');
    }
}
