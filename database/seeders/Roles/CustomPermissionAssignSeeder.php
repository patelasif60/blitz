<?php

namespace Database\Seeders\Roles;

use App\Models\Category;
use App\Models\CustomPermission;
use App\Models\ModelHasCustomPermission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class CustomPermissionAssignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('model_has_custom_permissions')->truncate();

        //Assign custom category permissions to all agents role users

        $users = User::where('role_id', Role::findByName('agent')->id)->get();

        /** $agentPermissions = CustomPermission::findByName('category')->pluck('id');  //For assign all categories to an agent uncomment this line */

        $agentPermissions = CustomPermission::where('name', 'category')->where('value', Category::WOOD)->pluck('id');

        $users->each(function($user) use($agentPermissions) {

            $agentPermissions->each(function($permission) use($user) {

                ModelHasCustomPermission::create([
                    'custom_permission_id'      =>  $permission,
                    'model_type'                =>  'App/Models/User',
                    'model_id'                  =>  $user->id
                ]);
            });

        });

        Schema::enableForeignKeyConstraints();



    }
}
