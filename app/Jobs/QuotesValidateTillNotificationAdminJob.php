<?php

namespace App\Jobs;
use Mail;
use App\Mail\QuotesValidateTillNotificationAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QuotesValidateTillNotificationAdminJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $admin_email,$ccUsers,$quote_exp_mail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($admin_email,$ccUsers,$quote_exp_mail)
    {
        $this->admin_email = $admin_email;
        $this->ccUsers = $ccUsers;
        $this->quote_exp_mail = $quote_exp_mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $admin_email = $this->admin_email;
        $ccUsers = $this->ccUsers;
        $quote_exp_mail = $this->quote_exp_mail;
        Mail::to($admin_email)->bcc($ccUsers)->queue(new QuotesValidateTillNotificationAdmin($quote_exp_mail));
    }
}
