<?php

namespace Database\Seeders;

use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\SystemRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RfnDefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** HO Permission @var $rfnHoPermissions */
        $rfnHoPermissions = [
            'buyer rfn update',
            'buyer rfn publish',
            'buyer rfn convert global rfn',
            'buyer rfn to rfq',
            'buyer rfn list',
            'buyer rfn list-all',
            'buyer rfn multi convert to rfq',
            'buyer rfn reject',
            'buyer global rfn create',
            'buyer global rfn update',
            'buyer global rfn cancel',
            'buyer global rfn to rfq',
            'buyer global rfn publish',
            'buyer global rfn list',
            'buyer global rfn list-all',
            'buyer global rfn multi convert to rfq',
            'buyer edit request global rfn',
            'buyer List All RFR Request'
        ];

        $permissions = DB::table('permissions')->whereIn('name', $rfnHoPermissions)->pluck('id')->toArray();

        $customRole = CustomRoles::create([
            'name'              =>  'HO',
            'permissions'       =>  json_encode($permissions),
            'guard'             =>  Auth::getDefaultDriver(),
            'model_type'        =>  User::class,
            'model_id'          =>  3,
            'system_role_id'    =>  SystemRole::FRONTOFFICE,
            'company_id'        =>  3,
            'system_default_role' => 1
        ]);
        if ($customRole) {
            $customPermission = CustomPermission::create([
                'name'              =>  'Custom Role',
                'model_type'        =>  CustomRoles::class,
                'value'             =>  $customRole->id,
                'system_role_id'    =>  SystemRole::FRONTOFFICE,
                'guard_name'             =>  Auth::getDefaultDriver()
            ]);
        }

        /** HO Permission @var $rfnHoPermissions */

        /** Branch @var $rfnBranchPermissions */
        $rfnBranchPermissions = [
            'buyer rfn create',
            'buyer rfn update',
            'buyer rfn cancel',
            'buyer rfn publish',
            'buyer rfn list',
            'buyer global rfn cancel',
            'buyer request global rfn',
            'buyer global rfn list',
            'buyer edit request global rfn',
            'buyer List RFR Request'
        ];

        $permissions = DB::table('permissions')->whereIn('name', $rfnBranchPermissions)->pluck('id')->toArray();

        $customRole = CustomRoles::create([
            'name'              =>  'Branch',
            'permissions'       =>  json_encode($permissions),
            'guard'             =>  Auth::getDefaultDriver(),
            'model_type'        =>  User::class,
            'model_id'          =>  3,
            'system_role_id'    =>  SystemRole::FRONTOFFICE,
            'company_id'        =>  3,
            'system_default_role' => 1
        ]);
        if ($customRole) {
            $customPermission = CustomPermission::create([
                'name'              =>  'Custom Role',
                'model_type'        =>  CustomRoles::class,
                'value'             =>  $customRole->id,
                'system_role_id'    =>  SystemRole::FRONTOFFICE,
                'guard_name'        =>  Auth::getDefaultDriver()
            ]);
        }
        /** Branch @var $rfnHoPermissions */

        $user = User::findOrFail(3);
        $user->givePermissionTo($rfnHoPermissions);

    }
}
