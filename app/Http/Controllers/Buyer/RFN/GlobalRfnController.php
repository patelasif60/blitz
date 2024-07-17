<?php

namespace App\Http\Controllers\Buyer\RFN;

use App\Models\Category;
use App\Models\Product;
use App\Models\Rfn;
use App\Models\RfnItems;
use App\Models\RfnResponse;
use App\Models\SubCategory;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use View;

class GlobalRfnController extends Controller
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

            return View::make('buyer.rfn.global-rfn-index')->with(compact(['permission']));

        } catch(\Exception $e) {
            Log::critical('Code - 400 | ErrorCode:B055 - Global RFN Tab index Request');
            abort('404');
        }
    }

    /**
     * Update Global Rfn and Rfn Items
     *
     * @param Collection $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGlobalRfn(Collection $data)
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
                $rfn->start_date    =   \Carbon\Carbon::parse($data->start_date)->format('Y-m-d h:i:s');
                $rfn->end_date      =   \Carbon\Carbon::parse($data->end_date)->format('Y-m-d h:i:s');
                $rfn->status        =   Rfn::PENDING_STATUS;
                $rfn->comment       =   $data->comment;
                $rfn->save();

                $rfn->rfnItem->category_id = $data->category_id;
                $rfn->rfnItem->category_name = $data->category_name;
                $rfn->rfnItem->subcategory_id = $data->subcategory_id;
                $rfn->rfnItem->subcategory_name = $data->subcategory_name;
                $rfn->rfnItem->product_id = $data->product_id;
                $rfn->rfnItem->product_name = $data->product_name;
                $rfn->rfnItem->item_description = $data->item_description;
                $rfn->rfnItem->unit_id = $data->unit_id;
                $isRfnUpdated = $rfn->rfnItem->save();

            });

            if ($isRfnUpdated) {
                return response()->json(['success' => true, 'message' => __('admin.global_rfn_updated_successfully')]);
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B048 - Buyer Global RFN Update');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

        Log::critical('Code - 403 | ErrorCode:B049 - Buyer Global RFN Update');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

    }

    /**
     * Store Global Rfn and Rfn Items
     *
     * @param Collection $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeGlobalRfn(Collection $data)
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
                    'type'          =>  $data->type,
                    'start_date'    =>  \Carbon\Carbon::parse($data->start_date)->format('Y-m-d h:i:s'),
                    'end_date'      =>  \Carbon\Carbon::parse($data->end_date)->format('Y-m-d h:i:s'),
                    'status'        =>  Rfn::PENDING_STATUS,
                    'comment'       =>  $data->comment
                ]);

                $rfnItems = RfnItems::create([
                    'rfn_id'            =>  $rfn->id,
                    'category_id'       =>  $data->category_id,
                    'category_name'     =>  $data->category_name,
                    'subcategory_id'    =>  $data->subcategory_id,
                    'subcategory_name'  =>  $data->subcategory_name,
                    'product_id'        =>  $data->product_id,
                    'product_name'      =>  $data->product_name,
                    'item_description'  =>  $data->item_description,
                    'unit_id'           =>  $data->unit_id


                ]);

                $isRfnStored = isset($rfnItems->id) ? true : false;

            });

            if ($isRfnStored) {
                return response()->json(['success' => true, 'message' => __('admin.global_rfn_created_successfully')]);
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B044 - Buyer Global RFN Store');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

        Log::critical('Code - 403 | ErrorCode:B045 - Buyer Global RFN Store');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createGlobalRfn($data)
    {
        try {
            if (!empty($data)) {
                $authUser = Auth::user();
                $isGlobalRfn = false;
                $rfnId = $data->rfn_id;
                $rfnData = Rfn::where('id', $rfnId)->first();
                $rfn_userId = $rfnData->user_id;
                $rfn_expectedDate = $rfnData->expected_date;
                DB::transaction(function () use($rfnId,$authUser,$rfn_userId,$rfn_expectedDate,$data,&$isGlobalRfn){
                    Rfn::where('id', $rfnId)->update([
                        'type'              =>  2,
                        'is_converted'       =>  1,
                        'start_date'    =>  \Carbon\Carbon::parse($data->startDate)->format('Y-m-d h:i:s'),
                        'end_date'     =>  \Carbon\Carbon::parse($data->endDate)->format('Y-m-d h:i:s')
                    ]);

                    $rfnResponse = RfnResponse::create([
                        'rfn_id'      =>  $rfnId,
                        'user_type'   =>  User::class,
                        'user_id'     =>  $rfn_userId,
                        'company_id'  =>  $authUser->default_company,
                        'expected_date'     =>  \Carbon\Carbon::parse($rfn_expectedDate)->format('Y-m-d h:i:s')
                    ]);

                    RfnItems::where('rfn_id', $rfnId)->update([
                        'rfn_response_id'  =>  $rfnResponse->id,
                    ]);

                    $isGlobalRfn = isset($rfnResponse->id) ? true : false;
                });
                if ($isGlobalRfn){
                    return response()->json(['success' => true, 'message' => __('buyer.global_rfn_created_successfully')]);
                }

            }

        }catch (\Exception $exception){
            Log::critical('Code - 500 | ErrorCode:B044- Buyer Create Global RFN');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }
        Log::critical('Code - 403 | ErrorCode:B045 - Buyer Create Global RFN');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
    }

    /**
     * Generate single Rfn
     *
     * @param $rfnId
     * @return \Illuminate\Http\JsonResponse
     */
    public function globalRfnToRfq($rfnId,$rfnResponseId)
    {
        try {
            $rfn = Rfn::with(['rfnItems'=>function($q) use ($rfnResponseId){
                    $q->whereIn('rfn_response_id',$rfnResponseId);
                }])->where('id',$rfnId)->first();
            $rfnData = collect();
            $rfnData->rfn_id = $rfnId;
            $rfnData->rfn_response_id = $rfnResponseId;
            $rfnData->item_id = $rfn->first()->rfnItems->first()->product_id;
            $rfnData->item_category_id = $rfn->rfnItems->first()->category_id;
            $rfnData->item_category_name = $rfn->rfnItems->first()->category_name;
            $rfnData->item_subCategory_id = $rfn->rfnItems->first()->subcategory_id;
            $rfnData->item_subCategory_name = $rfn->rfnItems->first()->subcategory_name;
            $rfnData->item_name = $rfn->rfnItems->first()->product_name;
            $rfnData->quantity = $rfn->rfnItems->sum('quantity');
            $rfnData->unit_id = $rfn->rfnItems->first()->unit_id;
            $rfnData->item_description = $rfn->rfnItems->first()->item_description;
            // Set session for RFN to RFQ
            session(['isRfn' => true, 'rfn' => $rfnData]);

            return response()->json(['success' => true, 'message' => __('admin.data_fetched')]);

        } catch (\Exception $exception) {
            session()->forget(['isRfn','rfn']);
            Log::critical('Code - 500 | ErrorCode:B052 - Buyer Generate RFQ from Global RFN');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

    }

    /**
     * Cancel Single  Rfn
     *
     * @param $rfnId
     * @return \Illuminate\Http\JsonResponse
     */

    public function cancelGlobalRfn($rfnId)
    {
        try {
            if (!empty($rfnId)) {
                $rfnCancel = Rfn::where('id', $rfnId)->update([
                        'status' =>  3
                    ]);
                $rfnResponseCancel = RfnResponse::where('rfn_id', $rfnId)->update([
                        'status' =>  3
                    ]);
                if ($rfnCancel){
                    return response()->json(['success' => true, 'message' => __('admin.rfn_cancel')]);
                }
            }
        }catch (\Exception $exception){
            Log::critical('Code - 500 | ErrorCode:B044- Buyer Cancel Global RFN');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }
        Log::critical('Code - 403 | ErrorCode:B045 - Buyer Cancel Global RFN');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
    }

    /**
     * Delete Rfn Request
     *
     * @param $rfnId
     * @param $rfnReaponseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRfnRequest($rfnId,$rfnReaponseId)
    {
        try {
            if (!empty($rfnReaponseId)) {
                $rfnData = RfnResponse::where('rfn_id', $rfnId)->whereNull('deleted_at')->get();

                $rfnResponsedelete = RfnResponse::where('id', $rfnReaponseId)->delete();
                $rfnItemdelete = RfnItems::where('rfn_response_id', $rfnReaponseId)->delete();
                if (count($rfnData)==1){
                    $rfndelete = Rfn::where('id', $rfnId)->delete();
                }
                if ($rfnResponsedelete){
                    return response()->json(['success' => true, 'message' => __('buyer.rfn_request_delete')]);
                }
            }
        }catch (\Exception $exception){
            Log::critical('Code - 500 | ErrorCode:B044- Buyer Delete Global RFN Request');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }
        Log::critical('Code - 403 | ErrorCode:B045 - Buyer Delete Global RFN Request');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
    }

    /**
     * Global RFN listing
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function getAllGlobalRfnList()
    {
        try {
            $authUser = Auth::user();
            $rfnList = Rfn::with('rfnItem','userable','rfnItem.product','rfnItem.unit','rfnResponses.defaultCompanyUser','rfnResponses.rfnItem','rfnResponses.rfnItem.unit','strictResponses')
                ->with(['rfnResponses'=>function($query) use ($authUser){
                    if ($authUser->hasPermissionTo('buyer List All RFR Request')) {
                        $query->where('company_id', $authUser->default_company);
                    }else {
                        $query->where('user_id', $authUser->id)->where('company_id', $authUser->default_company);
                    }
                }]);
            if ($authUser->hasPermissionTo('buyer global rfn list-all')) {
                $rfnList = $rfnList->where('company_id', $authUser->default_company);
            }else {
                $rfnList = $rfnList->where('user_id', $authUser->id)->where('company_id', $authUser->default_company);
            }
            /*********end: Buyer RFN set permissions based on custom role.**************/
            $rfnList =  $rfnList->where('type', 2)->orderBy('rfns.id','DESC')->whereNull('deleted_at')->get();
            return $rfnList;
        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B045 - All Buyer Global RFN List ');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }
    }
}
