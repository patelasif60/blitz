<?php

namespace Database\Seeders;

use Database\Seeders\Roles\PermissionAssignJneRoleSeeder;
use Database\Seeders\Roles\PermissionSetJneRoleSeeder;
use Database\Seeders\Roles\RolesSeeder;
use Illuminate\Database\Seeder;

class SetupJneRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(PermissionAssignJneRoleSeeder::class);
        $this->call(PermissionSetJneRoleSeeder::class);
    }
}
