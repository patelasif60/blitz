<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Database\Seeders\Roles\PermissionGroupSeeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ApprovalPermissionSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**begin: New Approval Permission**/
        DB::transaction(function () {
            Permission::UpdateOrCreate(['name' => 'approval buyer approval configurations']);
            Permission::UpdateOrCreate(['name' => 'toggle buyer approval configurations']);
        });
        /**end: New Approval Permission**/

        $role = Role::findByName('buyer');
        $role->givePermissionTo('approval buyer approval configurations');

        //Assign default buyer permissions to all buyer role users
        $users = User::where('role_id', Role::findByName('buyer')->id)->where('buyer_admin', User::BUYERADMIN)->get();
        $buyerPermissions = Role::findByName('buyer')->permissions->pluck('name');
        $users->each(function($user) use($buyerPermissions) {

            $user->assignRole('buyer');

            $user->givePermissionTo($buyerPermissions);

        });

        $this->call(PermissionGroupSeeder::class);


    }
}
