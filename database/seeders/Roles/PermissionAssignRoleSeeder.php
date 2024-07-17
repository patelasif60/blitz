<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;


class PermissionAssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();

        DB::table('role_has_permissions')->truncate();

        /*********begin: Assign permission to super admin role*****************/
        $role = Role::findByName('super-admin');
        $role->givePermissionTo('create category');
        $role->givePermissionTo('edit category');
        $role->givePermissionTo('delete category');
        $role->givePermissionTo('publish category');
        $role->givePermissionTo('unpublish category');

        $role->givePermissionTo('create sub-category');
        $role->givePermissionTo('edit sub-category');
        $role->givePermissionTo('delete sub-category');
        $role->givePermissionTo('publish sub-category');
        $role->givePermissionTo('unpublish sub-category');

        $role->givePermissionTo('create brands');
        $role->givePermissionTo('edit brands');
        $role->givePermissionTo('delete brands');
        $role->givePermissionTo('publish brands');
        $role->givePermissionTo('unpublish brands');

        $role->givePermissionTo('create units');
        $role->givePermissionTo('edit units');
        $role->givePermissionTo('delete units');
        $role->givePermissionTo('publish units');
        $role->givePermissionTo('unpublish units');

        $role->givePermissionTo('create charges');
        $role->givePermissionTo('edit charges');
        $role->givePermissionTo('delete charges');
        $role->givePermissionTo('publish charges');
        $role->givePermissionTo('unpublish charges');

        $role->givePermissionTo('create available banks');
        $role->givePermissionTo('edit available banks');
        $role->givePermissionTo('delete available banks');
        $role->givePermissionTo('publish available banks');
        $role->givePermissionTo('unpublish available banks');

        $role->givePermissionTo('create users');
        $role->givePermissionTo('edit users');
        $role->givePermissionTo('delete users');
        $role->givePermissionTo('publish users');
        $role->givePermissionTo('unpublish users');

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

        $role->givePermissionTo('create products');
        $role->givePermissionTo('edit products');
        $role->givePermissionTo('delete products');
        $role->givePermissionTo('publish products');
        $role->givePermissionTo('unpublish products');

        $role->givePermissionTo('create rfqs');
        $role->givePermissionTo('edit rfqs');
        $role->givePermissionTo('delete rfqs');
        $role->givePermissionTo('publish rfqs');
        $role->givePermissionTo('unpublish rfqs');

        $role->givePermissionTo('create group rfqs');
        $role->givePermissionTo('edit group rfqs');
        $role->givePermissionTo('delete group rfqs');
        $role->givePermissionTo('publish group rfqs');
        $role->givePermissionTo('unpublish group rfqs');

        $role->givePermissionTo('create quotes');
        $role->givePermissionTo('edit quotes');
        $role->givePermissionTo('delete quotes');
        $role->givePermissionTo('publish quotes');
        $role->givePermissionTo('unpublish quotes');

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

        $role->givePermissionTo('create payment groups');
        $role->givePermissionTo('edit payment groups');
        $role->givePermissionTo('delete payment groups');
        $role->givePermissionTo('publish payment groups');
        $role->givePermissionTo('unpublish payment groups');

        $role->givePermissionTo('create payment terms');
        $role->givePermissionTo('edit payment terms');
        $role->givePermissionTo('delete payment terms');
        $role->givePermissionTo('publish payment terms');
        $role->givePermissionTo('unpublish payment terms');

        $role->givePermissionTo('create department');
        $role->givePermissionTo('edit department');
        $role->givePermissionTo('delete department');
        $role->givePermissionTo('publish department');
        $role->givePermissionTo('unpublish department');

        $role->givePermissionTo('create designation');
        $role->givePermissionTo('edit designation');
        $role->givePermissionTo('delete designation');
        $role->givePermissionTo('publish designation');
        $role->givePermissionTo('unpublish designation');

        $role->givePermissionTo('create subscribed users');
        $role->givePermissionTo('edit subscribed users');
        $role->givePermissionTo('delete subscribed users');
        $role->givePermissionTo('publish subscribed users');
        $role->givePermissionTo('unpublish subscribed users');

        $role->givePermissionTo('create group trading');
        $role->givePermissionTo('edit group trading');
        $role->givePermissionTo('delete group trading');
        $role->givePermissionTo('publish group trading');
        $role->givePermissionTo('unpublish group trading');

        $role->givePermissionTo('create newsletter users');
        $role->givePermissionTo('edit newsletter users');
        $role->givePermissionTo('delete newsletter users');
        $role->givePermissionTo('publish newsletter users');
        $role->givePermissionTo('unpublish newsletter users');

        $role->givePermissionTo('create invite buyer');
        $role->givePermissionTo('edit invite buyer');
        $role->givePermissionTo('delete invite buyer');
        $role->givePermissionTo('publish invite buyer');
        $role->givePermissionTo('unpublish invite buyer');

        $role->givePermissionTo('create invite supplier');
        $role->givePermissionTo('edit invite supplier');
        $role->givePermissionTo('delete invite supplier');
        $role->givePermissionTo('publish invite supplier');
        $role->givePermissionTo('unpublish invite supplier');

        $role->givePermissionTo('create contact');
        $role->givePermissionTo('edit contact');
        $role->givePermissionTo('delete contact');
        $role->givePermissionTo('publish contact');
        $role->givePermissionTo('unpublish contact');

        $role->givePermissionTo('create notifications');
        $role->givePermissionTo('edit notifications');
        $role->givePermissionTo('delete notifications');
        $role->givePermissionTo('publish notifications');
        $role->givePermissionTo('unpublish notifications');

        $role->givePermissionTo('create term-and-conditions');
        $role->givePermissionTo('edit term-and-conditions');
        $role->givePermissionTo('delete term-and-conditions');
        $role->givePermissionTo('publish term-and-conditions');
        $role->givePermissionTo('unpublish term-and-conditions');

        $role->givePermissionTo('create translations');
        $role->givePermissionTo('edit translations');
        $role->givePermissionTo('delete translations');
        $role->givePermissionTo('publish translations');
        $role->givePermissionTo('unpublish translations');

        $role->givePermissionTo('create group transaction list');
        $role->givePermissionTo('edit group transaction list');
        $role->givePermissionTo('delete group transaction list');
        $role->givePermissionTo('publish group transaction list');
        $role->givePermissionTo('unpublish group transaction list');

        $role->givePermissionTo('create check-price');
        $role->givePermissionTo('edit check-price');
        $role->givePermissionTo('delete check-price');
        $role->givePermissionTo('publish check-price');
        $role->givePermissionTo('unpublish check-price');

        $role->givePermissionTo('create group-chat');
        $role->givePermissionTo('edit group-chat');
        $role->givePermissionTo('delete group-chat');
        $role->givePermissionTo('publish group-chat');
        $role->givePermissionTo('unpublish group-chat');

        /*********end: Assign permission to super admin role*****************/


        /*********begin: Assign permission to admin role*****************/
        $role = Role::findByName('admin');
        $role->givePermissionTo('create category');
        $role->givePermissionTo('edit category');
        $role->givePermissionTo('delete category');
        $role->givePermissionTo('publish category');
        $role->givePermissionTo('unpublish category');

        $role->givePermissionTo('create sub-category');
        $role->givePermissionTo('edit sub-category');
        $role->givePermissionTo('delete sub-category');
        $role->givePermissionTo('publish sub-category');
        $role->givePermissionTo('unpublish sub-category');

        $role->givePermissionTo('create brands');
        $role->givePermissionTo('edit brands');
        $role->givePermissionTo('delete brands');
        $role->givePermissionTo('publish brands');
        $role->givePermissionTo('unpublish brands');

        $role->givePermissionTo('create units');
        $role->givePermissionTo('edit units');
        $role->givePermissionTo('delete units');
        $role->givePermissionTo('publish units');
        $role->givePermissionTo('unpublish units');

        $role->givePermissionTo('create charges');
        $role->givePermissionTo('edit charges');
        $role->givePermissionTo('delete charges');
        $role->givePermissionTo('publish charges');
        $role->givePermissionTo('unpublish charges');

        $role->givePermissionTo('create available banks');
        $role->givePermissionTo('edit available banks');
        $role->givePermissionTo('delete available banks');
        $role->givePermissionTo('publish available banks');
        $role->givePermissionTo('unpublish available banks');

        $role->givePermissionTo('create users');
        $role->givePermissionTo('edit users');
        $role->givePermissionTo('delete users');
        $role->givePermissionTo('publish users');
        $role->givePermissionTo('unpublish users');

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

        $role->givePermissionTo('create products');
        $role->givePermissionTo('edit products');
        $role->givePermissionTo('delete products');
        $role->givePermissionTo('publish products');
        $role->givePermissionTo('unpublish products');

        $role->givePermissionTo('create rfqs');
        $role->givePermissionTo('edit rfqs');
        $role->givePermissionTo('delete rfqs');
        $role->givePermissionTo('publish rfqs');
        $role->givePermissionTo('unpublish rfqs');

        $role->givePermissionTo('create group rfqs');
        $role->givePermissionTo('edit group rfqs');
        $role->givePermissionTo('delete group rfqs');
        $role->givePermissionTo('publish group rfqs');
        $role->givePermissionTo('unpublish group rfqs');

        $role->givePermissionTo('create quotes');
        $role->givePermissionTo('edit quotes');
        $role->givePermissionTo('delete quotes');
        $role->givePermissionTo('publish quotes');
        $role->givePermissionTo('unpublish quotes');

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

        $role->givePermissionTo('create payment groups');
        $role->givePermissionTo('edit payment groups');
        $role->givePermissionTo('delete payment groups');
        $role->givePermissionTo('publish payment groups');
        $role->givePermissionTo('unpublish payment groups');

        $role->givePermissionTo('create payment terms');
        $role->givePermissionTo('edit payment terms');
        $role->givePermissionTo('delete payment terms');
        $role->givePermissionTo('publish payment terms');
        $role->givePermissionTo('unpublish payment terms');

        $role->givePermissionTo('create department');
        $role->givePermissionTo('edit department');
        $role->givePermissionTo('delete department');
        $role->givePermissionTo('publish department');
        $role->givePermissionTo('unpublish department');

        $role->givePermissionTo('create designation');
        $role->givePermissionTo('edit designation');
        $role->givePermissionTo('delete designation');
        $role->givePermissionTo('publish designation');
        $role->givePermissionTo('unpublish designation');

        $role->givePermissionTo('create group trading');
        $role->givePermissionTo('edit group trading');
        $role->givePermissionTo('delete group trading');
        $role->givePermissionTo('publish group trading');
        $role->givePermissionTo('unpublish group trading');

        $role->givePermissionTo('create subscribed users');
        $role->givePermissionTo('edit subscribed users');
        $role->givePermissionTo('delete subscribed users');
        $role->givePermissionTo('publish subscribed users');
        $role->givePermissionTo('unpublish subscribed users');

        $role->givePermissionTo('create newsletter users');
        $role->givePermissionTo('edit newsletter users');
        $role->givePermissionTo('delete newsletter users');
        $role->givePermissionTo('publish newsletter users');
        $role->givePermissionTo('unpublish newsletter users');

        $role->givePermissionTo('create invite buyer');
        $role->givePermissionTo('edit invite buyer');
        $role->givePermissionTo('delete invite buyer');
        $role->givePermissionTo('publish invite buyer');
        $role->givePermissionTo('unpublish invite buyer');

        $role->givePermissionTo('create invite supplier');
        $role->givePermissionTo('edit invite supplier');
        $role->givePermissionTo('delete invite supplier');
        $role->givePermissionTo('publish invite supplier');
        $role->givePermissionTo('unpublish invite supplier');

        $role->givePermissionTo('create contact');
        $role->givePermissionTo('edit contact');
        $role->givePermissionTo('delete contact');
        $role->givePermissionTo('publish contact');
        $role->givePermissionTo('unpublish contact');

        $role->givePermissionTo('create notifications');
        $role->givePermissionTo('edit notifications');
        $role->givePermissionTo('delete notifications');
        $role->givePermissionTo('publish notifications');
        $role->givePermissionTo('unpublish notifications');

        $role->givePermissionTo('create term-and-conditions');
        $role->givePermissionTo('edit term-and-conditions');
        $role->givePermissionTo('delete term-and-conditions');
        $role->givePermissionTo('publish term-and-conditions');
        $role->givePermissionTo('unpublish term-and-conditions');

        $role->givePermissionTo('create translations');
        $role->givePermissionTo('edit translations');
        $role->givePermissionTo('delete translations');
        $role->givePermissionTo('publish translations');
        $role->givePermissionTo('unpublish translations');

        $role->givePermissionTo('create group transaction list');
        $role->givePermissionTo('edit group transaction list');
        $role->givePermissionTo('delete group transaction list');
        $role->givePermissionTo('publish group transaction list');
        $role->givePermissionTo('unpublish group transaction list');

        $role->givePermissionTo('create check-price');
        $role->givePermissionTo('edit check-price');
        $role->givePermissionTo('delete check-price');
        $role->givePermissionTo('publish check-price');
        $role->givePermissionTo('unpublish check-price');

        $role->givePermissionTo('create group-chat');
        $role->givePermissionTo('edit group-chat');
        $role->givePermissionTo('delete group-chat');
        $role->givePermissionTo('publish group-chat');
        $role->givePermissionTo('unpublish group-chat');

        /*********end: Assign permission to admin role*****************/



        /*********begin: Assign permission to supplier role*****************/
        $role = Role::findByName('supplier');
        $role->givePermissionTo('edit supplier list');

        $role->givePermissionTo('create products');
        $role->givePermissionTo('edit products');
        $role->givePermissionTo('delete products');
        $role->givePermissionTo('publish products');
        $role->givePermissionTo('unpublish products');

        $role->givePermissionTo('create rfqs');
        $role->givePermissionTo('edit rfqs');
        $role->givePermissionTo('delete rfqs');
        $role->givePermissionTo('publish rfqs');
        $role->givePermissionTo('unpublish rfqs');

        $role->givePermissionTo('create group rfqs');
        $role->givePermissionTo('edit group rfqs');
        $role->givePermissionTo('delete group rfqs');
        $role->givePermissionTo('publish group rfqs');
        $role->givePermissionTo('unpublish group rfqs');

        $role->givePermissionTo('create quotes');
        $role->givePermissionTo('edit quotes');
        $role->givePermissionTo('delete quotes');
        $role->givePermissionTo('publish quotes');
        $role->givePermissionTo('unpublish quotes');

        $role->givePermissionTo('create order list');
        $role->givePermissionTo('edit order list');
        $role->givePermissionTo('delete order list');
        $role->givePermissionTo('publish order list');
        $role->givePermissionTo('unpublish order list');

        $role->givePermissionTo('create group trading');
        $role->givePermissionTo('edit group trading');
        $role->givePermissionTo('delete group trading');
        $role->givePermissionTo('publish group trading');
        $role->givePermissionTo('unpublish group trading');

        $role->givePermissionTo('create invite buyer');
        $role->givePermissionTo('edit invite buyer');
        $role->givePermissionTo('delete invite buyer');
        $role->givePermissionTo('publish invite buyer');
        $role->givePermissionTo('unpublish invite buyer');

        $role->givePermissionTo('create supplier address');
        $role->givePermissionTo('edit supplier address');
        $role->givePermissionTo('delete supplier address');
        $role->givePermissionTo('publish supplier address');
        $role->givePermissionTo('unpublish supplier address');

        $role->givePermissionTo('create notifications');
        $role->givePermissionTo('edit notifications');
        $role->givePermissionTo('delete notifications');
        $role->givePermissionTo('publish notifications');
        $role->givePermissionTo('unpublish notifications');

        $role->givePermissionTo('create group-chat');
        $role->givePermissionTo('edit group-chat');
        $role->givePermissionTo('delete group-chat');
        $role->givePermissionTo('publish group-chat');
        $role->givePermissionTo('unpublish group-chat');

        /*********end: Assign permission to supplier role*****************/




        /*********begin: Assign permission to agent role*****************/
        $role = Role::findByName('agent');
        //$role->givePermissionTo('create category');
        //$role->givePermissionTo('edit category');
        //$role->givePermissionTo('delete category');
        $role->givePermissionTo('publish category');
        //$role->givePermissionTo('unpublish category');

        $role->givePermissionTo('create sub-category');
        $role->givePermissionTo('edit sub-category');
        $role->givePermissionTo('delete sub-category');
        $role->givePermissionTo('publish sub-category');
        $role->givePermissionTo('unpublish sub-category');

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

        $role->givePermissionTo('create products');
        $role->givePermissionTo('edit products');
        $role->givePermissionTo('delete products');
        $role->givePermissionTo('publish products');
        $role->givePermissionTo('unpublish products');

        $role->givePermissionTo('create rfqs');
        $role->givePermissionTo('edit rfqs');
        $role->givePermissionTo('delete rfqs');
        $role->givePermissionTo('publish rfqs');
        $role->givePermissionTo('unpublish rfqs');

        $role->givePermissionTo('create group rfqs');
        $role->givePermissionTo('edit group rfqs');
        $role->givePermissionTo('delete group rfqs');
        $role->givePermissionTo('publish group rfqs');
        $role->givePermissionTo('unpublish group rfqs');

        $role->givePermissionTo('create quotes');
        $role->givePermissionTo('edit quotes');
        $role->givePermissionTo('delete quotes');
        $role->givePermissionTo('publish quotes');
        $role->givePermissionTo('unpublish quotes');

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

        $role->givePermissionTo('create group trading');
        $role->givePermissionTo('edit group trading');
        $role->givePermissionTo('delete group trading');
        $role->givePermissionTo('publish group trading');
        $role->givePermissionTo('unpublish group trading');

        $role->givePermissionTo('create invite buyer');
        $role->givePermissionTo('edit invite buyer');
        $role->givePermissionTo('delete invite buyer');
        $role->givePermissionTo('publish invite buyer');
        $role->givePermissionTo('unpublish invite buyer');

        $role->givePermissionTo('create invite supplier');
        $role->givePermissionTo('edit invite supplier');
        $role->givePermissionTo('delete invite supplier');
        $role->givePermissionTo('publish invite supplier');
        $role->givePermissionTo('unpublish invite supplier');

    /*********end: Assign permission to agent role*****************/


    /*********begine: Assign permission to Buyer role*****************/
        $role = Role::findByName('buyer');

        $role->givePermissionTo('create buyer rfqs');
        $role->givePermissionTo('edit buyer rfqs');
        $role->givePermissionTo('delete buyer rfqs');
        $role->givePermissionTo('publish buyer rfqs');
        $role->givePermissionTo('unpublish buyer rfqs');
        $role->givePermissionTo('publish-only buyer rfqs');
        $role->givePermissionTo('list-all buyer rfqs');

        $role->givePermissionTo('create buyer orders');
        $role->givePermissionTo('edit buyer orders');
        $role->givePermissionTo('delete buyer orders');
        $role->givePermissionTo('publish buyer orders');
        $role->givePermissionTo('unpublish buyer orders');
        $role->givePermissionTo('publish-only buyer orders');
        $role->givePermissionTo('list-all buyer orders');

        $role->givePermissionTo('create buyer quotes');
        $role->givePermissionTo('edit buyer quotes');
        $role->givePermissionTo('delete buyer quotes');
        $role->givePermissionTo('publish buyer quotes');
        $role->givePermissionTo('unpublish buyer quotes');
        $role->givePermissionTo('publish-only buyer quotes');
        $role->givePermissionTo('list-all buyer quotes');

        $role->givePermissionTo('create buyer address');
        $role->givePermissionTo('edit buyer address');
        $role->givePermissionTo('delete buyer address');
        $role->givePermissionTo('publish buyer address');
        $role->givePermissionTo('unpublish buyer address');
        $role->givePermissionTo('publish-only buyer address');
        $role->givePermissionTo('list-all buyer address');

        $role->givePermissionTo('create buyer groups');
        $role->givePermissionTo('edit buyer groups');
        $role->givePermissionTo('delete buyer groups');
        $role->givePermissionTo('publish buyer groups');
        $role->givePermissionTo('unpublish buyer groups');
        $role->givePermissionTo('list-all buyer groups');

        $role->givePermissionTo('create buyer company credits');
        $role->givePermissionTo('edit buyer company credits');
        $role->givePermissionTo('delete buyer company credits');
        $role->givePermissionTo('publish buyer company credits');
        $role->givePermissionTo('unpublish buyer company credits');

        $role->givePermissionTo('create buyer personal info');
        $role->givePermissionTo('edit buyer personal info');
        $role->givePermissionTo('delete buyer personal info');
        $role->givePermissionTo('publish buyer personal info');
        $role->givePermissionTo('unpublish buyer personal info');

        $role->givePermissionTo('create buyer company info');
        $role->givePermissionTo('edit buyer company info');
        $role->givePermissionTo('delete buyer company info');
        $role->givePermissionTo('publish buyer company info');
        $role->givePermissionTo('unpublish buyer company info');

        $role->givePermissionTo('create buyer side invite');
        $role->givePermissionTo('edit buyer side invite');
        $role->givePermissionTo('delete buyer side invite');
        $role->givePermissionTo('publish buyer side invite');
        $role->givePermissionTo('unpublish buyer side invite');

        $role->givePermissionTo('create buyer change password');
        $role->givePermissionTo('edit buyer change password');
        $role->givePermissionTo('delete buyer change password');
        $role->givePermissionTo('publish buyer change password');
        $role->givePermissionTo('unpublish buyer change password');

        $role->givePermissionTo('create buyer settings');
        $role->givePermissionTo('edit buyer settings');
        $role->givePermissionTo('delete buyer settings');
        $role->givePermissionTo('publish buyer settings');
        $role->givePermissionTo('unpublish buyer settings');

        $role->givePermissionTo('create buyer preferences');
        $role->givePermissionTo('edit buyer preferences');
        $role->givePermissionTo('delete buyer preferences');
        $role->givePermissionTo('publish buyer preferences');
        $role->givePermissionTo('unpublish buyer preferences');

        $role->givePermissionTo('create buyer payment term');
        $role->givePermissionTo('edit buyer payment term');
        $role->givePermissionTo('delete buyer payment term');
        $role->givePermissionTo('publish buyer payment term');
        $role->givePermissionTo('unpublish buyer payment term');

        $role->givePermissionTo('create buyer bank details');
        $role->givePermissionTo('edit buyer bank details');
        $role->givePermissionTo('delete buyer bank details');
        $role->givePermissionTo('publish buyer bank details');
        $role->givePermissionTo('unpublish buyer bank details');

        $role->givePermissionTo('create buyer users');
        $role->givePermissionTo('edit buyer users');
        $role->givePermissionTo('delete buyer users');
        $role->givePermissionTo('publish buyer users');
        $role->givePermissionTo('unpublish buyer users');
        $role->givePermissionTo('publish-only buyer users');
        $role->givePermissionTo('list-all buyer users');

        $role->givePermissionTo('create buyer roles and permissions');
        $role->givePermissionTo('edit buyer roles and permissions');
        $role->givePermissionTo('delete buyer roles and permissions');
        $role->givePermissionTo('publish buyer roles and permissions');
        $role->givePermissionTo('unpublish buyer roles and permissions');

        $role->givePermissionTo('create buyer approval configurations');
        $role->givePermissionTo('edit buyer approval configurations');
        $role->givePermissionTo('delete buyer approval configurations');
        $role->givePermissionTo('publish buyer approval configurations');
        $role->givePermissionTo('unpublish buyer approval configurations');

        $role->givePermissionTo('create buyer join group');
        $role->givePermissionTo('edit buyer join group');
        $role->givePermissionTo('delete buyer join group');
        $role->givePermissionTo('publish buyer join group');
        $role->givePermissionTo('unpublish buyer join group');

        $role->givePermissionTo('create buyer leave group');
        $role->givePermissionTo('edit buyer leave group');
        $role->givePermissionTo('delete buyer leave group');
        $role->givePermissionTo('publish buyer leave group');
        $role->givePermissionTo('unpublish buyer leave group');

        $role->givePermissionTo('create buyer payments');
        $role->givePermissionTo('edit buyer payments');
        $role->givePermissionTo('delete buyer payments');
        $role->givePermissionTo('publish buyer payments');
        $role->givePermissionTo('unpublish buyer payments');

        $role->givePermissionTo('create buyer profile');
        $role->givePermissionTo('edit buyer profile');
        $role->givePermissionTo('delete buyer profile');
        $role->givePermissionTo('publish buyer profile');
        $role->givePermissionTo('unpublish buyer profile');

        $role->givePermissionTo('create buyer chat');
        $role->givePermissionTo('edit buyer chat');
        $role->givePermissionTo('delete buyer chat');
        $role->givePermissionTo('publish buyer chat');
        $role->givePermissionTo('unpublish buyer chat');

        $role->givePermissionTo('create buyer preferred supplier');
        $role->givePermissionTo('edit buyer preferred supplier');
        $role->givePermissionTo('delete buyer preferred supplier');
        $role->givePermissionTo('publish buyer preferred supplier');
        $role->givePermissionTo('unpublish buyer preferred supplier');

        /****************** end: Assign permission to Buyer role ****************************/


        Schema::enableForeignKeyConstraints();

    }
}
