<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierDealWithCategory extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['supplier_id', 'category_id', 'sub_category_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    /*
     * @when add new category in supplier deal with category
     * */
    public static function createSupplierDealWithCategory($data){
        return self::create($data);
    }
    /*
    * relation between supplier deal with category and category
    * */
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    /*
     * relation between subcategory and dealing with category.
     */
    public function subCategory()
    {
        return $this->hasOne(SubCategory::class, 'id', 'sub_category_id');
    }
}
