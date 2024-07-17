<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAssignFinanceRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        /****************begin: Assign Permissions on Finance Role - Backend Side*******************/

        /****************begin: Assign to Finance*******/
        $role = Role::findByName('finance');
        $role->givePermissionTo('create buyers');
        $role->givePermissionTo('edit buyers');
        $role->givePermissionTo('delete buyers');
        $role->givePermissionTo('publish buyers');
        $role->givePermissionTo('unpublish buyers');

        $role->givePermissionTo('create buyer list');
        $role->givePermissionTo('edit buyer list');
        $role->givePermissionTo('delete buyer list');
        $role->givePermissionTo('publish buyer list');
        $role->givePermissionTo('unpublish buyer list');

        $role->givePermissionTo('create suppliers');
        $role->givePermissionTo('edit suppliers');
        $role->givePermissionTo('delete suppliers');
        $role->givePermissionTo('publish suppliers');
        $role->givePermissionTo('unpublish suppliers');

        $role->givePermissionTo('create supplier list');
        $role->givePermissionTo('edit supplier list');
        $role->givePermissionTo('delete supplier list');
        $role->givePermissionTo('publish supplier list');
        $role->givePermissionTo('unpublish supplier list');

        $role->givePermissionTo('create supplier transaction charges');
        $role->givePermissionTo('edit supplier transaction charges');
        $role->givePermissionTo('delete supplier transaction charges');
        $role->givePermissionTo('publish supplier transaction charges');
        $role->givePermissionTo('unpublish supplier transaction charges');

        $role->givePermissionTo('create orders');
        $role->givePermissionTo('edit orders');
        $role->givePermissionTo('delete orders');
        $role->givePermissionTo('publish orders');
        $role->givePermissionTo('unpublish orders');

        $role->givePermissionTo('create order list');
        $role->givePermissionTo('edit order list');
        $role->givePermissionTo('delete order list');
        $role->givePermissionTo('publish order list');
        $role->givePermissionTo('unpublish order list');

        $role->givePermissionTo('create transaction list');
        $role->givePermissionTo('edit transaction list');
        $role->givePermissionTo('delete transaction list');
        $role->givePermissionTo('publish transaction list');
        $role->givePermissionTo('unpublish transaction list');

        $role->givePermissionTo('create disbursement list');
        $role->givePermissionTo('edit disbursement list');
        $role->givePermissionTo('delete disbursement list');
        $role->givePermissionTo('publish disbursement list');
        $role->givePermissionTo('unpublish disbursement list');

        $role->givePermissionTo('create group transaction list');
        $role->givePermissionTo('edit group transaction list');
        $role->givePermissionTo('delete group transaction list');
        $role->givePermissionTo('publish group transaction list');
        $role->givePermissionTo('unpublish group transaction list');
        /****************end: Assign to Finance*******/

        /****************end: Assign Permissions on Finance Role - Backend Side*******************/

    }
}
