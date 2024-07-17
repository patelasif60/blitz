<?php

namespace App\Jobs;

use App\Mail\SupplierNotifyCategoryWiseRfqlist;
use App\Mail\UserLoginWithSocialite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SupplierNotifyCategoryWiseRfqlistJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $rfqsDetails;
    public $supplierDetails;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rfqsDetails,$supplierDetails)
    {
        $this->rfqsDetails = $rfqsDetails;
        $this->supplierDetails = $supplierDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($this->supplierDetails->contact_person_email)->bcc($ccUsers)->queue(new SupplierNotifyCategoryWiseRfqlist($this->rfqsDetails,$this->supplierDetails));
    }
}
