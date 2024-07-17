<?php

namespace Database\Seeders\Roles;

use App\Models\Category;
use App\Models\CustomPermission;
use App\Models\SystemRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('custom_permissions')->truncate();

        $categoryPermission = Category::all();

        $categoryPermission->each(function($category) {

            CustomPermission::create([
                'name'              =>  CustomPermission::CATAGEORY,
                'model_type'        =>  Category::class,
                'value'             =>  $category->id,
                'guard_name'        =>  'web',
                'system_role_id'    =>  SystemRole::where('name', 'back office')->where('guard_name', 'web')->pluck('id')->first()
            ]);

        });

        Schema::enableForeignKeyConstraints();
    }
}
