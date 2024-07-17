<?php

namespace App\Models\MongoDB;

use App\Models\ChatQuickMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class SupportChatMessage extends Model
{
    use HybridRelations, HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'support_chat_messages';
    //message 1:text 2:file
    protected $fillable = ['support_chat_id', 'message_type','message', 'mimtype', 'sender_role_id', 'sender_id', 'company_id'];
    protected $dates = ['created_at','deleted_at'];

    public static function getGroupChatMessages($id){
        if (empty($id)){
            $id = SupportChat::where(['user_id' => auth()->user()->id, 'company_id' => auth()->user()->default_company])->first()->_id??null;
        }
        if (!empty($id)){
            $messageList = self::with(['user:id'])->where('support_chat_id', $id)->get();
        }
        return $messageList??[];
    }

    public function user()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function getQucikMessages($role_id,$type){
        return ChatQuickMessage::where(['role_id'=>$role_id,'status'=>1,'header_type'=>'Support'])->get(['id','message']);
    }

    public function saveMessage($chat_id, $data){
        $saveMessage =  self::create(['support_chat_id' => $chat_id, 'message' => $data['message'], 'message_type' => $data['message_type'], 'sender_id' => $data['user_id'], 'sender_role_id' => $data['user_type'], 'mimtype' => $data['mimtype'], 'company_id' => (int)$data['company_id'] ]);
        return $saveMessage;
    }

    public static function getSupportChatMessages($request){
        $messageList = SupportChatMessage::with(['user'=>function($q){
            $q->withTrashed()->select(['id','firstname','lastname']);
        }])->where('support_chat_id', $request->id)->get();
        return $messageList;
    }

}
