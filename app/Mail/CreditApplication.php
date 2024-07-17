<?php

namespace App\Mail;

use App\Models\LoanApplication;
use App\Models\LoanEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CreditApplication extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $status;
    public $defaultCompany;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$status,$defaultCompany)
    {
        $this->data = $data;
        $this->status = $status;
        $this->defaultCompany=$defaultCompany;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $statusArray = array_flip(LoanApplication::STATUS);
        $record = [
            'company_id' => $this->defaultCompany,
            'application_id' => $this->data->id,
            'status' => $this->status,
            'type' => 'CREDIT'
        ];
        LoanEmail::createOrUpdateEmail($record);
        if($this->status == 'Requested'){
            return $this->subject( 'Credit Application Request for '.$this->data->loan_application_number )
                ->markdown('emails.credit.limit.creditApplication')
                ->with('data', $this->data);
        }else if($this->status == 'Approved'){
            return $this->subject( 'Pengajuan kredit diterima untuk '.$this->data->loan_application_number  )
                ->markdown('emails.credit.limit.creditApproved')
                ->with('data', $this->data);
        }else{
            return $this->subject( 'Pengajuan kredit tertolak untuk '.$this->data->loan_application_number )
                ->markdown('emails.credit.limit.creditReject')
                ->with('data', $this->data);
        }
    }
}
