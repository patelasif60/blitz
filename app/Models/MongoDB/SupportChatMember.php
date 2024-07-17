<?php

namespace App\Models\MongoDB;

use App\Models\Company;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Auth;

class SupportChatMember extends Model
{
    use HybridRelations, HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'support_chat_members';

    protected $fillable = ['support_chat_id', 'user_role_id', 'user_id', 'comman_data','unread_message_count', 'company_id', 'is_owner'];
    protected $dates = ['created_at','deleted_at'];

    public static function messageRead($groupChatId,$userId, $companyId)
    {
        return self::where(['support_chat_id'=>$groupChatId??null,'user_id'=>$userId, 'company_id' => $companyId])->update(['unread_message_count'=>0]);
    }

    public static function saveGroupMember($id, $userId=0, $isBuyer=0, $company_id=0){
        $adminLists = getAllAdmin();
        $buyerInsert = self::create(['support_chat_id' => $id, 'user_role_id' => (int)Role::BUYER, 'user_id' => $userId ,'unread_message_count'=>($isBuyer?0:1), 'company_id' => $company_id]);
        if ($adminLists){
            foreach ($adminLists as $key => $value){
                $unreadMessageCount = 1;
                if (empty($isBuyer) && $value==auth()->user()->id){
                    $unreadMessageCount = 0;
                }
                $adminSave = self::create(['support_chat_id' => $id, 'user_role_id' => 1, 'user_id' => $value ,'unread_message_count'=>$unreadMessageCount, 'company_id' => $company_id]);
            }
        }

        return true;
    }

    public static function chatCountIncrement($supportChatId,$userId)
    {
        return self::where(['support_chat_id'=>$supportChatId])->where('user_id', '!=', $userId)->increment('unread_message_count');
    }

    public static function getCountCompanyWiseUnreadMsg($user_id,$user_role_id,$company_id){
        return self::where(['user_id'=>(int)$user_id, 'user_role_id'=>(int)$user_role_id, 'company_id'=>(int)$company_id])->sum('unread_message_count');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
