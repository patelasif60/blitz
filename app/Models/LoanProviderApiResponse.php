<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * LoanProviderApiResponse
 */
class LoanProviderApiResponse extends Model
{
    use HasFactory, SystemActivities;

    const APIResponse   = 1;
    const APIRequest    = 2;

    /**
     * fillable The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'loan_provider_id',
        'applicant_id',
        'user_id',
        'request_data',
        'response_code',
        'response_data',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @param $data
     * @return mixed
     */
    public static function createOrUpdateLoanProvideApiResponse($data)
    {
        $result = null;
        if (isset($data['id'])) {
            $result = self::where(['id' => $data['id']])->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public static function bootApiActivity($loanProvider, $user, $loanApplicant, $type, $data)
    {

        if ($type == LoanProviderApiResponse::APIRequest) {

            $loanAPI = self::create([
                'loan_provider_id'      =>  $loanProvider,
                'user_id'               =>  $user,
                'applicant_id'          =>  $loanApplicant,
                'request_data'          =>  $data['request_data']
            ]);

        }

        if ($type == LoanProviderApiResponse::APIResponse) {

            if ($data['id']) {

                $loanAPI = self::where('id', $data['id'])->update([
                    'response_data'         =>  $data['request_data'],
                    'response_code'         =>  $data['response_code']
                ]);

            } else {
                $loanAPI = self::create([
                    'loan_provider_id'      =>  $loanProvider,
                    'user_id'               =>  $user,
                    'applicant_id'          =>  $loanApplicant,
                    'response_data'         =>  $data['request_data'],
                    'response_code'         =>  $data['request_code']
                ]);
            }

        }

        return $loanAPI;

    }

    /**
     * Loan provider API relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanProvider()
    {
        return $this->belongsTo(LoanProviderApiList::class);
    }
}
