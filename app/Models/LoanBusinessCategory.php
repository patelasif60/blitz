<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanBusinessCategory extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = [
        'id',
        'name',
        'description'
    ];
}
