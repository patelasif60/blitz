<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Requests\Supplier\BusinessDetailsRequest;
use App\Http\Requests\Supplier\SupplierProfessionalProfileRequest;
use App\Jobs\SupplierNotifyCategoryWiseRfqlistJob;
use App\Models\CompanyDetails;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\RfqProduct;
use App\Models\SupplierDealWithCategory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupplierCompanyDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BusinessDetailsRequest $request)
    {
        try {

            $supplier = Supplier::with(['user','user.company'])->where('id',Crypt::decrypt($request->user_id))->first();

            $companyDetails = CompanyDetails::create([
                'user_id'               => isset($supplier->user->id) ? $supplier->user->id : null,
                'model_type'            => Supplier::class,
                'model_id'              => isset($supplier->id) ? $supplier->id : null,
                'company_id'            => isset($supplier->user->company->id) ? $supplier->user->company->id : null,
                'founders'              => $request->founders,
                'name'                  => $request->name,
                'headquarters'          => $request->headquarters,
                'product_services'      => $request->product_services,
                'number_of_employee'    => $request->number_of_employee,
                'sector'                => $request->sector,
                'net_income'            => $request->net_income,
                'annual_sales'          => $request->annual_sales,
                'financial_target'      => $request->financial_target,
                'sector_type'           => $request->sector_type,
                'company_description'   => strip_tags($request->company_description),
            ]);

            CompanyDetails::bootSystemActivities();

            return response()->json(['success' => true,'message' => __('admin.business_details_added')],200);


        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B032 Business Details');
            return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],500);

        }

        Log::critical('Code - 400 | ErrorCode:B032 Business Details');
        return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],400);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BusinessDetailsRequest $request, $id)
    {
        $supplierID = Crypt::decrypt($id);
        $oldCategoryArray = SupplierDealWithCategory::where(['supplier_id'=>$supplierID,'deleted_at'=>null])->whereNotNull('sub_category_id')->pluck('sub_category_id')->toArray();

       /* try {*/
            $supplier = Supplier::with(['user','user.company'])->where('id',$supplierID)->first();

            /* Supplier deal with category add start */

            if(!empty($request->supplier_category)){
                $getSupplierProductsCatIds = SupplierProduct::with(['product'=>function($q){
                    $q->select(['id','subcategory_id']);
                }])->where('supplier_id',$supplierID)->select('product_id')->distinct()->get();
                $grey = array_unique($getSupplierProductsCatIds->pluck('product.subcategory_id')->toArray());

                $newCategoryArray = array_diff($request->supplier_category,$oldCategoryArray);
                $newArray = array_merge($grey,$request->supplier_category);
                $removeArray = array_diff($oldCategoryArray,$newArray);

                $subCatIdsArray = [];
                if(!empty($newCategoryArray)){
                    foreach($newCategoryArray as $subCategory){
                        $getCatId = SubCategory::where('id',$subCategory)->pluck('category_id')->first();
                        $subCatIdsArray[] = array('category_id'=>$getCatId,'sub_category_id'=>$subCategory,'supplier_id'=>$supplierID,'created_at'=>now(),'updated_at'=>now());
                    }
                    if(!empty($subCatIdsArray)){
                        SupplierDealWithCategory::insert($subCatIdsArray);
                    }
                }
                if(!empty($removeArray)){
                    foreach($removeArray as $subCategory){
                        SupplierDealWithCategory::where('supplier_id',$supplierID)->where('sub_category_id',$subCategory)->forceDelete();
                    }
                }
            }else{
                $getSupplierProductsCatIds = SupplierProduct::with(['product'=>function($q){
                    $q->select(['id','subcategory_id']);
                }])->where('supplier_id',$supplierID)->where('is_deleted',0)->select('product_id')->distinct()->get();
                $NotDeleteSubCategory = array_unique($getSupplierProductsCatIds->pluck('product.subcategory_id')->toArray());
                $deleteSubCatArray = array_diff($oldCategoryArray,$NotDeleteSubCategory);
                if(!empty($deleteSubCatArray)){
                    SupplierDealWithCategory::where('supplier_id',$supplierID)->whereIn('sub_category_id',$deleteSubCatArray)->forceDelete();
                }
            }
            /* Supplier deal with category add end */

            /*  check this category already added or new added //send mail if new added **/
            if(!empty($request->supplier_category)){
                $newCategoryArray = $request->supplier_category;
                $supplierDetails = Supplier::where(['is_deleted'=> 0, 'id'=>$supplierID])->select('id','contact_person_name','contact_person_last_name','contact_person_email')->first();
                $supplierNewaddCategoryList = SupplierProduct::checkSupplierWsieCategoryNewadded($supplierID,$newCategoryArray,$oldCategoryArray); // check this supplier deal in this categories

                if(!empty($supplierNewaddCategoryList) && !empty($supplierDetails)){
                    dispatch(new SupplierNotifyCategoryWiseRfqlistJob($supplierNewaddCategoryList, $supplierDetails));
                }
            }
            /* end mail */

            $companyDetails = CompanyDetails::where('model_id', $supplier->id)->where('model_type', Supplier::class)->update([
                'user_id'               =>isset($supplier->user->id) ? $supplier->user->id : null,
                'model_type'            => Supplier::class,
                'model_id'              => isset($supplier->id) ? $supplier->id : null,
                'company_id'            => isset($supplier->user->company->id) ? $supplier->user->company->id : '',
                'founders'              => $request->founders,
                'name'                  => $request->name,
                'headquarters'          => $request->headquarters,
                'product_services'      => $request->product_services,
                'number_of_employee'    => $request->number_of_employee,
                'sector'                => $request->sector,
                'net_income'            => $request->net_income,
                'net_income_currency'   => "IDR",
                'annual_sales'          => $request->annual_sales,
                'annual_sales_currency' => "IDR",
                'financial_target'      => $request->financial_target,
                'financial_target_currency'=> "IDR",
                'sector_type'           => $request->sector_type,
                'company_description'   => strip_tags($request->company_description),
            ]);

            CompanyDetails::bootSystemActivities();

            return response()->json(['success' => true, 'supplierId' => $supplier->id,'message' => __('admin.business_details_updated')],200);


       /* } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B031 Business Details');
            return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],500);

        }*/

        Log::critical('Code - 400 | ErrorCode:B031 Business Details');
        return response()->json(['success' => false,'message' => __('admin.something_went_wrong')],400);

    }

    /**
     *
     * Supplier Basic Details Update
     *
     * @param SupplierProfessionalProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSupplierBasicDetail(SupplierProfessionalProfileRequest $request, $id)
    {
        try {

            //$user = User::with(['supplier','company'])->where('id', Crypt::decrypt($id))->first();
            $supplier = Supplier::with(['user','user.company'])->where('id',Crypt::decrypt($id))->first();
            $policiesFilePath = '';
            if ($request->file('policiesImage')) {
                $policiesFileName = Str::random(10) . '_' . time() . 'policies_' . $request->file('policiesImage')->getClientOriginalName();
                $policiesFilePath = $request->file('policiesImage')->storeAs('uploads/supplier', $policiesFileName, 'public');
            }
            $companyDetails = CompanyDetails::updateOrCreate(['model_type'=>Supplier::class,'model_id'=>$supplier->id],
                [
                    'model_type'            => Supplier::class,
                    'model_id'              => $supplier->id,
                    'user_id'               => isset($supplier->user->id) ? $supplier->user->id : null,
                    'business_description'  => $request->business_description,
                    'mission'               => $request->mission,
                    'vision'                => $request->vision,
                    'history_growth'        => $request->history_growth,
                    'industry_information'  => $request->industry_information,
                    'policies'              => $request->policies,
                    'policies_image'        => $policiesFilePath,
                    'public_relations'      => $request->public_relations,
                    'advertising'           => $request->advertising,
                ]);

            CompanyDetails::bootSystemActivities();

            return response()->json(['success' => true, 'message' => __('admin.company_basic_detail_updated')],200);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')],500);
        }

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')],403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
