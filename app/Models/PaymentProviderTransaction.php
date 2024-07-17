<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProviderTransaction extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = [
        'users_type', 'users_id', 'company_id', 'payment_provider_id', 'transaction_status',
        'transaction_type_id', 'credit_ac_id', 'credit_ac_type', 'debit_ac_id', 'debit_ac_type',
        'transfer_id', 'source_id', 'destination_id', 'related_type', 'related_id',
        'amount', 'response_by_provider', 'created_type', 'created_id', 'created_at', 'updated_at', 'deleted_at'
    ];
    /**
     * Loan apply morph relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function related()
    {
        return $this->morphTo();
    }

    /**
     * Users morph relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function users()
    {
        return $this->morphTo();
    }
}
