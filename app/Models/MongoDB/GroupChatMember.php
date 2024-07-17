<?php

namespace App\Models\MongoDB;

use App\Models\Company;
use App\Models\GroupSupplier;
use App\Models\Quote;
use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompanies;
use App\Models\UserSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model;
use Auth;

class GroupChatMember extends Model
{
    use HybridRelations;
    protected $connection = 'mongodb';
    protected $collection = 'group_chat_members';

    protected $fillable = ['group_chat_id', 'user_role_id', 'user_id', 'comman_data','unread_message_count', 'company_id', 'check_permission', 'is_owner'];
    protected $dates = ['created_at','deleted_at'];

    public static function saveGroupMember($id, $chat_type, $chatId, $userId=0, $isBuyer=0, $company_id=0){
        $supplierLists = [];
        $adminLists = getAllAdmin();

        if ($chat_type == 'Rfq'){
            $rfq = Rfq::find($chatId);
            if (empty($userId)){
                $userId = $rfq->rfqUser->id;
            }
            //check permission wise add user
            checkChatPermissionWiseAddMember($company_id, $userId, $id, $chat_type);
            if ($userId != Auth::user()->id){
                $isBuyer = 0;
            }
            if(isset($rfq->group_id)){
                $groupSupplierId = GroupSupplier::where('group_id', $rfq->group_id)->pluck('supplier_id')->first();
                if($groupSupplierId){
                    $supplierUserId = getUserIdBySupplier($groupSupplierId);
                }
                if (isset($supplierUserId)){
                    $unreadMessageCount = 1;
                    if (empty($isBuyer) && $supplierUserId==auth()->user()->id){
                        $unreadMessageCount = 0;
                    }
                    $supplierSave = self::create(['group_chat_id' => $id, 'user_role_id' => 3, 'user_id' => $supplierUserId ,'unread_message_count'=>$unreadMessageCount, 'company_id' => $company_id]);
                }
            }else{
                $supplierLists = $rfq->rfqSuppliers()->groupBy('supplier_id')->get(['supplier_id']);
                if ($supplierLists) {
                    foreach ($supplierLists as $supplierList) {
                        $supplier = $supplierList->supplier()->first(['id']);
                        $supplier = $supplier->supplierUser()->first(['user_id']);
                        if (isset($supplier->user_id)){
                            $unreadMessageCount = 1;
                            if (empty($isBuyer) && $supplier->user_id==auth()->user()->id){
                                $unreadMessageCount = 0;
                            }
                            $supplierSave = self::create(['group_chat_id' => $id, 'user_role_id' => 3, 'user_id' => $supplier->user_id ,'unread_message_count'=>$unreadMessageCount, 'company_id' => $company_id]);
                        }
                    }
                }
            }
            $checkPermission = User::find($userId)->hasPermissionTo('list-all buyer rfqs');
        }

        if ($chat_type == 'Quote') {
            $quote = Quote::find($chatId);
            $userId = $quote->rfq->rfqUser()->first()->id;
            $supplier = $quote->supplier()->first(['id','name']);
            $supplier = $supplier->supplierUser()->first(['user_id']);
            if ($userId != Auth::user()->id){
                $isBuyer = 0;
            }
            if (isset($supplier->user_id)){
                $unreadMessageCount = 1;
                if (empty($isBuyer) && $supplier->user_id==auth()->user()->id){
                    $unreadMessageCount = 0;
                }
                $supplierSave = self::create(['group_chat_id' => $id, 'user_role_id' => 3, 'user_id' => $supplier->user_id ,'unread_message_count'=>$unreadMessageCount]);
            }
            checkChatPermissionWiseAddMember($company_id, $userId, $id, $chat_type);
            $checkPermission = User::find($userId)->hasPermissionTo('list-all buyer quotes');
        }

        $buyerInsert = self::create(['group_chat_id' => $id, 'user_role_id' => (int)Role::BUYER, 'user_id' => $userId ,'unread_message_count'=>($isBuyer?0:1), 'company_id' => $company_id, 'is_owner' => 1, 'check_permission' => $checkPermission ? 1:0]);

        if ($adminLists){
            foreach ($adminLists as $key => $value){
                $unreadMessageCount = 1;
                if (empty($isBuyer) && $value==auth()->user()->id){
                    $unreadMessageCount = 0;
                }
                $adminSave = self::create(['group_chat_id' => $id, 'user_role_id' => 1, 'user_id' => $value ,'unread_message_count'=>$unreadMessageCount, 'company_id' => $company_id]);
            }
        }
        /* remove agent entry for chat temporery 11-11-2022 (ST-433-CUSTOM-CHAT-QUOTE)
        // category wise agent entry. e.g wood
        $assignedCategory  = RfqProduct::where('rfq_id',$chatId)->groupBy('rfq_id')->pluck('category_id')->toArray();
         if(!empty($assignedCategory) && $assignedCategory[0] == 6){
            $agents = User::getAgentsByCustomPermissions('category',$assignedCategory)->first()->modelHasCustomPermissionMulti->where('model_type','App/Models/User')->pluck('model_id')->toArray();
            if (!empty($agents)){
                foreach ($agents as $key => $value){
                    $unreadMessageCount = 1;
                    if ($value == auth()->user()->id){
                        $unreadMessageCount = 0;
                    }
                    self::create(['group_chat_id' => $id, 'user_role_id' => (int)Role::AGENT, 'user_id' => (int)$value ,'unread_message_count'=>$unreadMessageCount, 'company_id' => $company_id]);
                }
            }
        }
        */
        return true;
    }

    public static function chatCountIncrement($groupChatId,$userId)
    {
        return self::where(['group_chat_id'=>$groupChatId])->where('user_id', '!=', $userId)->increment('unread_message_count');
    }

    public static function messageRead($groupChatId,$userId, $companyId)
    {
        return self::where(['group_chat_id'=>$groupChatId,'user_id'=>$userId, 'company_id' => $companyId])->update(['unread_message_count'=>0]);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function chatGroup()
    {
        return $this->belongsTo(ChatGroup::class,'group_chat_id');
    }
    public function chatMessages(){
            return $this->hasMany(GroupChatMessage::class, 'group_chat_id','group_chat_id')->latest();
    }

    /*Admin function start*/
    function getChatSupplierList($chat_id,$id,$type) {
        if($type == 'Rfq') {
            $rfq = Rfq::find($id);
            if(!empty($rfq->group_id)){
                $groupSupplierId = GroupSupplier::where('group_id', $rfq->group_id)->pluck('supplier_id')->first();
                $supplierLists = $rfq->rfqSuppliers()->where('supplier_id',$groupSupplierId)->with('supplier:id,name')->get(['supplier_id']);
            }else{
                $supplierLists = $rfq->rfqSuppliers()->groupBy('supplier_id')->with('supplier:id,name')->get(['supplier_id']);
            }
        }elseif ($type == 'Quote'){
            $quote = Quote::find($id);
            $supplierLists = $quote->supplier()->get(['id','name']);
        }
        return $supplierLists;
    }
    public function getUserSupplierList(){
        return UserSupplier::select('supplier_id')->get();
    }
    public static function getSupplierGroupChatList($type,$userId=0){
        if (empty($userId)){
            $userId = auth()->user()->id;
        }
        if($type=='Rfq'){
            $groupChatMember = self::where(["user_role_id"=> 3,'user_id'=>$userId])
                ->with(['chatGroup'=>function($q) use($type){
                    $q->where('chat_type',$type)->select(['_id','chat_id','created_at','group_name','chat_type']);
                }])
                ->orderBy('_id', 'desc')
                ->get();
        }elseif ($type=='Quote'){
            $groupChatMember = self::where(["user_role_id"=> 3,'user_id'=>$userId])
                ->with(['chatGroup'=>function($q) use($type){
                    $q->with(['quote'=> function ($q) {
                        $q->select(['id','rfq_id'])->with('rfq:id,reference_number');
                    }])->where('chat_type',$type)->select(['_id','chat_id','created_at','group_name','chat_type']);
                }])
                ->orderBy('_id', 'desc')
                ->get();
        }
        return $groupChatMember;
    }

    public static function getAgentGroupChatList($type){
        if($type=='Rfq'){
                $groupChatMember = self::with(['chatGroup'=>function($q) use($type){
                    $q->where('chat_type',$type)->select(['_id','chat_id','created_at','group_name','chat_type']);
                }])
                ->where(["user_role_id"=> (int)Role::AGENT, 'user_id' => (int)auth()->user()->id])
                ->orderBy('_id', 'desc')
                ->get();
        }elseif ($type=='Quote'){
            $groupChatMember = self::where(["user_role_id"=> Role::AGENT,'user_id'=>$userId])
                ->with(['chatGroup'=>function($q) use($type){
                    $q->with(['quote'=> function ($q) {
                        $q->select(['id','rfq_id'])->with('rfq:id,reference_number');
                    }])->where('chat_type',$type)->select(['_id','chat_id','created_at','group_name','chat_type']);
                }])
                ->orderBy('_id', 'desc')
                ->get();
        }
        return $groupChatMember;
    }

    public static function saveAllSupplierOnGroupLeft($rfqId){
        $existingSupplier = self::with(['chatGroup'=>function($q) use($rfqId){
            $q->where('chat_id',$rfqId);
        }])->where('user_role_id',3)->select(['user_id','group_chat_id'])->first();
        $rfq = Rfq::find($rfqId);
        $supplierLists = $rfq->rfqSuppliers()->groupBy('supplier_id')->get(['supplier_id']);
        if ($supplierLists) {
            foreach ($supplierLists as $supplierList) {
                $supplier = $supplierList->supplier()->first(['id']);
                $supplier = $supplier->supplierUser()->first(['user_id']);
                if (isset($supplier->user_id) && $supplier->user_id != $existingSupplier->user_id){
                    self::create(['group_chat_id' => $existingSupplier->group_chat_id, 'user_role_id' => 3, 'user_id' => $supplier->user_id ,'unread_message_count'=> 0]);
                }
            }
        }
    }

    /*Admin function end*/
}
