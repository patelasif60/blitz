<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupRMEnhancementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Permission::UpdateOrCreate(['name' => 'list-all buyer side invite']);
        Permission::UpdateOrCreate(['name' => 'list-all buyer bank details']);
        Permission::UpdateOrCreate(['name' => 'list-all buyer preferred supplier']);

        $role = Role::findByName('buyer');


        $role->givePermissionTo('list-all buyer side invite');
        $role->givePermissionTo('list-all buyer bank details');
        $role->givePermissionTo('list-all buyer preferred supplier');
    }
}
