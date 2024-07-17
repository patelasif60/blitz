<?php

namespace Database\Seeders\Roles;

use App\Models\PermissionsGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        PermissionsGroup::truncate();

        $allPermissions = Permission::getPermissions();

        /***********begin: Main Groups Level 1**************/

        PermissionsGroup::create(['name' => 'buyer', 'display_name' => 'Buyer', 'parent_id' => 0, 'is_main' => PermissionsGroup::MAIN, 'level' => PermissionsGroup::LEVEL1]);
        PermissionsGroup::create(['name' => 'supplier', 'display_name' => 'Supplier', 'parent_id' => 0, 'is_main' => PermissionsGroup::MAIN, 'level' => PermissionsGroup::LEVEL1]);
        PermissionsGroup::create(['name' => 'admin', 'display_name' => 'Admin', 'parent_id' => 0, 'is_main' => PermissionsGroup::MAIN, 'level' => PermissionsGroup::LEVEL1]);

        /***********end: Main Groups Level 1**************/

        /***********begin: Buyer Sub Groups Level 2**************/

        $group = PermissionsGroup::where('name', PermissionsGroup::BUYER)->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'RFQ', 'display_name' => 'RFQ', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 1, 'is_active' => 1],
            ['name' => 'Order', 'display_name' => 'Order', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 2, 'is_active' => 1],
            ['name' => 'Quotes', 'display_name' => 'Quote', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 3, 'is_active' => 1],
            ['name' => 'Groups', 'display_name' => 'Group', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 4, 'is_active' => 1],
            ['name' => 'Payment', 'display_name' => 'Payment', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 5, 'is_active' => 1],
            ['name' => 'Address', 'display_name' => 'Address', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 6, 'is_active' => 1],
            ['name' => 'Finance', 'display_name' => 'Finance', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 7, 'is_active' => 1],
            ['name' => 'Profile', 'display_name' => 'Profile', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 8, 'is_active' => 0],
            ['name' => 'Settings', 'display_name' => 'Settings', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 9, 'is_active' => 1]

        ]);
        /***********end: Buyer Sub Groups Level 2**************/

        /***********begin: Buyer Sub Groups Level 3 Inheritance**************/

        /****begin: RFQ Module**/
        $group = PermissionsGroup::where('name', 'RFQ')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([

            ['name' => 'Place RFQ', 'display_name' => 'Place / View', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer rfqs','publish buyer rfqs'])->pluck('id')), 'is_active' => 1],
            ['name' => 'RFQ List', 'display_name' => 'RFQ List', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer rfqs','publish-only buyer rfqs'])->pluck('id')), 'is_active' => 0],
            ['name' => 'Update RFQ', 'display_name' => 'Update', 'parent_id' => $group, 'class_name' => 'alert-success', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 3, 'permissions' => json_encode($allPermissions->whereIn('name', ['edit buyer rfqs','publish buyer rfqs'])->pluck('id')), 'is_active' => 1],
            ['name' => 'Delete RFQ', 'display_name' => 'Cancel', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 4, 'permissions' => json_encode($allPermissions->whereIn('name', ['delete buyer rfqs','publish buyer rfqs'])->pluck('id')), 'is_active' => 1],
            ['name' => 'RFQ List-All', 'display_name' => 'List-All', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 5, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer rfqs','list-all buyer rfqs'])->pluck('id')), 'is_active' => 1]

        ]);
        /****end: RFQ Module**/

        /****begin: Order Module**/
        $group = PermissionsGroup::where('name', 'Order')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([

            ['name' => 'Place Order', 'display_name' => 'Place / View', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer orders','publish buyer orders'])->pluck('id')), 'is_active' => 1, 'related_permissions' => json_encode([13,23])],
            ['name' => 'Order List', 'display_name' => 'Order List', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer orders', 'publish-only buyer orders'])->pluck('id')), 'is_active' => 0, 'related_permissions' => null],
            ['name' => 'Update Order', 'display_name' => 'Update', 'parent_id' => $group, 'class_name' => 'alert-success', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 3, 'permissions' => json_encode($allPermissions->whereIn('name', ['edit buyer orders','publish buyer orders'])->pluck('id')), 'is_active' => 1, 'related_permissions' => null],
            ['name' => 'Order List-All', 'display_name' => 'List-All', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 4, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer orders','list-all buyer orders'])->pluck('id')), 'is_active' => 1, 'related_permissions' => null]

        ]);
        /****end: Order Module**/

        /****begin: Quotes Module**/
        $group = PermissionsGroup::where('name', 'Quotes')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Create Quote', 'display_name' => 'Quote Accept', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer quotes','publish buyer quotes','edit buyer quotes'])->pluck('id')), 'is_active' => 0, 'related_permissions' => null],
            ['name' => 'Quote List', 'display_name' => 'List', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer quotes','publish-only buyer quotes'])->pluck('id')), 'is_active' => 1, 'related_permissions' => json_encode([13])],
            ['name' => 'Quote List-All', 'display_name' => 'List-All', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 3, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer quotes','list-all buyer quotes'])->pluck('id')), 'is_active' => 1, 'related_permissions' => null]

        ]);
        /****end: Quotes Module**/

        /****begin: Groups Module**/
        $group = PermissionsGroup::where('name', 'Groups')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Join Group', 'display_name' => 'Join', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer join group','edit buyer join group', 'publish buyer join group', 'list-all buyer groups'])->pluck('id')),'related_permissions' => json_encode([13,18,23])],
            ['name' => 'Leave Group', 'display_name' => 'Leave', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['delete buyer join group'])->pluck('id')),'related_permissions' => null]

        ]);
        /****end: Groups Module**/

        /****begin: Payment Module**/
        $group = PermissionsGroup::where('name', 'Payment')->pluck('id')->first();
        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Payments', 'display_name' => 'Payment', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer payments','create buyer payments'])->pluck('id')), 'is_active' => 1],
            ['name' => 'Payments All', 'display_name' => 'Payment All', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer payments', 'list-all buyer payments'])->pluck('id')), 'is_active' => 1]
        ]);
        /****end: Payment Module**/

        /****begin: Address Module**/
        $group = PermissionsGroup::where('name', 'Address')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([

            ['name' => 'Create', 'display_name' => 'Create / View', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer address', 'publish buyer address'])->pluck('id'))],
            ['name' => 'Update', 'display_name' => 'Update', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['edit buyer address', 'publish buyer address'])->pluck('id'))],
            ['name' => 'List', 'display_name' => 'List', 'parent_id' => $group, 'class_name' => 'alert-success', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 3, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer address', 'publish-only buyer address'])->pluck('id'))],
            ['name' => 'Delete', 'display_name' => 'Delete', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 4, 'permissions' => json_encode($allPermissions->whereIn('name', ['delete buyer address', 'publish buyer address'])->pluck('id'))],
            ['name' => 'List-All', 'display_name' => 'List-All', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 3, 'permissions' => json_encode($allPermissions->whereIn('name', ['publish buyer address','list-all buyer address'])->pluck('id'))]

        ]);
        /****end: Address Module**/

        /****begin: Company Credit Module**/
        $group = PermissionsGroup::where('name', 'Finance')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Credit Use', 'display_name' => 'Credit Use', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['utilize buyer company credit'])->pluck('id')), 'is_active' => 1],
            ['name' => 'Apply For Loan', 'display_name' => 'Apply For Loan', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer company credits','edit buyer company credits','publish buyer company credits'])->pluck('id')), 'is_active' => 1]
        ]);
        /****end: Company Credit Module**/


        /****begin: Profile Module**/
        $group = PermissionsGroup::where('name', 'Profile')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Personal Information', 'display_name' => 'Personal Information', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer personal info', 'edit buyer personal info', 'publish buyer personal info', 'delete buyer personal info', 'publish buyer profile'])->pluck('id')), 'is_active' => 0],
            ['name' => 'Change Password', 'display_name' => 'Change Password', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer change password', 'edit buyer change password', 'publish buyer change password', 'delete buyer change password', 'publish buyer password'])->pluck('id')), 'is_active' => 0]
        ]);
        /****end: Profile Module**/

        /****begin: Settings Module**/
        $group = PermissionsGroup::where('name', 'Settings')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Preferences', 'display_name' => 'Preferences', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer preferences', 'edit buyer preferences', 'publish buyer preferences', 'delete buyer preferences', 'publish buyer settings'])->pluck('id'))],
            ['name' => 'Payment Terms', 'display_name' => 'Payment Terms', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer payment term', 'edit buyer payment term', 'publish buyer payment term', 'delete buyer payment term', 'publish buyer settings'])->pluck('id'))],
            ['name' => 'Company Information', 'display_name' => 'Company Information', 'parent_id' => $group, 'class_name' => 'alert-success', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 3, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer company info', 'edit buyer company info', 'publish buyer company info', 'delete buyer company info', 'publish buyer profile'])->pluck('id'))],
            ['name' => 'Role & Permissions', 'display_name' => 'Role & Permissions', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 4, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer roles and permissions', 'edit buyer roles and permissions', 'publish buyer roles and permissions', 'delete buyer roles and permissions', 'publish buyer settings'])->pluck('id'))],
            ['name' => 'Users', 'display_name' => 'Users', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 5, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer users', 'edit buyer users', 'publish buyer users', 'delete buyer users', 'publish buyer settings'])->pluck('id'))],
        ]);

        /****end: Settings Module**/

        /***************begin: New Group Chat and Inheritance *************/
        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Chat', 'display_name' => 'Chat', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 10, 'is_active' => 0]
        ]);

            /****begin: Chat Module**/
            $group = PermissionsGroup::where('name', 'Chat')->pluck('id')->first();

            PermissionsGroup::where('id', $group)->insert([
                ['name' => 'Chat-Permission', 'display_name' => 'Chat Access', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer chat', 'edit buyer chat', 'delete buyer chat', 'publish buyer chat', 'unpublish buyer chat'])->pluck('id')), 'is_active' => 0],

            ]);
            /****end: Chat Module**/

        /***************end: New Group Chat and Inheritance *************/

        /***************begin: Prefer supplier Group and Permission*****/

        $group = PermissionsGroup::where('name', 'Settings')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Preferred Supplier', 'display_name' => 'Preferred Supplier', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 6, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer preferred supplier', 'edit buyer preferred supplier', 'delete buyer preferred supplier', 'publish buyer preferred supplier', 'unpublish buyer preferred supplier', 'publish buyer settings','list-all buyer preferred supplier'])->pluck('id')), 'is_active' => 1]
        ]);

        /***********end: Buyer Sub Groups Level 3 Inheritance**************/

        /**begin: Bank details Group and permission  **********/

        $group = PermissionsGroup::where('name', 'Settings')->pluck('id')->first();
        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Bank Details', 'display_name' => 'Bank Details', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 7, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer bank details', 'edit buyer bank details', 'delete buyer bank details', 'publish buyer bank details', 'unpublish buyer bank details', 'publish buyer settings','list-all buyer bank details'])->pluck('id')), 'is_active' => 1]
        ]);

        /***********end: Bank details Group and permission **************/

        /**begin: Invite Buyers/Supplier Group and permission  **********/

        $group = PermissionsGroup::where('name', 'Settings')->pluck('id')->first();
        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Invite Buyers/Supplier', 'display_name' => 'Invite Buyers/Supplier', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 8, 'permissions' => json_encode($allPermissions->whereIn('name', ['create buyer side invite', 'edit buyer side invite', 'delete buyer side invite', 'publish buyer side invite', 'unpublish buyer side invite', 'publish buyer settings','list-all buyer side invite'])->pluck('id')), 'is_active' => 1]
        ]);

        /***********end: Invite Buyers/Supplier Group and permission **************/
        /***************begin: Approval Configuration Group and Permission*****/

        $group = PermissionsGroup::where('name', PermissionsGroup::BUYER)->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Approval', 'display_name' => 'Approval', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 10, 'is_active' => 1]
        ]);

        $group = PermissionsGroup::where('name', 'Approval')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'Approval Toggle', 'display_name' => 'Approval Toggel', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['toggle buyer approval configurations', 'publish buyer approval configurations'])->pluck('id')), 'is_active' => 1,'related_permissions' => json_encode([13,18,23])],
            ['name' => 'Approval Person', 'display_name' => 'Approval', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['approval buyer approval configurations', 'publish buyer approval configurations'])->pluck('id')), 'is_active' => 1, 'related_permissions' => null]

        ]);
        /***************end: Approval Configuration Group and Permission*****/
        /** begin: RFN Group and permission **/
        $group = PermissionsGroup::where('name', PermissionsGroup::BUYER)->pluck('id')->first();
        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'RFN', 'display_name' => 'RFN', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 11, 'is_active' => 1],
            ['name' => 'Global RFN', 'display_name' => 'Global RFN', 'parent_id' => $group, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL2, 'sort' => 12, 'is_active' => 1]
        ]);

        /****begin: RFN Module**/
        $group = PermissionsGroup::where('name', 'RFN')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([
            ['name' => 'buyer rfn create', 'display_name' => 'Create', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn create','buyer rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer rfn update', 'display_name' => 'Update', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn update','buyer rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer rfn cancel', 'display_name' => 'Cancel', 'parent_id' => $group, 'class_name' => 'alert-success', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 3, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn cancel','buyer rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer rfn convert global rfn', 'display_name' => 'Convert Global Rfn', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 4, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn convert global rfn','buyer rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer rfn to rfq', 'display_name' => 'Rfn To Rfq', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 5, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn to rfq','buyer rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer rfn list', 'display_name' => 'List', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 6, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn list','buyer rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer rfn list-all', 'display_name' => 'List-All', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 7, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn list-all','buyer rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer rfn multi convert to rfq', 'display_name' => 'Multi Rfn Convert To Rfq', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 8, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn multi convert to rfq','buyer rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer rfn reject', 'display_name' => 'Reject', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 9, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer rfn reject','buyer rfn publish'])->pluck('id')), 'is_active' => 1]
        ]);
        /****end: RFN Module**/
        /****begin:Global RFN Module**/
        $group = PermissionsGroup::where('name', 'Global RFN')->pluck('id')->first();

        PermissionsGroup::where('id', $group)->insert([

            ['name' => 'buyer global rfn create', 'display_name' => 'Create', 'parent_id' => $group, 'class_name' => 'alert-primary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 1, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer global rfn create','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer global rfn update', 'display_name' => 'Update', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 2, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer global rfn update','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer global rfn cancel', 'display_name' => 'Cancel', 'parent_id' => $group, 'class_name' => 'alert-success', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 3, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer global rfn cancel','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer request global rfn', 'display_name' => 'Request For Rfn', 'parent_id' => $group, 'class_name' => 'alert-secondary', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 4, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer request global rfn','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer global rfn to rfq', 'display_name' => 'Rfn To Rfq', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 5, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer global rfn to rfq','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer global rfn list', 'display_name' => 'List', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 6, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer global rfn list','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer global rfn list-all', 'display_name' => 'List-All', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 7, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer global rfn list-all','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer global rfn multi convert to rfq', 'display_name' => 'Multi Rfn Convert To Rfq', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 8, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer global rfn multi convert to rfq','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer edit request global rfn', 'display_name' => 'Rfn Edit Request', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 9, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer edit request global rfn','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer delete request global rfn', 'display_name' => 'Rfn Delete Request', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 91, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer delete request global rfn','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer List RFR Request', 'display_name' => 'List RFR Request', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 92, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer List RFR Request','buyer global rfn publish'])->pluck('id')), 'is_active' => 1],
            ['name' => 'buyer List All RFR Request', 'display_name' => 'List All RFR Request', 'parent_id' => $group, 'class_name' => 'alert-danger', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'level' => PermissionsGroup::LEVEL3, 'sort' => 93, 'permissions' => json_encode($allPermissions->whereIn('name', ['buyer List All RFR Request','buyer global rfn publish'])->pluck('id')), 'is_active' => 1]

        ]);
        /****end: Global RFN Module**/

        /** end: RFN Group and permission  **/

        Schema::enableForeignKeyConstraints();

    }
}
