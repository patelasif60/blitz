<?php

namespace App\Jobs;

use App\Models\MongoDB\ChatGroup;
use App\Models\MongoDB\GroupChatMember;
use App\Models\Quote;
use App\Models\Rfq;
use App\Models\UserSupplier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChatAddNewSupplierAsUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $supplierID;
    public $userID;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($supplierId, $userID)
    {
        $this->supplierID = $supplierId;
        $this->userID = $userID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $groupChatLists = ChatGroup::all();
        foreach ($groupChatLists as $chatList){
            if ($chatList['chat_type'] == 'Rfq') {
                $rfq = Rfq::find($chatList['chat_id']);
            } elseif ($chatList['chat_type'] == 'Quote'){
                $quote = Quote::find($chatList['chat_id']);
                $rfq = Rfq::find($quote->rfq_id);
            } else {
                continue;
            }
            if (!empty($rfq)) {
                $supplierLists = $rfq->rfqSuppliers()->groupBy('supplier_id')->pluck('supplier_id')->toArray();
                $userList = UserSupplier::whereIn('supplier_id', $supplierLists)->pluck('user_id')->toArray();
                $getAllMembers = GroupChatMember::where('group_chat_id', $chatList['_id'])->pluck('user_id')->toArray();
                $diffranceId = array_diff($userList, $getAllMembers);
                if (!in_array($this->userID, $getAllMembers) && !empty($diffranceId)) {
                    //write code for insert member rfq / quote
                    if (!empty($supplierLists) && in_array($this->userID,$supplierLists) && $chatList['chat_type'] == 'Rfq') {
                        GroupChatMember::create(['group_chat_id' => $chatList['_id'], 'user_role_id' => 3, 'user_id' => $this->userID, 'unread_message_count' => 0]);
                    }
                    if (!empty($supplierLists) && in_array($this->userID,$supplierLists) && $chatList['chat_type'] == 'Quote' && isset($quote) && $quote->supplier_id == $this->userID) {
                        GroupChatMember::create(['group_chat_id' => $chatList['_id'], 'user_role_id' => 3, 'user_id' => $this->userID, 'unread_message_count' => 0]);
                    }
                }
            }
        }
    }
}
