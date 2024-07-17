<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentTerms extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = [
        'user_id',
        'payment_term_id',
        'is_deleted',
    ];


}
