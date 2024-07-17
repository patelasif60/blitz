<?php

namespace App\Jobs\buyer\Credit\KoinWorks;

use App\Mail\Buyer\Credit\Koinworks\KoinworksLoanApply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class KoinworksLoanApplyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$email)
    {
        $this->data = $data;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($this->email)->bcc($ccUsers)->send((new KoinworksLoanApply($this->data)));
    }
}
