<?php

namespace Database\Seeders;

use App\Models\Rfq;
use App\Models\User;
use App\Models\UserQuoteFeedback;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Log;

class SetupApprovalPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $approvalUser  = UserQuoteFeedback::whereNull('approval_person_id')->OrWhereNull('company_id')->get();

        $approvalUser->each(function($user){

            if (!empty($user->rfq_id)) {

                $company_id     =  Rfq::find($user->rfq_id)->company_id??null; // get rfq company id

                $approvalUsers  = collect();

                $approvalUsersByCompany = User::whereJsonContains('assigned_companies',$company_id)->get();

                foreach($approvalUsersByCompany as $cmpUser) {
                    $approverPermissionId = Permission::findByName('toggle buyer approval configurations')->id;       //Get permission id by permission name
                    $permissions = getRolePermissionAttribute($cmpUser->id ?? null,$company_id ?? null)['permissions'];                     //Get Role & permission by user id

                    if (!empty($permissions)) {

                        if (in_array($approverPermissionId, $permissions)) {
                            $approvalUsers->push($cmpUser);

                        }
                    }
                }

                //Update get approval user
                $user->approval_person_id   = !empty($approvalUsers) ?? $approvalUsers->first()->id;    // set approval process run person id
                $user->company_id           =  $company_id;                                                 // set default company id of user
                $user->save();

            }
        });

        Log::info('Approval id and  Company id set in user quote feedback.');
    }
}
