<?php

namespace App\Http\Controllers;

use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyUserController extends Controller
{
    /**
     * companyUserCreate: manage user details(department, designation etc) based on multiple company
     */
    public function companyUserCreate($childUser = null, $parentUser, $request, $companyId=null)
    {
        $userId = $childUser == null ? $parentUser->id : $childUser->id;
        $companyId = $companyId == null ? Auth::user()->default_company : $companyId; // Check companyId of  Owner Default company is passed otherwise auth user default company
        $companyUser = CompanyUser::where('users_id',$userId)->where('company_id',$companyId )->first();
        if($companyUser){
            $update = $companyUser->update(['designation' => $request->designation, 'department' => $request->department,'branches'=>$request->branch]);
            if($update){
                return true;
            }
        }else{
            $result = CompanyUser::create([
                'company_id'    =>  $parentUser->default_company,
                'designation'   =>  $request->designation,
                'department'   =>  $request->department,
                'users_type'   =>  User::class,
                'users_id'   =>  $userId,
                'branches' => $request->branch // Branches name add for RFN
            ]);
            if($result){
                return true;
            }
        }
        return false;
    }
}
