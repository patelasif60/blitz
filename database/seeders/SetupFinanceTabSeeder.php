<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupFinanceTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /****************begin: Create new Permissions for Finance Tab - Backend Side ( Admin / Supplier / Finance )*******************/
        DB::transaction(function () {
            Permission::UpdateOrCreate(['name' => 'create finance tab']);
            Permission::UpdateOrCreate(['name' => 'edit finance tab']);
            Permission::UpdateOrCreate(['name' => 'delete finance tab']);
            Permission::UpdateOrCreate(['name' => 'publish finance tab']);
            Permission::UpdateOrCreate(['name' => 'unpublish finance tab']);
        });

        $role = Role::findByName('finance');
        $role->givePermissionTo('create finance tab');
        $role->givePermissionTo('edit finance tab');
        $role->givePermissionTo('delete finance tab');
        $role->givePermissionTo('publish finance tab');
        $role->givePermissionTo('unpublish finance tab');

        $role = Role::findByName('supplier');
        $role->givePermissionTo('create finance tab');
        $role->givePermissionTo('edit finance tab');
        $role->givePermissionTo('delete finance tab');
        $role->givePermissionTo('publish finance tab');
        $role->givePermissionTo('unpublish finance tab');

        $role = Role::findByName('admin');
        $role->givePermissionTo('create finance tab');
        $role->givePermissionTo('edit finance tab');
        $role->givePermissionTo('delete finance tab');
        $role->givePermissionTo('publish finance tab');
        $role->givePermissionTo('unpublish finance tab');


        $role = Role::findByName('super-admin');
        $role->givePermissionTo('create finance tab');
        $role->givePermissionTo('edit finance tab');
        $role->givePermissionTo('delete finance tab');
        $role->givePermissionTo('publish finance tab');
        $role->givePermissionTo('unpublish finance tab');

    }
}
