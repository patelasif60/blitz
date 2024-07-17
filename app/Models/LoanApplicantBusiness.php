<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * LoanApplicantBusiness
 */
class LoanApplicantBusiness extends Model
{
    use HasFactory, SystemActivities;

    const TYPE = [
        1=>"individual",2=>"pt",3=>"cv"
    ];

    const NUMBER_OF_EMPLOYEE = [
        1=>"1 - 50",2=>"50 - 200",3=>"200 - 500",4=>"500 - 1000",5=>"1000 +"
    ];

    /**
     * fillable The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'applicant_id',
        'name',
        'type',
        'description',
        'website',
        'email',
        'phone_code',
        'phone_number',
        'owner_first_name',
        'owner_last_name',
        'npwp_number',
        'npwp_image',
        'average_sales',
        'establish_in',
        'number_of_employee',
        'bank_statement_image',
        'ownership_percentage',
        'category',
        'siup_number',
        'license_image',
        'district',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = ['type_name','number_of_employee_name'];

    public function getTypeNameAttribute()
    {
        return self::TYPE[$this->type];
    }

    public function getNumberOfEmployeeNameAttribute()
    {
        return self::NUMBER_OF_EMPLOYEE[$this->number_of_employee];
    }

    /**
     * applicant has many buisenss relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicant(){
        return $this->belongsTo(LoanApplicant::class);
    }
    /**
     * loanApplicant has one loanApplicantBusiness
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanApplicantBusinessAddress()
    {
        return $this->hasOne(LoanApplicantBusinessAddress::class,'applicant_business_id');//need to change model name
    }

    public function loanBusinessCategory(){
        return $this->belongsTo(LoanBusinessCategory::class,'category');
    }

    public static function reuploadDoucment($id,$request,$folder,$userId,$companyId)
    {
        $loanApplicantBusiness = self::where(['applicant_id'=>$id])->first();
        if (!empty($loanApplicantBusiness)) {
            //first removed old files
            deleteFile(public_path($loanApplicantBusiness->npwp_image));
            deleteFile(public_path($loanApplicantBusiness->bank_statement_image));
            deleteFile(public_path($loanApplicantBusiness->license_image));
            //upload new files and save
            $loanApplicantBusinessNpwpImageExt = $request->file('loanApplicantBusinessNpwpImage')->extension();
            $loanApplicantBusinessNpwpImage = $request->file('loanApplicantBusinessNpwpImage')->storeAs($folder,'loanApplicantBusinessNpwpImage'.Carbon::now()->format('ymdhis').'.'.$loanApplicantBusinessNpwpImageExt);

            $loanApplicantBankStatementExt = $request->file('loanApplicantBankStatement')->extension();
            $loanApplicantBankStatement = $request->file('loanApplicantBankStatement')->storeAs($folder,'loanApplicantBankStatement'.Carbon::now()->format('ymdhis').'.'.$loanApplicantBankStatementExt);

            $loanApplicantBusinessLicenceImageExt = $request->file('loanApplicantBusinessLicenceImage')->extension();
            $loanApplicantBusinessLicenceImage = $request->file('loanApplicantBusinessLicenceImage')->storeAs($folder,'loanApplicantBusinessLicenceImage'.Carbon::now()->format('ymdhis').'.'.$loanApplicantBusinessLicenceImageExt);

            $loanApplicantBusiness->npwp_image = setFinalPathForLoanApplication($userId,$companyId,$loanApplicantBusinessNpwpImage);
            $loanApplicantBusiness->bank_statement_image = setFinalPathForLoanApplication($userId,$companyId,$loanApplicantBankStatement);
            $loanApplicantBusiness->license_image = setFinalPathForLoanApplication($userId,$companyId,$loanApplicantBusinessLicenceImage);
            $loanApplicantBusiness->save();
            return $loanApplicantBusiness->refresh();
        }
    }
}
