<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\SoftDeletes;

class GetApprovalReason extends Model
{
    use HasFactory, SystemActivities, SoftDeletes;

    protected $tagname = "get_approval_reasons";

    protected $fillable = [
        'rfq_id',
        'quote_id',
        'user_id',
        'approval_person_id',
        'company_id',
        'reason_key',
        'reason_text',
    ];

    protected $dates = ['created_at','updated_at'];

    /*
    * Relation Beetween get approval reason and quote
    * */
    public function GetApprovalReason()
    {
        return $this->belongsTo(Quote::class);
    }
}
