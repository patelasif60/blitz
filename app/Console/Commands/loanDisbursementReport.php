<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Credit\KoinWorks\KoinWorkController;
use App\Jobs\Credit\KoinWorks\SupplierDisbursementJob;
use App\Jobs\loanDisbursementReportJob;
use App\Models\LoanApply;
use App\Models\LoanProvider;
use App\Models\LoanProviderApiResponse;
use App\Models\LoanStatus;
use App\Models\LoanTransaction;
use App\Models\PaymentProvider;
use App\Models\PaymentProviderTransaction;
use App\Models\Supplier;
use App\Models\TransactionsType;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class loanDisbursementReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loanDisbursementReport:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get records from loanDisbursementReport API and update to database ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $parameters = "from_date=".Carbon::now()->subDays(30)->format('Y-m-d')."&to_date=".Carbon::now()->format('Y-m-d');

        /**begin: Koinworks API Call**/
        $kwobj = new KoinWorkController;
        $response = $kwobj->loanDisbursementReport($parameters);
        /**end: Koinworks API Call**/

        foreach ($response['data'] as $key => $value) {
            $loanStatusIds = [ LOAN_STATUS['LOAN_CONFIRMED'] ];
            $loanApplyCollection = LoanApply::where('provider_loan_id', $value['loanID'])->whereIn('status_id', $loanStatusIds);

            $loanApplyObj = $loanApplyCollection->get()->first();
            if(!empty($loanApplyObj)){
                $dueDate = Carbon::now()->addDays(30)->toDateTimeString();
                $loanApplyCollection->update(['due_date' => $dueDate]); // update loan due date to after 30 days from today

                $disburseLoanStatusId  = LOAN_STATUS['ONGOING'];
                $disburseTransTypeId  = TRANSACTION_TYPES['DISBURSMENT_KOINWORKS_TO_BLITZNET'];
                $paymentProviderId  = PAYMENT_PROVIDERS['XENDIT'];
                $loanApplyCollection->update(['status_id' => $disburseLoanStatusId]); // update status to disbursed

                $loanTransData = [
                    'loan_id' => $loanApplyObj->id,
                    'order_id' => $loanApplyObj->order_id,
                    'applicant_id' => $loanApplyObj->applicant_id,
                    'application_id' => $loanApplyObj->application_id,
                    'users_type' => User::class, // morph user class
                    'users_id' => $loanApplyObj->user_id, // morph model id
                    'company_id' => $loanApplyObj->company_id,
                    'transaction_amount' => $value['disbursedAmount'], // need to confir , nullable
                    'transaction_status' => $disburseLoanStatusId, // completed - loan_status
                    'transaction_type_id' => $disburseTransTypeId // Disbursement koinworks to blitznet  - transaction_type table
                ];
                LoanTransaction::updateOrCreate($loanTransData);

                $paymentTransData = [
                    'users_type' => User::class,
                    'users_id' => $loanApplyObj->user_id,
                    'company_id' => $loanApplyObj->company_id,
                    'payment_provider_id' => $paymentProviderId, // xendit - foreign key from payment_providers table
                    'transaction_status' => PAYMENT_TRANSACTION_STATUS['COMPLETED'],
                    'transaction_type_id' => $disburseTransTypeId, // Disbursement koinworks to blitznet  - transaction_type table
                    'credit_ac_id' => $value['bankAccountNumber'], // need to confirm // borrower account id
                    'credit_ac_type' => $value['bankAccountName'], // need to confirm // borrower account name
                    'debit_ac_id' => KOINWORKS_ACCOUNT['ID'], //  koinworks account id
                    'debit_ac_type' => KOINWORKS_ACCOUNT['NAME'], // koinworks
                    'related_type' => LoanApply::class, // eg Loan::class, Order::class
                    'related_id' => $loanApplyObj->id, // modal id
                    'amount' => $value['disbursedAmount'], // need to confirm
                    'created_type' => User::class, // morph model name
                    'created_id' => User::BUYERADMIN, // morph model id - Blitznet Admin
                ];
                PaymentProviderTransaction::updateOrCreate($paymentTransData);
                //Supplier disbursement
                dispatch(new SupplierDisbursementJob($loanApplyObj));

                /**disbursement notification */
                $disburseData = [
                    'user_activity' => 'Koinworks to Blitznet disbursed',
                    'translation_key' => 'disbursement_koinworks_to_blitznet',
                    'type_id' => $loanApplyObj->id,
                    'loan_number' => $loanApplyObj->loan_number,
                    'status' => $loanApplyObj->loanStatus->status_display_name
                ];
                (new NotificationController)->addLoanNotification($disburseData); // send notification
                /**disbursement notification */
            }

        }
        /* if(isset($response['status']) && $response['status'] == 200){
            dd(sizeof($response['data']));
        } */
    }
}
