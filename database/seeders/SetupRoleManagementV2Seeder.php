<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use App\Models\Rfq;
use App\Models\Order;
use App\Models\User;
use App\Models\UserCompanies;

class SetupRoleManagementV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Start - Set company_id in orders table based on rfq ids
        $rfqs = Rfq::get(['id', 'company_id']);
        foreach ($rfqs as $row) {
            Order::where('rfq_id', $row->id)->update(['company_id' => $row->company_id]);
        }
        // End - Set company_id in orders table based on rfq ids
        
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

        dd('setup role management v2 run successfully');
    }
}
