<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqStatus extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'rfq_status';
    protected $fillable = [
        'name', 'description','backofflice_name','is_deleted'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

}