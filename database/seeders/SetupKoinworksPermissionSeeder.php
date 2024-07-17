<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupKoinworksPermissionSeeder extends Seeder
{
    /**
     * For koinworks permission for Loan
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::UpdateOrCreate(['name' => 'utilize buyer company credit']);

        $role = Role::findByName('buyer');
        
        $role->givePermissionTo('utilize buyer company credit');
    }
}
