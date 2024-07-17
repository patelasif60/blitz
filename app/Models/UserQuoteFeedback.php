<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class UserQuoteFeedback extends Model
{
    use HasFactory, SystemActivities;

    protected $table = 'user_quote_feedbacks';

    protected $fillable = [
        'user_id',
        'rfq_id',
        'quote_id',
        'security_code',
        'feedback',
        'resend_mail',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['created_at','updated_at'];

    /**
     * Searchable fields for approval filters
    */
    protected $searchable = [
        'rfq_id',
        'quote_id',
    ];

    /** Get quote from quote feedback */
    public function quotes() {
        return $this->hasOne(Quote::class,'id','quote_id');
    }

    /** Get quote from quote feedback */
    public function rfqs() {
        return $this->hasOne(Rfq::class,'id','rfq_id');
    }
    /**
     * Get all rfq and quotes for approval process (Ronak M)
     */
    public static function getAllRfqQuoteApproval() {
        $usersByCompany = User::whereJsonContains('assigned_companies',Auth::user()->default_company)->get(); // User of particular
        $userIds = array_column($usersByCompany->toArray(),'id'); // User Id fetch
        $userFeedbackRfqId = self::select('rfq_id')->whereIn('user_id', $userIds)->get(); // Get RFQ from Feedback by User ID.

        $query = UserQuoteFeedback::with('rfqs','rfqs.rfqProducts','rfqs.rfqStatus','rfqs.rfqUser', 'rfqs.getQuoteMetaData','rfqs.rfqProducts.unit','rfqs.rfqAttachment','rfqs.group','quotes','quotes.quoteStatus','quotes.quoteItem','quotes.supplier','quotes.getQuoteApprovalReason')->where('is_deleted',0)->where('user_id','=',Auth::user()->id);

        return $query;
    }
}
