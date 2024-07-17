<?php

namespace App\Console\Commands;

use App\Models\MongoDB\ChatGroup;
use App\Models\MongoDB\GroupChatMember;
use App\Models\MongoDB\GroupChatMessage;
use App\Models\Quote;
use App\Models\Rfq;
use App\Models\User;
use Illuminate\Console\Command;

class ChatEnhancement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatEnhancement:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $chatGroups = ChatGroup::all();
        foreach ($chatGroups as $chatGroup){
            if ($chatGroup->chat_type == 'Rfq'){
                $rfq = Rfq::find($chatGroup->chat_id);
                if (!isset($rfq) && empty($rfq)){
                    continue;
                }
            }

            if ($chatGroup->chat_type == 'Quote'){
                $quotes = Quote::find($chatGroup->chat_id);
                if (isset($quotes) && !empty($quotes)){
                    $rfq = Rfq::find($quotes->rfq_id);
                } else {
                    continue;
                }
            }

            $company_id = $rfq->company_id??0;
            $owner_id = $rfq->rfqUser->id??null;
            $updateGroupChat = ChatGroup::where('_id', $chatGroup->_id)->update(['company_id' => (int)$company_id, 'owner_id' => $owner_id]);

            $allChatMessages = GroupChatMessage::where('group_chat_id',$chatGroup->_id)->get();
            foreach ($allChatMessages as $allChatMessage){
                $updateGroupChatMessage = GroupChatMessage::where('_id',$allChatMessage->_id)->update(['company_id' => (int)$company_id]);
            }

            $allChatMembers = GroupChatMember::where('group_chat_id',$chatGroup->_id)->get();
            foreach ($allChatMembers as $allChatMember){
                $updateData = ['company_id' => (int)$company_id];
                if ($allChatMember->user_role_id == 2){
                    $user = User::find($allChatMember->user_id);
                    if ($chatGroup->chat_type == 'Rfq'){
                        $checkPermission = $user->hasPermissionTo('list-all buyer rfqs');
                    }
                    if ($chatGroup->chat_type == 'Quote'){
                        $checkPermission = $user->hasPermissionTo('list-all buyer quotes');
                    }
                    $isOwner = ($allChatMember->user_id == $owner_id)?1:0;
                    $checkPermission = (isset($checkPermission) && $checkPermission) ? 1 : 0;
                    $updateData = ['company_id' => (int)$company_id, 'check_permission' => $checkPermission, 'is_owner' => $isOwner];
                }
               $updateGroupChatMember = GroupChatMember::where('_id', $allChatMember->_id)->update($updateData);
            }
        }

        return "Successfully Done";
    }
}
