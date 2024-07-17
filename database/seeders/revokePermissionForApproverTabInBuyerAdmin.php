<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class revokePermissionForApproverTabInBuyerAdmin extends Seeder
{
    /**
     * Run this seeder for Revoke permission and role for Approval Tab in Buyer Admin
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('role_id', Role::findByName('buyer')->id)->where('buyer_admin', User::BUYERADMIN)->get();
        $approverPermissionId = Permission::findByName('approval buyer approval configurations')->id;       //Get permission id by permission name
        $users->each(function ($user) use($approverPermissionId) {
            $user->revokePermissionTo($approverPermissionId); // revoke permission
        });
        $role = Role::findByName('buyer');
        $role->revokePermissionTo('approval buyer approval configurations'); //Revoke Role from buyer
        return "Approver permission is revoke from buyer role";
    }
}
