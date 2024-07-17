<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanProviderApiList extends Model
{
    use HasFactory, SystemActivities;
    const REQUEST_NEW_LIMIT = 1;
    const GENERATE_TOKEN = 2;
    const REQUEST_LIMIT_OTP = 3;
    const VERIFY_LIMIT_OTP = 4;
    const GET_LIMIT = 5;
    const UPLOAD_SIGNED_CONTRACT = 6;
    const MY_LIMIT = 7;
    const REUPLOAD_DOCUMENT = 8;
    const LOAN_DISBURSEMENT_REPORT = 9;
    const LOAN_PARTNER_CANCELLED = 10;
    const REPAYMENT = 11;
    const CREATE_LOAN = 12;
    const REQUEST_LOAN_OTP=13;
    const VERIFY_LOAN_OTP=14;
    const LOAN_CONFIRMATION=15;
    /**
     *
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'loan_provider_id',
        'name',
        'description',
        'method',
        'path',
        'production_base_path',
        'staging_base_path',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * loanprovider has many api
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanProvider(){
        return $this->belongsTo(LoanProvider::class);
    }
}
