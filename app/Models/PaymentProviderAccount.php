<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProviderAccount extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    const KOINWORKDB = 'Koinwork Debit';
    const DEBIT_AC = 1;
    const CREDIT_AC = 2;
    const LIVE_ENV = 4;
    const BETA_ENV = 3;

    protected $fillable = [
        'payment_provider_id',
        'name',
        'email',
        'payment_provider_ac_id',
        'response_data',
        'description',
        'environment_type',
        'account_type'
    ];

    /**
     * Get koinworks xendit debit account collection
     *
     * @return mixed
     */
    public function koinworksXenDebit()
    {
        return self::where('payment_provider_id',PaymentProvider::XENDIT)->where('environment_type', config('app.env')=='live' ? PaymentProviderAccount::LIVE_ENV : PaymentProviderAccount::BETA_ENV)->where('account_type',PaymentProviderAccount::DEBIT_AC)->first();
    }

}
