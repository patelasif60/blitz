<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class SubscribedUser extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Subscribe User";
    protected $fillable = [
        'fullname', 'is_deleted','mobile','company_name','email','is_buyer','is_supplier'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

}
