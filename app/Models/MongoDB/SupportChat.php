<?php

namespace App\Models\MongoDB;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Auth;

class SupportChat extends Model
{
    use HybridRelations, HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'support_chats';

    protected $fillable = ['group_name', 'user_type', 'user_role_id','user_id', 'company_id'];
    protected $dates = ['created_at','deleted_at'];

    public static function createUpdateChatGroup($data){
        $result = self::where(['user_id'=>$data['user_id'], 'company_id'=>$data['company_id']])->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public static function getAdminGroupChatList(){
        return self::with(['SupportChatMember'=>function($q){
            $q->where(['user_id' => Auth::user()->id])->select(['support_chat_id','unread_message_count','user_id', 'company_id']);
        }])
        ->with('company:id,name')
        ->with(['user'=>function($q){
            $q->withTrashed()->select(['id','firstname','lastname']);
        }])
        ->get()
        ->groupBy('company_id');
    }

    public static function getSearchSupportChatData($string){
        if(empty($string)){
            return self::getAdminGroupChatList();
        }

        return self::with(['SupportChatMember' => function ($q) {
            $q->where('user_id', Auth::user()->id)->select(['support_chat_id','unread_message_count','user_id', 'company_id']);
            }])
            ->with('company:id,name')
            ->with('user:id,firstname,lastname')
            ->orWhereHas('company', function($q) use($string){
                $q->where('name', 'LIKE',"%$string%");
            })
            ->orWhereHas('user', function($q) use($string){
                $q->where('firstname', 'LIKE',"%$string%");
            })
            ->get()
            ->groupBy('company_id');
    }

    public function supportChatMember()
    {
        return $this->hasOne(SupportChatMember::class, 'support_chat_id');
    }
    public function supportChatMessage()
    {
        return $this->hasMany(SupportChatMessage::class, 'support_chat_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
