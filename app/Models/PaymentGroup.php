<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class PaymentGroup extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Payment Group";
    protected $table = 'payment_groups';
    protected $fillable = [
        'name',
        'description',
        'status',
        'is_deleted',
    ];
    protected $dates = ['created_at','updated_at'];
}
