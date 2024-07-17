<?php

namespace Database\Seeders;

use App\Models\InviteBuyer;
use App\Models\User;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Seeder;

class AddCompanyIdInInviteBuyerSeeder extends Seeder
{
    /**
     * This seeder is used to update company id for buyer invite supplier or buyer invite buyer
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $inviteUser  = InviteBuyer::where('user_type', 2)->get(['id', 'user_id', 'company_id']);
            $inviteUser->each(function($invite){
                if (!empty($invite->user_id)) {
                    $invite->company_id = User::find($invite->user_id)->default_company??null; // Only set company id for user type 2 (Buyer)
                    $invite->save();
                }
            });
            dd('Company id set in Invite buyer table.');
        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }
    }
}
