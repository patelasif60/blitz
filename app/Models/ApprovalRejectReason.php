<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SystemActivities;

class ApprovalRejectReason extends Model
{
    use HasFactory , SystemActivities;

    use SoftDeletes;

    protected $table = 'approval_reject_reasons';
    
    protected $fillable = [
        'rfq_id', 
        'quote_id',
        'approval_person_id',
        'user_quote_feedback_id',
        'reason_key',
        'reason_text',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at','deleted_at'];

    /*
    * Relation Beetween get approval reason and quote
    * */
    public function getApprovalRejectReasons()
    {
        return $this->belongsTo(Quote::class);
    }
}
