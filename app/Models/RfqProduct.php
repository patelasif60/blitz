<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqProduct extends Model
{
    use HasFactory, SystemActivities;

    protected $guarded = [];

    protected $dates = ['created_at','updated_at'];

    /**
     * Get product descriptive name without description
     *
     * @return string
     */
    public function getProductNameDescAttribute()
    {
        return $this->category . ' - ' . $this->sub_category . ' - ' . $this->product;
    }

    /**
     * Get product name with description
     *
     * @return string
     */
    public function getProductNameDescriptionAttribute()
    {
        return $this->category . ' - ' . $this->sub_category . ' - ' . $this->product .' - '. $this->product_description;
    }


    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function rfq(){
        return $this->belongsTo(Rfq::class);
    }

    public function supplierProduct(){
      return $this->hasMany(SupplierProduct::class,'product_id','product_id');
    }

    public function user_rfq(){
        return $this->belongsTo(UserRfq::class,'rfq_id','rfq_id');
    }

    /**
     * Get the product of RFQ Product - Has One
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne(Product::class,'id', 'product_id');
    }

}
