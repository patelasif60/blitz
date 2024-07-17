<?php

namespace Database\Seeders\Loan;

use App\Models\LoanProviderApiList;
use App\Models\LoanProvider;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CreditProviderAPISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        LoanProviderApiList::truncate();

        LoanProviderApiList::insert([
            ['name' => 'Request a New Limit', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'This API will create a user and provide user id. Apply for a BNPL limit all relevant information and documents for users who don\'t have an active limit.', 'method' => 'POST', 'path' => '/apis/v1/koinbnpl/limits'],
            ['name' => 'Generate Token', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Generate Token API will be used for Authorization using Bearer Token.', 'method' => 'POST', 'path' => '/apis/v1/auth'],
            ['name' => 'Request Limit OTP', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Request limit OTP for apply limit when status is waiting for document. While Koinworks team do the assessment process and approve the borrower, then limit status will change to waiting_for_document. ', 'method' => 'GET', 'path' => '/apis/v1/koinbnpl/my/limits/otp/request'],
            ['name' => 'Verify Limit OTP', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Once borrower input the Limit OTP, partner should do the OTP validation.', 'method' => 'POST', 'path' => '/apis/v1/koinbnpl/my/limits/otp/validate'],
            ['name' => 'Get Limit', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'API to fetch limit status for all users who have applied for a BNPL limit from your platform.This API can use to check limit status of the borrower.', 'method' => 'GET', 'path' => '/apis/v1/koinbnpl/limits'],
            ['name' => 'Upload Signed Contract', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Borrower with limit amount > 50 Mio limit and status is waiting_for_document, they will receive a contract. Upload a contract signed by the borrower that needs to be reviewed by Koinworks.', 'method' => 'POST', 'path' => '/apis/v1/koinbnpl/my/limits/contracts'],
            ['name' => 'User Limit', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Get limit information such as total limit, amount granted and remaining limit amount for the current user (borrower).', 'method' => 'GET', 'path' => '/apis/v1/koinbnpl/my/limits'],
            ['name' => 'Reupload Document', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Re-upload documents that have been rejected during the assessment process.In case of any missing/invalid documents flagged by Koinworks, Borrower will need to re-upload the required docs. The status will be Return_to_user', 'method' => 'POST', 'path' => '/apis/v1/koinbnpl/my/documents'],
            ['name' => 'Loan Disbursement Report', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'API to Get partner loan disbursement report from your platform. Partner can use this API to get detail loan disbursement report by date.', 'method' => 'GET', 'path' => '/apis/v1/koinbnpl/loans/disbursement'],
            ['name' => 'Loan Partner Cancelled', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Cancel Loan in case there are any issues regarding the order, only can do before Confirm Delivery. Limit amount will be released and be available to use by borrower.', 'method' => 'POST', 'path' => '/apis/v1/koinbnpl/my/loans/:loanId/cancel'],
            ['name' => 'Repayment', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Cancel Loan in case there are any issues regarding the order, only can do before Confirm Delivery. Limit amount will be released and be available to use by borrower.', 'method' => 'GET', 'path' => '/apis/v1/koinbnpl/loans/:loanId/repayment'],
            ['name' => 'Create Loan', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Create a loan request for users who already have an active limit and status is Approve..', 'method' => 'POST', 'path' => '/apis/v1/koinbnpl/my/loans'],
            ['name' => 'Request Loan OTP', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'while create loan process, in case user did not receive loan OTP, user can use this API to resend loan OTP request.', 'method' => 'GET', 'path' => '/apis/v1/koinbnpl/my/loans/:loanId/otp/request'],
            ['name' => 'Verify Loan OTP', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Once borrower input the Loan OTP, partner should validate OTP sent to borrower after loan creation..', 'method' => 'POST', 'path' => '/apis/v1/koinbnpl/my/loans/:loanId/otp/validate'],
            ['name' => 'Delivery Confirmation', 'loan_provider_id' => LoanProvider::KOINWORKS, 'description' => 'Use this API to confirm the delivery of the order, along with the final delivery amount.', 'method' => 'POST', 'path' => '/apis/v1/koinbnpl/my/loans/:loanId/confirm'],

        ]);

        Schema::enableForeignKeyConstraints();
    }
}
