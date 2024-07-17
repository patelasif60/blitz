<?php

namespace Database\Seeders\Roles;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class PermissionSetFinanceRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Set emails by app enviorment
        if( config('app.env') == 'production' || config('app.env') == 'live') {
            $fianceEmail     =   'eva.saputri@blitznet.co.id';
        } else {
            $fianceEmail     =   'eva.saputri@yopmail.com';
        }

        $isExist = User::where('email', $fianceEmail)->first();

        if (empty($isExist)) {
            $user = User::create([
                'firstname'     =>  'Eva',
                'lastname'      =>  'Saputri',
                'email'         =>  $fianceEmail,
                'password'      =>  Hash::make('Blitznet@12'),
                'role_id'       =>  SpatieRole::findByName('finance')->id ?? Role::FINANCE,
                'language_id'   =>  '1',
                'currency_id'   =>  '1',
                'is_active'     =>  '1',
                'added_by'      =>  '1',
                'updated_by'    =>  '1'
            ]);
        } else {
            $isExist->role_id = SpatieRole::findByName('finance')->id ?? Role::FINANCE;
            $isExist->save();
        }

        //Assign default agent permissions to all agent role users
        $users = User::where('role_id', SpatieRole::findByName('finance')->id)->get();

        $jnePermissions = SpatieRole::findByName('finance')->permissions->pluck('name');

        $users->each(function($user) use($jnePermissions) {

            $user->assignRole('finance');
            $user->givePermissionTo($jnePermissions);

        });
    }
}
