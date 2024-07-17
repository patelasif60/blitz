<?php

namespace App\Jobs;

use App\Mail\CreditApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class CreditApplicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    public $email;
    public $status;
    public $defaultCompany;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $email, $status,$defaultCompany)
    {
        $this->data = $data;
        $this->email = $email;
        $this->status = $status;
        $this->defaultCompany=$defaultCompany;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($this->email)->bcc($ccUsers)->queue(new CreditApplication($this->data,$this->status,$this->defaultCompany));
    }
}
