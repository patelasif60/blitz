<?php

namespace App\Console\Commands;

use App\Models\MongoDB\ChatGroup;
use App\Models\MongoDB\GroupChatMember;
use App\Models\Quote;
use App\Models\Rfq;
use App\Models\UserSupplier;
use Illuminate\Console\Command;

class RemoveLatestSupplier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'removeLatestSupplier:cron';

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
        $getAllChat = ChatGroup::all();
        foreach ($getAllChat as $chat){
            if ($chat->chat_type == 'Rfq'){
                $rfq = Rfq::find($chat->chat_id);
                $supplierLists = $rfq->rfqSuppliers()->groupBy('supplier_id')->pluck('supplier_id')->toArray();
                if (empty($supplierLists)){
                    continue;
                }
                $userList = UserSupplier::whereIn('supplier_id', $supplierLists)->pluck('user_id')->toArray();
                $getAllChatSupplierMember = GroupChatMember::where(['user_role_id' => 3, 'group_chat_id' => $chat->_id])->pluck('user_id')->toArray();
                if (empty($getAllChatSupplierMember)){
                    continue;
                } else {
                    $diffranceId = array_diff($getAllChatSupplierMember, $userList);
                    if (empty($diffranceId)){
                        continue;
                    } else {
                        GroupChatMember::where('group_chat_id', $chat->_id)->whereIn('user_id', $diffranceId)->delete();
                    }
                }
            }

            if ($chat->chat_type == 'Quote'){
                $quote = Quote::find($chat->chat_id);
                if (empty($supplierLists)){
                    continue;
                } else {
                    $supplier_user = getSupplierIdByUser($quote->supplier_id);
                    GroupChatMember::where(['user_role_id' => 3, 'group_chat_id' => $chat->_id])->where('user_id', '!=', $supplier_user)->delete();
                }
            }
        }
    }
}
