<?php

namespace App\Jobs;

use App\Mail\SendMailForChatUnreadMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendChatUnreadMessageMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $chatUnreadMsg;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chatUnreadMsg)
    {
        $this->chatUnreadMsg = $chatUnreadMsg->toArray();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $unreadMsg = $this->chatUnreadMsg;
        if (!empty($unreadMsg)) {
            foreach ($unreadMsg as $msg) {
                $msg['chat_messages'] = array_slice($msg['chat_messages'],0,2);
                if (empty($msg['chat_messages'])){
                    continue;
                }

                $user = $msg['user']['email'];
                Mail::to($user)->queue(new SendMailForChatUnreadMessage($msg));
            }
        }
    }
}
