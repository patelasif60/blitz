<?php

namespace App\Http\Controllers;

use App\Events\BuyerNotificationEvent;
use App\Events\BuyerRfqNotificationEvent;
use App\Events\quotesCountEvent;
use App\Events\rfqsEvent;
use App\Jobs\SendQuoteToBuyerEmailJob;
use App\Jobs\SendQuoteToSupplierEmailJob;
use App\Models\Groups;
use App\Models\GroupSupplier;
use App\Models\Notification;
use App\Models\CountryOne;
use App\Jobs\SetExpireOnInvoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\QuoteActivity;
use App\Models\QuoteItem;
use App\Models\RfqActivity;
use App\Models\RfqProduct;
use App\Models\Role;
use App\Models\State;
use App\Models\SupplierProduct;
use App\Models\User;
use App\Models\UserRfq;
use App\Models\UserSupplier;
use App\Models\AdminFeedbackReasons;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\QuoteChargeWithAmount;
use App\Models\OtherCharge;
use App\Models\Rfq;
use App\Models\SupplierAddress;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\LogisticsService;
use App\Models\Product;
use App\Models\QuoteStatus;
use App\Models\SystemActivity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivity;
use App\Models\UserAddresse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mail;
//use App\Mail\SendQuoteToBuyer;
use App\Models\UserQuoteFeedback;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Twilloverify\TwilloService;


class QuoteController extends Controller
{
    protected $verify;

    public function __construct()
    {
        $this->middleware('permission:create quotes|edit quotes|delete quotes|publish quotes|unpublish quotes', ['only'=> ['list']]);
        $this->middleware('permission:create quotes', ['only' => ['create']]);
        $this->middleware('permission:edit quotes', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete quotes', ['only' => ['delete']]);
        $this->verify = app('App\Twilloverify\TwilloService');
    }


    function list1(Request $request)
    {
        $condition = [];
        if($request->ajax()){
            if($request->rfq_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.id', 'value' => $request->rfq_ids]);
            }
            if($request->quote_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.quote_number', 'value' => $request->quote_ids]);
            }
            if($request->supp_company_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.supplier_id', 'value' => $request->supp_company_ids]);
            }
            /* if($request->product_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.product_id', 'value' => $request->product_ids]);
            } */
            /* if($request->cust_company_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'companies.id', 'value' => $request->cust_company_ids]);
            } */
            /* if($request->category_names){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfq_products.category', 'value' => $request->category_names]);
            } */
            if($request->category_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfq_products.category_id', 'value' => $request->category_ids]);
            }
            if($request->payment){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.is_require_credit', 'value' => $request->payment]);
            }
            if($request->status_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.status_id', 'value' => $request->status_ids]);
            }

            if($request->start_date && $request->end_date){
                $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d 00:00:00');
                $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d 23:59:59');
                array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'quotes.created_at', 'value' => [$start_date,  $end_date] ]);
            }
        }

        $select_columns = [];
        if (auth()->user()->role_id == 3){
            $select_columns = ['quotes.*', 'rfqs.is_require_credit', 'rfqs.id as rfqId', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.reference_number as rfq_number', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.name as status_name'];
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $quotes = Quote::join('rfqs', function ($join) {
                $join->on('rfqs.id', '=', 'quotes.rfq_id');
            })->join('rfq_products', function ($join) {
                $join->on('rfqs.id', '=', 'rfq_products.rfq_id');
            })->join('rfq_status', function ($join) {
                $join->on('rfqs.status_id', '=', 'rfq_status.id');
            })->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
                ->where('quote_items.supplier_id', $supplier_id);

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $quotes = $quotes->whereIn($value['column_name'], $value['value']);
                    }
                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $quotes = $quotes->whereBetween($value['column_name'], $value['value']);
                    }
                }
            }
            // ->orderBy('rfqs.id', 'desc')->groupBy('quotes.id')->get($select_columns);
            $whereNotification = ['admin_id' => 0, 'supplier_id' => $supplier_id, 'user_activity' => 'Create Quote', 'notification_type' => 'quote', 'side_count_show' => 0];
            Notification::where($whereNotification)->update(['side_count_show' => 1]);
        } else {
            $select_columns = ['quotes.*', 'rfqs.id as rfqId', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.reference_number as rfq_number', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.name as status_name'];
            $quotes = Quote::join('rfqs', function ($join) {
                $join->on('rfqs.id', '=', 'quotes.rfq_id');
            })->join('rfq_products', function ($join) {
                $join->on('rfqs.id', '=', 'rfq_products.rfq_id');
            })->join('rfq_status', function ($join) {
                $join->on('rfqs.status_id', '=', 'rfq_status.id');
            });

            //Agent category permission
            if (Auth::user()->hasRole('agent')) {

                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                $quotes = $quotes->whereIn('rfq_products.category_id', $assignedCategory);

            }

            //JNE custom permission
            if (Auth::user()->hasRole('jne')) {
                $quotes = $quotes->where('quotes.status_id',Quote::PARTIAL_QUOTE)
                    ->orwhereIn('quotes.id', $this->getJneActivityQuote());
            }

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $quotes = $quotes->whereIn($value['column_name'], $value['value']);
                    }
                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $quotes = $quotes->whereBetween($value['column_name'], $value['value']);
                    }
                }
            }
            // ->orderBy('rfqs.id', 'desc')->groupBy('quotes.id')->get($select_columns);
            $whereNotification = ['admin_id' => Auth::user()->id, 'supplier_id' => 0, 'user_activity' => 'Create Quote', 'notification_type' => 'quote', 'side_count_show' => 0];
            Notification::where($whereNotification)->update(['side_count_show' => 1]);
        }

        /* dynamic filter field values */
        $rfq_numbers_obj = clone $quotes;
        $rfq_numbers = $rfq_numbers_obj->groupBy('rfqs.id')->get(['rfqs.id','rfqs.reference_number']);

        $quotes_numbers_obj = clone $quotes;
        $quotes_numbers = $quotes_numbers_obj->groupBy('quotes.id')->get(['quotes.id','quotes.quote_number']);

        $status_obj = clone $quotes;
        $status_ids = $status_obj->groupBy('quotes.status_id')->pluck('quotes.status_id')->toArray();
        $status = [];
        if(sizeof($status_ids) > 0){
            $status = QuoteStatus::whereIn('id', $status_ids)->get(['id','name', 'backofflice_name']);
        }

        $categories_obj = clone $quotes;
        $categories = $categories_obj->orderBy('rfq_products.category','asc')->groupBy('rfq_products.category_id')->get(['rfq_products.category_id as id','rfq_products.category as name']);

        $supplier_companies_obj = clone $quotes;
        $supplier_companies = $supplier_companies_obj
            ->join('suppliers','quotes.supplier_id','=','suppliers.id')
            ->orderBy('suppliers.name')
            ->groupBy('quotes.supplier_id')->get(['quotes.supplier_id as id','suppliers.name']);

        $products = [];
        /* dynamic filter field values */

        $quotes = $quotes->orderBy('rfqs.id', 'desc')->groupBy('quotes.id')->get($select_columns);

        foreach ($quotes as $quote){
            $quoterItems = DB::table('quote_items')->where('quote_id',$quote->id)->get();
            $quote->product = $quoterItems->count().' '.__('admin.products');
            if ($quoterItems->count()==1){
                $quote->product = get_product_name_by_id($quoterItems[0]->rfq_product_id,1);
            }
        }

        $quoteDataHtml = view('admin/quote/quoteTableData', ['quotes' => $quotes])->render();
        if($request->ajax()){
            return $quoteDataHtml;
        }
        /**begin: system log**/
        Quote::bootSystemView(new Quote());
        /**end:  system log**/
        $getFeedbackReasions = AdminFeedbackReasons::where('reasons_type', 2)->get();
        return view('admin/quote/list', ['quoteDataHtml'=> $quoteDataHtml,'quotes' => $quotes,'quotes_numbers' => $quotes_numbers, 'rfq_numbers' => $rfq_numbers, 'status' => $status, 'supplier_companies' => $supplier_companies, 'categories' => $categories, 'products' => $products, 'feedbackReasions' => $getFeedbackReasions]);
    }
    function list(Request $request)
    {
        $supplier_id=0;
        $admin_id=0;
        if (auth()->user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $quotes = Quote::with(['order','rfq','rfq.rfqProduct','product','supplier','rfqProduct','quoteStatus','rfq.companyDetails','quoteItems','quoteItems.rfqProduct'])->where('quotes.supplier_id', $supplier_id);
        }
        else
        {
            $admin_id = Auth::user()->id;
            $quotes = Quote::with(['order','rfq','rfq.rfqProduct','product','supplier','rfqProduct','quoteStatus','rfq.companyDetails','quoteItems','quoteItems.rfqProduct']);
            //Agent category permission
            if (Auth::user()->hasRole('agent')) {
                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
                $quotes->whereHas('rfqProduct', function($quotes) use($assignedCategory){
                    $quotes->whereIn('category_id', $assignedCategory);
                });
            }
            //JNE custom permission
            if (Auth::user()->hasRole('jne')) {
                $quotes = $quotes->where('quotes.status_id',Quote::PARTIAL_QUOTE)->orwhereIn('quotes.id', $this->getJneActivityQuote());
            }
        }
        $whereNotification = ['admin_id' => $admin_id, 'supplier_id' => $supplier_id, 'user_activity' => 'Create Quote', 'notification_type' => 'quote', 'side_count_show' => 0];
        Notification::where($whereNotification)->update(['side_count_show' => 1]);
        if($request->ajax()){

            $sort               = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
            $columnIndex_arr    = $request->get('order');
            $columnName_arr     = $request->get('columns');
            $columnIndex        = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
            $column             = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';
            $search  = !empty($request->get('search')) ? $request->get('search')['value'] : '';
            if ($search) {
                $quotes->Where(function($q) use($search){
                    $q->orWhereHas('rfq', function($quotes) use($search){
                        $quotes->where('reference_number', 'LIKE',"%$search%")
                            ->orWhere('firstname' , 'LIKE', "%$search%")
                            ->orWhere('lastname' , 'LIKE', "%$search%");
                    });
                    $q->orWhereHas('rfqProduct', function($quotes) use($search){
                        $quotes->where('category', 'LIKE',"%$search%")
                            ->orWhere('sub_category', 'LIKE',"%$search%")
                            ->orWhere('product', 'LIKE',"%$search%");
                    });
                    $q->orWhereHas('quoteStatus', function($query) use($search){
                        $query->where('backofflice_name', 'LIKE',"%$search%");
                    });
                    $q->orWhereHas('rfq.companyDetails', function($query) use($search){
                        $query->where('name', 'LIKE',"%$search%");
                    });
                    $q->orWhere('quotes.created_at' , 'LIKE', "%$search%")
                        ->orWhere('quote_number' , 'LIKE', "%$search%");
                });
            }

            if ($request->filterData) {
                $filterData = $request->filterData;
                //Qoute Number
                if (Arr::exists($filterData, 'quote_ids')) {
                    $quotes->whereIn('quote_number', $filterData['quote_ids']);
                }
                if (Arr::exists($filterData, 'supp_company_ids')) {
                    $quotes->whereHas('supplier', function($quotes) use($filterData){
                        $quotes->whereIn('suppliers.id',$filterData['supp_company_ids']);
                    });
                }
                //Refrence Number
                if (Arr::exists($filterData, 'rfq_ids')) {
                    $quotes->whereIn('rfq_id', $filterData['rfq_ids']);
                }
                //Created Date
                if (Arr::exists($filterData, 'start_date') && Arr::exists($filterData, 'end_date')) {
                    $start_date = Carbon::createFromFormat('d-m-Y', $filterData['start_date'])->format('Y-m-d 00:00:00');
                    $end_date = Carbon::createFromFormat('d-m-Y', $filterData['end_date'])->format('Y-m-d 23:59:59');
                    $quotes->whereBetween('created_at', [$start_date, $end_date]);
                }
                //Category
                if (Arr::exists($filterData, 'category_names')) {
                    $quotes->whereHas('rfqProduct', function($quotes) use($filterData){
                        $quotes->whereIn('category',$filterData['category_names']);
                    });
                }
                if (Arr::exists($filterData,'payment')) {
                    $quotes->whereHas('rfq', function($quotes) use($filterData){
                        $quotes->whereIn('is_require_credit',$filterData['payment']);
                    });
                }
                //Status
                if (Arr::exists($filterData,'status_ids')) {
                    $quotes->whereHas('quoteStatus', function($quotes) use($filterData){
                        $quotes->whereIn('id',$filterData['status_ids']);
                    });
                }
                //Buyer company name
                if (Arr::exists($request->filterData, 'company_ids')) {
                    $quotes->whereHas('rfq.companyDetails', function($quotes) use($request){
                        $quotes->whereIn('id',$request->filterData['company_ids']);
                    });
                }

            }

            if ($column == 'id') {
                $quotes = $quotes->orderBy('created_at', 'desc');
            }else{
                //$quotes = $quotes->orderBy($column, $sort);
                if($column == 'quote_number'){
                    $quotes->orderBy('quote_number', $sort);
                }
                if($column == 'validTill'){
                    $quotes->orderBy('valid_till', $sort);
                }
                if($column == 'rfq_number'){
                    $quotes->orderBy('rfq_id', $sort);
                }
                if($column == 'orderNumber'){
                    $quotes->orderBy('id', $sort);
                }
                if($column == 'name'){
                    $quotes->orderBy(Rfq::select('rfqs.firstname')->whereColumn('quotes.rfq_id', 'rfqs.id')->limit(1),$sort);
                }
                if($column == 'category'){
                    $quotes->orderBy(RfqProduct::select('category')->whereColumn('quotes.rfq_id','rfq_products.rfq_id')->limit(1),$sort);
                }
                if($column == 'product_name'){
                    $quotes->orderBy(QuoteItem::join('products', 'quote_items.product_id', '=', 'products.id')->select('products.name')->whereColumn('quotes.id','quote_items.quote_id')->limit(1),$sort);
                }
                if($column == 'statusId'){
                    $quotes->orderBy(QuoteStatus::select('quote_status.backofflice_name')->whereColumn('quotes.status_id','quote_status.id')->limit(1),$sort);
                }
                if($column == 'createDate'){
                    $quotes->orderBy('created_at',$sort);
                }
                if($column == 'buyer_company_name'){
                    $quotes->orderBy(Rfq::join('companies', 'rfqs.company_id', '=', 'companies.id')->select('companies.name')->whereColumn('quotes.rfq_id','rfqs.id')->limit(1),$sort);
                }
            }
            $qt = clone $quotes;
            $quotes = $quotes->groupBy('quotes.id');
            $start  = $request->get("start");
            $length = $request->get("length");
            $totalDisplayRecords = $qt->count();
            if($length > 1)
            {
               $quotes  = $quotes->skip($start)->take($length)->get();
            }
            else{
               $quotes  = $quotes->get();
            }
            foreach ($quotes as $quote){
                $quoteOrder = !empty($quote->order) ? $quote->order : '';
                $quote_number = $quote->quote_number;
                $quote->quote_number = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="vieQuoteDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModalnew" data-id="'.$quote->id.'"}}">'.$quote->quote_number.'</a>';
                $quote->rfq_number = isset($quote->rfq)?'<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewRfqDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="'.$quote->rfq_id.'"}}">'.$quote->rfq->reference_number.'</a>':'';
                $quote->name = isset($quote->rfq) ? $quote->rfq->firstname . ' ' . $quote->rfq->lastname : '';
                $quote->buyer_company_name = isset($quote->rfq) ? ( ($quote->rfq->companyDetails) ? $quote->rfq->companyDetails->name : '' ) : '' ;
                $quote->category =  isset($quote->rfq) ? $quote->rfq->rfqProduct->category : '';
                $quote->createDate = Carbon::parse($quote->created_at)->format('d-m-Y H:i:s');
                $quote->validTill = date('d-m-Y', strtotime($quote->valid_till));
                if(isset($quote->status_id)){
                    $quote->statusId = auth()->user()->role_id == 1 && $quote->status_id == 5 ? __('rfqs.partial_quotation_received') : __('rfqs.'.$quote->quoteStatus->backofflice_name);
                }else{
                    $quote->statusId = '';
                }
                $quote->product_name = $quote->quoteItems->count() == 1 ? (isset($quote->quoteItems->first()->rfqProduct->product_name_description) ? $quote->quoteItems->first()->rfqProduct->product_name_description : '-') : $quote->quoteItems->count().' '.__('admin.products');
                $action = '';
                if((($quote->status_id == 1 || $quote->status_id == 3 || $quote->status_id == 5) && (auth()->user()->role_id == 1 || Auth::user()->hasRole('agent') || Auth::user()->hasRole('jne'))) || ($quote->status_id == 5 && auth()->user()->role_id == 3 ))
                {
                    if(Auth::user()->can('edit quotes')){
                        $action .='<a href="'.route('quotes-edit', ['id' => Crypt::encrypt($quote->id)]).'" class=" show-icon"  data-toggle="tooltip" ata-placement="top" title="'.__('admin.edit').'"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                    }
                }
                elseif($quote->status_id == 2 && auth()->user()->role_id == 1 && $quote->group_id == null)
                {

                    if(Auth::user()->can('edit quotes')){
                        if(isset($quoteOrder->payment_status) && !empty($quoteOrder) && $quoteOrder->payment_status==0){
                            $action .= '<a href="'.route('quotes-edit', ['id' => Crypt::encrypt($quote->id)]).'"  class=" show-icon"  data-toggle="tooltip" ata-placement="top" title="'.__('admin.edit').'"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                        }
                    }
                }
                if(Auth::user()->can('publish quotes')){
                    $action .= '<a class="ms-2 cursor-pointer vieQuoteDetail" data-id="'.$quote->id .'" data-toggle="tooltip" ata-placement="top" title="'.__('admin.view').'"><i class="fa fa-eye"></i></a>';
                }
                if(!Auth::user()->hasRole('jne') && !Auth::user()->hasRole('agent')){
                    if(!in_array($quote->status_id, [3,4,5])){
                        $action .= "<a class=\"ms-2 cursor-pointer\" onclick=\"chat.adminShowRfqChat('$quote->id','Quote','$quote_number')\"  style=\"color: cornflowerblue;\" data-id=\"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Chat\"><i class=\"fa fa-comments\"></i></a>";
                    }
                }
                if(Auth::user()->role_id == ADMIN)
                {
                    $action .= '<a class=" cursor-pointer viewFeedback" style="color: cornflowerblue;" data-id="'.$quote->id.'" data-toggle="tooltip" data-placement="top" title="'.__('admin.feedback').'" data-bs-original-title="'. __('admin.feedback').'" aria-label=""><i class="fa fa-commenting ms-2 text-info"></i></a>';
                }
                $quote->actions = $action;
            }

            return response()->json([
                "draw" => intval($request->get('draw')),
                "iTotalRecords" => $totalDisplayRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $quotes
            ]);
        }else{
            $qt = $quotes->get();
            $rfq_numbers = array_unique($qt->pluck('rfq.reference_number','rfq.id')->toArray());
            $quotes_numbers = array_unique($qt->pluck('quote_number','id')->toArray());
            $status_ids = array_unique($qt->pluck('status_id')->toArray());
            $companies = $qt->unique('rfq.companyDetails.id');
            $status = [];
            if(sizeof($status_ids) > 0){
                $status = QuoteStatus::whereIn('id', $status_ids)->get(['id','name', 'backofflice_name']);
            }
            $categories = RfqProduct::whereIn('rfq_id',$qt->pluck('rfq.id')->toArray())->orderBy('category','asc')->groupBy('category_id')->get(['rfq_products.category_id as id','rfq_products.category as name']);
            $supplier_companies = Supplier::whereIn('id',$qt->pluck('supplier_id')->toArray())->orderBy('name')->groupBy('id')->get(['id','name']);
            $getFeedbackReasions = AdminFeedbackReasons::where('reasons_type', 2)->get();
        }
        /**begin: system log**/
        Quote::bootSystemView(new Quote());
        /**end:  system log**/

        return view('admin/quote/list', ['quotes_numbers' => $quotes_numbers, 'rfq_numbers' => $rfq_numbers, 'status' => $status, 'supplier_companies' => $supplier_companies, 'categories' => $categories,'feedbackReasions' => $getFeedbackReasions,'companies' =>$companies]);
    }
    function listAjax(Request $request)
    {
        $condition = [];
        if($request->rfq_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.rfq_id', 'value' => $request->rfq_ids]);
        }
        if($request->quote_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.quote_number', 'value' => $request->quote_ids]);
        }
        if($request->supp_company_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.supplier_id', 'value' => $request->supp_company_ids]);
        }
        if($request->product_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.product_id', 'value' => $request->product_ids]);
        }
        /* if($request->cust_company_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'companies.id', 'value' => $request->cust_company_ids]);
        } */
        if($request->category_names){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfq_products.category', 'value' => $request->category_names]);
        }
        if($request->payment){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.is_require_credit', 'value' => $request->payment]);
        }
        if($request->status_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.status_id', 'value' => $request->status_ids]);
        }

        if($request->start_date && $request->end_date){
            $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d 00:00:00');
            $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d 23:59:59');
            array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'quotes.created_at', 'value' => [$start_date,  $end_date] ]);
        }

        if (auth()->user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $quotes = Quote::join('rfqs', function ($join) {
                $join->on('rfqs.id', '=', 'quotes.rfq_id');
            })->join('rfq_products', function ($join) {
                $join->on('rfqs.id', '=', 'rfq_products.rfq_id');
            })->join('rfq_status', function ($join) {
                $join->on('rfqs.status_id', '=', 'rfq_status.id');
            })->where('quotes.supplier_id', $supplier_id)
                ->orderBy('rfqs.id', 'desc');

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $quotes = $quotes->whereIn($value['column_name'], $value['value']);
                    }

                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $quotes = $quotes->whereBetween($value['column_name'], $value['value']);
                    }
                }
            }

            $quotes = $quotes->get(['quotes.*','rfqs.is_require_credit', 'rfqs.id as rfqId', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.reference_number as rfq_number', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.name as status_name']);
        } else {
            $quotes = Quote::join('rfqs', function ($join) {
                $join->on('rfqs.id', '=', 'quotes.rfq_id');
            })->join('rfq_products', function ($join) {
                $join->on('rfqs.id', '=', 'rfq_products.rfq_id');
            })->join('rfq_status', function ($join) {
                $join->on('rfqs.status_id', '=', 'rfq_status.id');
            })->orderBy('rfqs.id', 'desc');

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $quotes = $quotes->whereIn($value['column_name'], $value['value']);
                    }

                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $quotes = $quotes->whereBetween($value['column_name'], $value['value']);
                    }
                }
            }

            $quotes = $quotes->get(['quotes.*','rfqs.is_require_credit', 'rfqs.id as rfqId', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.reference_number as rfq_number', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.name as status_name']);
        }

        $quoteDataHtml = view('admin/quote/quoteTableData', ['quotes' => $quotes])->render();
        return $quoteDataHtml;
        // dd($condition);
        // return 'hey quote list ajax';
    }


    function quoteDetail($id)
    {
        $quote_id = Quote::find($id);
        $quotes = Quote::join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
            ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
            ->join('users', 'users.id', '=', 'user_rfqs.user_id')
            ->join('user_companies', 'user_rfqs.user_id', '=', 'user_companies.user_id')
            ->join('companies', 'rfqs.company_id', '=', 'companies.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->leftJoin('group_members','quotes.rfq_id','=','group_members.rfq_id')
            ->orderBy('rfqs.id', 'desc')
            ->where('quotes.id', $id)
            ->first(['quotes.*', 'rfqs.id as rfqId', DB::raw('CONCAT(rfqs.firstname," ",rfqs.lastname) as fullname'), 'rfqs.pincode', 'rfqs.email', 'rfqs.mobile', 'rfqs.reference_number', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.name as status_name', 'units.name as unit_name', DB::raw('CONCAT(suppliers.contact_person_name," ",suppliers.contact_person_last_name) as supplier_name'), 'suppliers.name as supplier_company', 'companies.name as user_company_name', 'quotes.comment', 'quotes.certificate', 'suppliers.email as suppliers_email', 'suppliers.mobile as suppliers_mobile', 'group_members.group_id', 'suppliers.cp_phone_code as supplier_phone_code', 'users.phone_code as buyer_phone_code','quote_items.min_delivery_days','quote_items.max_delivery_days','rfqs.created_at as rfq_date','quote_items.inclusive_tax_other','quote_items.inclusive_tax_logistic','quote_items.certificate as product_certificate']);

        $multiple_quote = QuoteItem::leftJoin('units', 'quote_items.price_unit', '=', 'units.id')->where('quote_id', $id)->get(['quote_items.*','units.name as quote_unit_name']);

        if (auth()->user()->role_id == 3){
            $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))->where('quotes_charges_with_amounts.quote_id', $id)->where('quotes_charges_with_amounts.charge_type', 0)->orderBy('charge_type', 'asc')->get();
        } else {
            $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))->where('quotes_charges_with_amounts.quote_id', $id)->orderBy('charge_type', 'asc')->get();
        }

        $approversList = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')
            ->join('user_approval_configs','user_approval_configs.user_id','=','users.id')
            ->leftJoin('designations','designations.id','=','users.designation')
            ->where('user_quote_feedbacks.quote_id',$id)
            ->where('user_quote_feedbacks.resend_mail',0)
            ->where('user_approval_configs.user_type','Approver')
            ->get(['users.firstname','users.lastname','designations.name','user_quote_feedbacks.feedback']);
        $certificate_attachment = QuoteItem::where('quote_id', $id)->where('certificate','!=','')->count();
        $quoteHTML = view('admin/quote/quoteDetailmodal', ['quote' => $quotes, 'quotes_charges_with_amounts' => $quotes_charges_with_amounts, 'approversList' => $approversList, 'quote_items' => $multiple_quote,'certificate_attachment' =>$certificate_attachment])->render();
        $returnHTML = view('admin/quote/quoteDetails', ['quote' => $quotes, 'quotes_charges_with_amounts' => $quotes_charges_with_amounts])->render();

        /**begin: system log**/
        $quote_id->bootSystemView(new Quote(), 'Quote', SystemActivity::RECORDVIEW, $quote_id->id);
        /**end: system log**/
        return response()->json(array('success' => true, 'html' => $returnHTML, 'quoteHTML' => $quoteHTML));
    }

    function create(Request $request)
    {
        $company_id = Rfq::where('id',$request->rfq_id)->pluck('company_id')->first();

        $certificateFilePath = '';
        $calculte_amount = $this->calculateSupplierAmount($request->all());
        $check_logistic = isset($request->logistic_check) && !empty($request->logistic_check) ? 1 : 0;

        if (auth()->user()->role_id == 3){
            $chargeTypeZero = $typeFlatRp = 0;

            $company_id = Rfq::find($request->rfq_id)->company_id;

            $datas = OtherCharge::join('xendit_commision_fees', 'other_charges.id', '=', 'xendit_commision_fees.charge_id')
                ->join('companies','xendit_commision_fees.company_id', '=','companies.id')
                ->where(['other_charges.charges_type' => 2,'other_charges.is_deleted' => 0, 'other_charges.status' => 1, 'xendit_commision_fees.is_delete' => 0, 'xendit_commision_fees.company_id' => $company_id])
                ->groupBy('charge_id')
                ->get(['xendit_commision_fees.charge_id as charges', 'xendit_commision_fees.charges_value as charge_value','xendit_commision_fees.type as type']);
            $typeZero = array_sum($datas->where('type',0)->pluck('charge_value')->toArray());
            $typePercentage = $request->amount * $typeZero /100;

            $typeFlatRp = $datas->where('type',1)->sum('charge_value');


            //add static charge supplier
            if(($request->payment_type ==3 || $request->payment_type == 4) && ($check_logistic == 1 && Auth::user()->role_id == 3))
            {
                $typeFlatRp = 0;
                $typePercentage = 0;
            }
            $request->finalAmount = $request->finalAmount + $typeFlatRp + $typePercentage;
            $request['payment_charges'] = $datas->toArray();
        }
        $termsconditionsFilePath = '';
        if ($request->file('termsconditions_file')) {
            $termsconditionsFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $request->file('termsconditions_file')->getClientOriginalName();
            $termsconditionsFilePath = $request->file('termsconditions_file')->storeAs('uploads/supplier/supplier_tcdoc', $termsconditionsFileName, 'public');
        } else if ($request->oldtermsconditions_file){
            $old_tcdocument = explode('/',$request->oldtermsconditions_file);
            //dd($old_tcdocument[2]);
            $termsconditionsFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $old_tcdocument[2];

            if (file_exists('uploads/supplier/supplier_tcdoc/'.$termsconditionsFileName)){
                $termsconditionsFilePath= 'uploads/supplier/supplier_tcdoc/'.$termsconditionsFileName;
                Storage::copy('/public/'.$request->oldtermsconditions_file, '/public/'.$termsconditionsFilePath);
            }else{
                $termsconditionsFilePath= "";
            }
        }else{
            $termsconditionsFilePath= "";
        }
        $groupId = DB::table('rfqs')->where('id',$request->rfq_id)->pluck('group_id')->first();
        $quote = new Quote;
        $quote->supplier_id = $request->supplier;
        $quote->group_id = $groupId??null;
        $quote->rfq_id = $request->rfq_id;
        $quote->valid_till = date('Y-m-d', strtotime($request->valid_till));
        $quote->final_amount = round($request->finalAmount, 2);
        $quote->tax = round($request->tax, 2);
        $quote->tax_value = round($request->tax_amount, 2);
        $quote->note = $request->note;
        $quote->certificate = $certificateFilePath;
        $quote->termsconditions_file = $termsconditionsFilePath;
        $quote->comment = $request->comment;
        $quote->status_id = ($check_logistic == 0 && auth()->user()->role_id != 1)? 5 : 1;
        $quote->supplier_final_amount = $calculte_amount['supplier_final_amount'];
        $quote->supplier_tex_value = $calculte_amount['supplier_tax_value'];
        $quote->address_name = $request->address_name;
        $quote->address_line_1 = $request->address_line_1;
        $quote->address_line_2 = $request->address_line_2 ? $request->address_line_2 : '';
        $quote->district = $request->district;
        $quote->sub_district = $request->sub_district;
        $quote->city = $request->cityId > 0 ? '' : $request->city;
        $quote->provinces = $request->stateId > 0 ? '' : $request->provinces;
        $quote->city_id =  $request->cityId;
        $quote->state_id =  $request->stateId;
        $quote->country_id =  $request->countryId;
        $quote->pincode = $request->pincode;
        $quote->user_id = Auth::id();
        $quote->payment_type = $request->payment_type;
        $quote->credit_days = $request->credit_days;
        $quote->full_quote_by = $check_logistic == 1 ? auth()->user()->id : null;
        /* START :: Update Default Address While Quote Send. */
        UserAddresse::where('id', $request->supplier_address_id)->update([
            "address_name" => $request->address_name,
            "address_line_1" => $request->address_line_1,
            "address_line_2" => $request->address_line_2,
            'city' => $request->city,
            'sub_district' => $request->sub_district,
            'district' => $request->district,
            'country_id' => $request->countryId,
            'state_id' => $request->stateId,
            'city_id' => $request->cityId,
            'pincode' => $request->pincode,
        ]);
        /* END :: Update Default Address While Quote Send. */


        $quote->save();
        $quoteId = $quote->id;
        $quote->quote_number = 'BQTN-' . $quoteId;
        $quote->save();
       // BRFQ-1152

        if($check_logistic == 1)
        {
            $rfqDetailssms = Rfq::find($request->rfq_id);
            $smsData['rfq_number'] = $rfqDetailssms->reference_number;
            $smsData['quote_number'] = 'BQTN-' . $quoteId;
            $sendMsg = $this->verify->sendMsg($rfqDetailssms->firstname,$rfqDetailssms->lastname,'quote_received',$rfqDetailssms->phone_code,$rfqDetailssms->mobile,$smsData);    
        }
        

        /**begin: system log**/
        $quote->bootSystemActivities();
        /**end: system log**/
        $quote_items = [];
        $i = 101;

        if (!empty($request->checkrfq)){
            foreach ($request->checkrfq as $key => $value){
                $certificateFilePath = '';
                if ($request->file('certificate') && !empty($request->file('certificate')[$key])) {
                    $certificateFileName = Str::random(10) . '_' . time() . 'certificate_' . $request->file('certificate')[$key]->getClientOriginalName();
                    $certificateFilePath = $request->file('certificate')[$key]->storeAs('uploads', $certificateFileName, 'public');
                }
                $quote_items[] = array(
                    'rfq_product_id' => $request->rfq_product_id[$key],
                    'quote_id' => $quote->id,
                    'quote_item_number' => 'BQTN-'.$quoteId.'/'.$i,
                    'supplier_id' => $request->supplier,
                    'product_id' => $request->product_id[$key],
                    'product_price_per_unit' => $request->price[$key],
                    'product_quantity' => $request->qty[$key],
                    'price_unit' => $request->unit_id[$key],
                    'product_amount' => $request->price[$key]*$request->qty[$key],
                    'min_delivery_days' => $request->minDays,
                    'max_delivery_days' => $request->maxDays,
                    'logistic_check' => (($check_logistic == 0 && auth()->user()->role_id == 1)? 1 : $check_logistic),
                    'logistic_provided' => (($check_logistic != 0)? 1 : 0),
                    'inclusive_tax_other' => $request->inclusive_tax_other??0,
                    'inclusive_tax_logistic' => $request->inclusive_tax_logistic??0,
                    'certificate' => $certificateFilePath,
                    'weights' => $request->weights[$key],
                    'dimensions' => $request->dimensions[$key] ?? 0,
                    'length' => $request->length[$key] ?? 1,
                    'width' => $request->width[$key] ?? 1,
                    'height' => $request->height[$key] ?? 1,
                    'pickup_service' => $request->pickup_service ?? null,
                    'pickup_fleet' => $request->pickup_fleet ?? null,
                    'logistics_service_code' => $request->logistics_service_code ?? null,
                    'insurance_flag' => 1,
                    'wood_packing' => $request->wood_packing ?? null
                );
                $i++;
            }
        }
        QuoteItem::insert($quote_items);

        if ($check_logistic == 1 || auth()->user()->role_id == 1) {
            $this->quoteToBuyer($request->all(), $quote);
        }
        if (isset($request->charges)) {
            for ($i = 0; $i < count($request->charges); $i++) {
                if (!empty($request->charges[$i]) && $request->charges[$i] != 0) {
                    $chargeDetail = OtherCharge::find($request->charges[$i]);
                    $quoteCharge = new QuoteChargeWithAmount;
                    $quoteCharge->quote_id = $quoteId;
                    $quoteCharge->charge_id = $chargeDetail->id;
                    $quoteCharge->charge_name = $chargeDetail->name;
                    $quoteCharge->custom_charge_name = isset($request->custom_charge_name[$i]) ? $request->custom_charge_name[$i] : '';
                    $quoteCharge->value_on = $chargeDetail->value_on;
                    $quoteCharge->addition_substraction = $chargeDetail->addition_substraction;
                    $quoteCharge->type = $chargeDetail->type;
                    $quoteCharge->charge_value = round($request->chargeValue[$i], 2);
                    $quoteCharge->charge_amount = round($request->charge_amount[$i], 2);
                    $quoteCharge->charge_type = $chargeDetail->charges_type;
                    $quoteCharge->save();
                }
            }
        }
        if (isset($request->payment_charges)) {
            foreach ($request->payment_charges as $paymentCharge) {
                if (!empty($paymentCharge['charges'])) {
                    $chargeDetail = OtherCharge::with(['company' => function ($q) use($company_id) {
                        $q->where(['is_delete' => 0, 'company_id' => $company_id])->select(['charge_id', 'company_id']);
                    }])
                        ->with(['xenditCommisionFee'=>function($q) use($company_id){
                            $q->where(['company_id' => $company_id, 'is_delete'=>0])->select(['charge_id', 'company_id','type','charges_value','is_delete','charges_type','addition_substraction']);
                        }])
                        ->where(['id' => $paymentCharge['charges']])->first();

                    //$chargeDetail = collect($chargeDetail)->where('company','!=',null);

                    $chargeVal = ($paymentCharge['charges'] == 10)? $chargeDetail['charges_value']: $paymentCharge['charge_value'];

                    if (auth()->user()->role_id == 3) {
                        if($paymentCharge['type'] == 0){  //calculate amount on percentage
                            $chargeAmount = $paymentCharge['charge_value'] * $request->amount / 100;
                        }else{
                            $chargeAmount = ($paymentCharge['charges'] == 10) ? $chargeDetail['charges_value'] : $paymentCharge['charge_value'];
                        }
                    } else {
                        $chargeAmount = ($paymentCharge['charges'] == 10) ? $chargeDetail['charges_value'] : $paymentCharge['charge_amount'];
                    }
                    if(($request->payment_type ==3 || $request->payment_type == 4) && ($check_logistic == 1 && Auth::user()->role_id == 3))
                    {
                        $chargeVal = 0;
                        $chargeAmount = 0;
                    }
                    if ($chargeDetail) {
                        $quoteCharge = new QuoteChargeWithAmount;
                        $quoteCharge->quote_id = $quoteId;
                        $quoteCharge->charge_name = $chargeDetail['name'];
                        $quoteCharge->charge_id = $chargeDetail['id'];
                        $quoteCharge->addition_substraction = $chargeDetail->xenditCommisionFee['addition_substraction'];
                        $quoteCharge->type = $chargeDetail->xenditCommisionFee['type'];
                        $quoteCharge->charge_value = round((float)$chargeVal, 2);
                        $quoteCharge->charge_amount = round((float)$chargeAmount, 2);
                        $quoteCharge->charge_type = $chargeDetail->xenditCommisionFee['charges_type'];
                        $quoteCharge->save();
                    }
                }
            }
        }

        if((auth()->user()->role_id == 1 || auth()->user()->role_id == 3) && $request->supplier_address_id == 0) {
            $supplierAddress = new SupplierAddress();
            $supplierAddress->supplier_id = $request->supplier;
            $supplierAddress->address_name = $request->address_name;
            $supplierAddress->address_line_1 = $request->address_line_1;
            $supplierAddress->address_line_2 = $request->address_line_2 ? $request->address_line_2 : '';
            $supplierAddress->pincode = $request->pincode;
            $supplierAddress->city = $request->cityId > 0 ? '' : $request->city;
            $supplierAddress->state = $request->stateId > 0 ? '' : $request->provinces;
            $supplierAddress->city_id =  $request->cityId;
            $supplierAddress->state_id =  $request->stateId;
            $supplierAddress->country_id =  $request->countryId;
            $supplierAddress->sub_district = $request->sub_district;
            $supplierAddress->district = $request->district;
            $supplierAddress->default_address = $request->default_address == true ? 1 : 0;
            $supplierAddress->save();
        }
        $this->adminQuoteNotification($quote->id, $request->supplier);
        broadcast(new quotesCountEvent());
        broadcast(new BuyerRfqNotificationEvent());
        if($groupId){
            return redirect('admin/group-rfq');
        }
        return redirect('admin/rfq');
    }

    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $quote_id = Quote::find($id);
        $quotes = Quote::join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
            ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
            ->join('user_companies', 'user_rfqs.user_id', '=', 'user_companies.user_id')
            ->join('users', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->leftJoin('sub_categories', 'rfq_products.sub_category', '=', 'sub_categories.name')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->leftJoin('groups','quotes.group_id','=','groups.id')
            ->with(['quotePlatformCharges'=>function($q){
                $q->where('charge_name', '<>' , 'Group Discount');
            }])
            ->with('quoteLogisticCharges')
            ->with('quotePaymentFees')
            ->with('quoteGroupDiscountCharges')
            ->orderBy('rfqs.id', 'desc')
            ->where('quotes.id', $id)
            ->get(['quotes.*', 'quote_items.supplier_id as supplier_id', 'quote_items.min_delivery_days', 'quote_items.logistic_check', 'quote_items.max_delivery_days', 'rfqs.id as rfqId', 'rfqs.firstname', 'rfqs.address_line_1 as rfq_address_line_1', 'rfqs.address_line_2 as rfq_address_line_2', 'rfqs.sub_district as rfq_sub_district', 'rfqs.district as rfq_district', 'rfqs.city as rfq_city', 'rfqs.state as rfq_state', 'rfqs.city_id as rfq_city_id', 'rfqs.state_id as rfq_state_id',  'rfqs.lastname', 'rfqs.pincode', 'rfqs.created_at as rfqs_date', 'rfqs.email', 'rfqs.mobile', 'rfqs.reference_number', 'rfqs.rental_forklift', 'rfqs.unloading_services', 'rfq_products.category','rfq_products.category_id', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.name as status_name', 'units.name as unit_name', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company', 'companies.name as user_company_name', 'quotes.comment', 'quotes.certificate', 'suppliers.email as suppliers_email', 'suppliers.mobile as suppliers_mobile', 'companies.name as company_name', 'rfq_products.expected_date', 'rfq_products.quantity', 'rfqs.is_require_credit', 'users.phone_code as user_phone_code','groups.target_quantity','groups.achieved_quantity','quote_items.inclusive_tax_other','quote_items.inclusive_tax_logistic', 'rfqs.company_id', 'quote_items.pickup_service', 'quote_items.pickup_fleet', 'quote_items.logistics_service_code', 'quote_items.insurance_flag', 'quote_items.wood_packing', 'quote_items.logistic_provided'])->first();

        //Quotation Accepted or Partial Quotation Sent
        if (in_array($quotes->status_id,[2,5])) {
            //not admin and not Partial Quotation Sent
            if (auth()->user()->role_id != 1 && $quotes->status_id != 5) {
                return redirect('admin/quotes')->with('error', __('admin.access_denied'));
            }
        }
        $platformCharges = OtherCharge::all()->where('charges_type', 0)->where('is_deleted', 0)->where('status',1)->where('name', '<>' , 'Group Discount');
        $logisticCharges = OtherCharge::all()->where('charges_type', 1)->where('is_deleted', 0)->where('status',1);
        if(!empty($quotes->group_id)){
            $suppliers = GroupSupplier::leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                ->where('group_suppliers.group_id',$quotes->group_id)
                ->get(['suppliers.id as supplierId', 'suppliers.name as supplierName','group_suppliers.supplier_id as grpSuppId']);
        }else {
            //Below is old code which we updated just below this old code - This old code is important for us.
            /*$suppliers = DB::table('rfq_products')
                ->join('sub_categories', 'rfq_products.sub_category', '=', 'sub_categories.name')
                ->join('products', function ($join) {
                    $join->on(strtolower('rfq_products.product_id'), '=', strtolower('products.id'))
                        ->on('sub_categories.id', '=', 'products.subcategory_id');
                })
                ->join('supplier_products', 'products.id', '=', 'supplier_products.product_id')
                ->join('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
                ->where('rfq_products.rfq_id', $quotes->rfqId)
                ->select('suppliers.id as supplierId', 'suppliers.name as supplierName')
                ->get();*/

            $suppliers = Supplier::with(['supplierProducts.rfqProduct','supplierProducts'])->whereHas('supplierProducts.rfqProduct', function($q) use($quotes){
                $q->where('rfq_id',$quotes->rfqId);
            })
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->select('id as supplierId', 'name as supplierName')
            ->get();


        }
        //This Code Not Use if show any issue in the product get list uncomment this code and return it.
        /*$product_list = DB::table('rfq_products')
            ->join('products', 'rfq_products.product_id', '=', 'products.id')
            ->leftJoin('supplier_products', 'products.id', '=', 'supplier_products.product_id')
            ->leftJoin('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
            ->where('supplier_products.is_deleted', 0)
            ->where('suppliers.status', 1)
            ->where('suppliers.is_deleted', 0)
            ->where('rfq_products.rfq_id', $quotes->rfqId)
            ->where('supplier_products.supplier_id', $quotes->supplier_id)
            ->pluck('rfq_products.product_id')
            ->toArray();*/

        $countries = CountryOne::get(['id', 'name']);
        if (!empty($defaultAddress)) {
            $states = State::where('country_id', $quotes->country_id)->get(['id', 'name']);
        } else {
            $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
        }
        $cities = City::where(['country_id' => $quotes->country_id, 'state_id' => $quotes->state_id])->get(['id', 'name']);
        $company_id = $quotes->company_id;
        $logistics_services = LogisticsService::all()->where('deleted_at',null);

        if ($quotes) {
            $paymentCharges = OtherCharge::with(['company' => function ($q) use($company_id) {
                $q->where(['is_delete' => 0, 'company_id' => $company_id])->select(['charge_id', 'company_id']);
            }])
                /* this relathion is old so commented for future reference
                ->with(['quoteChargeWithAmount' => function ($q) use($quote_id) {
                    $q->where(['is_deleted' => 0, 'quote_id' => $quote_id->id])->select(['charge_id', 'charge_value', 'charge_amount', 'charge_type', 'type', 'addition_substraction']);
                }])
                */
                ->with(['xenditCommisionFee'=>function($q) use($company_id){
                    $q->where(['company_id' => $company_id, 'is_delete'=>0])->select(['charge_id', 'company_id','type','charges_value as charge_value','is_delete','charges_type as charge_type','addition_substraction']);
                }])
                ->where(['charges_type' => 2, 'is_deleted' => 0, 'status' => 1])->get();
            $paymentCharges = collect($paymentCharges->toArray())->where('company','!=',null);

            /**begin: system log**/
            $quote_id->bootSystemView(new Quote(), 'Quote', SystemActivity::EDITVIEW, $quote_id->id);
            /**end: system log**/
            return view('/admin/quote/quoteEdit', ['quotes' => $quotes, 'suppliers' => $suppliers, 'platformCharges' => $platformCharges, 'logisticCharges' => $logisticCharges, 'quotePlatformCharges' => $quotes->quotePlatformCharges->toArray(), 'quoteLogisticCharges' => $quotes->quoteLogisticCharges->toArray(), 'paymentCharges' => array_values($paymentCharges->toArray()), 'paymentFees' => $quotes->quotePaymentFees->toArray(), 'states' => $states, "cityes" => $cities, "countries" => $countries, 'quoteGroupDiscountCharges' => $quotes->quoteGroupDiscountCharges->toArray(), 'logistics_services' => $logistics_services]);
        } else {
            return redirect('admin/quotes');
        }
    }

    function update(Request $request)
    {
        $rfqDetails = Quote::with('rfq:id,company_id')->where('id',$request->quote_id)->select('rfq_id')->first();
        $company_id = $rfqDetails['rfq']['company_id'];

        $update_activity = [];
        $certificateFilePath = '';

        $SupplierId = getSupplierByLoginId(Auth::user()->id);
        $rfqProducts = $request->rfq_products??[];
        $groupId = $request->groupId??null;
        if (isset($request->rfq_products)) {
            $request->request->remove('rfq_products');
        }
        $calculte_amount = $this->calculateSupplierAmount($request->all());

        $quote = Quote::join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')->select('rfq_id', 'final_amount as finalAmount', 'tax_value as tax_amount', 'quotes.certificate', 'inclusive_tax_other', 'inclusive_tax_logistic', 'note', 'comment', 'valid_till', 'tax', 'status_id', 'quote_number', 'quotes.id','address_name', 'address_line_1', 'address_line_2', 'district', 'sub_district', 'city', 'provinces', 'pincode', 'termsconditions_file','city_id as cityId','state_id as stateId','country_id as countryId','group_id as groupId')->find($request->quote_id);
        //if order placed on quote
        $order = $quote->order()->first();
        $orderId = $order->id??0;
        $status_id = $quote['status_id'];
        $newExpDate = changeDateFormat($request->valid_till,'Y-m-d');
        $expDate = changeDateFormat($quote['valid_till'],'Y-m-d');
        $newExpDate > $expDate ? $expDateUpdate = true : $expDateUpdate = false;

        $termsconditionsFilePath = '';
        if ($request->file('termsconditions_file')) {
            if ($quote->termsconditions_file!='') {
                Storage::delete('/public/' . $quote->termsconditions_file);
            }
            $termsconditionsFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $request->file('termsconditions_file')->getClientOriginalName();
            if(file_exists($request->file('termsconditions_file')->storeAs('uploads/supplier/supplier_tcdoc', $termsconditionsFileName, 'public'))){
                if ($quote->termsconditions_file!='') {
                    Storage::delete('/public/uploads/supplier/supplier_tcdoc' . $termsconditionsFileName);
                }
                $termsconditionsFilePath = $request->file('termsconditions_file')->storeAs('uploads/supplier/supplier_tcdoc', $termsconditionsFileName, 'public');
            }else if(!file_exists($request->file('termsconditions_file')->storeAs('uploads/supplier/supplier_tcdoc', $termsconditionsFileName, 'public'))){
                $termsconditionsFilePath = $request->file('termsconditions_file')->storeAs('uploads/supplier/supplier_tcdoc', $termsconditionsFileName, 'public');
            }else{
                $termsconditionsFilePath =$request->oldtermsconditions_file;
            }
        }else{
            $termsconditionsFilePath =$request->oldtermsconditions_file;
        }
        //if admin want to change rfq product quantity when Quotation Accepted
        if (auth()->user()->role_id == 1 && $status_id==2 && !empty($orderId)) {
            $isRfqChange = 0;
            $rfqUserId = 0;
            $rfqReferenceNumber = '';
            foreach ($rfqProducts as $rfqProductId=>$rfqProductData) {
                $productRfq = RfqProduct::where(['id' => $rfqProductId, 'rfq_id' => $quote->rfq_id])->first();
                if ($productRfq->quantity!=$rfqProductData['quantity']) {
                    if (empty($rfqUserId) || empty($rfqReferenceNumber)){
                        $rfqReferenceNumber = $productRfq->rfq->reference_number;
                        $rfqUserId = $productRfq->user_rfq->user_id;
                    }
                    $input['user_id'] = Auth::user()->id;
                    $input['rfq_id'] = $quote->rfq_id;
                    $input['key_name'] = 'quantity';
                    $input['old_value'] = $productRfq->quantity ?? '';
                    $input['new_value'] = $rfqProductData['quantity'];
                    RfqActivity::create($input);
                    $isRfqChange = 1;

                    $productRfq->update($rfqProductData);
                }
            }
            if($isRfqChange && $rfqUserId){
                /*$userActivity = new UserActivity();
                $userActivity->user_id = $rfqUserId;
                $userActivity->activity = $rfqReferenceNumber .' Updated By Blitznet Team';
                $userActivity->type = 'rfq';
                $userActivity->record_id = $quote->rfq_id;
                $userActivity->save();*/

                $commanData = [];
                if((int)Auth::user()->role_id == 1){
                    $commanData = array('quote_number' => $rfqReferenceNumber, 'updated_by' => 'Blitznet Team', 'icons' => 'fa-user');
                }else{
                    $commanData = array('quote_number' => $rfqReferenceNumber, 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-user');
                }
                buyerNotificationInsert($rfqUserId, 'Update RFQ', 'buyer_update_rfq', 'rfq', $quote->rfq_id, $commanData);
                broadcast(new BuyerNotificationEvent());

            }
        }elseif(($status_id < 2 && empty($orderId))) {
            //if admin and supplier want to change rfq product quantity when Quotation Pending
            $isRfqChange = 0;
            $rfqUserId = 0;
            $rfqReferenceNumber = '';
            foreach ($rfqProducts as $rfqProductId=>$rfqProductData) {
                $productRfq = RfqProduct::where(['id' => $rfqProductId, 'rfq_id' => $quote->rfq_id])->first();
                //dd($productRfq->quantity .'-'. $rfqProductData['quantity']);
                if ($productRfq->quantity!=$rfqProductData['quantity']) {
                    if (empty($rfqUserId) || empty($rfqReferenceNumber)){
                        $rfqReferenceNumber = $productRfq->rfq->reference_number;
                        $rfqUserId = $productRfq->user_rfq->user_id;
                    }
                    $input['user_id'] = Auth::user()->id;
                    $input['rfq_id'] = $quote->rfq_id;
                    $input['key_name'] = 'quantity';
                    $input['old_value'] = $productRfq->quantity ?? '';
                    $input['new_value'] = $rfqProductData['quantity'];
                    RfqActivity::create($input);
                    $isRfqChange = 1;

                    if($groupId != null){
                        $grouprReachedQty = Groups::where('id', $groupId)->pluck('reached_quantity')->first();
                        $reachedQuantity =  $grouprReachedQty - $productRfq->quantity;
                        $updateReachedQuantity = $reachedQuantity + $rfqProductData['quantity'];
                        Groups::where('id', $groupId)->update(['reached_quantity' => $updateReachedQuantity]);
                    }
                    $productRfq->update($rfqProductData);
                }
            }
            if($isRfqChange && $rfqUserId){
                /*$userActivity = new UserActivity();
                $userActivity->user_id = $rfqUserId;
                $userActivity->activity = $rfqReferenceNumber .' Updated By Blitznet Team';
                $userActivity->type = 'rfq';
                $userActivity->record_id = $quote->rfq_id;
                $userActivity->save();*/

                $commanData = [];
                if((int)Auth::user()->role_id == 1){
                    $commanData = array('quote_number' => $rfqReferenceNumber, 'updated_by' => 'Blitznet Team', 'icons' => 'fa-user');
                }else{
                    $commanData = array('quote_number' => $rfqReferenceNumber, 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-user');
                }
                buyerNotificationInsert($rfqUserId, 'Update RFQ', 'buyer_update_rfq', 'rfq', $quote->rfq_id, $commanData);
                broadcast(new BuyerNotificationEvent());

            }
        }

        if ($request->supplier_address_id == 0){
            $newSupplierAddress = new SupplierAddress;
        } else {
            $newSupplierAddress = SupplierAddress::find($request->supplier_address_id);
        }

        $newSupplierAddress->supplier_id = $request->has('supplier') ? $request->supplier : $SupplierId;
        $newSupplierAddress->address_name = $request->address_name;
        $newSupplierAddress->address_line_1 = $request->address_line_1;
        $newSupplierAddress->address_line_2 = $request->address_line_2 ? $request->address_line_2 : '';
        $newSupplierAddress->pincode = $request->pincode;
        $newSupplierAddress->city = $request->cityId > 0 ? '' : $request->city;
        $newSupplierAddress->state = $request->stateId > 0 ? '' : $request->provinces;
        $newSupplierAddress->city_id =  $request->cityId;
        $newSupplierAddress->state_id =  $request->stateId;
        $newSupplierAddress->country_id =  $request->countryId;
        $newSupplierAddress->district = $request->district;
        $newSupplierAddress->sub_district = $request->sub_district;
        $newSupplierAddress->save();

        unset($request->supplier_address_id);
        // Activity log
        $i = 0;
        foreach ($request->all() as $key => $value) {
            if ($key == 'valid_till') {
                $value = date('Y-m-d', strtotime($request->valid_till));
            }
            if ($key=='termsconditions_file') {
                $value = $termsconditionsFilePath;
            }
            if (!in_array($key, ['_token', 'quote_id', 'productId', 'price_per_unit', 'custom_charge_name', 'charges', 'chargeType', 'chargeValue', 'charge_amount', 'payment_charges', 'checkrfq', 'product_id', 'qty', 'unit_id', 'amount', 'minDays', 'maxDays', 'supplier', 'weights', 'dimensions',  'length', 'width', 'height','pickup_service','pickup_fleet', 'logistics_service_code','insurance_flag','wood_packing','logistic_check','rfq_product_id', 'price','supplier_address_id','certificate','oldtermsconditions_file','old_termsconditions_file']) && $quote[$key] != $value) {
                $update_activity[$i]['key_name'] = $key;
                $update_activity[$i]['user_id'] = Auth::id();
                $update_activity[$i]['quote_id'] = $request->quote_id;
                if(($key == 'valid_till') && (date('Y-m-d', strtotime($quote['valid_till'])) != date('Y-m-d', strtotime($value)))){
                    $update_activity[$i]['old_value'] = date('Y-m-d', strtotime($quote['valid_till']));
                    $update_activity[$i]['new_value'] = date('Y-d-m', strtotime($value));

                }
                elseif(isset($request->logistic_check) && !empty($request->logistic_check) || $key == 'logistic_check') {
                    $update_activity[$i]['old_value'] = $quote[$key]??0;
                    $update_activity[$i]['new_value'] = $value;
                } else {
                    $update_activity[$i]['old_value'] = $quote[$key];
                    $update_activity[$i]['new_value'] = $value;
                }
                $update_activity[$i]['user_type'] = User::class;
                $update_activity[$i]['created_at'] = Carbon::now();
                $i++;
            }
        }

        //quote item activity
        $quote_items_old =QuoteItem::where('quote_id', $request->quote_id)->pluck('rfq_product_id')->toArray();
        $exits_product_key = $request->rfq_product_id;
        array_walk_recursive($exits_product_key, function(&$value){
            $value = intval($value);
        });

        $check_logistic = isset($request->logistic_check) && !empty($request->logistic_check) ? 1 : 0;
        if (!empty($exits_product_key)){
            if (empty($exits_product_key)){
                $exits_product_key = [];
            }
            $diff_values = array_diff($quote_items_old, $exits_product_key);
            if (!empty($diff_values)){
                foreach ($diff_values as $delete_key){
                    $update_activity[$i]['key_name'] = 'remove_product_value';
                    $update_activity[$i]['user_id'] = Auth::id();
                    $update_activity[$i]['quote_id'] = $request->quote_id;
                    $update_activity[$i]['old_value'] = '';
                    $update_activity[$i]['new_value'] = $delete_key;
                    $update_activity[$i]['user_type'] = User::class;
                    $update_activity[$i]['created_at'] = Carbon::now();
                    $qitem = QuoteItem::where('quote_id', $request->quote_id)->where('rfq_product_id', $delete_key)->first();
                    if (auth()->user()->role_id == 1 && $status_id==2 && !empty($orderId)) {
                        $qitem->orderItem()->delete();
                    } elseif (($status_id < 2 && empty($orderId))) {
                        //added by ekta
                        $qitem->orderItem()->delete();
                    }
                    $qitem->delete();
                    $i++;
                }
            }
            $j = count($quote_items_old)+101;
            foreach ($exits_product_key as $key => $value){
                $quoteItem = QuoteItem::select('id', 'product_price_per_unit as price', 'product_quantity as qty', 'price_unit as unit_id', 'min_delivery_days as minDays', 'max_delivery_days as maxDays', 'certificate', 'weights', 'dimensions', 'length', 'width', 'height')->where('quote_id', $request->quote_id)->where('rfq_product_id', $value)->first();
                if (!empty($quoteItem)){
                    //update
                    foreach ($quoteItem->toArray() as $a_key => $a_value) {

                        if(array_key_exists($a_key,$request->all())){
                            if (is_array($request->all()[$a_key])) {
                                if ($a_key == 'certificate' && isset($request->all()[$a_key][$a_value])) {
                                    $reqvalue = $request->all()[$a_key][$key];
                                } else if ($a_key != 'certificate') {
                                    $reqvalue = $request->all()[$a_key][$key];
                                }
                            }else{
                                $reqvalue = $request->all()[$a_key];
                            }
                        }

                        if (!empty($request->file('certificate')) && is_array($request->file('certificate'))) {

                            if( $a_key == 'certificate' && array_key_exists($key,$request->all()[$a_key])) {
                                $update_activity[$i]['user_id'] = Auth::id();
                                $update_activity[$i]['quote_id'] = $request->quote_id;
                                $update_activity[$i]['key_name'] = $a_key;
                                $update_activity[$i]['old_value'] = Str::substr($quoteItem[$a_key], stripos($quoteItem[$a_key], 'certificate_') + 12);
                                $update_activity[$i]['new_value'] = isset($request->file('certificate')[$key]) ? $request->file('certificate')[$key]->getClientOriginalName() : '';
                                $update_activity[$i]['user_type'] = User::class;
                                $update_activity[$i]['created_at'] = Carbon::now();
                            }
                            $i++;
                        }elseif (isset($request->all()[$a_key][$key]) && $quoteItem[$a_key] != $reqvalue) {
                            if( $a_key != 'certificate') {
                                $update_activity[$i]['user_id'] = Auth::id();
                                $update_activity[$i]['quote_id'] = $request->quote_id;
                                $update_activity[$i]['key_name'] = $a_key;
                                $update_activity[$i]['old_value'] = $quoteItem[$a_key];
                                $update_activity[$i]['new_value'] = $reqvalue;
                                $update_activity[$i]['user_type'] = User::class;
                                $update_activity[$i]['created_at'] = Carbon::now();
                            }
                            $i++;
                        }

                        $certificateFilePath = '';
                        if (!empty($request->file('certificate'))) {
                            if (isset($request->file('certificate')[$key])){
                                $certificateFileName = Str::random(10) . '_' . time() . 'certificate_' . $request->file('certificate')[$key]->getClientOriginalName();
                                $certificateFilePath = $request->file('certificate')[$key]->storeAs('uploads', $certificateFileName, 'public');

                            }
                        }
                    }

                    $updateQuoteItem = array(
                        'supplier_id' => $request->has('supplier') ? $request->supplier : $SupplierId,
                        'product_price_per_unit' => $request->all()['price'][$key],
                        'product_amount' => $request->all()['price'][$key]*$request->all()['qty'][$key],
                        'min_delivery_days' => $request->minDays,
                        'max_delivery_days' => $request->maxDays,
                        'logistic_check' => (($check_logistic == 0 && auth()->user()->role_id == 1)? 1 : $check_logistic),
                        'logistic_provided' => (($check_logistic != 0)? 1 : 0),
                        'inclusive_tax_other' => isset($request->inclusive_tax_other)??0,
                        'inclusive_tax_logistic' => isset($request->inclusive_tax_logistic)??0,
                        'certificate' => ($certificateFilePath) ? $certificateFilePath : $quoteItem->certificate,
                        'weights' => $request->all()['weights'][$key],
                        'dimensions' => $request->all()['dimensions'][$key] ?? 0,
                        'length' => $request->all()['length'][$key] ?? 1,
                        'width' => $request->all()['width'][$key] ?? 1,
                        'height' => $request->all()['height'][$key] ?? 1,
                        'pickup_service' => $request->pickup_service ?? null,
                        'pickup_fleet' => $request->pickup_fleet ?? null,
                        'logistics_service_code' => $request->logistics_service_code ?? null,
                        'insurance_flag' => 1,
                        'wood_packing' => $request->wood_packing ?? null
                    );
                    //if needed, admin can change quote item quantity when Quotation Accepted
                    if (auth()->user()->role_id == 1 && $status_id<=2 && !empty($orderId)) {
                        $updateQuoteItem['product_quantity'] = $request->all()['qty'][$key];
                    }elseif (($status_id < 2 && empty($orderId))) {
                        //added by ekta
                        $updateQuoteItem['product_quantity'] = $request->all()['qty'][$key];
                    }
                    $quoteItem->update($updateQuoteItem);
                    if (auth()->user()->role_id == 1 && $status_id==2 && !empty($orderId)) {
                        $quoteItem->orderItem()->update(['product_amount'=>$updateQuoteItem['product_amount']]);
                    }elseif (($status_id < 2 && empty($orderId))) {
                        //added by ekta
                        $quoteItem->orderItem()->update(['product_amount'=>$updateQuoteItem['product_amount']]);
                    }
                } else {
                    //add
                    $quote_items = array(
                        'rfq_product_id' => $request->rfq_product_id[$key],
                        'quote_id' => $request->quote_id,
                        'quote_item_number' => 'BQTN-'.$request->quote_id.'/'.$j,
                        'supplier_id' => $request->has('supplier') ? $request->supplier : $SupplierId,
                        'product_id' => $request->product_id[$key],
                        'product_price_per_unit' => $request->price[$key],
                        'product_quantity' => $request->qty[$key],
                        'price_unit' => $request->unit_id[$key],
                        'product_amount' => $request->price[$key]*$request->qty[$key],
                        'min_delivery_days' => $request->minDays,
                        'max_delivery_days' => $request->maxDays,
                        'logistic_check' => (($check_logistic == 0 && auth()->user()->role_id == 1)? 1 : $check_logistic),
                        'logistic_provided' => (($check_logistic != 0)? 1 : 0),
                        'certificate' => $certificateFilePath,
                        'weights' => $request->weights[$key],
                        'dimensions' => $request->dimensions[$key] ?? 0,
                        'length' => $request->length[$key] ?? 1,
                        'width' => $request->width[$key] ?? 1,
                        'height' => $request->height[$key] ?? 1,
                        'pickup_service' => $request->pickup_service ?? null,
                        'pickup_fleet' => $request->pickup_fleet ?? null,
                        'logistics_service_code' => $request->logistics_service_code ?? null,
                        'insurance_flag' => 1,
                        'wood_packing' => $request->wood_packing ?? null
                    );
                    $quoteItem = QuoteItem::createOrUpdateQuoteItem($quote_items);
                    if (auth()->user()->role_id == 1 && $status_id==2 && !empty($orderId)) {
                        $orderItem = OrderItem::createOrUpdateOrderItem([
                            'order_id' => $orderId,
                            'quote_item_id'=>$quoteItem->id,
                            'order_item_status_id'=>null,
                            'product_amount'=>$quoteItem->product_amount,
                            'min_delivery_date'=>$order->min_delivery_date,
                            'max_delivery_date'=>$order->max_delivery_date,
                            'product_id'=>$quoteItem->product_id,
                            'rfq_product_id'=>$quoteItem->rfq_product_id,
                        ]);
                        $orderItem->order_item_number = 'BORN-'.$order->id.'/'.$j;
                        $orderItem->save();
                    } elseif (($status_id < 2 && empty($orderId))) {
                        $orderItem = OrderItem::createOrUpdateOrderItem([
                            'order_id' => $orderId,
                            'quote_item_id'=>$quoteItem->id,
                            'order_item_status_id'=>null,
                            'product_amount'=>$quoteItem->product_amount,
                            'min_delivery_date'=>$order->min_delivery_date,
                            'max_delivery_date'=>$order->max_delivery_date,
                            'product_id'=>$quoteItem->product_id,
                            'rfq_product_id'=>$quoteItem->rfq_product_id,
                        ]);
                        $orderItem->order_item_number = 'BORN-'.$order->id.'/'.$j;
                        $orderItem->save();
                    }
                    $j++;
                    $update_activity[$i]['key_name'] = 'add_product_value';
                    $update_activity[$i]['user_id'] = Auth::id();
                    $update_activity[$i]['quote_id'] = $request->quote_id;
                    $update_activity[$i]['old_value'] = '';
                    $update_activity[$i]['new_value'] = $request->rfq_product_id[$key];
                    $update_activity[$i]['user_type'] = User::class;
                    $update_activity[$i]['created_at'] = Carbon::now();
                    $i++;
                }
            }

        }

        $quote = Quote::find($request->quote_id);

        if(!$quote->full_quote_by && $check_logistic == 1){
            $quote->full_quote_by = auth()->user()->id;
        }
        elseif($quote->getUser->role_id != 3){
            $quote->full_quote_by = auth()->user()->id;
        }
        $quote->supplier_id = $request->has('supplier') ? $request->supplier : $SupplierId;
        $quote->valid_till = date('Y-m-d', strtotime($request->valid_till));
        $quote->final_amount = round($request->finalAmount, 2);
        $quote->tax = round($request->tax, 2);
        $quote->tax_value = round($request->tax_amount, 2);
        $quote->note = $request->note;
        $quote->supplier_final_amount = $calculte_amount['supplier_final_amount'];
        $quote->supplier_tex_value = $calculte_amount['supplier_tax_value'];
        $quote->address_name = $request->address_name;
        $quote->address_line_1 = $request->address_line_1;
        $quote->address_line_2 = $request->address_line_2 ? $request->address_line_2 :'';
        $quote->district = $request->district;
        $quote->sub_district = $request->sub_district;
        $quote->city = $request->cityId > 0 ? '' : $request->city;
        $quote->provinces = $request->stateId > 0 ? '' : $request->provinces;
        $quote->city_id =  $request->cityId;
        $quote->state_id =  $request->stateId;
        $quote->country_id = $request->countryId;
        $quote->pincode = $request->pincode;
        $quote->comment = $request->comment;
        $quote->termsconditions_file = $termsconditionsFilePath ? $termsconditionsFilePath : '';
        $quote->save();
        if (auth()->user()->role_id == 1 && $status_id==3 && $expDateUpdate==true){
            $quote->status_id = 1;
            $quote->save();
        }
        if (auth()->user()->role_id == 1 && $status_id==2 && !empty($orderId)) {
            if ($order->payment_amount!=$quote->final_amount) {
                $order->payment_amount = $quote->final_amount;
                $order->save();

                $activeInvoices = DB::table('order_transactions')
                    ->where(['order_id'=>$orderId,'status'=>'PENDING'])
                    ->get(['id','user_id','invoice_id']);

                $activeInvoices2 = getActiveBulkPaymentInvoices($orderId);

                $activeInvoices->merge($activeInvoices2);
                //expire old pending invoices
                foreach ($activeInvoices as $activeInvoice) {
                    dispatch(new SetExpireOnInvoice($activeInvoice));
                }
            }
        }
        $user = UserRfq::where('rfq_id', $quote->rfq_id)->pluck('user_rfqs.user_id')->first();
        if($check_logistic == 1)
        {
            $rfqDetailssms = Rfq::find($quote->rfq_id);
            $smsData['rfq_number'] = $rfqDetailssms->reference_number;
            $smsData['quote_number'] = 'BQTN-' . $request->quote_id;
            $sendMsg = $this->verify->sendMsg($rfqDetailssms->firstname,$rfqDetailssms->lastname,'quote_received',$rfqDetailssms->phone_code,$rfqDetailssms->mobile,$smsData);    
        }

        if (!empty($user)) {
            /*$userActivity = new UserActivity();
            $userActivity->user_id = $user;
            $userActivity->activity = 'Quote - ' . $quote->quote_number . ' Updated By Blitznet Team';
            $userActivity->type = 'quote';
            $userActivity->record_id = $quote->id;
            $userActivity->save();*/

            $commanData = [];
            if((int)Auth::user()->role_id == 1){
                $commanData = array('quote_number' => $quote->quote_number, 'updated_by' => 'Blitznet Team', 'rfq' => $quote->rfq_id, 'icons' => 'fa-check-square-o');
            }else{
                $commanData = array('quote_number' => $quote->quote_number, 'updated_by' => Auth::user()->full_name, 'rfq' => $quote->rfq_id, 'icons' => 'fa-check-square-o');
            }
            buyerNotificationInsert($user, 'Update Quote', 'buyer_update_quote', 'quote', $quote->id, $commanData);
            broadcast(new BuyerNotificationEvent());
        }

        if(!empty($request->payment_charges)) {
            $chareg_array = $request->charges;
            $chargeValue_array = $request->chargeValue;
            $charge_amount_array = $request->charge_amount;
            $charge_type_array = $request->chargeType;

            foreach ($request->payment_charges as $key => $value) {
                array_push($chareg_array, $value['charges']);
                array_push($chargeValue_array, $value['charge_value']);
                array_push($charge_amount_array, $value['charge_amount']);
                array_push($charge_type_array, $value['charge_type']);
            }
            $request->charges = $chareg_array;
            $request->chargeValue = $chargeValue_array;
            $request->charge_amount = $charge_amount_array;
            $request->chargeType = $charge_type_array;
        }
        if (isset($request->charges)) {
            //$quote_charges_remove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->whereNotIn('charge_id', array_filter($request->charges))->delete();
            if (auth()->user()->role_id == 3){
                $old_quote_charge = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_type', 0)->get()->toArray();
            } else {
                $old_quote_charge = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->get()->toArray();
            }
            if (count($old_quote_charge) > count($request->charges)) {
                foreach ($old_quote_charge as $key => $value) {
                    if(in_array($value['charge_id'], array_filter($request->charges))){
                        $new_val_key = array_search($value['charge_id'], $request->charges);
                        $update_charge = array('quote_id' => $request->quote_id, 'charge_id' => $value['charge_id'], 'charge_name' => $value['charge_name'], 'value_on' => $value['value_on'], 'addition_substraction' => $value['addition_substraction'], 'type' => $value['type'], 'charge_value' => round($request->chargeValue[$new_val_key], 2), 'charge_amount' => round($request->charge_amount[$new_val_key], 2), 'charge_type' => $value['charge_type'], 'custom_charge_name' => isset($request->custom_charge_name[$new_val_key]) ? $request->custom_charge_name[$new_val_key] : null);

                        if($value['charge_value'] != round($request->chargeValue[$new_val_key], 2)){
                            $new_message =  $value['charge_name'] .'-'. round($request->chargeValue[$new_val_key], 2) .' '. $request->chargeType[$new_val_key];
                            $old_message = $value['charge_name'] .'-'. $value['charge_value'].' '.($value['type'] == 0 ?'%' : 'RP (Flat)');
                            QuoteActivity::insert(array('key_name' => 'charges_updated', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                        }
                        if($value['charge_amount'] != round($request->charge_amount[$new_val_key], 2)){
                            $new_message =  $value['charge_name'] .'-'. round($request->charge_amount[$new_val_key], 2);
                            $old_message = $value['charge_name'] .'-'. $value['charge_amount'];
                            QuoteActivity::insert(array('key_name' => 'charges_updated', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                        }
                        if(!empty($value['custom_charge_name'])){
                            if($value['custom_charge_name'] != $request->custom_charge_name[$new_val_key]){
                                $new_message =  $value['charge_name'] .'-'. $request->custom_charge_name[$new_val_key];
                                $old_message = $value['charge_name'] .'-'. $value['custom_charge_name'];
                                QuoteActivity::insert(array('key_name' => 'charges_updated', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                            }
                        }

                        QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $value['charge_id'])->updateOrCreate(['charge_id' => $value['charge_id'], 'quote_id' => $request->quote_id], $update_charge);
                    } else {
                        $quote_charges_remove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $value['charge_id'])->delete();
                        if ($quote_charges_remove){
                            $new_message =  $value['charge_name'];
                            $old_message = $value['charge_name'] .' '. ($value['type'] == 0 ?'%' : 'RP (Flat)') .' '. $value['charge_value'] .' '. $value['charge_amount'];
                            QuoteActivity::insert(array('key_name' => 'charges_deleted', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                        } else {
                            $new_message =  $value['charge_name'];
                            $old_message = '';
                            $new_val_key = array_search($value['charge_id'], $request->charges);
                            $update_charge = array('quote_id' => $request->quote_id, 'charge_id' => $value['charge_id'], 'charge_name' => $value['charge_name'], 'value_on' => $value['value_on'], 'addition_substraction' => $value['addition_substraction'], 'type' => $value['type'], 'charge_value' => round($request->chargeValue[$new_val_key], 2), 'charge_amount' => round($request->charge_amount[$new_val_key], 2), 'charge_type' => $value['charge_type'], 'custom_charge_name' => isset($request->custom_charge_name[$new_val_key]) ? $request->custom_charge_name[$new_val_key] : null);
                            QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $value['charge_id'])->updateOrCreate(['charge_id' => $value['charge_id'], 'quote_id' => $request->quote_id], $update_charge);
                            QuoteActivity::insert(array('key_name' => 'charges_added', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                        }
                    }
                }
            } else {
                foreach ($request->charges as $key => $value) {
                    if (empty($value)) {
                        if (auth()->user()->role_id == 3){
                            $quote_charges_remove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_type', 0)->whereNotIn('charge_id', array_filter($request->charges))->get();
                        } else {
                            $quote_charges_remove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->whereNotIn('charge_id', array_filter($request->charges))->get();
                        }
                        if ($quote_charges_remove) {
                            foreach ($quote_charges_remove as $remove) {
                                $chargeRemove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $remove->charge_id)->delete();
                                if ($chargeRemove) {
                                    $new_message = $remove->charge_name;
                                    $old_message = $remove->charge_name . ' ' . ($remove->type == 0 ? '%' : 'RP (Flat)') . ' ' . $remove->charge_value . ' ' . $remove->charge_amount;
                                    QuoteActivity::insert(array('key_name' => 'charges_deleted', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                                }
                            }
                        }
                        continue;
                    } else {
                        /* this comment code is check fututr reference*/
                        //$charge_details = OtherCharge::find($value);
                        $charge_details = OtherCharge::with(['company' => function ($q) use($company_id) {
                            $q->where(['is_delete' => 0, 'company_id' => $company_id])->select(['charge_id', 'company_id']);
                        }])
                            ->with(['xenditCommisionFee'=>function($q) use($company_id){
                                $q->where(['company_id' => $company_id, 'is_delete'=>0])->select(['id','charge_id', 'company_id','type','charges_value','is_delete','charges_type','addition_substraction']);
                            }])
                            ->where(['id' => $value])->first();

                        if (auth()->user()->role_id == 3){
                            $current_charges = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_type', 0)->where('charge_id', $value)->first();
                            $quote_charges_remove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_type', 0)->whereNotIn('charge_id', array_filter($request->charges))->get();
                        } else {
                            $current_charges = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $value)->first();
                            $quote_charges_remove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->whereNotIn('charge_id', array_filter($request->charges))->get();
                        }
                        if ($quote_charges_remove) {
                            foreach ($quote_charges_remove as $remove) {
                                $chargeRemove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $remove->charge_id)->delete();
                                if ($chargeRemove) {
                                    $new_message = $remove->charge_name;
                                    $old_message = $remove->charge_name . ' ' . ($remove->type == 0 ? '%' : 'RP (Flat)') . ' ' . $remove->charge_value . ' ' . $remove->charge_amount;
                                    QuoteActivity::insert(array('key_name' => 'charges_deleted', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                                }
                            }
                        }
                        $addition_substraction = empty($charge_details->xenditCommisionFee) ? $charge_details['addition_substraction'] : $charge_details->xenditCommisionFee['addition_substraction'];
                        $type = empty($charge_details->xenditCommisionFee) ? $charge_details['type'] : $charge_details->xenditCommisionFee['type'];
                        if (in_array($value, array_column($old_quote_charge, 'charge_id'))) {
                            $update_charge = array('quote_id' => $request->quote_id, 'charge_id' => $value, 'charge_name' => $charge_details['name'], 'value_on' => $charge_details['value_on'], 'addition_substraction' => $addition_substraction, 'type' => $type, 'charge_value' => round($request->chargeValue[$key], 2), 'charge_amount' => round($request->charge_amount[$key], 2), 'charge_type' => $charge_details['charges_type'], 'custom_charge_name' => isset($request->custom_charge_name[$key])?$request->custom_charge_name[$key]:null);

                            if($current_charges['charge_value'] != round($request->chargeValue[$key], 2)){
                                $new_message =  $charge_details['name'] .'-'. round((float)$request->chargeValue[$key], 2) .''. ($request->chargeType[$key] == 0 ?'%' : 'RP (Flat)');
                                $old_message = $charge_details['name'] .'-'. $current_charges['charge_value'] .''.($current_charges['type'] == 0 ?'%' : 'RP (Flat)');
                                QuoteActivity::insert(array('key_name' => 'charges_updated', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                            }
                            if($current_charges['charge_amount'] != round($request->charge_amount[$key], 2)){
                                $new_message =  $charge_details['name'] .'-'. round((float)$request->chargeValue[$key], 2);
                                $old_message = $charge_details['name'] .'-'. $current_charges['charge_amount'];
                                QuoteActivity::insert(array('key_name' => 'charges_updated', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                            }
                            if(isset($request->custom_charge_name[$key]) && $current_charges['custom_charge_name'] != $request->custom_charge_name[$key]){
                                $new_message =  $charge_details['name'] .'-'. $request->custom_charge_name[$key];
                                $old_message = $charge_details['name'] .'-'. $current_charges['custom_charge_name'];
                                QuoteActivity::insert(array('key_name' => 'charges_updated', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                            }
                            if(($request->payment_type ==3 || $request->payment_type == 4) && ($check_logistic == 1 && Auth::user()->role_id == 3 && $charge_details['charges_type']>0))
                            {
                                $update_charge = array('quote_id' => $request->quote_id, 'charge_id' => $value, 'charge_name' => $charge_details['name'], 'value_on' => $charge_details['value_on'], 'addition_substraction' => $addition_substraction, 'type' => $type, 'charge_value' => 0, 'charge_amount' => 0, 'charge_type' => $charge_details['charges_type'], 'custom_charge_name' => isset($request->custom_charge_name[$key])?$request->custom_charge_name[$key]:null);
                            }
                            QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $value)->updateOrCreate(['charge_id' => $value, 'quote_id' => $request->quote_id], $update_charge);
                        } else {
                            if (auth()->user()->role_id == 3){
                                $quote_charges_remove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_type', 0)->where('charge_id', $value)->delete();
                            } else {
                                $quote_charges_remove = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $value)->delete();
                            }
                            if ($quote_charges_remove){
                                $new_message =  $charge_details['name'];
                                $old_message = $charge_details['name'] .' '. ($current_charges['type'] == 0 ?'%' : 'RP (Flat)') .' '. $current_charges['charge_value'] .' '. $current_charges['charge_amount'];
                                QuoteActivity::insert(array('key_name' => 'charges_deleted', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                            } else {
                                $new_message =  $charge_details['name'];
                                $old_message = '';
                                $update_charge = array('quote_id' => $request->quote_id, 'charge_id' => $value, 'charge_name' => $charge_details['name'], 'value_on' => $charge_details['value_on'], 'addition_substraction' => $addition_substraction, 'type' => $type, 'charge_value' => round($request->chargeValue[$key], 2), 'charge_amount' => round($request->charge_amount[$key], 2), 'charge_type' => $charge_details['charges_type'], 'custom_charge_name' => isset($request->custom_charge_name[$key]) ? $request->custom_charge_name[$key] : null);
                                if(($request->payment_type ==3 || $request->payment_type == 4) && ($check_logistic == 1 && Auth::user()->role_id == 3 && $charge_details['charges_type'] > 0))
                                {
                                    $update_charge = array('quote_id' => $request->quote_id, 'charge_id' => $value, 'charge_name' => $charge_details['name'], 'value_on' => $charge_details['value_on'], 'addition_substraction' => $addition_substraction, 'type' => $type, 'charge_value' => 0, 'charge_amount' => 0, 'charge_type' => $charge_details['charges_type'], 'custom_charge_name' => isset($request->custom_charge_name[$key]) ? $request->custom_charge_name[$key] : null);
                                }
                                QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_id', $value)->updateOrCreate(['charge_id' => $value, 'quote_id' => $request->quote_id], $update_charge);
                                QuoteActivity::insert(array('key_name' => 'charges_added', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                            }
                        }
                    }
                }
            }
        }
        $check_logistic_values = $old_quote_charge = QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_type', 1)->get()->toArray();

        if (($status_id == 5 && count($check_logistic_values) > 0) || ($status_id == 5 && isset($request->logistic_check) && $request->logistic_check != 0)){
            $request['rfq_id'] = $quote['rfq_id'];
            $this->quoteToBuyer($request->all(), $quote);
            $quote->status_id = 1;
            $quote->save();
            QuoteItem::where('quote_id', $request->quote_id)->update(['logistic_check' => 1]);
        }
        if (count($check_logistic_values) > 0 && isset($request->logistic_check) && $request->logistic_check != 0){
            QuoteChargeWithAmount::where('quote_id', $request->quote_id)->where('charge_type', 1)->update(['is_delete' => 1]);
        }
        if ($update_activity) {
            $quote_track = QuoteActivity::insert($update_activity);
            $this->adminQuotwUpdateNotificationChange($request->quote_id);
        }

        /**begin: system log**/
        $quote->bootSystemActivities();
        /**end: system log**/
        return redirect('admin/quotes');
    }

    public function adminQuotwUpdateNotificationChange($quote_id){
        if (Auth::user()->role_id == Role::ADMIN){
            $sendAdminNotification[] = array('user_id' => Auth::user()->id, 'admin_id' => Auth::user()->id, 'user_activity' => 'Edit Quote', 'translation_key' => 'quote_edit_notification', 'notification_type' => 'quote', 'notification_type_id'=> $quote_id, 'created_at' => Carbon::now());
            Notification::insert($sendAdminNotification);
        } else if (Auth::user()->role_id == Role::BUYER || Auth::user()->role_id == Role::SUPPLIER){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $getAllAdmin = getAllAdmin();
            $sendAdminNotification = [];
            if (!empty($getAllAdmin)){
                foreach ($getAllAdmin as $key => $value){
                    $sendAdminNotification[] = array('user_id' => Auth::user()->id, 'admin_id' => $value, 'user_activity' => 'Edit Quote', 'translation_key' => 'quote_edit_notification', 'notification_type' => 'quote', 'notification_type_id'=> $quote_id,'created_at' => Carbon::now());
                }
                Notification::insert($sendAdminNotification);
            }
            $sendSupplierNotification = array('user_id' => Auth::user()->id, 'supplier_id' => Auth::user()->id, 'user_activity' => 'Edit Quote', 'translation_key' => 'quote_edit_notification', 'notification_type' => 'quote', 'notification_type_id'=> $quote_id,'created_at' => Carbon::now());
            Notification::insert($sendSupplierNotification);
        }
        broadcast(new rfqsEvent());
    }

    function downloadCertificate(Request $request)
    {
        $image = QuoteItem::where('id', $request->id)->pluck($request->fieldName)->first();
        if (!empty($image)) {
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $image, '', $headers);
            //return response()->download(public_path('storage/'.$image));
        }
        return response()->json(array('success' => false));
    }

    function quoteActivity($id)
    {

        $quoteActivity = QuoteActivity::where('quote_id', $id)->orderBy('id', 'DESC')->get();
        $quote = Quote::where('id', $id)->where('is_deleted', '0')->first();
        $user = User::where('id', $quote->user_id)->first();
        $countries= CountryOne::pluck('name', 'id')->toArray();
        $states = State::pluck('name', 'id')->toArray();
        $cities = City::pluck('name', 'id')->toArray();

        $activityhtml = view('admin/quote/quoteactivities', ['quoteActivies' => $quoteActivity, 'quote' => $quote, 'user' => $user, 'countries' => $countries, 'states' => $states, 'cities' => $cities])->render();
        return response()->json(array('success' => true, 'activityhtml' => $activityhtml));
    }

    function removeCertificate(Request $request){
        $quote = QuoteItem::find($request->id);
        $quote->certificate = '';
        $quote->save();
        QuoteActivity::insert(array('key_name' => 'certificate', 'user_id' => Auth::id(), 'quote_id' => $request->quote_id, 'old_value' => Str::substr($request->filePath, stripos($request->filePath, 'certificate_') + 12), 'new_value' => '', 'user_type' => User::class, 'created_at' => Carbon::now()));
        Storage::delete('/public/' . $request->filePath);
        return response()->json(array('success' => true));
    }
    function removeTermsConditionFile(Request $request){
        $quote = Quote::find($request->id);
        $filePath = $quote->termsconditions_file;
        $quote->termsconditions_file = '';
        $quote->save();
        QuoteActivity::insert(array('key_name' => 'termsconditions_file', 'user_id' => Auth::id(), 'quote_id' => $request->id, 'old_value' => Str::substr($filePath, stripos($filePath, 'termsconditions_file_') + 21), 'new_value' => '', 'user_type' => User::class, 'created_at' => Carbon::now()));
        Storage::delete('/public/' . $filePath);
        return response()->json(array('success' => true));
    }

    function quoteToBuyer($request, $quote){

        $rfq = Rfq::find($request['rfq_id']);
        $rfq->status_id = 2;
        $rfq->save();
        $user = DB::table('user_rfqs')
            ->where('rfq_id', $request['rfq_id'])
            ->get(['user_rfqs.user_id']);
        if (count($user) && $user[0]->user_id) {
            $commanData = [];
            if((int)Auth::user()->role_id == 1){
                $commanData = array('quote_number' => $quote['quote_number'], 'updated_by' => 'blitznet team', 'rfq' => $request['rfq_id'], 'icons' => 'fa-check-square-o');
            }else{
                $commanData = array('quote_number' => $quote['quote_number'], 'updated_by' => Auth::user()->full_name, 'rfq' => $request['rfq_id'], 'icons' => 'fa-check-square-o');
            }

            buyerNotificationInsert($user[0]->user_id, 'Quote Create', 'buyer_create_quote', 'quote', $quote['id'], $commanData);
            broadcast(new BuyerNotificationEvent());
        }

        $quoteId = $quote['id'];
        $quoteData = DB::table('quotes')
            ->join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('units', 'units.id', '=', 'rfq_products.unit_id')
            ->join('suppliers', 'suppliers.id', '=', 'quote_items.supplier_id')
            ->where('quotes.id', $quoteId)
            ->get(['rfqs.firstname', 'rfqs.lastname', 'rfqs.reference_number', 'rfqs.email', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_products.quantity', 'units.name as unit_name', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email']);
        $quoteItems = QuoteItem::where('quote_id', $quoteId)->get()->toArray();
        $quoteMailData = [
            'quote' => $quoteData[0],
            'quote_item' => $quoteItems,
            // 'pdf' => public_path($path),
        ];
        try {
            dispatch(new SendQuoteToBuyerEmailJob($quoteMailData, $quoteData[0]->email));
            dispatch(new SendQuoteToSupplierEmailJob($quoteMailData, $quoteData[0]->supplier_email));
            /*$ccUsers = \Config::get('static_arrays.bccusers');
            Mail::to($quoteData[0]->email)->bcc($ccUsers)->send(new SendQuoteToBuyer($quoteMailData));*/
        } catch (\Exception $e) {
            echo 'Error - ' . $e;
        }
    }

    function calculateSupplierAmount($data){
        $calculate_supplier_final_amount = 0;
        $calculate_supplier_tax_value = 0;
        $finalAmount = $data['amount'];
        $discount = 0;
        $newAmount = $data['amount'];

        //'inclusive_tax_other' => $request->inclusive_tax_other??0,
        //'inclusive_tax_logistic' => $request->inclusive_tax_logistic??0,

        foreach ($data['charges'] as $key =>$charges){
            if (empty($charges)){
                continue;
            }
            $other_charge = OtherCharge::find($charges);
            if (!empty($other_charge) && $other_charge->charges_type != 0){
                continue;
            }
            if ($charges != 3) {
                if ($other_charge->addition_substraction == 0) {
                    $finalAmount = $finalAmount - $data['charge_amount'][$key];

                } else {
                    $finalAmount = $finalAmount +  $data['charge_amount'][$key];
                }
                if(!isset($data['inclusive_tax_other']))
                {
                    if($other_charge->addition_substraction == 0){
                        $newAmount = $newAmount -  $data['charge_amount'][$key];
                    }
                    else{
                        $newAmount = $newAmount + $data['charge_amount'][$key];
                    }
                }
            }
            else {
                $discount = $data['charge_amount'][$key];
            }
        }
        $totalAmount = $finalAmount - $discount;
        $newTaxAmount = $newAmount - $discount;
        $taxamount = ($newTaxAmount * $data['tax']) / 100;
        $calculate_supplier_final_amount = $totalAmount + $taxamount;
        $calculate_supplier_tax_value = $taxamount;

        return ['supplier_final_amount' => $calculate_supplier_final_amount, 'supplier_tax_value' => $calculate_supplier_tax_value];
    }

    public function adminQuoteNotification($quote_id, $supplier_id = 0){
        if (Auth::user()->role_id == Role::ADMIN){
            $sendAdminNotification[] = array('user_id' => Auth::user()->id, 'admin_id' => Auth::user()->id, 'user_activity' => 'Create Quote', 'translation_key' => 'quote_create_notification', 'notification_type' => 'quote', 'notification_type_id'=> $quote_id);
            Notification::insert($sendAdminNotification);
            $sendSupplierNotification = array('user_id' => Auth::user()->id, 'supplier_id' => $supplier_id, 'user_activity' => 'Create Quote', 'translation_key' => 'quote_create_notification', 'notification_type' => 'quote', 'notification_type_id'=> $quote_id);
            Notification::insert($sendSupplierNotification);
        } else if(Auth::user()->role_id == Role::BUYER || Auth::user()->role_id == Role::SUPPLIER) {
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $getAllAdmin = getAllAdmin();
            $sendAdminNotification = [];
            if (!empty($getAllAdmin)){
                foreach ($getAllAdmin as $key => $value){
                    $sendAdminNotification[] = array('user_id' => auth()->user()->id, 'admin_id' => $value, 'user_activity' => 'Create Quote', 'translation_key' => 'quote_create_notification', 'notification_type' => 'quote', 'notification_type_id'=> $quote_id);
                }
                Notification::insert($sendAdminNotification);
            }
            $sendSupplierNotification = array('user_id' => Auth::user()->id, 'supplier_id' => $supplier_id, 'user_activity' => 'Create Quote', 'translation_key' => 'quote_create_notification', 'notification_type' => 'quote', 'notification_type_id'=> $quote_id);
            Notification::insert($sendSupplierNotification);
        }
        broadcast(new rfqsEvent());
    }

    /**
     * Get Quote Activity of JNE
     *
     * @return array|mixed
     */
    public function getJneActivityQuote()
    {
        $user = User::where('role_id', Role::JNE)->first();
        $quoteId = SystemActivity::getActivityAttribute($user, Quote::class, SystemActivity::UPDATED);

        return !empty($quoteId) ? $quoteId : [];
    }

    /**
     * Loan amount exceeded or not
     *
     * @param $id
     * @param $amount
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkLoanOrderAmountExceed(Request $request)
    {
        try {
            $id = $request->id;

            $amount = $request->amount;
            Log::critical('Code - 400 | ErrorCode:B003 Request checking'.  json_encode($request));

            $order = Order::with('loanApply','loanTransactions','quote')->whereHas('quote',function ($query) use($id){
                return $query->where('id', $id);
            })->first();

            if ($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']) {

                $loanAmount = (float)$order->quote->final_amount;

                if ($amount > $loanAmount) {

                    return response()->json(['success' => true, 'message' => __('admin.loan_amount_should_not_exceed',['amount' => 'Rp '.number_format($loanAmount)])]);

                }

            }
        } catch (\Exception $exception) {
            Log::critical('Code - 503 | ErrorCode:B003 Loan Amount Checking');

            return response()->json(['success' => false, 'message' => '']);

        }
        return response()->json(['success' => false, 'message' => '']);

    }
}