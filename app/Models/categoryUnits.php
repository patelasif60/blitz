<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoryUnits extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'categories_units';
	public $timestamps = false;
    protected $fillable = [
        'category_id', 'is_deleted',
        'unit_id'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];


}
