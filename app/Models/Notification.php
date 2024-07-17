<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class Notification extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Notification";
    protected $fillable = [
        'admin_id', 'supplier_id','user_activity','translation_key','is_show','is_multiple_show', 'notification_type', 'notification_type_id', 'is_deleted'
    ];

    public function user(){
        return $this->belongsTo(User::class,'company_id','id');
    }
}
