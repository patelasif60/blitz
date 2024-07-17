<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SystemActivities;

class Product extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;
   // use Search;

    protected $tagname = "Product";
    protected $fillable = [
        'name', 'description','status','is_verify','subcategory_id','is_deleted','added_by','updated_by','deleted_by'
    ];

    protected $searchable = [
        'name',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];
    public function trackAddData()
    {
        return $this->hasOne(User::class,'id','added_by');
    }
    public function trackUpdateData()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }

    /**
     * Relationship b/n Subcategory and Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function category()
    {
        return $this->hasOneThrough(Category::class,SubCategory::class,  'category_id','id', 'id', 'id');
    }


    /**
     * Relationship b/n Product and subcategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subcategory()
    {
        return $this->hasOne(SubCategory::class,'id','subcategory_id');
    }

    /*
     * relation between product to supplier
     * */
    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class,'product_id','id');
    }

}
