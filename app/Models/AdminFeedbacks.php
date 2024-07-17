<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFeedbacks extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = ['user_id',  'reason_id', 'feedback_id', 'feedback_type'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function reason(){
        return $this->belongsTo(AdminFeedbackReasons::class,'reason_id');
    }
}
