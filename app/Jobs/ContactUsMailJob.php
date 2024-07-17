<?php

namespace App\Jobs;

use App\Mail\ContactUsMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ContactUsMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    public $mail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $mail)
    {
        $this->data = $data;
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($this->mail)->bcc($ccUsers)->queue(new ContactUsMail($this->data));
        
    }
}
