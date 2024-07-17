<?php

namespace App\Jobs;
use Mail;
use App\Mail\QuotesValidateTillNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QuotesValidateTillNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $email,$data;
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
        $email = $this->email;
        $data = $this->data;
        Mail::to($email)->queue(new QuotesValidateTillNotification($data));
    }
}
