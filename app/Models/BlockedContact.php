<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockedContact extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;
    protected $table = 'blocked_contacts';
    protected $fillable = [
        'contact_email'
    ];
    protected $dates = ['created_at','updated_at','deleted_at'];
}
