<?php

namespace Database\Seeders\Roles;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class PermissionSetJneRoleSeeder extends Seeder
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
            $jneEmail     =   'Salessupport1@jne.co.id';
        } else {
            $jneEmail     =   'Salessupport1@yopmail.com';
        }

        $isExist = User::where('email', $jneEmail)->first();

        if (empty($isExist)) {
            $user = User::create([
                'firstname'     =>  'Sales',
                'lastname'      =>  'Support',
                'email'         =>  $jneEmail,
                'password'      =>  Hash::make('Blitznet@jne12'),
                'role_id'       =>  SpatieRole::findByName('jne')->id ?? 7,
                'language_id'   =>  '1',
                'currency_id'   =>  '1',
                'is_active'     =>  '1',
                'added_by'      =>  '1',
                'updated_by'    =>  '1'
            ]);
        } else {
            $isExist->role_id = SpatieRole::findByName('jne')->id ?? 7;
            $isExist->save();
        }

        //Assign default agent permissions to all agent role users
        $users = User::where('role_id', SpatieRole::findByName('jne')->id)->get();

        $jnePermissions = SpatieRole::findByName('jne')->permissions->pluck('name');

        $users->each(function($user) use($jnePermissions) {

            $user->assignRole('jne');
            $user->givePermissionTo($jnePermissions);

        });
    }
}
