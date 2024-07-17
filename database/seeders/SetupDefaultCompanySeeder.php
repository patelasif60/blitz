<?php

namespace Database\Seeders;

use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Seeder;
use App\Models\Rfq;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAddresse;
use App\Models\UserCompanies;

class SetupDefaultCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            /***********************************begin: User table default company set*******************************************/
            User::where('role_id', Role::BUYER)->where('approval_invite', 0)->update(['buyer_admin' => 1]);

            $users = User::where('role_id', Role::BUYER)->where('buyer_admin', User::BUYERADMIN)->get();

            $users->each(function ($user) {

                $userCompanies = UserCompanies::where('user_id', $user->id)->get();

                $userCompanies->each(function($company) use($user) {

                    if($company->groupBy('id')->count() == 1) {
                        $user->default_company = $company->company_id;
                        $user->save();

                    }else if ($company->created_at->timestamp == $user->created_at->timestamp && $user->default_company == null) {
                        $user->default_company = $company->id;
                        $user->save();
                    }


                });

            });
            /***********************************end: User table default company set*******************************************/


            /***********************************begin: RFQ table company set*******************************************/
            $rfqs = Rfq::with('rfqUser')->get();

            $rfqs->each(function($rfq){

                if (!empty($rfq->rfqUser->default_company)) {

                    $rfq->company_id = $rfq->rfqUser->default_company;
                    $rfq->save();

                }

            });
            /***********************************end: RFQ table company set*******************************************/

            /***********************************begin: Set default company in user address table ***********************************/
            $userAddress = UserAddresse::get(['id', 'user_id', 'company_id'])->toArray();
            foreach ($userAddress as $row) {
                if(isset($row['user_id']) && $row['user_id']){
                    $existUser = User::where('id', $row['user_id'])->first();
                    if(!empty($existUser) && $row['company_id'] == null){
                        $userAddress = UserAddresse::where('user_id',$row['user_id'])->update(['company_id' => $existUser->default_company]);
                    }
                }
            }
            /***********************************end: Set default company in user address table ***********************************/

            dd('Default companies ID updated.');

        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }

    }
}
