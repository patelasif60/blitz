<?php

namespace Database\Seeders\Roles;

use App\Models\SystemRole;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SystemRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        SystemRole::truncate();

        SystemRole::Insert([
            ['name' => 'back office', 'guard_name' => 'web', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'supplier office', 'guard_name' => 'web', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'front office', 'guard_name' => 'web', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
