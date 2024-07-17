<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Traits\SystemActivities;

class PaymentTerms extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Payment Terms";
    protected $table = 'payment_terms';
    protected $fillable = [
        'name',
        'payment_group_id',
        'description',
        'status',
        'is_deleted',
    ];
    protected $dates = ['created_at','updated_at'];
    public function PaymentGroup()
    {
        return $this->hasOne(PaymentGroup::class,'id','payment_group_id');
    }
    public function userPaymentterms()
    {
        return $this->hasOne(UserPaymentTerms::class,'payment_term_id','id')->where('user_id', Auth::user()->id);
    }
}
