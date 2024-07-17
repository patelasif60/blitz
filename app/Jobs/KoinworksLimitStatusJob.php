<?php

namespace App\Jobs;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class KoinworksLimitStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $loanApplications;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($loanApplications)
    {
        $this->loanApplications = $loanApplications;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $statusArray = array_flip(LoanApplication::STATUS);
            foreach ($this->loanApplications as $row) {
                if($row['provider_application_id']){
                    $result = app('App\Http\Controllers\Credit\KoinWorks\KoinWorkController')->getLimit($row['provider_application_id']);

                    if(isset($result['status']) && $result['status'] && $result['data'][0]['limitStatusValue']){
                        $data = LoanApplication::with('user:id,firstname,lastname')->where('id', $row['id'])->first(['id','loan_application_number','user_id','loan_limit','senctioned_amount','created_at','applicant_id','reserved_amount']);

                        $data->update(['status' => $result['data'][0]['status'],'status_name' => $result['data'][0]['limitStatusValue'] ]);
                        $email = $data->loanApplicantBusiness->email;
                        if($statusArray['Rejected'] = $result['data'][0]['status']){
                            dispatch(new CreditApplicationJob($data,$email,'Rejected',Auth::user()->default_company));
                        }elseif ($statusArray['Approved'] = $result['data'][0]['status']){
                            //save reserved amount
                            $data->senctioned_amount = $result['data']['reserveAmount'];
                            $data->reserved_amount = $result['data']['remainingAmount'];
                            if ($data->loan_limit>FIFTY_MILLION) {
                                $data->expire_date = $result['data']['expiredAt'];
                            }
                            $data->save();
                            dispatch(new CreditApplicationJob($data,$email,'Approved',Auth::user()->default_company));
                        }
                        $applicantObject = $row->applicant()->where('contracts', NULL);
                        // $applicantObject = LoanApplicant::where('id', $row['applicant_id'])->where('contracts', NULL)

                        // Update loan applicant's contracts
                        if(isset($result['data'][0]['contractURL']) && !empty($result['data'][0]['contractURL']) && $applicantObject->count() > 0){
                            $res = $applicantObject->update(['contracts' => $result['data'][0]['contractURL'] ]);
                        }
                    }
                }
            }
        } catch (\Exception $e){
            return $e;
        }
    }
}
