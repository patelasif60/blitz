<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groups extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $tagname = "Groups";

    //group status
    const OPEN = 1;
    const HOLD = 2;
    const CLOSED = 3;
    const EXPIRED = 4;

    protected $fillable = [
        'name', 'group_number', 'added_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at', 'social_token',
        'category_id', 'subCategory_id','product_id','description','end_date', 'reached_quantity', 'status', 'location_code', 'price', 'min_order_quantity','max_order_quantity','group_margin'
    ];

    public function groupMember(){
        return $this->hasOne(GroupMember::class, 'group_id');
    }

    public function groupSuppler(){
        return $this->hasOne(GroupSupplier::class, 'group_id');
    }

    public function groupDiscountOption(){
        return $this->hasOne(GroupSupplierDiscountOption::class, 'group_id');
    }

    public function productDetailsMultiple(){
        return $this->hasMany(GroupSupplierDiscountOption::class, 'group_id');
    }

    public function groupTagsMultiple(){
        return $this->hasMany(GroupTag::class, 'group_id');
    }

    public function groupImagesMultiple(){
        return $this->hasMany(GroupImages::class, 'group_id');
    }

    public function groupMembersMultiple(){
        return $this->hasMany(GroupMember::class,'group_id');
    }

    public function rfq()
    {
        return $this->hasOne(Rfq::class,'group_id');
    }

    public function rfqs()
    {
        return $this->hasMany(Rfq::class,'group_id');
    }

    public function quote()
    {
        return $this->hasOne(Quote::class,'group_id');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class,'group_id');
    }

    public function disbursement(){
        return $this->hasOne(Disbursements::class,'group_id');
    }

    public function disbursements(){
        return $this->hasMany(Disbursements::class,'group_id');
    }

    public function groupMembersDiscount()
    {
        return $this->hasOne(GroupMembersDiscount::class,'group_id');
    }

    public function groupMembersDiscounts()
    {
        return $this->hasMany(GroupMembersDiscount::class,'group_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class,'group_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'group_id');
    }

    public function blitznetCommission()
    {
        return $this->hasOne(BlitznetCommission::class,'group_id');
    }

    public function blitznetCommissions()
    {
        return $this->hasMany(BlitznetCommission::class,'group_id');
    }

}
