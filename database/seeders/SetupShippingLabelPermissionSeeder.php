<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupShippingLabelPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Permission::create(['name' => 'publish shipping-label']);
         Permission::create(['name' => 'create shipping-label']);

         $role = Role::findByName('admin');
         $role->givePermissionTo('create shipping-label');
         $role->givePermissionTo('publish shipping-label');

         $role = Role::findByName('jne');
         $role->givePermissionTo('create shipping-label');
         $role->givePermissionTo('publish shipping-label');
    }
}
