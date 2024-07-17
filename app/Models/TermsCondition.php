<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class TermsCondition extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Terms & Conditions";
    protected $fillable = [
        'buyer_default_tcdoc', 'supplier_default_tcdoc',
    ];

     protected $dates = ['created_at','updated_at'];
}
