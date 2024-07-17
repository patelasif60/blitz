<?php

namespace App\Http\Requests\Buyer\RolesPermission;

use App\Rules\Frontend\BuyerRoleNameUnique;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class AddBuyerRoleRequest extends FormRequest
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
        $buyerRoleId = request()->segment(count(request()->segments())) ? request()->segment(count(request()->segments())) : '';


        if (!empty($buyerRoleId) && $buyerRoleId != 'roles') {
            try {
                $buyerRoleId = Crypt::decrypt($buyerRoleId);

            } catch (\Exception $e) {
                throw DecryptException::withMessages([__('profile.something_went_wrong')]);
            }
        }


        return [
            'roleName'          =>  ['required', new BuyerRoleNameUnique(request()->roleName, $buyerRoleId)],
            'rolePermission'    =>  'required'
        ];
    }
}
