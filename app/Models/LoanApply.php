<?php

namespace App\Models;

use App\Http\Controllers\API\Xendit\XenditPaymentInvoiceController;
use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanApply extends Model
{
    use HasFactory, SystemActivities, SoftDeletes;

    protected $table = 'loan_applies';

    protected $fillable = [
        'loan_provider_id', 'loan_number', 'user_id', 'provider_user_id', 'applicant_id', 'application_id', 'company_id', 'order_id', 'quote_id', 'provider_loan_id', 'loan_amount', 'loan_confirm_amount', 'loan_repay_amount', 'interest', 'additional_amount', 'paid_amount', 'disbursed_to_supplier', 'disbursed_to_koinworks', 'due_date', 'description', 'status_id', 'tenure_days', 'created_at', 'updated_at', 'deleted_at'
    ];

    const STATUS = [
        '17a27970-c85d-4ea0-bb9a-b7c3d32d2b62'=>"Partner Cancelled",

        '7f25fba3-d916-11e9-97fa-00163e010bca'=>"Assessment Approved",

        '3b6b220b-2019-4279-8343-cbe6167f7571'=>"Pending Disburse",

        '3b6b220b-2019-4279-8343-cbe6167f7572'=>"Funding Process",

        '723bff9e-f700-11e9-97fa-00163e010bca'=>"Waiting For Disburse",

        '3b6b220b-2019-4279-8343-cbe6167f7574'=>"On Going",

        '3b6b220b-2019-4279-8343-cbe6167f7575'=>"Paid Off",

        'ecc2abee-e4d0-11e9-97fa-00163e010bca'=>"NPL"

    ];

    public static function createOrUpdateLoanApply($data){
        $where = [];
        if (isset($data['quote_id'])){
            $where = array_merge($where,['quote_id' => $data['quote_id']]);
        }
        if (isset($data['provider_loan_id'])){
            $where = array_merge($where,['provider_loan_id' => $data['provider_loan_id']]);
        }

        $result = self::where($where)->first();

        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public static function getLoanDetails($id)
    {
        $loanApply = self::with('orders:id,order_number,order_status')
                    ->with('companies:id,name')
                    ->with('loanApplications:id,loan_limit,senctioned_amount,remaining_amount')
                    ->with(['loanApplicants'=>function($q){
                        $q->select(['id','first_name','last_name'])->with('loanApplicantBusiness:id,applicant_id,email,phone_number,phone_code');
                    }])
                    ->with(['orderItems'=>function($q){
                        $q->select(['order_id','order_item_number', 'quote_item_id', 'rfq_product_id', 'product_id', 'product_amount', 'order_item_status_id'])
                            ->with('quoteItem:id,product_price_per_unit,product_quantity,product_amount,price_unit');
                    }])
                    ->with(['loanTransaction'=>function($q){
                        $q->with('transactionsType:id,name')->with('loanStatus:id,status_display_name');
                    }])
                    ->where(['id'=>$id])->first();
        return $loanApply;
    }

    /**
     * get order table data
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function orders(){
        return $this->belongsTo(Order::class,'order_id');
    }

    /**
     * get company data
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function companies(){
        return $this->belongsTo(Company::class,'company_id');
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function loanApplicants(){
        return $this->belongsTo(LoanApplicant::class,'applicant_id');
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function loanApplications()
    {
        return $this->belongsTo(LoanApplication::class,'application_id');
    }

    public function loanApplicantBusiness()
    {
        return $this->hasOne(LoanApplicantBusiness::class,'applicant_id','applicant_id');
    }

    /**
     * user details
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * get quote items table data
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasmany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'order_id','order_id');
    }

    /**
     * get order table data
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function loanStatus(){
        return $this->belongsTo(LoanStatus::class,'status_id');
    }

    /**
     * get loan transaction data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanTransactions(){
        return $this->hasMany(LoanTransaction::class,'loan_id');
    }

    /**
     * Get all payment link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function paymentLink()
    {
        return $this->morphMany(XenditPaymentInvoice::class, 'related_to');
    }

    /**
     * Get 3rd party payment transactions
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function paymentProviderTransaction(){
        return $this->morphMany(PaymentProviderTransaction::class,'related');
    }

    /** Get loan transaction data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanTransaction(){
        return $this->hasOne(LoanTransaction::class,'loan_id');
    }

}
