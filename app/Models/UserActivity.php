<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
    protected $table = 'user_activities';

    protected $fillable = [
        'user_id', 'activity', 'is_activity_shown', 'type', 'record_id', 'is_deleted', 'created_at', 'updated_at'
    ];

    public static function createOrUpdateUserActivity($data){
        $result = null;
        if (isset($data['id'])) {
            $result = self::where(['id' => $data['id']])->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

}
