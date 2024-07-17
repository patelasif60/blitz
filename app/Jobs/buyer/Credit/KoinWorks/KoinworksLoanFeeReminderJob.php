<?php

namespace App\Jobs;

use App\Mail\Buyer\Credit\Koinworks\KoinworksLoanFeeReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class KoinworksLoanFeeReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    private $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$data)
    {
        $this->email = $email;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($this->email)->bcc($ccUsers)->send((new KoinworksLoanFeeReminderMail($this->data)));
    }
}
