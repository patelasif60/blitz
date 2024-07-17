<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTransaction extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = [
        'loan_id', 'order_id', 'applicant_id', 'application_id', 'users_type', 'users_id', 'company_id', 'transaction_reference_id', 'transaction_amount', 'transaction_proof', 'transaction_status', 'remarks', 'transaction_ac_type', 'transaction_type_id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function transactionsType(){
        return $this->belongsTo(TransactionsType::class,'transaction_type_id');
    }
    public function loanStatus(){
        return $this->belongsTo(LoanStatus::class,'transaction_status');
    }

    /**
     * Relationship b/n Loan Transaction & Charges Type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanCharge(){
        return $this->belongsTo(LoanProviderCharges::class,'charge_type_id');
    }

}
