<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAssignJneRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        /****************begin: Create new Permissions for Logistic Charges - Backend Side (Super-Admin / Admin / Supplier / Agent / Jne )*******************/
        DB::transaction(function () {
            Permission::UpdateOrCreate(['name' => 'create quote logistic charges']);
            Permission::UpdateOrCreate(['name' => 'edit quote logistic charges']);
            Permission::UpdateOrCreate(['name' => 'delete quote logistic charges']);

            Permission::UpdateOrCreate(['name' => 'create quotes chat']);
        });
        /****************begin: Create new Permissions for Logistic Charges - Backend Side (Super-Admin / Admin / Supplier / Agent / Jne )*******************/

        /****************begin: Assign JNE Permissions for Logistic Charges - Backend Side (Super-Admin / Admin / Supplier / Agent / Jne )*******************/

        /****************begin: Assign to Agent*******/
        $role = Role::findByName('agent');
        $role->givePermissionTo('create quote logistic charges');
        $role->givePermissionTo('edit quote logistic charges');
        $role->givePermissionTo('delete quote logistic charges');
        /****************end: Assign to Agent*******/

        /****************begin: Assign to Admin*******/
        $role = Role::findByName('admin');
        $role->givePermissionTo('create quote logistic charges');
        $role->givePermissionTo('edit quote logistic charges');
        $role->givePermissionTo('delete quote logistic charges');
        /****************end: Assign to Admin*******/

        /****************begin: Assign to Admin*******/
        $role = Role::findByName('super-admin');
        $role->givePermissionTo('create quote logistic charges');
        $role->givePermissionTo('edit quote logistic charges');
        $role->givePermissionTo('delete quote logistic charges');
        /****************end: Assign to Admin*******/

        /****************begin: Assign to Supplier*******/
        $role = Role::findByName('supplier');
        $role->givePermissionTo('create quote logistic charges');
        $role->givePermissionTo('edit quote logistic charges');
        $role->givePermissionTo('delete quote logistic charges');
        /****************end: Assign to Supplier*******/

        /****************begin: Assign to Jne*******/
        $role = Role::findByName('jne');
        $role->givePermissionTo('create quote logistic charges');
        $role->givePermissionTo('edit quote logistic charges');
        $role->givePermissionTo('delete quote logistic charges');

        $role->givePermissionTo('publish orders');
        $role->givePermissionTo('publish order list');
        $role->givePermissionTo('edit order list');

        $role->givePermissionTo('edit quotes');
        $role->givePermissionTo('publish quotes');



        /****************end: Assign to Jne*******/

        /****************end: Assign JNE Permissions for Logistic Charges - Backend Side (Super-Admin / Admin / Supplier / Agent / Jne )*******************/

    }
}
