<?php

namespace Database\Seeders;

use Database\Seeders\Roles\AgentSeeder;
use Database\Seeders\Roles\PermissionAssignRoleSeeder;
use Database\Seeders\Roles\PermissionAssignUserSeeder;
use Database\Seeders\Roles\PermissionSeeder;
use Database\Seeders\Roles\RolesSeeder;
use Database\Seeders\Roles\SystemRolesSeeder;
use Database\Seeders\Roles\PermissionGroupSeeder;
use Illuminate\Database\Seeder;

class SetupPermissionRoleSeeder extends Seeder
{
    /**
     * Run to seed all permission related seeders for first time.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(SystemRolesSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PermissionAssignRoleSeeder::class);
        //$this->call(AgentSeeder::class);
        $this->call(PermissionAssignUserSeeder::class);
        $this->call(PermissionGroupSeeder::class);


    }
}
