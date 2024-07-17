<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProductBrand extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = [
        'supplier_product_id', 'brand_id','is_deleted'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

}
