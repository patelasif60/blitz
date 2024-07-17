<?php

namespace App\Jobs;
use Mail;
use App\Mail\ChangeStatusQuotesExpire;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChangeStatusQuotesExpireJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $email,$quote_exp_mail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$quote_exp_mail)
    {
        $this->email = $email;
        $this->quote_exp_mail = $quote_exp_mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->email;
        $quote_exp_mail = $this->quote_exp_mail;
        Mail::to($email)->queue(new ChangeStatusQuotesExpire($quote_exp_mail));
    }
}
