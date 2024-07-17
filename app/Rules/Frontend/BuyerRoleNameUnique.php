<?php

namespace App\Rules\Frontend;

use App\Models\CustomRoles;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class BuyerRoleNameUnique implements Rule
{
    protected $buyerRoleName;
    protected $buyerRoleId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($buyerRoleName, $buyerRoleId = '')
    {
        $this->buyerRoleName    = $buyerRoleName;
        $this->buyerRoleId      = $buyerRoleId;


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
        $roleName = trim(preg_replace('/\s+/', ' ', $this->buyerRoleName));
        $customRole = CustomRoles::where('company_id', \Auth::user()->default_company)->where('name',$roleName)->where('model_type', User::class)->whereNotIn('id', [$this->buyerRoleId])->get()->count();

        return $customRole > 0 ? false : true ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.roleName.BuyerRoleNameUnique');
    }
}
