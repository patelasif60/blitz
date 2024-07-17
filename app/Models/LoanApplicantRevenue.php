<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicantRevenue extends Model
{
    use HasFactory, SystemActivities;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_id',
        'company_id',
        'monthly_date',
        'revenue',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function createOrUpdateApplicantRevenue($data){

        $result = self::where(['applicant_id'=>$data['applicant_id'],'monthly_date'=>$data['monthly_date']])->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

}
