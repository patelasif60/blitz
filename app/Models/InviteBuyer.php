<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class InviteBuyer extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Invite Buyer";
    protected $table = 'invite_buyer';
    protected $fillable = [
        'supplier_id','user_id', 'user_email', 'status', 'resend_count','added_by','token','date','created_at','updated_at','is_deleted','company_id'
    ];
    protected $dates = ['created_at','updated_at'];
    public function trackAddData()
    {
        return $this->hasOne(User::class,'id','added_by');
    }
}
