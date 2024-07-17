<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\GroupMember;
use App\Models\ModelHasCustomPermission;
use App\Models\Order;
use App\Models\Rfq;
use App\Models\User;
use App\Models\UserAddresse;
use App\Models\UserCompanies;
use App\Models\UserRfq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class CompanyOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // company_id - user_id
        $companyOwnerIds = [162 => 261, 33 => 103, 104 => 176];

        foreach ($companyOwnerIds as $key => $value) {
            Company::where('id', $key)->update(['owner_user' => $value, 'created_by' => $value]);
        }
        
        //company_name - company_id
        $companyOwnerNames = ["PT. Fajar Benua Indopack" => 162, "PT Karoseri Anak Bangsa" => 33, "PT. Matahari Leisure" => 104, "PT MATAHARI LEISURE" => 104, "PT.MATAHARI LEISURE" => 104];
        
        foreach ($companyOwnerNames as $companyName => $companyId) {
            $companyIds = Company::where('name', $companyName)->pluck('id')->toArray();

            $childUserIds = UserCompanies::whereIn('company_id', $companyIds)->pluck('user_id')->toArray();
            foreach ($childUserIds as $childUserId) {
                $user = User::where('id', $childUserId)->where('role_id', 2);
                if($user->get()->count() > 0){
                    $childUser = User::where('id', $childUserId)->where('role_id', 2)->first();//dd($childUser);
                    $assignedCompaniesArr = !empty($childUser->assigned_companies) ? json_decode($childUser->assigned_companies) : [];
                    
                    if(!in_array($companyId, $assignedCompaniesArr)) {
                        array_push($assignedCompaniesArr, $companyId);
                    }
                    $user->update(['assigned_companies' => json_encode($assignedCompaniesArr)]);
                    
                    $existUserCompanyCount = UserCompanies::where('company_id', $companyId)->where('user_id', $childUserId)->get()->count();
                    if($existUserCompanyCount < 1){
                        $userCompanyObj = new UserCompanies;
                        $userCompanyObj->company_id = $companyId;
                        $userCompanyObj->user_id = $childUserId;
                        $userCompanyObj->save();
                    }

                    $test = CustomRoles::where('company_id',$companyId)->where('name', "Default User")->where('system_default_role', 1)->first();
                    $roleName = $test->name;
                    $roleId = $test->id;
                    $customPerRes = CustomPermission::where('model_type', CustomRoles::class)->where('value', $roleId)->get()->first();
                    $customPermissionId = $customPerRes->id;
                    $AdminPermissions = CustomRoles::getAdminUserPermission();
                    $permissionCount = ModelHasCustomPermission::where('custom_permission_id', $customPermissionId)->where('model_type', User::class)->where('model_id', $childUserId,)->where('custom_permissions', $AdminPermissions)->get()->count();
                    if($permissionCount < 1){
                        ModelHasCustomPermission::insert(['custom_permission_id' => $customPermissionId, 'model_type' => User::class, 'model_id' => $childUserId, 'custom_permissions' => json_encode($AdminPermissions) ]);

                    }
                    

                    // update company id in rfqs table
                    $rfqIds = UserRfq::where('user_id', $childUserId)->pluck('rfq_id')->toArray();
                    if(sizeof($rfqIds) > 0){
                        Rfq::whereIn('id', $rfqIds)->update(['company_id' => $companyId]);
                    }

                    // update company id in orders table
                    Order::where('user_id', $childUserId)->update(['company_id'=> $companyId]);

                    // update company id in user address table
                    UserAddresse::where('user_id', $childUserId)->update(['company_id'=> $companyId]);
                }
            }
        }
        dd('updated');
    }
}
