<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRfq extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = [
        'user_id',
        'rfq_id',
        'is_deleted',
        'created_at',
        'updated_at'
    ];

    public function userrfqName()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function rfq() {
        return $this->hasOne(Rfq::class,'id','rfq_id');
    }
}
