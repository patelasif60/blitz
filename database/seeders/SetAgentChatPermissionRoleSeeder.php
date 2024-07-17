<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SetAgentChatPermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*********begin: Assign permission to agent role*****************/
        $role = Role::findByName('agent');

        $role->givePermissionTo('create group-chat');
        $role->givePermissionTo('edit group-chat');
        $role->givePermissionTo('delete group-chat');
        $role->givePermissionTo('publish group-chat');
        $role->givePermissionTo('unpublish group-chat');

        /*********end: Assign permission to agent role*****************/
    }
}
