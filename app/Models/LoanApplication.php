<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class LoanApplication extends Model
{
    use HasFactory, SystemActivities;
    /**
     * fillable The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_application_number',
        'user_id',
        'loan_provider_id',
        'provider_user_id',
        'provider_application_id',
        'applicant_id',
        'company_id',
        'loan_limit',
        'senctioned_amount',
        'reserved_amount',
        'remaining_amount',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hiddenActivity = [
        'created_at',
        'updated_at',
        'verify_otp',
        'uploaded_contracts',
        'status_name'
    ];

    const STATUS = [
        '54648454-8fbd-4492-8749-f86e14aa81f6'=>"Requested",

        'c9e1cd60-7252-44ef-a360-6307b7ec5f92'=>"Assessment",

        '3a8fd367-cfa8-4a1d-89e5-85effce998bc'=>"Approved",

        '97bc6ed9-72b2-4010-9c9f-1e286be5ded8'=>"Declined",

        '2ac0ad02-4b73-4848-b6ae-6658109dcbaf'=>"Rejected",

        '390cc42c-0b52-40e8-9a28-ba9c1717ac03'=>"Confirmed",

        'ab825088-d5d1-41ca-a19e-0d45920874fc'=>"Expired",

        'badfa20d-3562-45eb-bf4a-79a1c59fd3e9'=>"Waiting For Documents",

        'eb29fb79-f7bf-486a-b926-e14a87c23df8'=>"Obsolete",

        '0ff092ed-86c8-49f2-b8bb-7384abbba233'=>"Return to User"

    ];

    const MAX_CREDIT_LIMIT = 2000000000;

    /**
     * applicant has many application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getStatusNameAttribute()
    {
        return isset(self::STATUS[$this->status])?self::STATUS[$this->status]:'Pending';
    }
    public function applicant(){
        return $this->belongsTo(LoanApplicant::class);
    }
    public function loanProvider()
    {
        return $this->belongsTo(LoanProvider::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function loanApplicantSpouse()
    {
        return $this->hasOne(LoanApplicantSpouse::class,'applicant_id','applicant_id');
    }
    public function loanApplicantBusiness()
    {
        return $this->hasOne(LoanApplicantBusiness::class,'applicant_id','applicant_id');
    }

    public function loan()
    {
        return $this->hasOne(LoanApply::class,'application_id');
    }

    public function loans()
    {
        return $this->hasMany(LoanApply::class,'application_id');
    }

    /* Get limit Applicant view details*/
    public static function getApplicationDatail($id){
        return self::with('applicant:id,first_name,last_name,email,phone_code,phone_number,ktp_nik,ktp_image,ktp_with_selfie_image,family_card_image,gender,marital_status,net_salary,other_source_of_income,total_other_income')
            ->with('loanApplicantSpouse:applicant_id,first_name,last_name,relationship_with_borrower,ktp_nik,ktp_image,phone_code,phone_number')
            ->with('loanApplicantBusiness:applicant_id,name,type,npwp_image,license_image,bank_statement_image,siup_number,ownership_percentage,email,phone_number,phone_code')
            ->where('id',$id)->first(['id','loan_application_number','user_id','applicant_id','senctioned_amount','remaining_amount','loan_limit','status','created_at','provider_user_id','provider_application_id']);

    }

    /* Get Credit detail by user id*/
    public static function getCreditDatail(){
        return self::where('company_id', Auth::user()->default_company)->where('status', '3a8fd367-cfa8-4a1d-89e5-85effce998bc')->first();
    }
}
