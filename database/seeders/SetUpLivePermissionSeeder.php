<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Order;
use App\Models\Rfq;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAddresse;
use App\Models\UserCompanies;
use Illuminate\Database\Seeder;
use Doctrine\DBAL\Query\QueryException;


class SetUpLivePermissionSeeder extends Seeder
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

            /* begin set default company id and buyer admin of single companies */
            $userIdsFromDuplicateCompany = UserCompanies::whereIn('company_id', [4, 13, 32, 33, 110])->pluck('user_id')->toArray();
            if(sizeof($userIdsFromDuplicateCompany) > 0){
                $singleCompanyUsers = User::where('role_id', Role::BUYER)->where('is_delete', 0)->whereNotIn('id', $userIdsFromDuplicateCompany)->pluck('id')->toArray();
                foreach ($singleCompanyUsers as $userId) {
                    $companyId = UserCompanies::where('user_id', $userId)->pluck('company_id')->first();
                    if($companyId){
                        User::where('id', $userId)->update(['default_company' => $companyId, 'buyer_admin' => 1 ]);
                    }
                }
            }
            /* end set default company id and buyer admin of single companies */

            // Start - Set owner_user and created_by in companies table
            $userCompanies = UserCompanies::get(['user_id', 'company_id']);
            foreach($userCompanies as $row){
                $existUser = User::where('id', $row->user_id)->count();
                if($existUser > 0){ // update record in companies table only if user_id exist in users table
                    Company::where('id',$row->company_id)->update(['owner_user' => $row->user_id, 'created_by' => $row->user_id]);
                }
            }
            // End - Set owner_user and created_by in companies table

            // Start assigned companies to users table
            $allUsers = User::get(['id','assigned_companies']);
            foreach ($allUsers as $row) {
                $userCmpIds = UserCompanies::where('user_id', $row->id)->get()->pluck(['company_id'])->toArray();
                if(sizeof($userCmpIds) > 0){
                    User::where('id', $row->id)->update(['assigned_companies' => json_encode($userCmpIds) ]);
                }
            }
            // Start assigned companies to users table

            /* begin set default company id and buyer admin of multiple companies */
            User::where('id', 34)->update(['default_company' => 33, 'buyer_admin' => 1, 'assigned_companies' => json_encode([33])]);
            Company::where('id', 33)->update(['created_by' => 34, 'owner_user' => 34]);

            User::where('id', 103)->update(['default_company' => 33, 'buyer_admin' => 0, 'assigned_companies' => json_encode([33])]);

            User::where('id', 14)->update(['default_company' => 13, 'buyer_admin' => 1, 'assigned_companies' => json_encode([13])]);
            Company::where('id', 13)->update(['created_by' => 14, 'owner_user' => 14]);

            User::where('id', 33)->update(['default_company' => 32, 'buyer_admin' => 1, 'assigned_companies' => json_encode([32])]);
            Company::where('id', 32)->update(['created_by' => 33, 'owner_user' => 33]);

            User::where('id', 183)->update(['default_company' => 110, 'buyer_admin' => 1, 'assigned_companies' => json_encode([110])]);
            Company::where('id', 110)->update(['created_by' => 183, 'owner_user' => 183]);
            /* end set default company id and buyer admin of multiple companies */


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

             // Start - Set company_id in orders table based on rfq ids
            $rfqs = Rfq::get(['id', 'company_id']);
            foreach ($rfqs as $row) {
                Order::where('rfq_id', $row->id)->update(['company_id' => $row->company_id]);
            }
            // End - Set company_id in orders table based on rfq ids


            dd('Default companies ID updated.');

        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }
    }
}
