<?php

namespace App\Jobs;

use App\Mail\QuoteApprovalMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class QuoteApprovalMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        //dd($this->user['quote']->quote_number);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {  
        Mail::to($this->user['user']->email)->queue(new QuoteApprovalMail($this->user)); 
    }
}
