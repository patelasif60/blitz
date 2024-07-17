<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProductDiscountRange extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = [
        'supplier_product_id', 'product_id','supplier_id','min_qty','max_qty','unit_id','discount','discounted_price'
    ];
}
