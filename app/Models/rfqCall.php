<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rfqCall extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'rfqs_call';
	protected $dates = ['created_at','updated_at'];
}
