<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMembersDiscount extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'group_members_discounts';
    protected $fillable = [
        'group_member_id',
        'rfq_id',
        'quote_id',
        'order_id',
        'group_id',
        'avail_discount',
        'achieved_discount',
        'prospect_discount',
        'refund_discount',
        'is_deleted',
        'created_at',
        'updated_by'
    ];
}
