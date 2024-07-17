<?php

namespace App\Models\MongoDB;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model;

class GroupChatCount extends Model
{
    use HybridRelations;
    protected $connection = 'mongodb';
    protected $collection = 'group_chat_counts';

    protected $fillable = ['group_chat_id', 'group_chat_message_id', 'sender_role_id', 'sender_id', 'receiver_role_id', 'receiver_id', 'is_read', 'unread_message_count'];
    protected $dates = ['created_at','deleted_at'];

    public static function bulkInsert($data){
        $userData = self::raw( function ( $collection ) use ($data) {
            return $collection->insertMany($data);
        });
        return $userData;
    }

}
