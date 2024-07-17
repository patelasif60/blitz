<?php

namespace App\Models\MongoDB;

use App\Models\ChatQuickMessage;
use App\Models\User;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model;

class GroupChatMessage extends Model
{
    use HybridRelations;
    protected $connection = 'mongodb';
    protected $collection = 'group_chat_messages';
    //message 1:text 2:file
    protected $fillable = ['group_chat_id', 'message_type','message', 'mimtype', 'sender_role_id', 'sender_id', 'company_id'];
    protected $dates = ['created_at','deleted_at'];

    public static function createGroupChatMessage($data){
        return self::create($data);
    }

    public static function getGroupChatMessages($id, $chat_id, $chat_type){
        if (empty($id)){
            $id = ChatGroup::where(['chat_id' => (int)$chat_id, 'chat_type' => $chat_type])->first()->_id??null;
        }
        $messageList = GroupChatMessage::with(['GroupChatCount'=>function($q){
            $q->select(['group_chat_message_id','receiver_id','receiver_role_id','is_read']);
        },'user:id,firstname,lastname'])->where('group_chat_id', $id)->get();
        return $messageList;
    }

    public function GroupChatCount() {
        return $this->hasMany(GroupChatCount::class,'group_chat_message_id','id');
    }

    public function saveMessage($chat_id, $data){
        $saveMessage =  self::create(['group_chat_id' => $chat_id, 'message' => $data['message'], 'message_type' => $data['message_type'], 'sender_id' => $data['user_id'], 'sender_role_id' => $data['user_type'], 'mimtype' => $data['mimtype'], 'company_id' => (int)$data['company_id'] ]);
        return $saveMessage;
    }
    public function getQucikMessages($role_id,$type){
        return ChatQuickMessage::where(['role_id'=>$role_id,'status'=>1,'header_type'=>$type])->get(['id','message']);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class,'sender_id')->where('role_id',3);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class,'sender_id')->where('role_id',2);
    }

    public function admin()
    {
        return $this->belongsTo(User::class,'sender_id')->where('role_id',1);

    }
}
