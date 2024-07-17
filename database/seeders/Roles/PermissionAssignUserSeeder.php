<?php

namespace Database\Seeders\Roles;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class PermissionAssignUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();

        DB::table('model_has_roles')->truncate();

        DB::table('model_has_permissions')->truncate();


        //Assign default agent permissions to all agent role users
        $users = User::where('role_id', Role::findByName('agent')->id)->get();

        $agentPermissions = Role::findByName('agent')->permissions->pluck('name');

        $users->each(function($user) use($agentPermissions) {

            $user->assignRole('agent');

            $user->givePermissionTo($agentPermissions);

        });

        //Assign default admin permissions to all admin role users
        $users = User::where('role_id', Role::findByName('admin')->id)->get();

        $agentPermissions = Role::findByName('admin')->permissions->pluck('name');

        $users->each(function($user) use($agentPermissions) {

            $user->assignRole('admin');

            $user->givePermissionTo($agentPermissions);

        });

        //Assign default supplier permissions to all supplier role users

        $users = User::where('role_id', Role::findByName('supplier')->id)->get();

        $agentPermissions = Role::findByName('supplier')->permissions->pluck('name');

        $users->each(function($user) use($agentPermissions) {

            $user->assignRole('supplier');

            $user->givePermissionTo($agentPermissions);

        });

        //Assign default buyer permissions to all buyer role users

        $users = User::where('role_id', Role::findByName('buyer')->id)->where('buyer_admin', User::BUYERADMIN)->get();
        $buyerPermissions = Role::findByName('buyer')->permissions->pluck('name');
        $users->each(function($user) use($buyerPermissions) {

            $user->assignRole('buyer');

            $user->givePermissionTo($buyerPermissions);

        });

        Schema::enableForeignKeyConstraints();

    }
}
