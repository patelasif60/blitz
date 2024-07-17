<?php

use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierDealWithCategory;
use App\Models\SupplierProduct;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * assigne existing tag category to suppler deal with category.
 * */
Artisan::command('tagcategory', function () {
    if (Schema::hasColumn('suppliers', 'interested_in')) {
        $allSuppliers = Supplier::where('is_deleted',0)->where('interested_in','<>','')->whereNotNull('interested_in')
            ->select('id','interested_in')->get();
        $supplierCatTag= [];
        foreach ($allSuppliers as $i =>$supplierTags){
            if(!empty($supplierTags->interested_in)){
                $interested_in = explode(',', $supplierTags->interested_in);
                $categoryIdArray = Category::where('is_deleted',0)->where('status',1)->whereIN('name',$interested_in)->pluck('id')->toArray();
                $supplierCatTag[$i]['id'] = $supplierTags->id;
                $supplierCatTag[$i]['interested_in'] = $interested_in;
                $supplierCatTag[$i]['category_id'] = $categoryIdArray;
            }
        }
        $supplierCategoryTag = collect($supplierCatTag)->where('category_id','!=',null);

        foreach($supplierCategoryTag as $sct){
            $sdwc = SupplierDealWithCategory::where(['supplier_id'=>$sct['id'],'deleted_at'=>null])->pluck('category_id')->toArray();
            $newTagCategory = array_diff($sct['category_id'],$sdwc);
            $newTagCat =[];
            if(!empty($newTagCategory)){
                foreach ($newTagCategory as $key => $value){
                    $newTagCat[] =  array('category_id' => $value,'supplier_id' => $sct['id'],'created_at'=>now(),'updated_at'=>now());
                }
                SupplierDealWithCategory::insert($newTagCat);
            }
        }
        dump('success');
    }
})->purpose('category tag entry in deal with category');

Artisan::command('upgrade', function () {
    //add new field address_id in rfqs
    if (!Schema::hasColumn('rfqs', 'address_id')) {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->integer('address_id')->after('billing_tax_option')->nullable();
        });
    }

})->purpose('Upgrade DB');

/*
 * Script for supplier product get category and entry supplier wise deal with category table
 * */
Artisan::command('dealwithsubcategories', function () {
    if (Schema::hasColumn('supplier_deal_with_categories', 'sub_category_id')) {
        $allSuppliers = SupplierProduct::where('is_deleted',0)->select('supplier_id')->distinct()->get();
        $insertData = [];
        foreach ($allSuppliers as $supplier){
            $collect = collect();
            $supplierId = $supplier->supplier_id;
            $getSupplierProducts = SupplierProduct::with(['product'=>function($q){
                $q->select(['id','subcategory_id'])->with('subCategory:id,category_id');
            }])->where('supplier_id',$supplierId)->select('product_id')->distinct()->get();

            foreach ($getSupplierProducts as $product){
                if(!empty($product->product->subCategory->category_id)){
                    if (!$collect->contains('sub_category_id', $product->product->subCategory->id)){
                        $collect->push([
                            'category_id' => $product->product->subCategory->category_id,
                            'sub_category_id' => $product->product->subCategory->id,
                        ]);
                    }
                }
            }
            if(!empty($collect)){
                foreach($collect as $arrayColu){
                    $insertData[] = array('supplier_id'=>$supplierId,
                        'category_id'=>$arrayColu['category_id'],
                        'sub_category_id'=>$arrayColu['sub_category_id'],
                        'created_at'=>now(),
                        'updated_at'=>now()
                    );
                }
            }
        }

        if(!empty($insertData)){
            SupplierDealWithCategory::insert($insertData);
        }
        dump('success');
    }
})->purpose('New table Script');
