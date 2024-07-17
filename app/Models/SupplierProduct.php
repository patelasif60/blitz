<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;
use Illuminate\Support\Facades\Request;

class SupplierProduct extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = [
        'product_id', 'supplier_id','is_deleted','description','price','min_quantity','quantity_unit_id','sattus','discount','discounted_price','product_ref','added_by','updated_by','deleted_by'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function productList(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    /**
     * Product image by supplier
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productImage()
    {
        return $this->hasOne(SupplierProductImage::class,'supplier_product_id','id');
    }

    /**
     * Supplier Products Discount Ranges
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productDiscountRange()
    {
        return $this->hasOne(SupplierProductDiscountRange::class,'supplier_product_id','id');
    }

    /**
     * Supplier product unit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productUnit()
    {
        return $this->hasOne(Unit::class,'id','quantity_unit_id');
    }

    public function trackAddData()
    {
        return $this->hasOne(User::class,'id','added_by');
    }
    public function trackUpdateData()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }

    /*@ekta  function for check supllier wise category exist or new added
     *
     * */
    public function checkSupplierWsieCategoryNewadded($supplierId,$categoryArray,$oldCategoryArray=''){
        $newCategoryArray = array_diff($categoryArray,$oldCategoryArray);
        if(!empty($newCategoryArray)){
            foreach ($newCategoryArray as $c => $cat){
                $allRfq = Rfq::with('rfqProducts:rfq_id,category_id,sub_category_id,product_id,category,sub_category,product,expected_date')->select('id','reference_number')->where('is_deleted',0);
                $allRfq->whereHas('rfqProducts', function ($allRfq) use ($cat) {
                    $allRfq->where('sub_category_id', $cat)->whereIN('status_id', ['1','2']);
                });
                $allRfq->orderBy('id', 'desc');
                $rfqsDetails = $allRfq->get();
                $rdc = $rfqsDetails->count();
                if($rdc > 0){
                    foreach ($rfqsDetails as $i => $rfqsDetail) {
                        $productCount = count($rfqsDetail->rfqProducts);
                        $productCount == 1 ? $productName = $rfqsDetail->rfqProducts[0]['category'].' - '.$rfqsDetail->rfqProducts[0]['sub_category'].' - '.$rfqsDetail->rfqProducts[0]['product'] : $productName = count($rfqsDetail->rfqProducts) . ' ' . __('admin.products') ;
                        $rfqsDetails[$i]['product_name'] = $productName;
                        $rfqsDetails[$i]['product_expected'] = $rfqsDetail->rfqProducts[0]['expected_date'];
                        $rfqsDetails[$i]['category_ids'] = $rfqsDetail->rfqProducts[0]['category_id'];
                        $rfqsDetails[$i]['sub_category_ids'] = $rfqsDetail->rfqProducts[0]['sub_category_id'];
                    }
                    $newCategoryArray[$c] = $rfqsDetails->toArray();
                    $newCategoryArray[$c]['cat_name'] = $rfqsDetail->rfqProducts[0]['category'];
                    $newCategoryArray[$c]['subcat_id'] = $cat;
                    $newCategoryArray[$c]['subcat_name'] = SubCategory::where('id',$cat)->where('is_deleted',0)->pluck('name')->first();
                }else{
                    $newCategoryArray[$c] = [];
                    $newCategoryArray[$c]['subcat_id'] = $cat;
                    $newCategoryArray[$c]['subcat_name'] = '';
                }
            }
            return $newCategoryArray;
        }else{
            return [];
        }
    }
    
    /*
     * relatio between supplier product to product
     * */
    public function products(){
        return $this->hasMany(Product::class,'id','product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rfqProduct(){
        return $this->hasMany(RfqProduct::class,'product_id','product_id');
    }

}
