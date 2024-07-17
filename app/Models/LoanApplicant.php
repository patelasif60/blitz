<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * LoanApplicant  Table
 */
class LoanApplicant extends Model
{
    use HasFactory,SystemActivities;
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const GENDER = [
        1=>'Male',2=>'Female',
    ];

    const MARITIAL_STATUS = [
        1=>'KAWIN',2=>'BELUM KAWIN',3=>'CERAI MATI',4=>'CERAI HIDUP',
    ];

    const RELIGION = [
        1=>"ISLAM",2=>"KATHOLIK",3=>"KRISTEN",4=>"BUDHA",5=>"HINDU",6=>"KONGHUCHU",7=>"OTHER"
    ];

    const OTHER_SOURCE_OF_INCOME = [
        1=>"BUSINESS REVENUE",2=>"FUND REVENUE",3=>"INHERIRTANCE",4=>"SALARY",5=>"PARENT/GUARDIAN"
    ];

    const POSITION = [
        1=>"DIRECTOR",2=>"VICE DIRECTOR",3=>"MANAGER",4=>"COMMISIONER"
    ];

    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'loan_provider_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'ktp_nik',
        'ktp_image',
        'ktp_with_selfie_image',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'marital_status',
        'religion',
        'education',
        'occupation',
        'total_other_income',
        'other_source_of_income',
        'net_salary',
        'my_position',
        'first_account_created_at',
        'family_card_image',
        'phone_code',
        'contracts',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = ['full_name','marital_status_name','gender_name','religion_name','other_source_income_name','my_position_name'];

    /**
     * Get gender of applicant
     *
     * @return mixed|string
     */
    public function getGenderNameAttribute()
    {
        return self::GENDER[$this->gender];
    }

    /**
     * Get fullname of applicant
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get marital status of applicant
     *
     * @return string
     */
    public function getMaritalStatusNameAttribute()
    {
        return self::MARITIAL_STATUS[$this->marital_status];
    }

    public function getReligionNameAttribute()
    {
        return self::RELIGION[$this->religion];
    }

    public function getOtherSourceIncomeNameAttribute()
    {
        return self::OTHER_SOURCE_OF_INCOME[$this->other_source_of_income];
    }

    public function getMyPositionNameAttribute()
    {
        return self::POSITION[$this->my_position];
    }

    /**
     * company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * loanProviders provide many loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanProviders()
    {
        return $this->hasMany(LoanProvider::class);
    }

    /**
     * loanApplications has many applicant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class,'applicant_id');
    }

    /**
     * loanApplicant has one loanApplicantAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanApplicantAddress()
    {
        return $this->hasOne(LoanApplicantAddress::class,'applicant_id');
    }

    /**
     * loanApplicant has one loanApplicantSpouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanApplicantSpouse()
    {
        return $this->hasOne(LoanApplicantSpouse::class,'applicant_id');
    }

    /**
     * loanApplicant has one loanApplicantBusiness
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanApplicantBusiness()
    {
        return $this->hasOne(LoanApplicantBusiness::class,'applicant_id');
    }

    public static function reuploadDoucment($id,$request,$folder,$userId,$companyId)
    {
        $loanApplicant = self::where(['id'=>$id])->first();
        if (!empty($loanApplicant)) {
            //first removed old files
            deleteFile(public_path($loanApplicant->ktp_image));
            deleteFile(public_path($loanApplicant->ktp_with_selfie_image));
            deleteFile(public_path($loanApplicant->family_card_image));
            //upload new files and save
            $ktpImageExt = $request->file('ktpImage')->extension();
            $ktpImage = $request->file('ktpImage')->storeAs($folder, 'ktpImage' . Carbon::now()->format('ymdhis') . '.' . $ktpImageExt);

            $ktpSelfiImageExt = $request->file('ktpSelfiImage')->extension();
            $ktpSelfiImage = $request->file('ktpSelfiImage')->storeAs($folder, 'ktpSelfiImage' . Carbon::now()->format('ymdhis') . '.' . $ktpSelfiImageExt);

            $familyCardImageExt = $request->file('familyCardImage')->extension();
            $familyCardImage = $request->file('familyCardImage')->storeAs($folder, 'familyCardImage' . Carbon::now()->format('ymdhis') . '.' . $familyCardImageExt);

            $loanApplicant->ktp_image = setFinalPathForLoanApplication($userId, $companyId, $ktpImage);
            $loanApplicant->ktp_with_selfie_image = setFinalPathForLoanApplication($userId, $companyId, $ktpSelfiImage);
            $loanApplicant->family_card_image = setFinalPathForLoanApplication($userId, $companyId, $familyCardImage);
            $loanApplicant->save();
            return $loanApplicant->refresh();
        }
    }
}
