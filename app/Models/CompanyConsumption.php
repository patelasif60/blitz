<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyConsumption extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = [
        'product_cat_id', 'unit_id', 'annual_consumption', 'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
