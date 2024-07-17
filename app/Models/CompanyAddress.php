<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Supplier Address";

    protected $table = 'company_address';

    protected $fillable = [
        'model_type',
        'model_id',
        'company_id',
        'user_id',
        'address',
        'is_deleted'
    ];

    public function users()
    {
        return $this->morphTo(User::class, 'model_type','model_id');
    }
}
