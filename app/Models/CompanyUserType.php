<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUserType extends Model
{
    use HasFactory, SystemActivities;

    protected $table = 'company_user_type';

    public $timestamps = true;

    protected $guarded = ['id'];
}
