<?php

namespace App\Console\Commands;

use App\Models\MongoDB\GroupChatMember;
use Illuminate\Console\Command;
use App\Jobs\SendChatUnreadMessageMail;
class ChatUnreadMessageMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatUnreadMessage:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send mail to all users who doesn't read message";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $chatUnreadMsg = GroupChatMember::with(['chatGroup:_id,group_name','chatMessages:group_chat_id,message,sender_id,sender_role_id,created_at','user:id,email,firstname,lastname'])->where('unread_message_count','!=',0)->get(['_id','group_chat_id','user_role_id','user_id','unread_message_count']);
        // send mail to admin, supplier and buyer
        dispatch(new SendChatUnreadMessageMail($chatUnreadMsg));
        // send mail to admin, supplier and buyer
        return 'done';
    }
}
