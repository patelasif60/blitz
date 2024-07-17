<?php

namespace App\Http\Controllers\Buyer\RFN;

use App\Models\Category;
use App\Models\Product;
use App\Models\Rfn;
use App\Models\RfnItems;
use App\Models\RfnResponse;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use View;


class RfnController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:buyer rfn publish|buyer global rfn publish', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $authUser = Auth::user();

            $permission['rfnForm'] = $authUser->hasPermissionTo('buyer rfn create');
            $permission['globalRfnForm'] = $authUser->hasPermissionTo('buyer global rfn create');;

            return View::make('buyer.rfn.index')->with(compact(['permission']));

        } catch(\Exception $e) {
            Log::critical('Code - 400 | ErrorCode:B026 - RFN Tab index Request');
             abort('404');
        }
    }

    /**
     * Store Rfn and Rfn Items
     *
     * @param Collection $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRfn(Collection $data)
    {
        try {
            $authUser = Auth::user();
            $isRfnStored = false;

            // Get and Set the Category, Subcategory and Product details
            $category = Category::where('id', $data->category_id)->pluck('name')->first();
            $subCategory = SubCategory::where('id',$data->subcategory_id)->pluck('name')->first();
            $product = Product::where('subcategory_id',$data->subcategory_id)->where('name',$data->product_name)->pluck('id')->first();

            $data->category_name = isset($data->category_name) ? $data->category_name : (!empty($category) ? $category : 'Other');
            $data->subcategory_name = isset($data->subcategory_name) ? $data->subcategory_name : (!empty($subCategory) ? $subCategory : 'Other');
            $data->product_id = isset($data->product_id) ? $data->product_id : (!empty($product) ? $product : '0');

            // Create Rfn and Items
            DB::transaction(function () use($data,$authUser,&$isRfnStored){
                $rfn = Rfn::create([
                    'user_type'     =>  User::class,
                    'user_id'       =>  $authUser->id,
                    'company_id'    =>  $authUser->default_company,
                    'comment'       =>  $data->comment,
                    'type'          =>  $data->type,
                    'expected_date' =>  \Carbon\Carbon::parse($data->expected_date)->format('Y-m-d h:i:s'),
                    'status'        =>  Rfn::PENDING_STATUS
                ]);

                $rfnItems = RfnItems::create([
                    'rfn_id'            =>  $rfn->id,
                    'category_id'       =>  $data->category_id,
                    'category_name'     =>  $data->category_name,
                    'subcategory_id'    =>  $data->subcategory_id,
                    'subcategory_name'  =>  $data->subcategory_name,
                    'product_id'        =>  $data->product_id,
                    'product_name'      =>  $data->product_name,
                    'quantity'          =>  $data->quantity,
                    'item_description'  =>  $data->item_description,
                    'unit_id'           =>  $data->unit_id
                ]);

                $isRfnStored = isset($rfnItems->id) ? true : false;

            });

            if ($isRfnStored) {
                return response()->json(['success' => true, 'message' => __('admin.rfn_created_successfully')]);
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B042 - Buyer RFN Store');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

        Log::critical('Code - 403 | ErrorCode:B043 - Buyer RFN Store');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

    }

    /**
     * Update Rfn and Rfn Items
     *
     * @param Collection $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRfn(Collection $data)
    {
        try {
            $authUser = Auth::user();
            $isRfnUpdated = false;
            // Get and Set the Category, Subcategory and Product details
            $rfn = Rfn::with('rfnItem')->where('id',$data->rfn_id)->first();
            $category = Category::where('id', $data->category_id)->pluck('name')->first();
            $subCategory = SubCategory::where('id',$data->subcategory_id)->pluck('name')->first();
            $product = Product::where('subcategory_id',$data->subcategory_id)->where('name',$data->product_name)->pluck('id')->first();

            $data->category_name = isset($data->category_name) ? $data->category_name : (!empty($category) ? $category : 'Other');
            $data->subcategory_name = isset($data->subcategory_name) ? $data->subcategory_name : (!empty($subCategory) ? $subCategory : 'Other');
            $data->product_id = ($data->product_name == $rfn->rfnItem->product_name) ? $data->product_id : (!empty($product) ? $product : '0');

            // Create Rfn and Items
            DB::transaction(function () use($rfn,$data,$authUser,&$isRfnUpdated){

                $rfn->user_type     =   User::class;
                $rfn->user_id       =   $authUser->id;
                $rfn->company_id    =   $authUser->default_company;
                $rfn->type          =   $data->type;
                $rfn->comment       =   $data->comment;
                $rfn->expected_date =  \Carbon\Carbon::parse($data->expected_date)->format('Y-m-d h:i:s');
                $rfn->status        =  $rfn->status;
                $rfn->save();

                $rfn->rfnItem->category_id = $data->category_id;
                $rfn->rfnItem->category_name = $data->category_name;
                $rfn->rfnItem->subcategory_id = $data->subcategory_id;
                $rfn->rfnItem->subcategory_name = $data->subcategory_name;
                $rfn->rfnItem->product_id = $data->product_id;
                $rfn->rfnItem->product_name = $data->product_name;
                $rfn->rfnItem->quantity = $data->quantity;
                $rfn->rfnItem->item_description = $data->item_description;
                $rfn->rfnItem->unit_id = $data->unit_id;
                $isRfnUpdated = $rfn->rfnItem->save();


            });

            if ($isRfnUpdated) {
                return response()->json(['success' => true, 'message' => __('admin.rfn_updated_successfully')]);
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B046 - Buyer RFN Update');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

        Log::critical('Code - 403 | ErrorCode:B047 - Buyer RFN Update');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

    }

    /**
     * Generate single Rfn
     *
     * @param $rfnId
     * @return \Illuminate\Http\JsonResponse
     */
    public function singleRfnToRfq($rfnId)
    {
        try {
            $rfn = Rfn::with(['rfnItem'])->where('id',$rfnId)->first();

            $rfnData = collect();

            $rfnData->rfn_id = $rfn->id;
            $rfnData->rfn_response_id = '';
            $rfnData->item_id = $rfn->rfnItem->product_id;
            $rfnData->item_category_id = $rfn->rfnItem->category_id;
            $rfnData->item_category_name = $rfn->rfnItem->category_name;
            $rfnData->item_subCategory_id = $rfn->rfnItem->subcategory_id;
            $rfnData->item_subCategory_name = $rfn->rfnItem->subcategory_name;
            $rfnData->item_name = $rfn->rfnItem->product_name;
            $rfnData->quantity = $rfn->rfnItem->quantity;
            $rfnData->unit_id = $rfn->rfnItem->unit_id;
            $rfnData->item_description = $rfn->rfnItem->item_description;

            // Set session for RFN to RFQ
            session(['isRfn' => true, 'rfn' => $rfnData]);

            return response()->json(['success' => true, 'message' => __('admin.data_fetched')]);

        } catch (\Exception $exception) {
            session()->forget(['isRfn','rfn']);
            Log::critical('Code - 500 | ErrorCode:B052 - Buyer Generate Single RFN');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Update the converted RFN
     *
     * @param Collection $data
     */
    public function updateRfnConversion(Collection $data)
    {
        try {
            $rfn = Rfn::findOrFail($data->rfnId);
            if ($rfn->type == 2){
               $response = RfnResponse::whereIn('id',$data->rfnResponseId)->update(['rfq_id'=>$data->rfqId ,'status'=>Rfn::APPROVED_STATUS ]);
                $rfn->rfq_id = $data->rfqId;
                $rfn->status = Rfn::APPROVED_STATUS;
            }else{
                $rfn->rfq_id = $data->rfqId;
                $rfn->status = Rfn::APPROVED_STATUS;
            }
            $rfn->save();
            session()->forget(['isRfn','rfn']);

            return response()->json(['success' => true, 'message' => __('admin.data_updated')]);

        } catch (\Exception $exception) {
            session()->forget(['isRfn','rfn']);
            Log::critical('Code - 500 | ErrorCode:B053 - Buyer Generate Single RFN');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Join RFN request
     * @param Collection $data
     */
    public function joinRfnRequest(Collection $data)
    {
        try {
            $authUser = Auth::user();
            $isrfnJoin = false;
            // Get and Set the Category, Subcategory and Product details
            $rfnData = Rfn::with('rfnItem','rfnItem.product','rfnItem.unit')->where('id', $data->rfn_id)->orderBy('rfns.id','DESC')->first();
            $data->category_id = !empty($rfnData->rfnItem->category_id) ? $rfnData->rfnItem->category_id : 0;
            $data->category_name = isset($rfnData->rfnItem->category_name) ? $rfnData->rfnItem->category_name : 'Other';
            $data->subcategory_id = !empty($rfnData->rfnItem->subcategory_id) ? $rfnData->rfnItem->subcategory_id : '0';
            $data->subcategory_name = isset($rfnData->rfnItem->subcategory_name) ? $rfnData->rfnItem->subcategory_name : 'Other';
            $data->product_id = isset($rfnData->rfnItem->product_id) ? $rfnData->rfnItem->product_id : '0';
            $data->product_name = isset($rfnData->rfnItem->product_name) ? $rfnData->rfnItem->product_name : '';
            $data->unit_id = !empty($rfnData->rfnItem->unit) ? $rfnData->rfnItem->unit->id : '0';
            DB::transaction(function () use($data,$authUser,$rfnData,&$isrfnJoin){

                $rfnResponse = RfnResponse::create([
                    'rfn_id'      =>  $data->rfn_id,
                    'user_type'   =>  User::class,
                    'user_id'     =>  $authUser->id,
                    'company_id'  =>  $authUser->default_company,
                    'expected_date' => \Carbon\Carbon::parse($data->expected_date)->format('Y-m-d h:i:s')
                ]);

                $rfnItems = RfnItems::create([
                    'rfn_id'            =>  $data->rfn_id,
                    'rfn_response_id'   =>  $rfnResponse->id,
                    'category_id'       =>  $data->category_id,
                    'category_name'     =>  $data->category_name,
                    'subcategory_id'    =>  $data->subcategory_id,
                    'subcategory_name'  =>  $data->subcategory_name,
                    'product_id'        =>  $data->product_id,
                    'product_name'      =>  $data->product_name,
                    'quantity'          =>  $data->quantity,
                    'item_description'  =>  $data->product_description,
                    'unit_id'           =>  $data->unit_id

                ]);
                $isrfnJoin = isset($rfnItems->id) ? true : false;
            });
            if ($isrfnJoin) {
                return response()->json(['success' => true, 'message' => __('admin.global_rfn_join_successfully')]);
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B054 - Buyer Global RFN Join Request');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }
    }

    /**
     * Edit Join RFN
     * @param Collection $data
     */
    public function editjoinRfnRequest(Collection $data)
    {
        try {
            $isrfnJoin = false;
            // Get and Set the Category, Subcategory and Product details
            $rfnItmData = RfnItems::with('unit')->where('id', $data->itemId)->orderBy('id','DESC')->first();
            $rfnResponse = RfnResponse::where('id',$data->joinRfnResponse_id)->first();

            DB::transaction(function () use($data,$rfnItmData,$rfnResponse,&$isrfnJoin){
                $rfnItmData->quantity =$data->quantity;
                $rfnItmData->item_description = $data->product_description;
                $rfnItmData->save();

                $rfnResponse->expected_date = \Carbon\Carbon::parse($data->expected_date)->format('Y-m-d h:i:s');
                $respone = $rfnResponse->save();
                $isrfnJoin = isset($respone) ? true : false;
            });
            if ($isrfnJoin) {
                return response()->json(['success' => true, 'message' => __('admin.global_rfn_join_edit_successfully')]);
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B055 - Buyer Global RFN Join Edit Request');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }
    }

    public function getAllRfnList(){
        try {
             $authUser = Auth::user();

            $AllrfnList = RfnItems::getAllRfnList();
            $rfnList= $AllrfnList->whereHas('rfn', function($query) use($authUser){
                    /*********begin:Buyer RFN set permissions based on custom role.**************/
                    if ($authUser->hasPermissionTo('buyer rfn list-all')) {
                        $query->where('company_id', $authUser->default_company);
                    }else {
                        $query->where('user_id', $authUser->id)->where('company_id', $authUser->default_company);
                    }
                    /*********end: Buyer RFN set permissions based on custom role.**************/
                     $query->where('type',1);
                });
             $rfnList=  $rfnList->orderBy('id','DESC')->get()->groupBy(['product_id','unit_id']);
             return $rfnList;
             return response()->json(['success' => true,'rfnList' =>$rfnList , 'message' => __('admin.something_went_wrong')]);

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B055 - Buyer RFN List');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }
    }
}
