<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class RfnItems extends Model
{
    use HasFactory, SystemActivities, SoftDeletes;

    const PREFIX = 'BIRFN';

    protected $fillable = [
        'rfn_id',
        'rfn_response_id',
        'item_number_prefix',
        'item_number',
        'category_id',
        'category_name',
        'subcategory_id',
        'subcategory_name',
        'product_id',
        'product_name',
        'unit_id',
        'quantity',
        'item_description'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::created(function ($rfnItems) {
            $rfnItems->setReferenceNumber($rfnItems);
        });
    }

    /**
     * Update reference number
     *
     * @param RfnItems $rfnItems
     */
    protected function setReferenceNumber(RfnItems $rfnItems)
    {
        $rfnItems->item_number_prefix = RfnItems::PREFIX;
        $rfnItems->item_number = $rfnItems->rfn_id.'/10'.$rfnItems->id;
        $rfnItems->save();
    }

    /**
     * Get the reference_number
     *
     * @return string
     */
    public function getReferenceNumberAttribute()
    {
        return $this->item_number_prefix.'-'.$this->item_number;
    }

    /**
     *Get the product_fullname
     */
    public function getProductFullnameAttribute()
    {
        return $this->category_name.' - '.$this->subcategory_name.' - '.$this->product_name;
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

    /**
     * Get the Unit of RFQ Product - Has One
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    /**
     * Get the RFN list
     */
    public function rfn() {
        return $this->hasOne(Rfn::class,'rfn_number','rfn_id');
    }
    /**
     * Get the RFN Response
     */
    public function rfnResponse() {
        return $this->hasOne(Rfn::class,'id','rfn_response_id');
    }
    /** Get Rfns list */
    public function rfns() {
        return $this->hasMany(Rfn::class,'rfn_number','rfn_id');
    }

    /**
     * Get list of all rfns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
     public function getAllRfnList()
     {

        return RfnItems::with('rfn','unit','rfn.defaultCompanyUser');

     }
}
