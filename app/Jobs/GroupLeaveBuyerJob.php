<?php

namespace App\Jobs;

use App\Mail\GroupLeaveBuyer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class GroupLeaveBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    public $email; //buyer email
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $email)
    {
        $this->data = $data;
        $this->email = $email;
        //dd($this->$data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($this->email)->bcc($ccUsers)->queue(new GroupLeaveBuyer($this->data));

    }
}
