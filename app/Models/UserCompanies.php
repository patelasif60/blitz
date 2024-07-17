<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class UserCompanies extends Model
{
    use HasFactory, HybridRelations, SystemActivities;
    protected $connection = 'mysql';
    protected $fillable = [
        'user_id',
        'company_id',
        'is_deleted'
    ];

    public function userCompany()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
