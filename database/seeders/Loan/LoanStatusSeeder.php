<?php

namespace Database\Seeders\Loan;

use App\Models\LoanStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LoanStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        LoanStatus::truncate();
        LoanStatus::insert([
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '54648454-8fbd-4492-8749-f86e14aa81f6', 'status_name' => 'Requested' ,  'status_display_name' => 'Requested' ,'status_description' => 'user has request for a new limit',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => 'c9e1cd60-7252-44ef-a360-6307b7ec5f92', 'status_name' => 'Assessment' , 'status_display_name' => 'Assessment' ,'status_description' => 'KoinWorks team do the assesment',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '3a8fd367-cfa8-4a1d-89e5-85effce998bc', 'status_name' => 'Approved' ,   'status_display_name' => 'Approved' ,'status_description' => 'Certain limit given to user',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '97bc6ed9-72b2-4010-9c9f-1e286be5ded8', 'status_name' => 'Declined' ,   'status_display_name' => 'Declined' ,'status_description' => 'User declined the offering contract',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '2ac0ad02-4b73-4848-b6ae-6658109dcbaf', 'status_name' => 'Rejected' ,   'status_display_name' => 'Rejected' ,'status_description' => 'KoinWorks team rejected the application',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '390cc42c-0b52-40e8-9a28-ba9c1717ac03', 'status_name' => 'Confirmed' ,  'status_display_name' => 'Confirmed' ,'status_description' => null,  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => 'ab825088-d5d1-41ca-a19e-0d45920874fc', 'status_name' => 'Expired' ,    'status_display_name' => 'Expired' ,'status_description' => 'Limit cannot be used anymore and need to resubmit application to get limit again',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => 'badfa20d-3562-45eb-bf4a-79a1c59fd3e9', 'status_name' => 'Waiting For Documents' , 'status_display_name' => 'Waiting For Documents' ,'status_description' => 'User need to upload the sign contract',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => 'eb29fb79-f7bf-486a-b926-e14a87c23df8', 'status_name' => 'Obsolete' ,   'status_display_name' => 'Obsolete' ,'status_description' => 'User did not reupload required doc in within 14 days',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '0ff092ed-86c8-49f2-b8bb-7384abbba233', 'status_name' => 'Return to User' , 'status_display_name' => 'Return to User' ,'status_description' => 'Application is returned to borrower, asking for re-uploading one or more documents',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '17a27970-c85d-4ea0-bb9a-b7c3d32d2b62', 'status_name' => 'Partner Cancelled' ,  'status_display_name' => 'Cancel' ,'status_description' => 'In case buyer/seller made cancelation for the loan and release blocked avalailable limit amount',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '7f25fba3-d916-11e9-97fa-00163e010bca', 'status_name' => 'Assessment Approved' , 'status_display_name' => 'Awaiting Delivery Confirmation' ,'status_description' => 'Loan is Assessment Approved. At this state, you can cancel and confirm the loan',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '3b6b220b-2019-4279-8343-cbe6167f7571', 'status_name' => 'Pending Disburse' ,   'status_display_name' => 'Pending Disbursement' ,'status_description' => 'Loan Confirmed and ready for disbursement. At this state, you can not cancel the loan',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '3b6b220b-2019-4279-8343-cbe6167f7572', 'status_name' => 'Funding Process' ,   'status_display_name' => 'Pending Disbursement' ,'status_description' => 'Loan has been funding process',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '723bff9e-f700-11e9-97fa-00163e010bca', 'status_name' => 'Waiting For Disburse' ,   'status_display_name' => 'Pending Disbursement' ,'status_description' => 'Koinworks will initiate the disbursement process to partner/seller account',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '3b6b220b-2019-4279-8343-cbe6167f7574', 'status_name' => 'On Going' ,  'status_display_name' => 'Disbursed' ,'status_description' => 'Loan is disbursed and still on going',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => '3b6b220b-2019-4279-8343-cbe6167f7575', 'status_name' => 'Paid Off' ,    'status_display_name' => 'Paid Off' ,'status_description' => 'Loan has been paid by the borrower',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => 'ecc2abee-e4d0-11e9-97fa-00163e010bca', 'status_name' => 'NPL' , 'status_display_name' => 'NPL' , 'status_description' => 'Loan has been late for more than 90 days and defined as non performing loan',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => NULL, 'status_name' => 'Loan Disbursed', 'status_display_name' => 'Loan Disbursed' , 'status_description' => 'Loan has been disbursed',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => NULL, 'status_name' => 'Loan Confirmed', 'status_display_name' => 'Loan Confirmed' , 'status_description' => 'Loan has been confirmed',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => NULL, 'status_name' => 'Loan Cancelled', 'status_display_name' => 'Loan Cancelled' , 'status_description' => 'Loan has been cancelled',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => NULL, 'status_name' => 'Re-Paid By Buyer', 'status_display_name' => 'Paid By Buyer' , 'status_description' => 'Loan has been paid by buyer',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => NULL, 'status_name' => 'Completed', 'status_display_name' => 'Completed' , 'status_description' => 'Completed',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'] , 'status_code' => NULL, 'status_name' => 'Failed', 'status_display_name' => 'Failed' , 'status_description' => 'Failed',  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

        ]);
        Schema::enableForeignKeyConstraints();
    }
}
