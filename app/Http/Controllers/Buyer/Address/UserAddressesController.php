<?php

namespace App\Http\Controllers\Buyer\Address;

use App\Models\User;
use App\Models\UserAddresse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserAddressesController extends Controller
{
    /**
     * Is address belongs to User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isAddressBelongsTo(Request $request)
    {
        try {
            $isOwner = User::checkCompanyOwner();
            $authUser = Auth::user();

            if($isOwner == true || $authUser->hasPermissionTo('list-all buyer address')){
                $address = UserAddresse::isBelongsTo($request->id,null,$authUser->default_company)->first();

            } else {
                $address = UserAddresse::isBelongsTo($request->id,$authUser->id,$authUser->default_company)->first();

            }
            if (!empty($address)) {
                return response()->json(['success' => true, 'message' => __('admin.permission_access')]);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

        }

        return response()->json(['success' => false, 'message' => __('admin.permission_denied')]);
    }
}
