<?php

namespace App\Jobs;

use App\Mail\GroupClose;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class GroupCloseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    public $email; //supplier email
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
        $users = array('1','2');
        foreach ($users as $userType){
            $user_mail = $userType == 1 ? 'support@blitznet.co.id' : $this->email;
            Mail::to($user_mail)->bcc($ccUsers)->queue(new GroupClose($this->data,$userType));
        }
    }
}
