<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteActivity extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['user_id', 'quote_id','key_name ','old_value'.'new_value','user_type'];

    protected $dates = ['created_at','updated_at'];

    public function rfquserName()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
