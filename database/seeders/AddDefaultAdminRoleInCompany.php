<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\ModelHasCustomPermission;
use App\Models\SystemRole;
use App\Models\User;
use App\Models\UserCompanies;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class AddDefaultAdminRoleInCompany extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        $AdminPermissions = CustomRoles::getAdminUserPermission();

        $users = User::where('role_id', 2)->get();

        foreach ($users as $user) {
            if ($user->buyer_admin) {
                $res = CustomRoles::where('company_id',$user->default_company)->where('system_default_role',1)->first();

                if(empty($res)){
                    $customRole = CustomRoles::create([
                        'name'                  =>  'Default User',
                        'permissions'           =>  json_encode($AdminPermissions),
                        'guard'                 =>  Auth::getDefaultDriver(),
                        'model_type'            =>  User::class,
                        'model_id'              =>  $user->id,
                        'system_role_id'        =>  SystemRole::FRONTOFFICE,
                        'company_id'            =>  $user->default_company,
                        'system_default_role'   =>  1
                    ]);

                    $customPermission = CustomPermission::create([
                        'name'              =>  'Custom Role',
                        'model_type'        =>  CustomRoles::class,
                        'value'             =>  $customRole->id,
                        'system_role_id'    =>  SystemRole::FRONTOFFICE,
                        'guard_name'             =>  Auth::getDefaultDriver()
                    ]);
                }
            }
        }
        $customRole = CustomRoles::where('system_default_role', 0)->where('name','Default User')->update([
            'name'  =>  'Default User1'
        ]);

        // dd($customRole);
    }
}