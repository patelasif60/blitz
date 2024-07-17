<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFeedbackReasons extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = ['reasons', 'reasons_type', 'created_at', 'updated_at'];
}
