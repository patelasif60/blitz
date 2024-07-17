<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMember extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'group_members';
    protected $fillable = [
        'group_id',
        'user_id',
        'company_id',
        'rfq_id',
        'is_deleted',
        'created_at',
        'updated_by'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
