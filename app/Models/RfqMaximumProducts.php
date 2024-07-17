<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqMaximumProducts extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'rfq_maximum_products';
    protected $fillable = [
        'rfq_id', 'max_products'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at','deleted_at'];
}
