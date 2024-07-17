<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * LoanApplicantSpouse
 */
class LoanApplicantSpouse extends Model
{
    use HasFactory, SystemActivities;

    const RELATIONSHIP_WITH_BORROWER = [
        1=>"PARENT",2=>"SIBLING",3=>"SPOUSE",4=>"COLLEAGUE",5=>"PROFESSIONAL",6=>"OTHER"
    ];

    /**
     * fillable The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'applicant_id',
        'relationship_with_borrower',
        'first_name',
        'last_name',
        'email',
        'ktp_image',
        'phone_code',
        'phone_number',
        'ktp_nik',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = ['full_name','relationship_with_borrower_name'];

    public function getRelationshipWithBorrowerNameAttribute()
    {
        return self::RELATIONSHIP_WITH_BORROWER[$this->relationship_with_borrower];
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * applicant has one spose relationship
     *
     * @return void
     */
    public function applicant(){
        return $this->hasOne(LoanApplicant::class);
    }

    public static function reuploadDoucment($id,$request,$folder,$userId,$companyId)
    {
        $loanApplicantSpouse = self::where(['applicant_id'=>$id])->first();
        if (!empty($loanApplicantSpouse)) {
            //first removed old files
            deleteFile(public_path($loanApplicantSpouse->ktp_image));
            //upload new files and save
            $otherKtpImageExt = $request->file('otherKtpImage')->extension();
            $otherKtpImage = $request->file('otherKtpImage')->storeAs($folder,'otherKtpImage'.Carbon::now()->format('ymdhis').'.'.$otherKtpImageExt);

            $loanApplicantSpouse->ktp_image = setFinalPathForLoanApplication($userId,$companyId,$otherKtpImage);
            $loanApplicantSpouse->save();
            return $loanApplicantSpouse->refresh();
        }
    }
}
