<?php

namespace Database\Seeders;

use Database\Seeders\Roles\PermissionAssignFinanceRoleSeeder;
use Database\Seeders\Roles\PermissionSetFinanceRoleSeeder;
use Database\Seeders\Roles\RolesSeeder;
use Illuminate\Database\Seeder;

class SetupFinanceRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(PermissionAssignFinanceRoleSeeder::class);
        $this->call(PermissionSetFinanceRoleSeeder::class);
    }
}
