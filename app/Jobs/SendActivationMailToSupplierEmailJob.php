<?php

namespace App\Jobs;

use App\Mail\SendActivationMailToUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendActivationMailToSupplierEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $email)
    {
        $this->user = $data;
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
        Mail::to($this->email)->bcc($ccUsers)->queue(new SendActivationMailToUser($this->user));
    }
}
