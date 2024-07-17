<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class XenditPaymentInvoice extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $fillable =[
        'related_to_type',
        'related_to_id',
        'xendit_id',
        'invoice_type',
        'payment_link',
        'expiry_date',
        'status',
        'response'
    ];

}
