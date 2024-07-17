<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Schema::disableForeignKeyConstraints();

        Permission::truncate();

        // create permissions
        Permission::create(['name' => 'create category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);
        Permission::create(['name' => 'publish category']);
        Permission::create(['name' => 'unpublish category']);

        Permission::create(['name' => 'create sub-category']);
        Permission::create(['name' => 'edit sub-category']);
        Permission::create(['name' => 'delete sub-category']);
        Permission::create(['name' => 'publish sub-category']);
        Permission::create(['name' => 'unpublish sub-category']);

        Permission::create(['name' => 'create brands']);
        Permission::create(['name' => 'edit brands']);
        Permission::create(['name' => 'delete brands']);
        Permission::create(['name' => 'publish brands']);
        Permission::create(['name' => 'unpublish brands']);

        Permission::create(['name' => 'create units']);
        Permission::create(['name' => 'edit units']);
        Permission::create(['name' => 'delete units']);
        Permission::create(['name' => 'publish units']);
        Permission::create(['name' => 'unpublish units']);

        Permission::create(['name' => 'create charges']);
        Permission::create(['name' => 'edit charges']);
        Permission::create(['name' => 'delete charges']);
        Permission::create(['name' => 'publish charges']);
        Permission::create(['name' => 'unpublish charges']);

        Permission::create(['name' => 'create available banks']);
        Permission::create(['name' => 'edit available banks']);
        Permission::create(['name' => 'delete available banks']);
        Permission::create(['name' => 'publish available banks']);
        Permission::create(['name' => 'unpublish available banks']);

        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'publish users']);
        Permission::create(['name' => 'unpublish users']);

        Permission::create(['name' => 'create suppliers']);
        Permission::create(['name' => 'edit suppliers']);
        Permission::create(['name' => 'delete suppliers']);
        Permission::create(['name' => 'publish suppliers']);
        Permission::create(['name' => 'unpublish suppliers']);

        Permission::create(['name' => 'create supplier list']);
        Permission::create(['name' => 'edit supplier list']);
        Permission::create(['name' => 'delete supplier list']);
        Permission::create(['name' => 'publish supplier list']);
        Permission::create(['name' => 'unpublish supplier list']);

        Permission::create(['name' => 'create supplier transaction charges']);
        Permission::create(['name' => 'edit supplier transaction charges']);
        Permission::create(['name' => 'delete supplier transaction charges']);
        Permission::create(['name' => 'publish supplier transaction charges']);
        Permission::create(['name' => 'unpublish supplier transaction charges']);

        Permission::create(['name' => 'create buyers']);
        Permission::create(['name' => 'edit buyers']);
        Permission::create(['name' => 'delete buyers']);
        Permission::create(['name' => 'publish buyers']);
        Permission::create(['name' => 'unpublish buyers']);

        Permission::create(['name' => 'create buyer list']);
        Permission::create(['name' => 'edit buyer list']);
        Permission::create(['name' => 'delete buyer list']);
        Permission::create(['name' => 'publish buyer list']);
        Permission::create(['name' => 'unpublish buyer list']);

        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);
        Permission::create(['name' => 'publish products']);
        Permission::create(['name' => 'unpublish products']);

        Permission::create(['name' => 'create rfqs']);
        Permission::create(['name' => 'edit rfqs']);
        Permission::create(['name' => 'delete rfqs']);
        Permission::create(['name' => 'publish rfqs']);
        Permission::create(['name' => 'unpublish rfqs']);

        Permission::create(['name' => 'create group rfqs']);
        Permission::create(['name' => 'edit group rfqs']);
        Permission::create(['name' => 'delete group rfqs']);
        Permission::create(['name' => 'publish group rfqs']);
        Permission::create(['name' => 'unpublish group rfqs']);

        Permission::create(['name' => 'create quotes']);
        Permission::create(['name' => 'edit quotes']);
        Permission::create(['name' => 'delete quotes']);
        Permission::create(['name' => 'publish quotes']);
        Permission::create(['name' => 'unpublish quotes']);

        Permission::create(['name' => 'create orders']);
        Permission::create(['name' => 'edit orders']);
        Permission::create(['name' => 'delete orders']);
        Permission::create(['name' => 'publish orders']);
        Permission::create(['name' => 'unpublish orders']);

        Permission::create(['name' => 'create order list']);
        Permission::create(['name' => 'edit order list']);
        Permission::create(['name' => 'delete order list']);
        Permission::create(['name' => 'publish order list']);
        Permission::create(['name' => 'unpublish order list']);

        Permission::create(['name' => 'create transaction list']);
        Permission::create(['name' => 'edit transaction list']);
        Permission::create(['name' => 'delete transaction list']);
        Permission::create(['name' => 'publish transaction list']);
        Permission::create(['name' => 'unpublish transaction list']);

        Permission::create(['name' => 'create disbursement list']);
        Permission::create(['name' => 'edit disbursement list']);
        Permission::create(['name' => 'delete disbursement list']);
        Permission::create(['name' => 'publish disbursement list']);
        Permission::create(['name' => 'unpublish disbursement list']);

        Permission::create(['name' => 'create payment groups']);
        Permission::create(['name' => 'edit payment groups']);
        Permission::create(['name' => 'delete payment groups']);
        Permission::create(['name' => 'publish payment groups']);
        Permission::create(['name' => 'unpublish payment groups']);

        Permission::create(['name' => 'create payment terms']);
        Permission::create(['name' => 'edit payment terms']);
        Permission::create(['name' => 'delete payment terms']);
        Permission::create(['name' => 'publish payment terms']);
        Permission::create(['name' => 'unpublish payment terms']);

        Permission::create(['name' => 'create department']);
        Permission::create(['name' => 'edit department']);
        Permission::create(['name' => 'delete department']);
        Permission::create(['name' => 'publish department']);
        Permission::create(['name' => 'unpublish department']);

        Permission::create(['name' => 'create designation']);
        Permission::create(['name' => 'edit designation']);
        Permission::create(['name' => 'delete designation']);
        Permission::create(['name' => 'publish designation']);
        Permission::create(['name' => 'unpublish designation']);

        Permission::create(['name' => 'create group trading']);
        Permission::create(['name' => 'edit group trading']);
        Permission::create(['name' => 'delete group trading']);
        Permission::create(['name' => 'publish group trading']);
        Permission::create(['name' => 'unpublish group trading']);

        Permission::create(['name' => 'create subscribed users']);
        Permission::create(['name' => 'edit subscribed users']);
        Permission::create(['name' => 'delete subscribed users']);
        Permission::create(['name' => 'publish subscribed users']);
        Permission::create(['name' => 'unpublish subscribed users']);

        Permission::create(['name' => 'create newsletter users']);
        Permission::create(['name' => 'edit newsletter users']);
        Permission::create(['name' => 'delete newsletter users']);
        Permission::create(['name' => 'publish newsletter users']);
        Permission::create(['name' => 'unpublish newsletter users']);

        Permission::create(['name' => 'create invite buyer']);
        Permission::create(['name' => 'edit invite buyer']);
        Permission::create(['name' => 'delete invite buyer']);
        Permission::create(['name' => 'publish invite buyer']);
        Permission::create(['name' => 'unpublish invite buyer']);

        Permission::create(['name' => 'create invite supplier']);
        Permission::create(['name' => 'edit invite supplier']);
        Permission::create(['name' => 'delete invite supplier']);
        Permission::create(['name' => 'publish invite supplier']);
        Permission::create(['name' => 'unpublish invite supplier']);

        Permission::create(['name' => 'create supplier address']);
        Permission::create(['name' => 'edit supplier address']);
        Permission::create(['name' => 'delete supplier address']);
        Permission::create(['name' => 'publish supplier address']);
        Permission::create(['name' => 'unpublish supplier address']);

        Permission::create(['name' => 'create contact']);
        Permission::create(['name' => 'edit contact']);
        Permission::create(['name' => 'delete contact']);
        Permission::create(['name' => 'publish contact']);
        Permission::create(['name' => 'unpublish contact']);

        Permission::create(['name' => 'create notifications']);
        Permission::create(['name' => 'edit notifications']);
        Permission::create(['name' => 'delete notifications']);
        Permission::create(['name' => 'publish notifications']);
        Permission::create(['name' => 'unpublish notifications']);

        Permission::create(['name' => 'create term-and-conditions']);
        Permission::create(['name' => 'edit term-and-conditions']);
        Permission::create(['name' => 'delete term-and-conditions']);
        Permission::create(['name' => 'publish term-and-conditions']);
        Permission::create(['name' => 'unpublish term-and-conditions']);

        Permission::create(['name' => 'create translations']);
        Permission::create(['name' => 'edit translations']);
        Permission::create(['name' => 'delete translations']);
        Permission::create(['name' => 'publish translations']);
        Permission::create(['name' => 'unpublish translations']);

        Permission::create(['name' => 'create group transaction list']);
        Permission::create(['name' => 'edit group transaction list']);
        Permission::create(['name' => 'delete group transaction list']);
        Permission::create(['name' => 'publish group transaction list']);
        Permission::create(['name' => 'unpublish group transaction list']);

        Permission::create(['name' => 'create check-price']);
        Permission::create(['name' => 'edit check-price']);
        Permission::create(['name' => 'delete check-price']);
        Permission::create(['name' => 'publish check-price']);
        Permission::create(['name' => 'unpublish check-price']);

        Permission::create(['name' => 'create group-chat']);
        Permission::create(['name' => 'edit group-chat']);
        Permission::create(['name' => 'delete group-chat']);
        Permission::create(['name' => 'publish group-chat']);
        Permission::create(['name' => 'unpublish group-chat']);
        /*******************begin: Buyer Permissions **************************/

        Permission::create(['name' => 'create buyer rfqs']);
        Permission::create(['name' => 'edit buyer rfqs']);
        Permission::create(['name' => 'delete buyer rfqs']);
        Permission::create(['name' => 'publish buyer rfqs']);
        Permission::create(['name' => 'unpublish buyer rfqs']);
        Permission::create(['name' => 'publish-only buyer rfqs']);
        Permission::create(['name' => 'list-all buyer rfqs']);

        Permission::create(['name' => 'create buyer orders']);
        Permission::create(['name' => 'edit buyer orders']);
        Permission::create(['name' => 'delete buyer orders']);
        Permission::create(['name' => 'publish buyer orders']);
        Permission::create(['name' => 'unpublish buyer orders']);
        Permission::create(['name' => 'publish-only buyer orders']);
        Permission::create(['name' => 'list-all buyer orders']);

        Permission::create(['name' => 'create buyer quotes']);
        Permission::create(['name' => 'edit buyer quotes']);
        Permission::create(['name' => 'delete buyer quotes']);
        Permission::create(['name' => 'publish buyer quotes']);
        Permission::create(['name' => 'unpublish buyer quotes']);
        Permission::create(['name' => 'publish-only buyer quotes']);
        Permission::create(['name' => 'list-all buyer quotes']);

        Permission::create(['name' => 'create buyer address']);
        Permission::create(['name' => 'edit buyer address']);
        Permission::create(['name' => 'delete buyer address']);
        Permission::create(['name' => 'publish buyer address']);
        Permission::create(['name' => 'unpublish buyer address']);
        Permission::create(['name' => 'publish-only buyer address']);
        Permission::create(['name' => 'list-all buyer address']);

        Permission::create(['name' => 'create buyer groups']);
        Permission::create(['name' => 'edit buyer groups']);
        Permission::create(['name' => 'delete buyer groups']);
        Permission::create(['name' => 'publish buyer groups']);
        Permission::create(['name' => 'unpublish buyer groups']);
        Permission::create(['name' => 'list-all buyer groups']);

        Permission::create(['name' => 'create buyer company credits']);
        Permission::create(['name' => 'edit buyer company credits']);
        Permission::create(['name' => 'delete buyer company credits']);
        Permission::create(['name' => 'publish buyer company credits']);
        Permission::create(['name' => 'unpublish buyer company credits']);

        Permission::create(['name' => 'create buyer personal info']);
        Permission::create(['name' => 'edit buyer personal info']);
        Permission::create(['name' => 'delete buyer personal info']);
        Permission::create(['name' => 'publish buyer personal info']);
        Permission::create(['name' => 'unpublish buyer personal info']);

        Permission::create(['name' => 'create buyer company info']);
        Permission::create(['name' => 'edit buyer company info']);
        Permission::create(['name' => 'delete buyer company info']);
        Permission::create(['name' => 'publish buyer company info']);
        Permission::create(['name' => 'unpublish buyer company info']);

        Permission::create(['name' => 'create buyer side invite']);
        Permission::create(['name' => 'edit buyer side invite']);
        Permission::create(['name' => 'delete buyer side invite']);
        Permission::create(['name' => 'publish buyer side invite']);
        Permission::create(['name' => 'unpublish buyer side invite']);

        Permission::create(['name' => 'create buyer change password']);
        Permission::create(['name' => 'edit buyer change password']);
        Permission::create(['name' => 'delete buyer change password']);
        Permission::create(['name' => 'publish buyer change password']);
        Permission::create(['name' => 'unpublish buyer change password']);

        Permission::create(['name' => 'create buyer settings']);
        Permission::create(['name' => 'edit buyer settings']);
        Permission::create(['name' => 'delete buyer settings']);
        Permission::create(['name' => 'publish buyer settings']);
        Permission::create(['name' => 'unpublish buyer settings']);

        Permission::create(['name' => 'create buyer preferences']);
        Permission::create(['name' => 'edit buyer preferences']);
        Permission::create(['name' => 'delete buyer preferences']);
        Permission::create(['name' => 'publish buyer preferences']);
        Permission::create(['name' => 'unpublish buyer preferences']);

        Permission::create(['name' => 'create buyer payments']);
        Permission::create(['name' => 'edit buyer payments']);
        Permission::create(['name' => 'delete buyer payments']);
        Permission::create(['name' => 'publish buyer payments']);
        Permission::create(['name' => 'unpublish buyer payments']);
        Permission::create(['name' => 'publish-only buyer payments']);
        Permission::create(['name' => 'list-all buyer payments']);

        Permission::create(['name' => 'create buyer payment term']);
        Permission::create(['name' => 'edit buyer payment term']);
        Permission::create(['name' => 'delete buyer payment term']);
        Permission::create(['name' => 'publish buyer payment term']);
        Permission::create(['name' => 'unpublish buyer payment term']);

        Permission::create(['name' => 'create buyer bank details']);
        Permission::create(['name' => 'edit buyer bank details']);
        Permission::create(['name' => 'delete buyer bank details']);
        Permission::create(['name' => 'publish buyer bank details']);
        Permission::create(['name' => 'unpublish buyer bank details']);
        Permission::create(['name' => 'publish-only buyer bank details']);
        Permission::create(['name' => 'list-all buyer bank details']);

        Permission::create(['name' => 'create buyer users']);
        Permission::create(['name' => 'edit buyer users']);
        Permission::create(['name' => 'delete buyer users']);
        Permission::create(['name' => 'publish buyer users']);
        Permission::create(['name' => 'unpublish buyer users']);
        Permission::create(['name' => 'publish-only buyer users']);
        Permission::create(['name' => 'list-all buyer users']);

        Permission::create(['name' => 'create buyer roles and permissions']);
        Permission::create(['name' => 'edit buyer roles and permissions']);
        Permission::create(['name' => 'delete buyer roles and permissions']);
        Permission::create(['name' => 'publish buyer roles and permissions']);
        Permission::create(['name' => 'unpublish buyer roles and permissions']);

        Permission::create(['name' => 'create buyer approval configurations']);
        Permission::create(['name' => 'edit buyer approval configurations']);
        Permission::create(['name' => 'delete buyer approval configurations']);
        Permission::create(['name' => 'publish buyer approval configurations']);
        Permission::create(['name' => 'unpublish buyer approval configurations']);

        Permission::create(['name' => 'create buyer join group']);
        Permission::create(['name' => 'edit buyer join group']);
        Permission::create(['name' => 'delete buyer join group']);
        Permission::create(['name' => 'publish buyer join group']);
        Permission::create(['name' => 'unpublish buyer join group']);

        Permission::create(['name' => 'create buyer leave group']);
        Permission::create(['name' => 'edit buyer leave group']);
        Permission::create(['name' => 'delete buyer leave group']);
        Permission::create(['name' => 'publish buyer leave group']);
        Permission::create(['name' => 'unpublish buyer leave group']);

        Permission::create(['name' => 'create buyer profile']);
        Permission::create(['name' => 'edit buyer profile']);
        Permission::create(['name' => 'delete buyer profile']);
        Permission::create(['name' => 'publish buyer profile']);
        Permission::create(['name' => 'unpublish buyer profile']);

        Permission::create(['name' => 'create buyer chat']);
        Permission::create(['name' => 'edit buyer chat']);
        Permission::create(['name' => 'delete buyer chat']);
        Permission::create(['name' => 'publish buyer chat']);
        Permission::create(['name' => 'unpublish buyer chat']);

        Permission::create(['name' => 'create buyer preferred supplier']);
        Permission::create(['name' => 'edit buyer preferred supplier']);
        Permission::create(['name' => 'delete buyer preferred supplier']);
        Permission::create(['name' => 'publish buyer preferred supplier']);
        Permission::create(['name' => 'unpublish buyer preferred supplier']);

        Permission::create(['name' => 'approval buyer approval configurations']);
        Permission::create(['name' => 'toggle buyer approval configurations']);


        /*******************end: Buyer Permissions **************************/

        //************New permissions will start here: **************//


        Schema::enableForeignKeyConstraints();

    }
}
