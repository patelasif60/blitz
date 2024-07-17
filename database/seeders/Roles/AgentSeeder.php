<?php

namespace Database\Seeders\Roles;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Agent;
use Spatie\Permission\Models\Role;

class AgentSeeder extends Seeder
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
            $rangaEmail     =   'rangga.wisesha@blitznet.co.id';
            $nurwahidEmail  =   'nur.whaid@blitznet.co.id';
        } else {
            $rangaEmail     =   'rangga.wisesha@yopmail.com';
            $nurwahidEmail  =   'nur.whaid@yopmail.com';
        }

        //Create agent Rangga
        $userExisting = User::where('email', $rangaEmail)->first();

        if (empty($userExisting)) {

            $user = User::create([
                'firstname'     => 'Rangga',
                'lastname'      => 'Wisesha',
                'email'         => $rangaEmail,
                'password'      => Hash::make('Blitznet@admin12'),
                'role_id'       => Role::findByName('agent')->id ?? 5,
                'language_id'   => '1',
                'currency_id'   => '1',
                'is_active'     => '1',
                'added_by'      => '1',
                'updated_by'    => '1'
            ]);

            $agent = Agent::create([
                'user_id'       => $user->id,
                'first_name'    => 'Rangga',
                'last_name'     => 'Akamaya',
                'email'         => $rangaEmail,
                'phone_code'    => '',
                'mobile'        => '',
            ]);

        } else {
            $userExisting->role_id = Role::findByName('agent')->id ?? 5;
            $userExisting->save();
        }

        //Create agent Nur Wahid
        $userExisting = User::where('email', $nurwahidEmail)->first();

        if (empty($userExisting)) {

            $user = User::create([
                'firstname'     => 'Nur',
                'lastname'      => 'Wahid',
                'email'         => $nurwahidEmail,
                'password'      => Hash::make('Blitznet@admin12'),
                'role_id'       => Role::findByName('agent')->id ?? 5,
                'language_id'   => '1',
                'currency_id'   => '1',
                'is_active'     => '1',
                'added_by'      => '1',
                'updated_by'    => '1'
            ]);

            $agent = Agent::create([
                'user_id'       => $user->id,
                'first_name'    => 'Nur',
                'last_name'     => 'Wahid',
                'email'         => $nurwahidEmail,
                'phone_code'    => '',
                'mobile'        => '',
            ]);

        } else {
            $userExisting->role_id = Role::findByName('agent')->id ?? 5;
            $userExisting->save();
        }
    }
}
