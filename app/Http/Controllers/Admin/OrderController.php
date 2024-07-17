<?php
//
namespace App\Http\Controllers\Admin;

use App\Events\BuyerNotificationEvent;
use App\Events\LoanEvent;
use App\Events\rfqsEvent;
use App\Http\Controllers\Admin\Loan\LoanController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Credit\KoinWorks\KoinWorkController;
use App\Http\Controllers\QuoteController;
use App\Jobs\SendOrderItemStatusMailToBuyerJob;
use App\Jobs\SendOrderItemStatusMailToSupplierJob;
use App\Jobs\SendOrderStatusMailToBuyerJob;
use App\Jobs\SendOrderStatusMailToSupplierJob;
use App\Jobs\SendPoToBuyerJob;
use App\Jobs\SendPoToSupplierJob;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Payment\XenditController;
use App\Models\AirWayBillNumber;
use App\Models\Category;
use App\Models\LoanProvider;
use App\Models\LoanProviderApiResponse;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderActivity;
use App\Models\OrderCreditDays;
use App\Models\OrderItem;
use App\Models\OrderItemStatus;
use App\Models\OrderItemTracks;
use App\Models\OrderPo;
use App\Models\OrderStatus;
use App\Models\OrderTrack;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Rfq;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserAddresse;
use App\Models\UserQuoteFeedback;
use App\Models\UserSupplier;
use App\Models\AdminFeedbackReasons;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use URL;
use Mail;
use PDF;
use App\Export\OrderExport;
use App\Models\LoanApply;
use App\Models\CountryOne;
use App\Models\OrderBatch;
use App\Models\State;
use App\Models\SupplierAddress;
use App\Models\SystemActivity;
use App\Models\Company;
use App\Models\RfqProduct;
use Illuminate\Support\Arr;
use App\Services\NotificationService;

class OrderController extends Controller
{
    /**
    define $service variable;
    */
     protected $verify;
    protected $service;
    /**
     * Developed by Munir
     * Filter related changes by Ronak Bhabhor
     * 20/04/2022
     * index: get order list based on supplier and admin
     */

    public function __construct()
    {
        $this->middleware('permission:create order list|edit order list|delete order list|publish order list|unpublish order list', ['only'=> ['index']]);
        $this->middleware('permission:create order list', ['only' => ['listAjax', 'create']]);
        $this->middleware('permission:edit order list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete orders list', ['only' => ['delete']]);
        $this->service = new NotificationService;
        $this->destinationPath = config('settings.order_document_upload');
        $this->verify = app('App\Twilloverify\TwilloService');
    }


    public function index1(Request $request)
    {
        $condition = [];
        if($request->ajax()){
            if($request->rfq_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.reference_number', 'value' => $request->rfq_ids]);
            }
            if($request->quote_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.quote_number', 'value' => $request->quote_ids]);
            }
            if($request->order_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'orders.order_number', 'value' => $request->order_ids]);
            }
            if($request->supp_company_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'suppliers.id', 'value' => $request->supp_company_ids]);
            }
            if($request->product_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'products.id', 'value' => $request->product_ids]);
            }
            if($request->cust_company_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'companies.id', 'value' => $request->cust_company_ids]);
            }
            if($request->category_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'categories.id', 'value' => $request->category_ids]);
            }
            if($request->payment){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'orders.is_credit', 'value' => $request->payment]);
            }
            if($request->status_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'order_status.id', 'value' => $request->status_ids]);
            }
            if($request->min_price && $request->max_price){
                if (auth()->user()->role_id == 3){
                    array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'quotes.supplier_final_amount', 'value' => [$request->min_price,  $request->max_price] ]);
                }else{
                    array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'quotes.final_amount', 'value' => [$request->min_price,  $request->max_price] ]);
                }
            }

            if($request->start_date && $request->end_date){
                $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d 00:00:00');
                $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d 23:59:59');
                array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'orders.created_at', 'value' => [$start_date,  $end_date] ]);
            }
        }

        $select_columns = [];
        if (auth()->user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $orders = DB::table('orders')
                ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
                ->join('order_status', 'orders.order_status', '=', 'order_status.id')
                ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'orders.supplier_id', '=', 'suppliers.id')
                ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('user_companies', 'orders.user_id', '=', 'user_companies.user_id')
                ->join('companies', 'orders.company_id', '=', 'companies.id')
                ->where('suppliers.id', $supplier_id);

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $orders = $orders->whereIn($value['column_name'], $value['value']);
                    }

                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $orders = $orders->whereBetween($value['column_name'], $value['value']);
                    }

                }
            }

            $select_columns = ['orders.*', 'ocd.request_days', 'rfqs.id as rfq_id', 'ocd.approved_days', 'order_status.name as order_status_name', 'quotes.quote_number', 'quotes.final_amount', 'rfqs.reference_number', 'companies.name as company_name', 'suppliers.name as supplier_company_name', 'quotes.supplier_final_amount'];
            $whereNotification = ['supplier_id' => $supplier_id, 'admin_id' => 0, 'user_activity' => 'Place Order', 'notification_type' => 'order', 'side_count_show' => 0];
            Notification::where($whereNotification)->update(['side_count_show' => 1]);
        } else {
            $orders = DB::table('orders')
                ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
                ->join('order_status', 'orders.order_status', '=', 'order_status.id')
                ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'orders.supplier_id', '=', 'suppliers.id')
                ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('user_companies', 'orders.user_id', '=', 'user_companies.user_id')
                ->join('companies', 'orders.company_id', '=', 'companies.id');

            //Agent category permission
            if (Auth::user()->hasRole('agent')) {

                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                $orders = $orders->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
                ->whereIn('rfq_products.category_id', $assignedCategory);

            }

            //Jne logistic added quote will be display
            if (Auth::user()->hasRole('jne')) {
                $orders->whereIn('quotes.id',(new QuoteController())->getJneActivityQuote());
            }

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $orders = $orders->whereIn($value['column_name'], $value['value']);
                    }

                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $orders = $orders->whereBetween($value['column_name'], $value['value']);
                    }

                }
            }

            $select_columns = ['orders.*', 'ocd.request_days', 'rfqs.id as rfq_id', 'ocd.approved_days', 'order_status.name as order_status_name', 'quotes.quote_number', 'quotes.final_amount', 'rfqs.reference_number', 'companies.name as company_name', 'suppliers.name as supplier_company_name'];
            $whereNotification = ['admin_id' => Auth::user()->id, 'supplier_id' => 0, 'user_activity' => 'Place Order', 'notification_type' => 'order', 'side_count_show' => 0];
            Notification::where($whereNotification)->update(['side_count_show' => 1]);
        }

        /* dynamic filter field values */
        $rfq_number_obj = clone $orders;
        $rfq_numbers = $rfq_number_obj
            ->groupBy('rfqs.id')
            ->get(['orders.id','rfqs.id', 'rfqs.reference_number']);

        $quote_number_obj = clone $orders;
        $quotes_numbers = $quote_number_obj
            ->groupBy('quotes.id')
            ->get(['orders.id','quotes.id','quotes.quote_number']);

        $order_number_obj = clone $orders;
        $order_numbers = $order_number_obj
            ->groupBy('orders.id')
            ->get(['orders.id','orders.order_number'])->toArray();

        $supplier_companies_obj = clone $orders;
        $supplier_companies = $supplier_companies_obj
            ->orderBy('suppliers.name', 'asc')
            ->groupBy('suppliers.id')
            ->get(['suppliers.id', 'suppliers.name']);

        $customer_companies_obj = clone $orders;
        $customer_companies = $customer_companies_obj
            ->orderBy('companies.name', 'asc')
            ->groupBy('companies.id')
            ->get(['companies.id', 'companies.name']);

        $status_obj = clone $orders;
        $status = $status_obj
            ->groupBy('order_status.id')
            ->get(['order_status.id', 'order_status.name']);

        $price_range = []; // need to change based on order list in admin/supplier
        $payment_obj = clone $orders;
        if (auth()->user()->role_id == 3){
            $price_min = $payment_obj->min('supplier_final_amount');
            $price_max = $payment_obj->max('supplier_final_amount');
            $price_range = ['min' => (int) $price_min, 'max' => (int) $price_max];
        }else{
            $price_min = $payment_obj->min('final_amount');
            $price_max = $payment_obj->max('final_amount');
            $price_range = ['min' => (int) $price_min, 'max' => (int) $price_max];
        }

        $categories = [];
        $products = []; // need to discuss
        /* dynamic filter field values */

        $orders = $orders->orderBy('id', 'desc')->groupBy('orders.id')->get($select_columns);

        foreach ($orders as $order){
            $orderItems = DB::table('order_items')->where('order_id',$order->id)->get();
            $order->product = $orderItems->count().' '.__('admin.products');
            if ($orderItems->count()==1){
                $order->product = get_product_name_by_id($orderItems[0]->rfq_product_id,1);
            }
        }

        $orderDataHtml = view('admin/order/orderTableData', ['orders' => $orders])->render();
        if($request->ajax()){
            return $orderDataHtml;
        }
        /**begin: system log**/
        Order::bootSystemView(new Order());
        /**end:  system log**/
        $getFeedbackReasions = AdminFeedbackReasons::where('reasons_type', 3)->get();
        return view('admin/order/index', ['orders' => $orders,'orderDataHtml' => $orderDataHtml, 'rfq_numbers' => $rfq_numbers, 'quotes_numbers' => $quotes_numbers, 'order_numbers' => $order_numbers, 'supplier_companies' => $supplier_companies, 'products' => $products, 'customer_companies' => $customer_companies, 'categories' => $categories, 'status' => $status, 'price_range' => $price_range, 'feedbackReasions' => $getFeedbackReasions]);
    }
    public function index(Request $request)
    {
        $supplier_id=0;
        $admin_id=0;
        $authUser = Auth::user();
        if ($authUser->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $orders = Order::with('orderCreditDay','orderStatus','quote','rfq','supplier','companyDetails')->where('orders.supplier_id',$supplier_id);
        } else {
            $admin_id = $authUser->id;
            $orders = Order::with('orderCreditDay','orderStatus','quote','rfq','supplier','companyDetails');
            //Agent category permission
            if ($authUser->hasRole('agent')) {
                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
                $orders->whereHas('rfqProduct', function($orders) use($assignedCategory){
                        $orders->whereIn('category_id', $assignedCategory);
                    });
            }
            //Jne logistic added quote will be display
            if ($authUser->hasRole('jne')) {
                $orders->whereHas('quote', function($orders){
                    $orders->whereIn('quotes.id',(new QuoteController())->getJneActivityQuote());
                });
                //$orders->whereIn('quotes.id',(new QuoteController())->getJneActivityQuote());
            }
        }
        $whereNotification = ['admin_id' => $admin_id, 'supplier_id' => $supplier_id, 'user_activity' => 'Place Order', 'notification_type' => 'order', 'side_count_show' => 0];
            Notification::where($whereNotification)->update(['side_count_show' => 1]);
        if($request->ajax())
        {
            $draw               = $request->get('draw');
            $start              = $request->get("start");
            $length             = $request->get("length");
            $sort               = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
            $search             = !empty($request->get('search')) ? $request->get('search')['value'] : '';

            $columnIndex_arr    = $request->get('order');
            $columnName_arr     = $request->get('columns');
            $columnIndex        = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
            $column             = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';


            if ($search) {
                $orders->Where(function($q) use($search){
                    $q->orWhereHas('rfq', function($orders) use($search){
                        $orders->where('reference_number', 'LIKE',"%$search%")
                        ->orWhere('is_require_credit' , 'LIKE', "%$search%");
                    });
                    $q->orWhereHas('rfqProduct', function($orders) use($search){
                        $orders->where('product', 'LIKE',"%$search%");
                    });
                    $q->orWhereHas('quote', function($orders) use($search){
                        $orders->where('quote_number', 'LIKE',"%$search%")
                            ->orwhere('final_amount', 'LIKE',"%$search%")
                            ->orWhere('supplier_final_amount', 'LIKE',"%$search%");
                    });
                    $q->orWhereHas('supplier', function($orders) use($search){
                        $orders->where('name', 'LIKE',"%$search%");
                    });
                    $q->orWhereHas('orderStatus', function($orders) use($search){
                        $orders->where('name', 'LIKE',"%$search%");
                    });
                    $q->orWhereHas('companyDetails', function($orders) use($search){
                        $orders->where('name', 'LIKE',"%$search%");
                    });
                    $q->orWhere('orders.created_at' , 'LIKE', "%$search%")
                     ->orWhere('orders.is_credit' , 'LIKE', "%$search%")
                     ->orWhere('order_number' , 'LIKE', "%$search%");
                });
            }
            if ($request->filterData) {
                $filterData = $request->filterData;
                if (Arr::exists($filterData, 'rfq_ids')) {
                    $orders->whereIn('rfq_id', $filterData['rfq_ids']);
                }
                if (Arr::exists($filterData, 'quote_ids')) {
                    $orders->whereHas('quote', function($orders) use($filterData){
                        $orders->whereIn('quote_number',$filterData['quote_ids']);
                    });
                }
                if (Arr::exists($filterData, 'order_ids')) {
                    $orders->whereIn('order_number', $filterData['order_ids']);
                }
                if (Arr::exists($filterData, 'cust_company_ids')) {
                    $orders->whereHas('companyDetails', function($orders) use($filterData){
                        $orders->whereIn('companies.id',$filterData['cust_company_ids']);
                    });
                }
                if (Arr::exists($filterData, 'supp_company_ids')) {
                    $orders->whereHas('supplier', function($orders) use($filterData){
                        $orders->whereIn('suppliers.id',$filterData['supp_company_ids']);
                    });
                }
                if (Arr::exists($filterData, 'payment')) {
                    $orders->whereIn('is_credit', $filterData['payment']);
                }
                if (Arr::exists($filterData, 'status_ids')) {
                    $orders->whereHas('orderStatus', function($orders) use($filterData){
                        $orders->whereIn('order_status.id',$filterData['status_ids']);
                    });
                }
                if (Arr::exists($filterData, 'min_price') && Arr::exists($filterData, 'max_price')) {
                    if ($authUser->role_id == 3){
                        $orders->whereHas('quote', function($orders) use($filterData){
                            $orders->whereBetween('supplier_final_amount', [$filterData['min_price'],$filterData['max_price']]);
                        });
                    }else{
                        $orders->whereHas('quote', function($orders) use($filterData){
                            $orders->whereBetween('final_amount', [$filterData['min_price'],$filterData['max_price']]);
                        });
                    }
                }

                if (Arr::exists($filterData, 'start_date') && Arr::exists($filterData, 'end_date')) {
                    $start_date = Carbon::createFromFormat('d-m-Y', $filterData['start_date'])->format('Y-m-d 00:00:00');
                    $end_date = Carbon::createFromFormat('d-m-Y', $filterData['end_date'])->format('Y-m-d 23:59:59');
                    $orders->whereBetween('created_at', [$start_date, $end_date]);
                }
            }

            $orderCount = clone $orders;

            if ($column == 'id') {
                $orders = $orders->orderBy('orders.id', 'desc')->groupBy('orders.id');
            }else{
                if($column == 'referenceNumber'){
                    $orders->orderBy('rfq_id', $sort);
                }
                if($column == 'quoteNumber'){
                    $orders->orderBy('quote_id', $sort);
                }
                if($column == 'orderNumber'){
                    $orders->orderBy('id', $sort);
                }
                if($column == 'tot_price'){
                    if($authUser->role_id == 3){
                            $orders->orderBy(Quote::select('supplier_final_amount')->whereColumn('orders.quote_id', 'quotes.id'),$sort);
                    }
                    else{
                        $orders->orderBy(Quote::select('final_amount')->whereColumn('orders.quote_id', 'quotes.id'),$sort);
                    }
                }
                if($column == 'payment_terms'){
                    $orders->orderBy('is_credit',$sort);
                }
                if($column == 'company_name'){
                    $orders->orderBy(Company::select('name')->whereColumn('orders.company_id', 'companies.id'),$sort);
                }
                if($column == 'supplier_company_name'){
                    $orders->orderBy(Supplier::select('name')->whereColumn('orders.supplier_id', 'suppliers.id'),$sort);
                }
                if($column == 'product'){
                    $orders->orderBy(RfqProduct::select('product')->whereColumn('orders.rfq_id','rfq_products.rfq_id')->limit(1),$sort);
                }
                if($column == 'createdAt'){
                    $orders->orderBy('created_at',$sort);
                }
                if($column == 'orderStatus'){
                    $orders->orderBy(orderStatus::select('name')->whereColumn('orders.order_status','order_status.id')->limit(1),$sort);
                }
            }

            $totalDisplayRecords = $orderCount->count() ;
            if($length > 1)
            {
              $orders  = $orders->skip($start)->take($length)->get();
            }
            else{
              $orders  = $orders->get();
            }
            foreach ($orders as $order){
                 $refNumber = isset($order->rfq) ? $order->rfq->reference_number:'-';
                 $qtNumber = isset($order->quote) ? $order->quote->quote_number:'-';
                $order->referenceNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewRfqDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="'.$order->rfq_id.'">'.$refNumber.'</a>';
                $order->quoteNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="vieQuoteDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModalnew" data-id="'.$order->quote_id.'">'.$qtNumber.'</a>';
                $order->orderNumber ='<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="getSingleOrderDetail hover_underline" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="'.$order->id.'">'.$order->order_number.'</a>';

                if($order->payment_type == 0){
                    $order->payment_terms = '<span class="badge badge-pill badge-success">'.__('admin.advanced').'</span>';
                }elseif($order->payment_type == 1){
                    $days = $order->credit_days ??  null;
                    $order->payment_terms='<span class="badge badge-pill badge-danger">'.__('admin.credit').' - '.$days.'</span>';
                }
                elseif($order->payment_type == 2){
                    $order->payment_terms='<span class="badge badge-pill badge-danger">'.__('admin.loan_provider_credit').'</span>';
                }
                elseif($order->payment_type == 3){
                    $order->payment_terms='<span class="badge badge-pill badge-danger">'.__('admin.lc').'</span>';
                }
                else{
                    $order->payment_terms='<span class="badge badge-pill badge-danger">'.__('admin.skbdn').'</span>';
                }


                $order->company_name = isset($order->companyDetails)? $order->companyDetails->name : '-';
                $order->supplier_company_name = isset($order->supplier) ? $order->supplier->name : '-';

                $order->product = '-';
                if(isset($order->orderItems))
                {

                    $order->product = $order->orderItems->count() == 1 ? get_product_name_by_id($order->orderItems->first()->rfq_product_id,1) : $order->orderItems->count().' '.__('admin.products');
                }
                $order->createdAt = Carbon::parse($order->created_at)->format('d-m-Y H:i:s');

                if($authUser->role_id == 3){
                    $order->tot_price = 'Rp ' . number_format($order->quote->supplier_final_amount, 2);
                }
                else
                {
                    $billedAmount = $order->quote->final_amount;
                    $bulkOrderDiscount = getBulkOrderDiscount($order->id);
                    if($bulkOrderDiscount>0){
                        $billedAmount = $billedAmount-$bulkOrderDiscount;
                    }
                    $order->tot_price = 'Rp ' . number_format($billedAmount, 2);
                }


                if($order->order_status==5){
                    $order->orderStatus = '<span class="fs-11 bg-pre text-success text-nowrap"><i class="fa fa-check-circle-o" aria-hidden="true"></i>
                        '.__('order.'.trim($order->orderStatus->name)) .'
                    </span>';
                }elseif($order->order_status==10){
                    $order->orderStatus = '<span class="fs-11 bg-pre text-danger text-nowrap"><i class="fa fa-times-circle-o" aria-hidden="true"></i>
                            '.__('order.'.trim($order->orderStatus->name)) .'
                    </span>';
                }elseif($order->order_status==8){
                    $orderStatusData = $order->payment_due_date ? __('order.payment_due',['date' => changeDateFormat($order->payment_due_date,'d/m/Y')]) : sprintf(__('order.'.$order->orderStatus->name),'DD/MM/YYYY');

                    $order->orderStatus = '<span class="fs-11 bg-pre text-recieved text-nowrap"><i class="fa fa-cart-arrow-down text-recieved" aria-hidden="true"></i>
                    '. $orderStatusData .'</span>';
                }elseif($order->order_status==7){
                    $order->orderStatus = '<span class="fs-11 bg-pre text-danger text-nowrap"><i class="fa fa-times-circle-o" aria-hidden="true"></i>
                            '.__('order.'.trim($order->orderStatus->name)) .'
                    </span>';
                }else{
                    $order->orderStatus = '<span class="fs-11 bg-pre text-recieved text-nowrap"><i class="fa fa-cart-arrow-down text-recieved" aria-hidden="true"></i>
                        '.__('order.'.trim($order->orderStatus->name)) .'
                    </span>';
                }

                $action = '';
                if($authUser->can('edit order list'))
                {
                $action .='<a href="'.route('order-edit', ['id' => Crypt::encrypt($order->id)]).'" class="pe-2 show-icon" data-toggle="tooltip" ata-placement="top" title="'.__('admin.edit').'"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                }
                if($authUser->can('publish order list')){
                    $action .='<a class="pe-2 cursor-pointer getSingleOrderDetail" data-id="'.$order->id.'" data-toggle="tooltip" ata-placement="top" title="'.__('admin.view').'"><i class="fa fa-eye"></i></a>';
                }
                if($authUser->role_id == ADMIN){
                    $action .='<a class=" cursor-pointer viewFeedback" style="color: cornflowerblue;" data-id="'.$order->id.'" data-toggle="tooltip" data-placement="top" title="'.__('admin.feedback').'" data-bs-original-title="'.__('admin.feedback').'" aria-label=""><i class="fa fa-commenting ms-2 text-info"></i></a>';
                }
                $order->actions = $action;
            }
            return response()->json([
                "draw" => intval($request->get('draw')),
                "iTotalRecords" => $totalDisplayRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $orders
            ]);
        }
        else
        {

            $ord =  $orders->get();
            $rfq_numbers = array_unique($ord->pluck('rfq.reference_number','rfq.id')->toArray());
            $quotes_numbers = array_unique($ord->pluck('quote.quote_number','quote.id')->toArray());
            $order_numbers = array_unique($ord->pluck('order_number','id')->toArray());
            $supplier_companies = array_unique($ord->pluck('supplier.name','supplier.id')->toArray());
            $customer_companies = array_unique($ord->pluck('companyDetails.name','companyDetails.id')->toArray());
            $status = array_unique($ord->pluck('orderStatus.name','orderStatus.id')->toArray());
            $supplier_final_amount = array_unique($ord->pluck('quote.supplier_final_amount','quote.id')->toArray());
            $final_amount = array_unique($ord->pluck('quote.final_amount','quote.id')->toArray());

            if(count($ord) == 0)
            {
                $price_range = ['min' => 0 ,'max' => 0];
            }
            else{
                $price_range = $authUser->role_id == 3 ? ['min' => min(array_values($supplier_final_amount)), 'max' => max(array_values($supplier_final_amount))] :  ['min' => min(array_values($final_amount)), 'max' => max(array_values($final_amount))];
            }
        }

        Order::bootSystemView(new Order());
        /**end:  system log**/
        $getFeedbackReasions = AdminFeedbackReasons::where('reasons_type', 3)->get();
        return view('admin/order/index', ['rfq_numbers' => $rfq_numbers, 'quotes_numbers' => $quotes_numbers, 'order_numbers' => $order_numbers, 'supplier_companies' => $supplier_companies,'customer_companies' => $customer_companies, 'status' => $status, 'price_range' => $price_range, 'feedbackReasions' => $getFeedbackReasions]);
    }
    function listAjax(Request $request)
    {
        $condition = [];
        if($request->rfq_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.reference_number', 'value' => $request->rfq_ids]);
        }
        if($request->quote_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'quotes.quote_number', 'value' => $request->quote_ids]);
        }
        if($request->order_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'orders.order_number', 'value' => $request->order_ids]);
        }
        if($request->supp_company_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'suppliers.id', 'value' => $request->supp_company_ids]);
        }
        if($request->product_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'products.id', 'value' => $request->product_ids]);
        }
        if($request->cust_company_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'companies.id', 'value' => $request->cust_company_ids]);
        }
        if($request->category_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'categories.id', 'value' => $request->category_ids]);
        }
        if($request->payment){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'orders.is_credit', 'value' => $request->payment]);
        }
        if($request->status_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'order_status.id', 'value' => $request->status_ids]);
        }
        if($request->min_price && $request->max_price){
            if (auth()->user()->role_id == 3){
                array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'quotes.supplier_final_amount', 'value' => [$request->min_price,  $request->max_price] ]);
            }else{
                array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'quotes.final_amount', 'value' => [$request->min_price,  $request->max_price] ]);
            }
        }

        if($request->start_date && $request->end_date){
            $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d 00:00:00');
            $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d 23:59:59');
            array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'orders.created_at', 'value' => [$start_date,  $end_date] ]);
        }

        if (auth()->user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $orders = DB::table('orders')
                ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
                ->join('order_status', 'orders.order_status', '=', 'order_status.id')
                ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
                ->join('companies', 'user_companies.company_id', '=', 'companies.id')
                ->join('products', 'quotes.product_id', '=', 'products.id')
                ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->where('suppliers.id', $supplier_id)->orderBy('id', 'desc');

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $orders = $orders->whereIn($value['column_name'], $value['value']);
                    }

                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $orders = $orders->whereBetween($value['column_name'], $value['value']);
                    }

                    if(strtoupper($value['condition']) == "WHERE"){
                        $orders = $orders->where($value['column_name'], $value['value']);
                    }
                }
            }

            $orders = $orders->groupBy('orders.id')
                ->get(['orders.*', 'ocd.request_days', 'rfqs.id as rfq_id', 'ocd.approved_days', 'ocd.status as request_days_status', 'order_status.name as order_status_name', 'quotes.quote_number', 'quotes.final_amount', 'rfqs.reference_number as rfq_reference_number', 'users.firstname', 'users.lastname', 'companies.name as company_name', 'products.name as product_name', 'rfq_products.product_description as product_description', 'sub_categories.name as sub_category_name', 'categories.name as category_name', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name', 'quotes.tax', 'quotes.product_amount']);
        } else {
            $orders = DB::table('orders')
                ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
                ->join('order_status', 'orders.order_status', '=', 'order_status.id')
                ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
                ->join('companies', 'user_companies.company_id', '=', 'companies.id')
                ->join('products', 'quotes.product_id', '=', 'products.id')
                ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->orderBy('id', 'desc');

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $orders = $orders->whereIn($value['column_name'], $value['value']);
                    }

                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $orders = $orders->whereBetween($value['column_name'], $value['value']);
                    }
                }
            }

            $orders= $orders->groupBy('orders.id')->get(['orders.*', 'ocd.request_days', 'rfqs.id as rfq_id', 'ocd.approved_days', 'ocd.status as request_days_status', 'order_status.name as order_status_name', 'quotes.quote_number', 'quotes.final_amount', 'rfqs.reference_number as rfq_reference_number', 'users.firstname', 'users.lastname', 'companies.name as company_name', 'products.name as product_name', 'rfq_products.product_description as product_description', 'sub_categories.name as sub_category_name', 'categories.name as category_name', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name']);

            foreach ($orders as $order) {
                $orderPo = DB::table('order_pos')
                    ->where('order_id', $order->id)
                    ->get();
                if (count($orderPo) == 0) {
                    $order->pogenerated = false;
                } else {
                    $order->pogenerated = true;
                }
            }
        }

        $orderStatus = DB::table('order_status')->get();
        $orderDataHtml = view('admin/order/orderTableData', ['orders' => $orders, 'orderStatus' => $orderStatus->sortBy('show_order_id')->take(17),'creditOrderStatus'=>$orderStatus->sortBy('credit_sorting')->all()])->render();

        return $orderDataHtml;
    }

    // order excel export
    function orderExportExxcel()
    {
        ob_end_clean();
        ob_start();

        /**begin: system log**/
        Order::bootSystemView(new Order(), 'Order Export', SystemActivity::VIEW);
        /**end: system log**/
        return Excel::download(new OrderExport, 'order.xlsx');

        ob_flush();

    }

    public function edit($orderId) {
        $orderId = Crypt::decrypt($orderId);
        $order = Order::find($orderId);

        $orderBatches = OrderBatch::with('getAirWayBillNumber')
            ->whereNotNull('airwaybill_id')
            ->where('order_id',$orderId)
            ->get();

        $orderStatusHtml = $this->getOrderStatusDetails($orderId, 1);
        // $awb = AirWayBillNumber::select('airwaybill_number')->where('order_id',$orderId)->first();
        $awb = null;
        $buyerData = UserAddresse::join('orders','orders.user_id','=','user_addresses.user_id')
            ->where('orders.id',$orderId)->first(['user_addresses.address_name','user_addresses.address_line_1','user_addresses.address_line_2','user_addresses.city','user_addresses.state','user_addresses.pincode']);
        $group_id = Quote::leftJoin('rfqs','quotes.rfq_id','=','rfqs.id')->where('quotes.id',$order->quote_id)->pluck('rfqs.group_id')->first();

        /**begin: system log**/
        $order->bootSystemView(new Order(), 'Order', SystemActivity::EDITVIEW, $order->id);
        /**end: system log**/

        return view('admin/order/edit', [
        'order' => $order,
        'orderPo' => $order->orderPo()->first(['id','inv_number']),
        'orderStatusDropDownHtml' => $this->getOrderStatusDropDown($order),
        'orderStatusHtml' => $orderStatusHtml,
        'paymentDetailHtml'=> $this->getPaymentDetailsTab($order),
        'activityDetailHtml'=> $this->getActivityDetailsTab($order),
        'orderBatchesHtml' => $this->getOrderBatchesData($order),
        'buyerData' => $buyerData,
        'orderBatches' => $orderBatches,
        'awb' => $awb,
        'group_id' => $group_id
        ]);
    }

    //Download Pickup request PDF file for admin & supplier

    public function downloadAirwayBill($id)
    {
        $orderBatch = \App\Models\OrderBatch::with([
            'order:id,quote_id,user_id,company_id,supplier_id,rfq_id,created_at',
            'order.rfq:id,address_name,address_line_1,address_line_2,sub_district,district,state_id,city_id,pincode',
            'order.quote:id,quote_number,address_name,address_line_1,address_line_2,sub_district,district,state_id,city_id,pincode',
            'order.quote.quoteItem',
            'order.supplier:id,name,contact_person_name,contact_person_last_name',
            'order.orderItems',
            'order.orderItems.product:id,name,description',
        ])
            ->whereNotNull('airwaybill_id')
            ->where('airwaybill_id', $id)
            ->first();

        $orderItems = collect([]);
        $productAmount = 0;
        $orderBatchNumber = 'Pickup-request-'.$orderBatch->order_batch;
        $orderItemCategory = null;
        $orderItemCategoryId = null;
        $serviceDropAddressKeyName = null;
        $servicePickupAddressKeyName = null;

        if ($orderBatch->order_item_ids) {
            $orderItemIds = json_decode($orderBatch->order_item_ids);
            $orderItemData = \App\Models\OrderItem::with('quoteItem.product')
                ->whereIn('id', $orderItemIds)
                ->get();

            $orderItemCategoryId =  $orderItemData->first()->quoteItem->rfqProduct->category_id;

            if(!in_array($orderItemCategoryId,Category::SERVICES_CATEGORY_IDS)){
                $serviceDropAddressKeyName = 'dropServicesAddress';
                $servicePickupAddressKeyName = 'pickupServicesAddress';
            }

            foreach ($orderItemData as $k => $orderItemDetail) {
                $productAmount += $orderItemDetail->quoteItem->product_amount;

                $orderItems = $orderItems->push([
                    'id' => $orderItemDetail->id,
                    'productId' => $orderItemDetail->quoteItem->product->id,
                    'productName' => get_product_name_by_id($orderItemDetail->quoteItem->product->id),
                    'productDescription' => get_product_name_by_id($orderItemDetail->quoteItem->rfq_product_id, 1),
                    'productAmount' => $orderItemDetail->quoteItem->product_amount,
                    'product_quantity' => $orderItemDetail->quoteItem->product_quantity,
                    'weights' => $orderItemDetail->quoteItem->weights,
                    'length' => $orderItemDetail->quoteItem->length,
                    'width' => $orderItemDetail->quoteItem->width,
                    'height' => $orderItemDetail->quoteItem->height,
                ]);
            }
        }

        $data = [
            'orderBatchId' => $orderBatch->id,
            'airwaybillId' => $orderBatch->airwaybill_id,
            'airwaybillNumber' => $orderBatch->getAirWayBillNumber->airwaybill_number,
            'orderBatch' => $orderBatch->order_batch,
            'orderItemIds' => $orderBatch->order_item_ids,
            'createdAt' => \Carbon\Carbon::parse($orderBatch->created_at)->format('d-m-Y h:m:s a'),
            'pickupDateAndTime' => \Carbon\Carbon::parse($orderBatch->order_pickup)->format('d-m-Y h:m A'),
            'batchItemManageSeparately' => $orderBatch->batch_item_manage_separately,
            'orderItemCategory' => $orderItemCategory,
            'orderItemCategoryId' => $orderItemCategoryId,
            'productAmount' => $productAmount,
            'serviceDropAddressKeyName' => $serviceDropAddressKeyName,
            'servicePickupAddressKeyName' => $servicePickupAddressKeyName,
            'pickupAddress' => [
                'id' => $orderBatch->order->rfq->id ? $orderBatch->order->rfq->id : null,
                'address_name' => $orderBatch->order->rfq->address_name ? $orderBatch->order->rfq->address_name : null,
                'address_line_1' => $orderBatch->order->rfq->address_line_1 ? $orderBatch->order->rfq->address_line_1 : null,
                'address_line_2' => $orderBatch->order->rfq->address_line_2 ? $orderBatch->order->rfq->address_line_2 : null,
                'sub_district' => $orderBatch->order->rfq->sub_district ? $orderBatch->order->rfq->sub_district : null,
                'district' => $orderBatch->order->rfq->district ? $orderBatch->order->rfq->district : null,
                'state_id' => $orderBatch->order->rfq->state_id ? $orderBatch->order->rfq->state_id : null,
                'city_id' => $orderBatch->order->rfq->city_id ? $orderBatch->order->rfq->city_id : null,
                'pincode' => $orderBatch->order->rfq->pincode ? $orderBatch->order->rfq->pincode : null,
                'stateName' => $orderBatch->order->quote->state_name ? $orderBatch->order->quote->state_name->name : null,
                'cityName' => $orderBatch->order->quote->city ? $orderBatch->order->quote->city->name : null,
            ],
            'dropAddress' => [
                'id' => $orderBatch->order->quote->id ? $orderBatch->order->quote->id : null,
                'address_name' => $orderBatch->order->quote->address_name ? $orderBatch->order->quote->address_name : null,
                'address_line_1' => $orderBatch->order->quote->address_line_1 ? $orderBatch->order->quote->address_line_1 : null,
                'address_line_2' => $orderBatch->order->quote->address_line_2 ? $orderBatch->order->quote->address_line_2 : null,
                'sub_district' => $orderBatch->order->quote->sub_district ? $orderBatch->order->quote->sub_district : null,
                'district' => $orderBatch->order->quote->district ? $orderBatch->order->quote->district : null,
                'state_id' => $orderBatch->order->quote->state_id ? $orderBatch->order->quote->state_id : null,
                'city_id' => $orderBatch->order->quote->city_id ? $orderBatch->order->quote->city_id : null,
                'pincode' => $orderBatch->order->quote->pincode ? $orderBatch->order->quote->pincode : null,
                'stateName' => $orderBatch->order->quote->state_name ? $orderBatch->order->quote->state_name->name : null,
                'cityName' => $orderBatch->order->quote->city ? $orderBatch->order->quote->city->name : null,
            ],
            'dropServicesAddress' => [
                'id' => $orderBatch->order->rfq->id ? $orderBatch->order->rfq->id : null,
                'address_name' => $orderBatch->order->rfq->address_name ? $orderBatch->order->rfq->address_name : null,
                'address_line_1' => $orderBatch->order->rfq->address_line_1 ? $orderBatch->order->rfq->address_line_1 : null,
                'address_line_2' => $orderBatch->order->rfq->address_line_2 ? $orderBatch->order->rfq->address_line_2 : null,
                'sub_district' => $orderBatch->order->rfq->sub_district ? $orderBatch->order->rfq->sub_district : null,
                'district' => $orderBatch->order->rfq->district ? $orderBatch->order->rfq->district : null,
                'state_id' => $orderBatch->order->rfq->state_id ? $orderBatch->order->rfq->state_id : null,
                'city_id' => $orderBatch->order->rfq->city_id ? $orderBatch->order->rfq->city_id : null,
                'pincode' => $orderBatch->order->rfq->pincode ? $orderBatch->order->rfq->pincode : null,
                'stateName' => $orderBatch->order->quote->state_name ? $orderBatch->order->quote->state_name->name : null,
                'cityName' => $orderBatch->order->quote->city ? $orderBatch->order->quote->city->name : null,
            ],
            'pickupServicesAddress' => [
                'id' => $orderBatch->order->quote->id ? $orderBatch->order->quote->id : null,
                'address_name' => $orderBatch->order->quote->address_name ? $orderBatch->order->quote->address_name : null,
                'address_line_1' => $orderBatch->order->quote->address_line_1 ? $orderBatch->order->quote->address_line_1 : null,
                'address_line_2' => $orderBatch->order->quote->address_line_2 ? $orderBatch->order->quote->address_line_2 : null,
                'sub_district' => $orderBatch->order->quote->sub_district ? $orderBatch->order->quote->sub_district : null,
                'district' => $orderBatch->order->quote->district ? $orderBatch->order->quote->district : null,
                'state_id' => $orderBatch->order->quote->state_id ? $orderBatch->order->quote->state_id : null,
                'city_id' => $orderBatch->order->quote->city_id ? $orderBatch->order->quote->city_id : null,
                'pincode' => $orderBatch->order->quote->pincode ? $orderBatch->order->quote->pincode : null,
                'stateName' => $orderBatch->order->quote->state_name ? $orderBatch->order->quote->state_name->name : null,
                'cityName' => $orderBatch->order->quote->city ? $orderBatch->order->quote->city->name : null,
            ],
            'receiptance' => [
                'supplierName' => $orderBatch->order->supplier->contact_person_name ?
                    $orderBatch->order->supplier->contact_person_name . ' ' . $orderBatch->order->supplier->contact_person_last_name :
                    '',
                'supplierCompanyName' => $orderBatch->order->supplier->name,
                'receiverName' => $orderBatch->receiver_name ? $orderBatch->receiver_name : '',
                'receiverPhone' => $orderBatch->receiver_pic_phone,
                'receiverEmail' => $orderBatch->receiver_email_address,
                'receiverCompanyName' => $orderBatch->receiver_company_name,
                'orderItems' => $orderItems
            ],
            'otherDetails' => [
                'logisticsServices' => $orderBatch->order->quote->quoteItem->logistics_service_code,
                'goodsType' => 'SHTPC',
                'serviceType' => $orderBatch->order->quote->quoteItem->pickup_service,
                'fleetType' => $orderBatch->order->quote->quoteItem->pickup_fleet,
                'woodPacking' => $orderBatch->order->quote->quoteItem->wood_packing,
                'goodsDescription' => get_product_desc_by_id($orderBatch->order->quote->quoteItem->rfq_product_id),
            ]
        ];

        $pdf = PDF::loadView('admin.order.admin-download-airway-bill', [
            'orderBatchDetail' => $data
        ]);

        return $pdf->download($orderBatchNumber . '.pdf');
    }

    public function getOrderStatusDropDown(Order $order){
        $orderStatus = DB::table('order_status')->where('is_deleted',0)->get();
        $creditOrderStatus = $orderStatus->sortBy('credit_sorting')->all();
        $isCreditApproved = $order->orderStatusTrack()->where('status_id',9)->count();
        $isCreditApplied = 0;
        if($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
            $whereNotIn = [8];
            if ($isCreditApproved){//if credit approved
                $whereNotIn = array_merge($whereNotIn,[10]);
            }
            $creditOrderStatus = $orderStatus->whereNotIn('id',$whereNotIn)->sortBy('credit_sorting');
        }else{
            $isCreditApplied = $order->loanApply()->count();
        }
        return view('admin.order.order_status_dropdown',[
                    'order'=>$order,
                    'orderStatus' => $orderStatus->sortBy('show_order_id')->where('show_order_id', '!=', 7)->take(12),
                    'creditOrderStatus'=>$creditOrderStatus,
                    'isCreditApplied'=>$isCreditApplied,
                    'orderItems'=>$order->orderItems()->get(),
                    'orderCreditDay'=>$order->orderCreditDay()->first(['status','approved_days','request_days']),
                    'disbursement'=>$order->disbursement()->where('status','FAILED')->first(),
                    'isCreditApproved'=>$isCreditApproved
                ])->render();
    }

    public function getPaymentDetailsTab($order){
        $amountDetails = $this->getQuoteChargeWithAmount($order->quote_id);
        $orderItemStatuses = DB::table('order_item_status')->get();
        $totalSameStatus = $order->orderItems()->groupBy('order_item_status_id')->count();
        //Supplier address and default address
        $supplierAddresses = SupplierAddress::where('supplier_id',$order->supplier_id)->where('is_deleted', 0)->orderBy('id','DESC')->get();
        $defaultAddress = SupplierAddress::where('supplier_id',$order->supplier_id)->where('default_address',1)->where('is_deleted', 0)->first();
        $states = State::where('country_id',CountryOne::DEFAULTCOUNTRY)->get();

        return view('admin.order.order_items.payment_detail',['order'=>$order,'quote'=>$order->quote()->first(),'orderItems'=>$order->orderItems()->orderBy('order_item_number')->get(),'batchOrderItems'=>$order->orderItems()->where('is_in_batch',0)->orderBy('order_item_number')->get(),'totalSameStatus'=>$totalSameStatus,'amountDetails'=>$amountDetails,'orderItemStatuses'=>$orderItemStatuses,'supplierAddresses' => $supplierAddresses,'defaultAddress' => $defaultAddress, 'states' => $states])->render();
    }

    public function getOrderBatchesData($order) {
        $orderItemStatuses = DB::table('order_item_status')->get();
        $totalSameStatus = $order->orderItems()->groupBy('order_item_status_id')->count();
        $order_batches = $this->getOrderBatchesByOrderId($order->id);

//        $batchArrData = [];
//        foreach($order_batches as $batch) {
//            $batchArrData = $order->orderItems()->whereIn('id',json_decode($batch->order_item_ids))->orderBy('order_item_number')->get();
//        }
        return view('admin.order.order_items.order_batches',['order'=>$order, 'quote'=>$order->quote()->first(), 'orderItems'=>$order->orderItems()->orderBy('order_item_number')->get(),'orderItemStatuses'=>$orderItemStatuses,'totalSameStatus'=>$totalSameStatus,'batchOrderItems'=>$order->orderItems()->where('is_in_batch',0)->orderBy('order_item_number')->get(),'order_batches' => $order_batches])->render();
    }

    //Get all order batches from "order_batches" table by order_id
    public function getOrderBatchesByOrderId($orderId) {
        $orderBatch = OrderBatch::where('order_id', $orderId)->where('deleted_at',NULL)->orderBy('id','ASC')->get();
        return $orderBatch;
    }

    public function getActivityDetailsTab($order){
        return view('admin.order.activity_detail_body',['order'=>$order,'orderPo' => $order->orderPo()->first(['id'])])->render();
    }

    public function getQuoteChargeWithAmount($quoteId){
        if (auth()->user()->role_id != 3){
            $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))->where('quotes_charges_with_amounts.quote_id', $quoteId)->orderBy('charge_type', 'asc')->get();
        } else {
            $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))->where('quotes_charges_with_amounts.quote_id', $quoteId)->where('quotes_charges_with_amounts.charge_type', 0)->orderBy('charge_type', 'asc')->get();
        }

        return $quotes_charges_with_amounts;
    }

    public function getOrderStatusDetails($orderId, $retur_html = 1)
    {
        $orderobj = Order::find($orderId);
        $order = DB::table('orders')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->where('orders.id', $orderId)
            ->first(['orders.*', 'ocd.request_days', 'ocd.approved_days', 'ocd.status as request_days_status']);
        $orderStatus = DB::table('order_status')
            ->leftJoin('order_tracks', function ($join) use ($orderId) {
                $join->on('order_status.id', '=', 'order_tracks.status_id')
                    ->where('order_tracks.order_id', $orderId);
            })
            ->where('order_status.is_deleted', 0)
            ->groupBy('order_status.name')
            ->get(['order_status.id as order_status_id', 'order_status.name as status_name', 'order_tracks.created_at', 'order_tracks.id as order_track_id', 'order_status.show_order_id', 'order_status.credit_sorting']);
        $orderTracks = OrderTrack::where('order_id', $orderId)->pluck('status_id');
        $order->full_quote_by = $orderobj->quote->full_quote_by;
        $order->closeflag = $orderobj->orderItems->first()->order_item_status_id ?? 0;
        $order->full_quote_by = $orderobj->quote->getUser->role_id ?? 0;
        $order->po_number = $orderobj->orderPo->po_number ?? 0;

        //if ($order->is_credit) {
        if ($order->payment_type == 1 || $order->payment_type == 2) {
            if($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                $whereNotIn = [8];
                if (!empty($orderTracks->contains(9))){//if credit approved
                    $whereNotIn = array_merge($whereNotIn,[10]);
                }
                $orderStatus = $orderStatus->whereNotIn('order_status_id',$whereNotIn);
            }
            $order->orderAllStatus = $orderStatus->sortBy('credit_sorting')->values()->all();
            $returnHTML = view('admin/order/credit_status_details', ['order' => $order, 'orderId' => $orderId, 'orderTracks' => $orderTracks])->render();
        }else{
            $order->orderAllStatus = $orderStatus->sortBy('show_order_id')->take(9)->values();
            $returnHTML = view('admin/order/advance_status_details', ['order' => $order, 'orderId' => $orderId, 'orderTracks' => $orderTracks])->render();
        }
        if($retur_html){
            return $returnHTML;
        }
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getOrderItemStatusDetails(Request $request)
    {
        $inputs = $request->all();
        $orderItem = OrderItem::find($inputs['order_item_id']);

        if (empty($orderItem)){
            return response()->json(array('success' => false, 'html' => ''));
        }

        $orderStatus = DB::table('order_item_status')
            ->leftJoin('order_item_tracks', function ($join) use ($inputs) {
                $join->on('order_item_status.id', '=', 'order_item_tracks.status_id')
                    ->where(['order_item_tracks.order_item_id'=> $inputs['order_item_id']]);
            })
            ->where('order_item_status.status', 1)
            ->groupBy('order_item_status.name')
            ->get(['order_item_status.id as order_item_status_id', 'order_item_status.name as status_name', 'order_item_tracks.created_at', 'order_item_tracks.id as order_track_id']);

        $returnHTML = view('admin/order/order_items/order_item_status', ['orderItem' => $orderItem,'allOrderStatus'=>$orderStatus])->render();//,'quoteItem'=> $orderItem->quoteItem()->first()

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function getSingleOrderDetail($orderId){
        $order = Order::find($orderId);
        $quotes_charges_with_amounts = $this->getQuoteChargeWithAmount($order->quote_id);
        $group_id = Quote::leftJoin('rfqs','quotes.rfq_id','=','rfqs.id')->where('quotes.id',$order->quote_id)->pluck('rfqs.group_id')->first();
        //dd($group_id);

        $returnStatusHTML = $this->getOrderStatusDetails($orderId, 1);
        $awb = AirWayBillNumber::select('airwaybill_number')->where('order_id',$orderId)->first();
        $orderPo = OrderPo::select('inv_number')->where('order_id',$orderId)->first();
        $customerRef = Order::where('id',$orderId)->first(['customer_reference_id']);
        $quote_items = QuoteItem::where('quote_id', $order->quote_id)->join('units', 'quote_items.price_unit', '=', 'units.id')->get();
        //dd($order->toArray());

        /**begin: system log**/
        $order->bootSystemView(new Order(), 'Order', SystemActivity::RECORDVIEW, $order->id);
        /**end: system log**/

        $returnHTML = view('admin/order/orderSingleDetails', ['order' => $order, 'Order_status' => $returnStatusHTML, 'amount_details' => $quotes_charges_with_amounts,'awb' => $awb,'customerRef' => $customerRef, 'quote_items' => $quote_items, 'group_id' => $group_id, 'orderPo' => $orderPo])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getOrder($orderId){
        $order = DB::table('orders')
            ->leftJoin('disbursements as disb', 'orders.id', '=', 'disb.order_id')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->join('products', 'quote_items.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->join('units', 'quote_items.price_unit', '=', 'units.id')
            ->leftJoin('order_pos', 'orders.id', '=', 'order_pos.order_id')
            ->where('orders.id', $orderId)
            ->first(['orders.*', 'disb.status as disbursement_status', 'ocd.request_days', 'ocd.approved_days', 'ocd.status as request_days_status', 'order_status.name as order_status_name', 'quotes.quote_number', 'quotes.valid_till', 'quotes.tax_value', 'quotes.tax',  'quotes.final_amount', 'quotes.supplier_final_amount', 'rfqs.reference_number as rfq_reference_number', 'units.name as unit_name', 'users.firstname', 'users.lastname', 'users.email as user_email', 'users.mobile as user_mobile', 'companies.name as company_name', 'products.name as product_name', 'rfq_products.product_description as product_description', 'sub_categories.name as sub_category_name', 'categories.name as category_name',  'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name', 'suppliers.address as supplier_address','suppliers.contact_person_email as supplier_email', 'suppliers.contact_person_phone as supplier_mobile', 'order_pos.id as oder_po', 'quote_items.min_delivery_days', 'quote_items.max_delivery_days', 'quotes.note', 'quote_items.logistic_provided', 'quote_items.product_price_per_unit', 'quote_items.product_amount', 'quote_items.product_quantity as product_quantity', 'suppliers.cp_phone_code as supplier_phone_code']);

        return $order;
    }

    function getOrderDetails($orderId)
    {
        $order = DB::table('orders')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
            ->join('rfq_status', 'rfq_status.id', '=', 'rfqs.status_id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('products', 'quote_items.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->join('units', 'quote_items.price_unit', '=', 'units.id')
            ->where('orders.id', $orderId)
            ->get(['orders.id as order_id', 'orders.order_number', 'orders.customer_reference_id','quotes.id as quotes_id', 'quotes.quote_number', 'quotes.created_at', 'quotes.valid_till', 'quotes.final_amount', 'quote_items.product_amount', 'quote_items.product_price_per_unit', 'rfq_status.name as status_name', 'rfqs.reference_number as rfq_reference_number', 'products.name as product_name', 'rfq_products.product_description as product_description', 'rfqs.firstname as rfq_firstname', 'rfqs.lastname as rfq_lastname', 'rfqs.pincode as rfq_pincode', 'sub_categories.name as sub_category_name', 'categories.name as category_name', 'units.name as unit_name', 'rfqs.mobile as rfq_mobile', 'quote_items.product_quantity as product_quantity', 'quote_items.min_delivery_days', 'quote_items.max_delivery_days', 'quotes.note', 'suppliers.contact_person_name as supplier_name', 'suppliers.contact_person_email as supplier_email', 'suppliers.contact_person_phone as supplier_phone', 'suppliers.name as supplier_company', 'quotes.tax', 'quotes.tax_value', 'suppliers.cp_phone_code as supplier_phone_code']);

        $quotes_charges_with_amounts = DB::table(('orders'))
            ->join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
            ->where('orders.id', $orderId)
            ->where('quotes_charges_with_amounts.charge_type', 0)
            ->orderBy('quotes_charges_with_amounts.created_at', 'desc')
            ->get();
        //	echo "<pre>"; print_r($quotes_charges_with_amounts); exit;

        $approversList = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')
            ->join('user_approval_configs','user_approval_configs.user_id','=','users.id')
            ->leftJoin('designations','designations.id','=','users.designation')
            ->where('user_quote_feedbacks.quote_id',$order[0]->quotes_id)
            ->where('user_quote_feedbacks.resend_mail',0)
            ->where('user_approval_configs.user_type','Approver')
            ->get(['users.firstname','users.lastname','designations.name','user_quote_feedbacks.feedback']);

        $returnHTML = view('admin/order/poDetails', ['order' => $order[0], 'quotes_charges_with_amounts' => $quotes_charges_with_amounts, 'approversList' => $approversList])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
/*
 * start code
 * code changes by munir
 * Date:-30/5/2022
 */
    public function sendPoToSupplier(Request $request)
    {
        $inputs = $request->all();
        return $this->directAndButtonGenerateCodeForPO($inputs);
    }

    public function directAndButtonGenerateCodeForPO($inputs, $buttonClick = 0){
        $inputs['user_id'] = Auth::id() ? Auth::id() : $inputs['user_id'];
        $orderObj = $this->generatePO($inputs);
        $html = $this->getActivityDetailsTab($orderObj);
        if ($buttonClick == 0){
            return response()->json(array('success' => true, 'activityDetailHtml' => $html));
        } else {
            return $html;
        }
    }
/*
 * This function created using sendPoToSupplier
 * code changes by munir
 * Date:-30/5/2022
 * */
    public function generatePO($data)
    {
        $orderId = $data['id'];
        $orderPo = new OrderPo();
        $orderPo->order_id = $orderId;
        $orderPo->comment = $data['comment']??'';
        $orderPo->save();
        //--$orderPo->po_number = 'BPON-' . $orderPo->id;
        $orderPo->po_number = 'PO-' . \Carbon\Carbon::now()->format('Y/m/d') . '-00000' . $orderPo->id;
        $orderPo->save();

        $orderActivity = new OrderActivity();
        $orderActivity->order_id = $orderId;
        $orderActivity->user_id = $data['user_id'];
        $orderActivity->key_name = 'generate_po';
        $orderActivity->old_value = '';
        $orderActivity->new_value = 'Generated PO';
        $orderActivity->user_type = User::class;
        $orderActivity->save();

        $order = Order::find($orderId);
        $supplier_id = $order->supplier_id;
        $orderPo = $order->orderPo()->first();
        $quotes_charges_with_amounts = DB::table(('orders'))
            ->join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
            ->where('orders.id', $orderId)
            ->where('quotes_charges_with_amounts.charge_type', 0)
            ->orderBy('quotes_charges_with_amounts.created_at', 'desc')
            ->get();
        $user_role = User::find($data['user_id'])->role_id;
        $order->po_number = $orderPo->po_number;
        $approversList = NULL;

        $returnHTML = view('admin/pdf/invoice', ['order' => $order, 'quotes_charges_with_amounts' => $quotes_charges_with_amounts, 'approversList' => $approversList])->render();
        $path = 'order-pdf/' . str_replace("/", "_", $order->po_number) . '.pdf';
        if(!File::exists('order-pdf/')) {
            File::makeDirectory('order-pdf/', $mode = 0755, true, true);
        }
        PDF::loadHTML($returnHTML)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false)
            ->save($path);

        //Buyer's PO Details
        $full_charges_buyers = Order::join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
            ->where('orders.id', $orderId)
            ->orderBy('quotes_charges_with_amounts.created_at', 'desc')->get();

        $returnBuyerPO = view('admin/pdf/buyerPO', ['order' => $order, 'quotes_charges_with_amounts' => $full_charges_buyers, 'approversList' => $approversList, 'user_role' => $user_role])->render();
        $buyer_path = 'buyer-order-pdf/' . str_replace("/", "_", $order->po_number) . '.pdf';
        if(!File::exists('buyer-order-pdf/')) {
            File::makeDirectory('buyer-order-pdf/', $mode = 0755, true, true);
        }
        PDF::loadHTML($returnBuyerPO)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false)
            ->save($buyer_path);
        //End

        $orderObj = $order;
        $order = DB::table('orders')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'orders.supplier_id', '=', 'suppliers.id')
            ->join('products', 'quote_items.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->join('units', 'quote_items.price_unit', '=', 'units.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.id', $orderId)
            ->first(['orders.id as order_id', 'orders.order_number', 'quotes.id as quotes_id', 'quotes.quote_number', 'quotes.created_at', 'quotes.valid_till', 'quotes.final_amount', 'products.name as product_name', 'products.description as product_description', 'sub_categories.name as sub_category_name', 'categories.name as category_name', 'units.name as unit_name', 'quotes.note', 'suppliers.contact_person_name as suppliers_name', 'suppliers.contact_person_email as suppliers_email', 'users.firstname as user_firstname', 'users.lastname as user_lastname','users.email as buyer_email']);
        $email = $order->suppliers_email;
        $buyer_email = $order->buyer_email;
        $order->po_number = $orderPo->po_number;
        $quote_items = QuoteItem::where('quote_id', $order->quotes_id)->get();
        //Send Mail to Supplier
        $supplier_order = [
            'order' => $order,
            'quote_items' => $quote_items,
            'email' => $email,
            'pdf' => public_path($path),
        ];

        try {
            dispatch(new SendPoToSupplierJob($supplier_order));
        } catch (\Exception $e) {
            //dd($e,"supplier");
        }

        //Send Mail to buyer
        $buyerOrder = [
            'order' => $order,
            'quote_items' => $quote_items,
            'email' => $buyer_email,
            'pdf' => public_path($buyer_path),
        ];
        try {
            dispatch(new SendPoToBuyerJob($buyerOrder));
        } catch (\Exception $e) {
            //dd($e,"buyer");
        }
        //start notification
        $authUserId = Auth::check()? Auth::id(): 1;
        $authUserRoleId = Auth::check()? Auth::user()->role_id : 1;
        $this->adminUpdateNotificationChange($orderId, $supplier_id, 0, 0, 'Generate PO', 'generate_po', 0, '', $authUserId, $authUserRoleId);
        //end notification

	    return $orderObj;
    }
    /*
     * start code
     * code changes by munir
     * Date:-30/5/2022
     */

    public function downloadPoPdf($orderId,$onlyBuyerPo=0)
    {
        $orderId = Crypt::decrypt($orderId);
        $order = Order::find($orderId);

        $quotes_charges_with_amounts = DB::table(('orders'))
            ->join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
            ->where('orders.id', $orderId)
            ->where('quotes_charges_with_amounts.charge_type', 0)
            ->orderBy('quotes_charges_with_amounts.created_at', 'desc')
            ->get();

        $full_charges_buyer = Order::join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
            ->where('orders.id', $orderId)
            ->orderBy('quotes_charges_with_amounts.created_at', 'desc')->get();

        $full_charges = Order::join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
            ->where('orders.id', $orderId)
            ->whereIn('quotes_charges_with_amounts.charge_type', [1,2])
            ->orderBy('quotes_charges_with_amounts.charge_type', 'asc')->get();

        $approversList = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')
            ->join('user_approval_configs','user_approval_configs.user_id','=','users.id')
            ->join('designations','designations.id','=','users.designation')
            ->where('user_quote_feedbacks.quote_id',$order->quotes_id)
            ->where('user_quote_feedbacks.resend_mail',0)
            ->where('user_approval_configs.user_type','Approver')
            ->get(['users.firstname','users.lastname','designations.name','user_quote_feedbacks.feedback']);

        //Admin and Buyer
        $user_role = auth()->user()->role_id;
        if (auth()->user()->role_id != 3){
            if($onlyBuyerPo==2) {
                $returnHTML = view('admin/pdf/blitznetInvoice', ['order' => $order, 'quotes_charges_with_amounts' =>$full_charges, 'approversList' => $approversList])->render();
            } elseif($onlyBuyerPo==1){
                $returnHTML = view('admin/pdf/buyerPO', ['order' => $order, 'quotes_charges_with_amounts' =>$full_charges_buyer, 'approversList' => $approversList,'user_role' =>$user_role])->render();
            }else{
                $returnHTML = view('admin/pdf/invoice', ['order' => $order, 'quotes_charges_with_amounts' => $quotes_charges_with_amounts, 'approversList' => $approversList])->render();
            }
        } else {
            $returnHTML = view('admin/pdf/invoice', ['order' => $order, 'quotes_charges_with_amounts' => $quotes_charges_with_amounts, 'approversList' => $approversList])->render();
        }

        $pdf = PDF::loadHTML($returnHTML)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);
        if($onlyBuyerPo==2) {
            return $pdf->download($order->orderPo()->first()->inv_number . '.pdf');
        }else{
            return $pdf->download($order->orderPo()->first()->po_number . '.pdf');
        }
    }

    public function downloadBuyerPoPdf($orderId){
        return $this->downloadPoPdf($orderId,1);
    }
    public function downloadblitznetInvoicePdf($orderId){
        return $this->downloadPoPdf($orderId,2);
    }
    //manage Order Items Separately status change
    public function manageOrderItemsSeparately($orderId){
        if(auth()->user()->role_id != 1 && Auth::user()->role_id != Role::AGENT && Auth::user()->role_id != Role::JNE){
            return response()->json(array('success' => false, 'message' => 'Access Denied.'));
        }
        $order = Order::find($orderId);
        if (empty($order)){
            return response()->json(array('success' => false, 'message' => __('order.order_not_found')));
        }
        $order->items_manage_separately = 1;
        $order->save();
        return response()->json(['success'=>true,'paymentDetailHtml' => $this->getPaymentDetailsTab($order),'orderBatchesHtml'=>$this->getOrderBatchesData($order)]);
    }
    /** Manage batch item separately
    *   Vrutika Rana - 05/09/2022
    */
    public function manageBatchItemsSeparately(Request $request){
        if(auth()->user()->role_id != 1 && Auth::user()->role_id != Role::AGENT && Auth::user()->role_id != Role::SUPPLIER && !Auth::user()->hasRole('jne')){
            return response()->json(array('success' => false, 'message' => 'Access Denied.'));
        }
        $order = Order::find($request->orderId);
        $order_items = OrderBatch::where('order_id',$request->orderId)->where('id',$request->batchId)->update(['batch_item_manage_separately'=>1]);

        if (empty($order)){
            return response()->json(array('success' => false, 'message' => __('order.order_not_found')));
        }
        $order->items_manage_separately = 1;
        $order->save();
        return response()->json(['success'=>true,'paymentDetailHtml' => $this->getPaymentDetailsTab($order),'orderBatchesHtml'=>$this->getOrderBatchesData($order)]);
    }
    /** Manage order delivery separately
    *   Vrutika Rana - 25/08/2022
    */
    public function manageOrderDeliverySeparately($orderId){
        if(auth()->user()->role_id != 1 && Auth::user()->role_id != Role::AGENT && Auth::user()->role_id != Role::SUPPLIER && !Auth::user()->hasRole('jne')){
            return response()->json(array('success' => false, 'message' => 'Access Denied.'));
        }
        $order = Order::find($orderId);
        if (empty($order)){
            return response()->json(array('success' => false, 'message' => __('order.order_not_found')));
        }
        $order->delivery_manage_separately = 1;
        $order->save();
        $this->service->sendOrderDeliverySeprateNotification($order);
        return response()->json(['success'=>true,'paymentDetailHtml' => $this->getPaymentDetailsTab($order),'orderBatchesHtml'=>$this->getOrderBatchesData($order)]);
    }
    //change order status for advance
    public function orderStatusChange(Request $request)
    {
        // if($request->selectedStatusID == 5) {
        //     $this->generateAirWayBillNumber($request->orderId);
        // }

        $selectedStatusID = $request->selectedStatusID;
        $orderId = $request->orderId;
        $otherData['is_backend_request'] = $request->is_backend_request??0;
        $isValidate = (int)$request->is_validate??0;
        $result = $this->orderStatusValidationCheck($orderId,$selectedStatusID)->getData();

        if ($result->valid===1 && $isValidate){
            return $this->setOrderStatusChange($selectedStatusID,$orderId,$otherData);
        }
        return $this->orderStatusValidationCheck($orderId,$selectedStatusID);
    }

    public function orderStatusValidationCheck($orderId,$selectedStatusID){
        $order = Order::find($orderId);
        $selectedStatus = DB::table('order_status')->where('id',$selectedStatusID)->first(['id','show_order_id','credit_sorting']);
        $orderItems = $order->orderItems()->get();
        $totalOrderItems = $orderItems->count();
        $totalDeliveredItems = $orderItems->where('order_item_status_id',10)->count();
        $isStatusChangeValid = !($order->order_status>=4 &&!($totalOrderItems===$totalDeliveredItems));

        $swal = [
            //'title'=> __('admin.something_error_message'),
            'icon' => "/assets/images/info.png",
            'buttons' => [__('admin.cancel'), __('admin.ok')],
            'dangerMode' => false,
        ];
        //status change validations
        if ($order->is_credit==0 && isAdvanceStatusChangeAllow($order->order_status,$selectedStatus->id,$selectedStatus->show_order_id)===false){
            $swal['title'] = 'Status change not allowed.';
            return response()->json(['success' => false, 'valid'=>0, 'swal' => $swal]);
        }elseif ($order->is_credit==1 && isCreditStatusChangeAllow($order->order_status,$selectedStatus->id,$selectedStatus->credit_sorting)===false) {
            $swal['title'] = 'Status change not allowed.';
            return response()->json(['success' => false, 'valid' => 0, 'swal' => $swal]);
        }
        if ($isStatusChangeValid===false){
            if($order->is_credit==1 || ($order->is_credit==0 && $order->order_status!=10)) {
                $swal['title'] = 'Unchangeable status! Until all items are delivered.';
                return response()->json(['success' => false, 'valid' => 0, 'swal' => $swal]);
            }
        }
        if ($selectedStatusID == 5 && (empty($order->tax_receipt) || empty($order->invoice))) {
            $message = 'Tax Receipt';
            if (empty($order->tax_receipt) && empty($order->invoice)) {
                $message = 'Invoice and Tax Receipt';
            } elseif (empty($order->invoice)) {
                $message = 'Invoice';
            }
            $swal['title'] = 'First, you should Upload '.$message.'!';
            return response()->json(['success' => false, 'valid'=>0, 'swal' => $swal]);
        }//preDump(1);
        return response()->json(['success' => true,'valid'=>1]);
    }

    //change order status for credit
    public function creditOrderStatusChange(Request $request)
    {
        // if($request->selectedStatusID == 5) {
        //     $this->generateAirWayBillNumber($request->orderId);
        // }
        $selectedStatusID = $request->selectedStatusID;
        $orderId = $request->orderId;
        $otherData['is_backend_request'] = $request->is_backend_request??0;
        $isValidate = (int)$request->is_validate??0;
        $result = $this->orderStatusValidationCheck($orderId,$selectedStatusID)->getData();

        if ($result->valid===1 && $isValidate){
            return $this->setOrderStatusChange($selectedStatusID,$orderId,$otherData);
        }
        return $this->orderStatusValidationCheck($orderId,$selectedStatusID);
    }

    // set order status
    public function setOrderStatusChange($selectedStatusID,$orderId,$otherData=[], $supplier_id = '')
    {
        $order = Order::find($orderId);
        $lastOrderStatus = $order->order_status;
        $groupId = null;//not group order
        $userData = User::find($order->user_id);

        if (isset($otherData['group_id'])){
            $groupId = $otherData['group_id'];//group order
            $otherData=[];//empty other data
        }
        $bulkDiscount = (float)0;
        if($order->bulkOrderPayments->first())
        {
            if($order->bulkOrderPayments->first()->bulkPayment){
                $bulkDiscount = $order->bulkOrderPayments->first()->bulkPayment->orderTransaction->status == 'PAID' ? (float)$order->bulkOrderPayments->first()->discounted_amount : '';
            }            
        }
        $billedAmount = $order->quote->final_amount - $bulkDiscount;
        $smsData['order_number'] = $order->order_number;
        $smsData['final_amount'] = number_format($billedAmount,2);

        $supplier_id = $supplier_id??$order->supplier_id;
        $userSupplier = UserSupplier::where('supplier_id', $supplier_id)->first();
        $authUserId = Auth::check()? Auth::id(): 1;
        $orderTrackUserId = Auth::check()? Auth::id(): 1;
        $authUserRoleId = Auth::check()? Auth::user()->role_id : 1;
        $orderTrackUserType = User::class;
        if (isset($userSupplier->user_id) && !empty($userSupplier->user_id)){
            $orderTrackUserId = $userSupplier->user_id;
        }
        $authUserId = $orderTrackUserId;

        if ($selectedStatusID == 2){
            $data['id'] = $order->id;
            $data['comment'] = $order->po_comment;
            $data['user_id'] = Auth::check()? Auth::id(): $authUserId;
            $poHtml = $this->directAndButtonGenerateCodeForPO($data, 1);
            if($order->payment_type == 0){
                $sendMsg = $this->verify->sendMsg($userData->firstname,$userData->lastname,'order_payment_pending',$userData->phone_code,$userData->mobile,$smsData);
            }
        }

        if ($order->is_credit) {
            if ($order->payment_type >=3){

                if($selectedStatusID !=5){
                    $orderPo = $order->orderPo()->first();
                    if (!$orderPo){
                        $data['id'] = $order->id;
                        $data['comment'] = $order->po_comment;
                        $data['user_id'] = Auth::check()? Auth::id(): $authUserId;
                        $poHtml = $this->directAndButtonGenerateCodeForPO($data, 1);
                    }
                    $orderItems = $order->orderItems()->get();
                    foreach ($orderItems as $orderItem) {
                        $lastOrderItemStatus = $orderItem->order_item_status_id;
                        $orderItemId = $orderItem->id;
                        OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$order->id,'order_item_id'=>$orderItemId,'status_id'=>1,'user_id'=>$authUserId]);
                        OrderActivity::createOrUpdateOrderActivity(['order_id'=>$order->id,'order_item_id'=>$orderItemId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'order_item_status', 'new_value' => 1, 'old_value' => $lastOrderItemStatus]);
                    }
                    OrderItem::where('order_id',$order->id)->update(['order_item_status_id'=>1]);
                }

                if ($selectedStatusID == 12 || $selectedStatusID == 11) {
                    if($selectedStatusID == 12)
                    {
                        $selectedStatusID = 4;//Order in Progress
                        $lastOrderStatus = 3;
                    }
                    else{
                        $selectedStatusID = 11;//Order in Progress
                        $lastOrderStatus = 11;
                        $xendit = new XenditController;
                        $xendit->createInvoice($order);
                    }
                }
                if($selectedStatusID == 3){
                    setOrderPaymentStatus($order);//set payment status
                    OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'status', 'new_value' => $selectedStatusID, 'old_value' => $lastOrderStatus]);
                    $selectedStatusID = 4;//Order in Progress
                    $lastOrderStatus = 3;
                }
            }else{
                $loanCancelProcess = false;
                if ($selectedStatusID == 10) {//Credit Rejected
                    if($order->is_credit == ORDER_IS_CREDIT['CREDIT']) {
                        OrderCreditDays::saveOrderCreditDayStatus($orderId,$selectedStatusID);//Credit Rejected status save on OrderCreditDayStatus
                    }elseif($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                        $loanCancelProcess = true;
                    }
                    $order->is_credit = 0;
                    $order->payment_type= 0;
                    $order->credit_days = null;
                    $sendMsg = $this->verify->sendMsg($userData->firstname,$userData->lastname,'order_credit_rejected',$userData->phone_code,$userData->mobile,$smsData);
                } elseif ($selectedStatusID == 9) {//Credit Approved
                    if($order->is_credit == ORDER_IS_CREDIT['CREDIT']) {
                        OrderCreditDays::saveOrderCreditDayStatus($orderId, $selectedStatusID);//Credit Approved status save on OrderCreditDayStatus
                    }
                    OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'status', 'new_value' => $selectedStatusID, 'old_value' => $lastOrderStatus]);
                    $sendMsg = $this->verify->sendMsg($userData->firstname,$userData->lastname,'order_credit_approved',$userData->phone_code,$userData->mobile,$smsData);
                    $sendMsg = $this->verify->sendMsg($userData->firstname,$userData->lastname,'order_payment_pending',$userData->phone_code,$userData->mobile,$smsData);

                    $selectedStatusID = 4;//Order in Progress
                    $lastOrderStatus = 9;
                    // set default Itemstatus to 1 when order is in progress and generate po if not genereated(Vrutika Rana 12-09-2022)
                     $orderPo = $order->orderPo()->first();
                    if (!$orderPo){
                        $data['id'] = $order->id;
                        $data['comment'] = $order->po_comment;
                        $data['user_id'] = Auth::check()? Auth::id(): $authUserId;
                        $poHtml = $this->directAndButtonGenerateCodeForPO($data, 1);
                    }
                    $orderItems = $order->orderItems()->get();
                    foreach ($orderItems as $orderItem) {
                        $lastOrderItemStatus = $orderItem->order_item_status_id;
                        $orderItemId = $orderItem->id;
                        OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$order->id,'order_item_id'=>$orderItemId,'status_id'=>1,'user_id'=>$authUserId]);
                        OrderActivity::createOrUpdateOrderActivity(['order_id'=>$order->id,'order_item_id'=>$orderItemId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'order_item_status', 'new_value' => 1, 'old_value' => $lastOrderItemStatus]);
                    }
                    OrderItem::where('order_id',$order->id)->update(['order_item_status_id'=>1]);
                }elseif($selectedStatusID == 7){//Order cancel
                    if($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                        $loanCancelProcess = true;
                    }
                } elseif ($selectedStatusID == 5) {//Order Completed
                    $ot = OrderTrack::where(['order_id'=>$orderId,'status_id'=>8])->first('id');
                    if (!empty($ot) && isset($ot->id)) {
                        $order->order_status = $selectedStatusID;
                    } else {
                        $xendit = new XenditController;
                        $xendit->createInvoice($order);
                        $selectedStatusID = 8;//Payment Due on %s
                    }
                } elseif ($selectedStatusID == 3) {//Payment Done
                    setOrderPaymentStatus($order);//set payment status
                }

                // Begin: loan cancel
                $loanApplyDetails = $order->loanApply()->first(['provider_loan_id', 'user_id', 'id', 'loan_number','applicant_id','provider_user_id','loan_confirm_amount']);
                if($loanCancelProcess == true && !empty($loanApplyDetails)){
                    $loanStatus = $order->loanApply->loanStatus->status_display_name;
                    $koinworks = new KoinWorkController;
                    $returnData = $koinworks->loanPartnerCancel($loanApplyDetails->provider_user_id, $loanApplyDetails->provider_loan_id);
                    $order->loanApply->update(['status_id' => LOAN_STATUS['PARTNER_CANCELLED'] ]);// update loan cancelled status in loan applies

                  // $reservedAmount = $order->loanApply->loanApplications->reserved_amount;
                    $remainingAmount = $order->loanApply->loanApplications->remaining_amount;
                    $reservedAmount = $order->loanApply->loanApplications->reserved_amount;
                 // $updatedReserveAmount = ((int) $reservedAmount) + ((int) $loanApplyDetails->loan_confirm_amount);
                    $updatedRemainingAmount = ((int) $remainingAmount) + ((int) $loanApplyDetails->loan_confirm_amount);
                    $updatedReserveAmount = ((int) $reservedAmount) - ((int) $loanApplyDetails->loan_confirm_amount);
                // $order->loanApply->loanApplications->update(['reserved_amount' => $updatedReserveAmount]); // Update reserve amount of limit table:loan application
                    $order->loanApply->loanApplications->update(['remaining_amount' => $updatedRemainingAmount,'reserved_amount' => $updatedReserveAmount]); // Update reserve amount of limit table:loan application

                    LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$loanApplyDetails->applicant_id,'user_id'=>$loanApplyDetails->user_id,'response_code'=>$returnData['status'],'response_data'=>json_encode($returnData)]);

                    /**begin: system log**/
                    LoanApply::bootSystemView(new LoanApply(), 'Loan Cancel', SystemActivity::UPDATED);
                    /**end: system log**/

                    /** start notification: loan cancel **/
                    buyerNotificationInsert($order->user_id, 'Admin Loan Cancel', 'loan_cancel_notification', 'loan', $loanApplyDetails->id, ['loan_number' => $loanApplyDetails->loan_number , 'status' => $loanStatus , 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);

                    $getAllAdmin = getAllAdmin();
                    $sendAdminNotification = [];
                    if (!empty($getAllAdmin)){
                        foreach ($getAllAdmin as $key => $value){
                            $sendAdminNotification[] = [
                                'user_id' => 1, 'admin_id' => $value, 'user_activity' => 'Admin Loan Cancel',
                                'translation_key' => 'loan_cancel_notification', 'notification_type' => 'loan', 'notification_type_id'=> $loanApplyDetails->id,
                                'common_data' => json_encode([
                                    'loan_number' => $loanApplyDetails->loan_number, 'status' => $loanStatus,
                                    'updated_by' => 'blitznet team', 'icons' => 'fa-gear'
                                ]),
                                'created_at' => Carbon::now()
                            ];
                        }
                        Notification::insert($sendAdminNotification);
                    }

                    // broadcast(new LoanEvent());
                    /** end notification: loan cancel **/

                }
                // End: loan cancel

                //Payment Done,Order Completed,Payment Due on %s
                if (!in_array($selectedStatusID,[3,5,8])) {
                    $order->payment_due_date = null;
                }
            }
            $order->order_status = $selectedStatusID;
        }else{
            if ($selectedStatusID==3){//Payment Done
                setOrderPaymentStatus($order);//set payment status
                $orderPo = $order->orderPo()->first();
                if (!$orderPo){
                    $data['id'] = $order->id;
                    $data['comment'] = $order->po_comment;
                    $data['user_id'] = Auth::check()? Auth::id(): $authUserId;
                    $poHtml = $this->directAndButtonGenerateCodeForPO($data, 1);
                }
                // set default Itemstatus to 1 when order is in progress and generate po if not genereated(Vrutika Rana 12-09-2022)
                $orderItems = $order->orderItems()->get();
                foreach ($orderItems as $orderItem) {
                    $lastOrderItemStatus = $orderItem->order_item_status_id;
                    $orderItemId = $orderItem->id;
                    OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$order->id,'order_item_id'=>$orderItemId,'status_id'=>1,'user_id'=>$authUserId]);
                    OrderActivity::createOrUpdateOrderActivity(['order_id'=>$order->id,'order_item_id'=>$orderItemId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'order_item_status', 'new_value' => 1, 'old_value' => $lastOrderItemStatus]);
                }
                OrderItem::where('order_id',$order->id)->update(['order_item_status_id'=>1]);
                OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'status', 'new_value' => $selectedStatusID, 'old_value' => $lastOrderStatus]);

                $selectedStatusID = 4;//Order in Progress
                $lastOrderStatus = 3;
            }
            $order->order_status = $selectedStatusID;
        }
        $order->save();
        $smsData['status'] = $order->orderStatus->name;
        $sendMsg = $this->verify->sendMsg($userData->firstname,$userData->lastname,'order_status_updated',$userData->phone_code,$userData->mobile,$smsData);

        // advance && (Order Confirmed & Payment Pending || Credit Rejected)
        if ($order->is_credit == ORDER_IS_CREDIT['CASH'] && ($selectedStatusID == 2 || $selectedStatusID == 10)){
            $xendit = new XenditController;
            $xendit->createInvoice($order);
        }

        $orderStatus = DB::table('order_status')->where('id', $selectedStatusID)->first();

        if ($order->is_credit == ORDER_IS_CREDIT['CASH']) {
            //for deleting all previous status
            if ($order->order_status != 7){
                $this->deletePreviousStatus($orderId, $orderStatus->show_order_id);
            }
            //for adding all previous status when direclty select status.
            $this->addAllMissingStatus($order, $orderStatus, $orderTrackUserId);
        }else{

            if ($order->payment_type == 3 || $order->payment_type == 4 )
            {

                //for deleting all previous Credit status
                if ($order->order_status != 7) {
                    $this->deletePreviousStatus($orderId, $orderStatus->show_order_id);
                }
                //for adding all previous Credit status when direclty select status.
                $this->addAllMissingStatus($order, $orderStatus, $orderTrackUserId);
            }
            else{
                //for deleting all previous Credit status
                if ($order->order_status != 7) {
                    $this->deletePreviousCreditStatus($order, $orderStatus);
                }
                //for adding all previous Credit status when direclty select status.
                $this->addAllMissingCreditStatus($order, $orderStatus, $orderTrackUserId,$lastOrderStatus);
            }
        }

        //order track
        $orderTrack = OrderTrack::createOrUpdateOrderTrack(['order_id'=>$orderId,'status_id'=>$selectedStatusID,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType]);

        //order activity
        OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'status', 'new_value' => $selectedStatusID, 'old_value' => $lastOrderStatus]);

        $statusName = __('order.'.trim($orderStatus->name));
        if($selectedStatusID==8)
            $statusName = $order->payment_due_date?sprintf($statusName,changeDateFormat($order->payment_due_date,'d/m/Y')):sprintf($statusName,'DD/MM/YYYY');;
        //user activity
        //UserActivity::createOrUpdateUserActivity(['user_id'=>$order->user_id,'activity'=>"Your order " . $order->order_number . ' ' . $statusName,'type'=>'order','record_id'=>$orderId]);
        if (Auth::check()) {
            /** check if order status is updated by User or Admin (Vrutika 19/12/2022) **/
            if (Auth::user()->role_id == 2){$updatedBy = Auth::user()->firstname.' '.Auth::user()->lastname;}else{$updatedBy = 'blitznet team';}
            $commanData = array('order_number' => $order->order_number, 'updated_by' => $updatedBy, 'order_status' => trim($orderStatus->name), 'payment_due_date' => $order->payment_due_date ? changeDateFormat($order->payment_due_date, 'd/m/Y') : '', 'icons' => 'fa-truck');
            buyerNotificationInsert($order->user_id, 'Order Main Status', 'buyer_main_order_status', 'order', $orderId, $commanData);
            broadcast(new BuyerNotificationEvent());
        }
        if ($selectedStatusID == 5) {//'Order Completed'

                $finsOrder = Order::where('rfq_id', $order->rfq_id);
                $total_Rfqs = $finsOrder->get()->count();
                $total_order = $finsOrder->where('order_status', '5')->get()->count();
                if ($total_Rfqs == $total_order){
                    $rfq = $order->rfq()->first();
                    $rfq->status_id = 4;
                    $rfq->save();
                }

                /*
                     * For Blitznet invoice Start
                     * code added by vrutika
                     * Date:-18/07/2022
                     * */

                $orderPo = OrderPo::where('order_id', $orderId)->first();
                $orderPo->inv_number = 'INV/' . \Carbon\Carbon::now()->format('Y/m/d') . '/BUI/00000' . $orderPo->id;
                $orderPo->save();
                $approversList = NULL;
                $full_charges = Order::join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
                    ->where('orders.id', $order->id)
                    ->whereIn('quotes_charges_with_amounts.charge_type', [1,2])
                    ->orderBy('quotes_charges_with_amounts.charge_type', 'asc')->get();

                $returnblitznetInvoice = view('admin/pdf/blitznetInvoice', ['order' => $order, 'quotes_charges_with_amounts' => $full_charges, 'approversList' => $approversList])->render();
                $buyer_path = 'buyer-blitznet-invoice-pdf/' . str_replace("/", "_", $orderPo->inv_number) . '.pdf';
                if(!File::exists('buyer-blitznet-invoice-pdf/')) {
                    File::makeDirectory('buyer-blitznet-invoice-pdf/', $mode = 0755, true, true);
                }
                PDF::loadHTML($returnblitznetInvoice)
                    ->setPaper('a4', 'portrait')
                    ->setWarnings(false)
                    ->save($buyer_path);

                //End
        }

        if (!empty($order->rfq_id)) {
            $hideStatus = [7];//Order Cancelled
            if (!in_array($selectedStatusID, $hideStatus)) {
                //Credit Approved || Credit Rejected
                if ($selectedStatusID==9 || $selectedStatusID==10){
                    dispatch(new SendOrderStatusMailToBuyerJob($order,1));
                }else {
                    dispatch(new SendOrderStatusMailToBuyerJob($order));
                }
                //not Credit Approved && not Credit Rejected
                if ($selectedStatusID!=9 && $selectedStatusID!=10) {
                   dispatch(new SendOrderStatusMailToSupplierJob($order));
                }
            }
        }

        $orderTrack->created_at_proper = changeDateTimeFormat($orderTrack->created_at,'d-m-Y H:i:s');

        $response = array('success' => true);
        if (!empty($otherData)) {
            if ($otherData['is_backend_request']==1){
                $response['orderStatusHtml'] = $this->getOrderStatusDetails($orderId);
                $response['orderStatusDropDownHtml'] = $this->getOrderStatusDropDown($order->refresh());
                $response['paymentDetailHtml'] = '';
                if (in_array($selectedStatusID,[4,5])) {
                    $response['paymentDetailHtml'] = $this->getPaymentDetailsTab($order->refresh());
                }
                $response['poHtml'] = $poHtml??'';
            }else{
                $dashboardController = new DashboardController;
                $refreshData = $dashboardController->refreshDashboardOrder($orderId)->getData();
                $response['html'] = $refreshData->html;
                $response['orderTrack'] = $orderTrack;
                $response['order_status_name'] = $statusName;
            }
        }


        $this->adminUpdateNotificationChange($orderId, $order->supplier_id, $selectedStatusID, $lastOrderStatus, 'Change Order Status', 'order_status_change_notification', $order->is_credit, '', $authUserId, $authUserRoleId);
        return response()->json($response);
    }

    public function adminUpdateNotificationChange($orderId, $supplier_id, $new_order_status, $old_order_status, $user_activity, $translation_key, $is_credit, $orderItem = '', $authUserId = 1, $authUserRoleId = 1 ){
        if ($authUserRoleId == 1){
            $sendAdminNotification[] = array('user_id' => $authUserId, 'admin_id' => $authUserId, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => 'order', 'notification_type_id'=> $orderId, 'common_data' => json_encode(['old_key' =>$old_order_status, 'new_key' => $new_order_status, 'order_item' => $orderItem, 'is_credit' => $is_credit]), 'created_at' => Carbon::now());
            $sendSupplierNotification[] = array('user_id' => $authUserId, 'supplier_id' => $supplier_id, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => 'order', 'notification_type_id'=> $orderId, 'common_data' => json_encode(['old_key' =>$old_order_status, 'new_key' => $new_order_status, 'order_item' => $orderItem, 'is_credit' => $is_credit]), 'created_at' => Carbon::now());
        } else {
            if ($authUserRoleId == 3) {
                $supplier_id = UserSupplier::where('user_id', $authUserId)->pluck('supplier_id')->first();
            }
            $getAllAdmin = getAllAdmin();
            $sendAdminNotification = [];
            if (!empty($getAllAdmin)){
                foreach ($getAllAdmin as $key => $value){
                    if ($authUserRoleId== 3) {
                        $sendAdminNotification[] = array('user_id' => $authUserId, 'admin_id' => $value, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => 'order', 'notification_type_id' => $orderId, 'common_data' => json_encode(['old_key' => $old_order_status, 'new_key' => $new_order_status, 'order_item' => $orderItem, 'is_credit' => $is_credit]),'created_at' => Carbon::now());
                    } else {
                        $sendAdminNotification[] = array('user_id' => $authUserId, 'admin_id' => $value, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => 'order', 'notification_type_id' => $orderId, 'common_data' => json_encode(['old_key' => $old_order_status, 'new_key' => $new_order_status, 'order_item' => $orderItem, 'is_credit' => $is_credit]), 'created_at' => Carbon::now());
                    }
                }
            }
            $sendSupplierNotification = array('user_id' => $authUserId, 'supplier_id' => $supplier_id, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => 'order', 'notification_type_id'=> $orderId, 'common_data' => json_encode(['old_key' =>$old_order_status, 'new_key' => $new_order_status, 'order_item' => $orderItem, 'is_credit' => $is_credit]), 'created_at' => Carbon::now());
            //Notification::insert($sendSupplierNotification);
        }
        Notification::insert($sendAdminNotification);
        Notification::insert($sendSupplierNotification);
        broadcast(new rfqsEvent());
    }


    //delete previous status for advanced
    public function deletePreviousStatus($orderId, $show_order_id)
    {
        $orderTracks = DB::table('order_tracks')
            ->join('order_status', 'order_tracks.status_id', '=', 'order_status.id')
            ->where('order_status.show_order_id', '>=', $show_order_id)
            ->where('order_tracks.order_id', intval($orderId))
            ->delete();
    }
    //add All Missing Status for advanced
    public function addAllMissingStatus($order, $currentOrderStatus, $orderTrackUserId)
    {
        $orderTracks = DB::table('order_tracks')
            ->where('order_tracks.order_id', $order->id)
            ->join('order_status', 'order_tracks.status_id', '=', 'order_status.id')
            ->orderBy('order_status.show_order_id', 'desc')
            ->get(['order_tracks.*', 'order_status.show_order_id']);

        if($order->payment_type !=0 )
        {
            if (count($orderTracks) && $orderTracks[0]->status_id && $order->order_status!=7) {
                if(isset($order->quote->getUser->role_id) && $order->quote->getUser->role_id == 3)
                {
                    for ($i = $orderTracks[0]->show_order_id + 1; $i < $currentOrderStatus->show_order_id; $i++) {
                        if($i==2){
                            $orderStatusId = 12;
                        }
                        else
                        {
                            $orderStatus = DB::table('order_status')
                            ->where('order_status.show_order_id', $i)
                            ->first();
                            $orderStatusId = $orderStatus->id;
                        }
                        if($orderStatusId != 10 && $orderStatusId != 3)
                        {
                            OrderTrack::createOrUpdateOrderTrack(['order_id'=>$order->id,'status_id'=>$orderStatusId,'user_id'=>$orderTrackUserId,'user_type'=>User::class]);
                            if ( $orderStatusId == 5) {
                                $rfq = $order->rfq()->first();
                                $rfq->status_id = 4;
                                $rfq->save();
                            }
                        }
                    }
                }else{
                    for ($i = $orderTracks[0]->show_order_id + 1; $i < $currentOrderStatus->show_order_id; $i++) {
                        if($i==2){
                            $orderStatusId = 11;
                        }
                        else
                        {
                            $orderStatus = DB::table('order_status')
                            ->where('order_status.show_order_id', $i)
                            ->first();
                            $orderStatusId =  $orderStatus->id;
                        }
                        if($orderStatusId != 10){
                            OrderTrack::createOrUpdateOrderTrack(['order_id'=>$order->id,'status_id'=>$orderStatusId,'user_id'=>$orderTrackUserId,'user_type'=>User::class]);
                            if ( $orderStatusId == 5) {
                                $rfq = $order->rfq()->first();
                                $rfq->status_id = 4;
                                $rfq->save();
                            }
                        }
                    }
                }
            }
        }
        else{
            $order_returned = [5];//'Order Completed'
            if (count($orderTracks) && $orderTracks[0]->status_id && $order->order_status!=7) {
                for ($i = $orderTracks[0]->show_order_id + 1; $i < $currentOrderStatus->show_order_id; $i++) {

                    $orderStatus = DB::table('order_status')
                        ->where('order_status.show_order_id', $i)
                        ->first();
                    //'Order Returned' && $order_returned
                    if ($currentOrderStatus->id == 6 && in_array($orderStatus->id,$order_returned)){
                        continue;
                    }elseif ($orderStatus->id==10){//Credit Rejected
                        continue;
                    }

                    OrderTrack::createOrUpdateOrderTrack(['order_id'=>$order->id,'status_id'=>$orderStatus->id,'user_id'=>$orderTrackUserId,'user_type'=>User::class]);
                    if ($orderStatus->id == 5) {
                        $rfq = $order->rfq()->first();
                        $rfq->status_id = 4;
                        $rfq->save();
                    }
                }
            }
        }
    }
    //delete previous status for Credit
    public function deletePreviousCreditStatus($order,$currentOrderStatus){
        DB::table('order_tracks')
            ->join('order_status', 'order_tracks.status_id', '=', 'order_status.id')
            ->where('order_status.credit_sorting', '>=', $currentOrderStatus->credit_sorting)
            ->where('order_tracks.order_id', $order->id)
            ->delete();
    }

    //add All Missing Status for Credit
    public function addAllMissingCreditStatus($order,$currentOrderStatus,$orderTrackUserId,$lastOrderStatus){
        /*preDump($order,0);
        preDump($currentOrderStatus);*/
        $orderTracks = DB::table('order_tracks')
            ->join('order_status', 'order_tracks.status_id', '=', 'order_status.id')
            ->where('order_tracks.order_id', $order->id)
            ->orderBy('order_status.credit_sorting', 'desc')
            ->get(['order_tracks.*', 'order_status.credit_sorting']);

        //preDump($orderTracks);

        $order_returned = [3,5,8];//'Payment Done','Order Completed','Payment Due DD/MM/YYYY'
        if (count($orderTracks) && $orderTracks[0]->status_id  && $order->order_status!=7) {
            for ($i = $orderTracks[0]->credit_sorting + 1; $i < $currentOrderStatus->credit_sorting; $i++) {

                $orderStatus = DB::table('order_status')
                    ->where('credit_sorting', $i)
                    ->first();

                if ($currentOrderStatus->id == 4 && $lastOrderStatus == 9 && $orderStatus->id == 10){//Order in Progress && Credit Approved && Credit Rejected
                    continue;
                }elseif ($currentOrderStatus->id == 6 && in_array($orderStatus->id,$order_returned)){//'Order Returned' && $order_returned
                    continue;
                }

                OrderTrack::createOrUpdateOrderTrack(['order_id'=>$order->id,'status_id'=>$orderStatus->id,'user_id'=>$orderTrackUserId,'user_type'=>User::class]);
                if ($orderStatus->id == 5) {//'Order Completed'
                    $rfq = $order->rfq()->first();
                    $rfq->status_id = 4;
                    $rfq->save();
                }
            }
        }
    }

    //order Item Status Change
    public function orderItemStatusChange(Request $request)
    {
        $selectedStatusID = $request->selectedStatusID;
        $orderId = $request->orderId;
        $batchId = $request->batchId;
        $orderItemId = $request->orderItemId;
        $otherData['is_backend_request'] = $request->is_backend_request ?? 0;
        if ($request->is_backend_request == 0) {
            $selectedItemIds = json_decode($request->selectedItemIds);
        } else {
            $selectedItemIds = array_map('intval', explode(',', $request->selectedItemIds));
        }
        $isValidate = (int)$request->is_validate ?? 0;
        $totalOrderItems = 0;
        $order = Order::find($orderId);
        $isSingleStatusChangeRequest = 0;
        if (!empty($orderItemId)) {
            $isSingleStatusChangeRequest = 1;
            $totalOrderItems = 1;
            $orderItem = OrderItem::find($orderItemId);
        } else {
            if (isset($batchId) && $batchId != '') {
                $orderItems = $order->orderItems()->where('order_batch_id', $batchId)->get();
            } else {
                if (isset($request->selectedItemIds) && $request->selectedItemIds != '') {
                    $orderItems = $order->orderItems()->whereIn('id', $selectedItemIds)->get();
                } else {
                    $orderItems = $order->orderItems()->where('order_id', $orderId)->get();
                }
            }
            $totalOrderItems = $orderItems->count();
            $orderItem = $orderItems[0];
        }
        if ($otherData['is_backend_request'] === 0 && in_array($selectedStatusID, [7, 8])) {
            //for front end request && QC Failed,QC Passed status
            if (isset($request->isServiceOrder) && $request->isServiceOrder == 1) {
                foreach ($orderItems as $i => $orderItem) {
                    $oData = [];
                    //last order item data return
                    if ($i == ($totalOrderItems - 1)) {
                        $oData = $otherData;
                    }
                    $result = $this->setOrderItemStatus($selectedStatusID, $order, $orderItem, $oData);
                }
                return $result;
            } else {
                    return $this->setOrderItemStatus($selectedStatusID, $order, $orderItem, $otherData);
            }
        }
        $result = $this->orderItemStatusValidationCheck($orderId,$orderItem,$selectedStatusID,$isSingleStatusChangeRequest)->getData();

        if (($result->valid===1 && $isValidate)){
            if (!empty($orderItemId)||$totalOrderItems===1){
                return $this->setOrderItemStatus($selectedStatusID, $order, $orderItem, $otherData);
            }else{
                foreach ($orderItems as $i=>$orderItem) {
                    $oData = [];
                    //last order item data return
                    if ($i==($totalOrderItems-1)){
                        $oData = $otherData;
                    }
                    $result = $this->setOrderItemStatus($selectedStatusID, $order, $orderItem, $oData);
                }
                return $result;
            }
            //if get single entry enable this code other wise go to setOrderItemStatus function remove code and uncomment this code
            //$this->adminUpdateNotificationChange($orderId, $order->supplier_id, $selectedStatusID, $orderItem->order_item_status_id, $user_activity = 'Change Order Item Status');
        }
        return $this->orderItemStatusValidationCheck($orderId,$orderItem,$selectedStatusID,$isSingleStatusChangeRequest);
    }

    public function orderItemStatusValidationCheck($orderId,$orderItem,$selectedStatusID,$isSingleStatusChangeRequest){
        $order = Order::find($orderId);
        $itemsManageSeparately = $order->items_manage_separately;
        $batchItemsManageSeparately = OrderBatch::where('id',$orderItem->order_batch_id)->value('batch_item_manage_separately');
        $deliveryManageSeparately = $order->delivery_manage_separately;
        $orderPo = $order->orderPo()->first(['id']);
        $orderItems = $order->orderItems()->get();
        $totalOrderItems = $orderItems->count();
        $totalDeliveredItems = $orderItems->where('order_item_status_id',10)->count();
        $logisticProvided = $order->quote->quoteItem()->value('logistic_provided');
        $isStatusChangeValid = ($totalOrderItems===$totalDeliveredItems);

        $swal = [
            //'title'=> __('admin.something_error_message'),
            'icon' => "/assets/images/info.png",
            'buttons' => [__('admin.cancel'), __('admin.ok')],
            'dangerMode' => false,
        ];

        if ($order->order_status!=4){
            $swal['title'] = 'Status change not allowed.';
            return response()->json(['success' => false, 'valid' => 0, 'swal' => $swal]);
        }elseif ($isSingleStatusChangeRequest!=0 && ($batchItemsManageSeparately==0 && $deliveryManageSeparately==0)){
            $swal['title'] = 'Status change not allowed.';
            return response()->json(['success' => false, 'valid' => 0, 'swal' => $swal]);
        }elseif (!isOrderItemStatusChangeAllow($selectedStatusID,$logisticProvided)){
            $swal['title'] = 'Status change not allowed.';
            return response()->json(['success' => false, 'valid' => 0, 'swal' => $swal]);
        }/*elseif ($isStatusChangeValid){
            $swal['title'] = 'Status change not allowed.';
            return response()->json(['success' => false, 'valid' => 0, 'swal' => $swal]);
        }*/

        if (empty($orderPo)) {
            if(auth()->user()->role_id == 1) {
                $swal['title'] = "First, you should generate a PO!";
            }else{
                $swal['title'] = "Please Contact to Blitznet Team and Generate Po.";
            }
            return response()->json(['success' => false, 'valid'=>0, 'swal' => $swal,'trigger'=>'generatePO']);
        }
        if(($selectedStatusID >= 3) && empty($orderItem->order_latter)) {
            $swal['title'] = 'First, you should Upload Order Letter !';
            return response()->json(['success' => false, 'valid'=>0, 'swal' => $swal]);
        }
        if(($selectedStatusID==8 || $selectedStatusID==7) && $order->payment_type > 2)
        {
            if(!$order->upload_order_doc){
                return response()->json(['success' => false, 'valid'=>0, 'swal' => 'lcdoc']);
            }
        }
        return response()->json(['success' => true,'valid'=>1]);
    }

    //set order item status
    public function setOrderItemStatus($selectedStatusID,$order,$orderItem,$otherData=[]){
        $lastOrderItemStatus = $orderItem->order_item_status_id;
        $orderId = $order->id;
        $orderItemId = $orderItem->id;
        $authUserId = Auth::check()? Auth::id(): 1;
        $authUserRoleId = Auth::check() ? Auth::user()->role_id : 1;
        $orderTrackUserType = User::class;

        if ($selectedStatusID==7){//QC Failed
            OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'status_id'=>$selectedStatusID,'user_id'=>$authUserId]);
            OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'order_item_status', 'new_value' => $selectedStatusID, 'old_value' => $lastOrderItemStatus]);
            $selectedStatusID = 9;//Order Troubleshooting
            $lastOrderItemStatus = 7;//QC Failed
        }elseif ($selectedStatusID==8){//QC Passed
            OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'status_id'=>$selectedStatusID,'user_id'=>$authUserId]);
            OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'order_item_status', 'new_value' => $selectedStatusID, 'old_value' => $lastOrderItemStatus]);
            $selectedStatusID = 10;
            $lastOrderItemStatus = 8;//QC Passed
        }elseif ($selectedStatusID==9){//if direct status change to Order Troubleshooting then add qc failed
            OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'status_id'=>7,'user_id'=>$authUserId]);
            OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'order_item_status', 'new_value' => 7, 'old_value' => $lastOrderItemStatus]);
        }

        if ($order->is_credit) {
            $orderItem->order_item_status_id = $selectedStatusID;
        }else{
            $orderItem->order_item_status_id = $selectedStatusID;
        }
        $orderItem->save();


        $orderItemStatus = DB::table('order_item_status')->where('id', $selectedStatusID)->first();

        //for deleting all previous status
        $this->deletePreviousItemStatus($orderItemId, $orderItemStatus->sort);
        //for adding all previous status when direclty select status.
        $this->addAllMissingItemStatus($orderId, $orderItemId, $orderItemStatus, $authUserId);

        //order track
        $orderItemTrack = OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'status_id'=>$selectedStatusID,'user_id'=>$authUserId]);

        //order activity
        OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'order_item_status', 'new_value' => $selectedStatusID, 'old_value' => $lastOrderItemStatus]);

        $statusName = __('order.'.trim($orderItemStatus->name));
        //user activity
        //UserActivity::createOrUpdateUserActivity(['user_id'=>$order->user_id,'activity'=>"Your order " . $order->order_number . "'s Item  " . $orderItem->order_item_number . " " . $statusName,'type'=>'order_item','record_id'=>$orderId]);
        $commanData = [];
        //$commanData = array('order_number' => $order->order_number, 'updated_by' => 'blitznet team', 'order_status' => trim($orderStatus->name), 'payment_due_date' => $order->payment_due_date);
        if((int)Auth::user()->role_id == 1){
            $commanData = array('order_number' => $order->order_number, 'updated_by' => 'blitznet team', 'order_status' => trim($orderItemStatus->name), 'order_item_number' => $orderItem->order_item_number, 'icons' => 'fa-truck');
        }else{
            $commanData = array('order_number' => $order->order_number, 'updated_by' => Auth::user()->full_name, 'order_status' => trim($orderItemStatus->name), 'order_item_number' => $orderItem->order_item_number, 'icons' => 'fa-truck');
        }
        buyerNotificationInsert($order->user_id, 'Order Inner Status Change', 'buyer_inner_status_change', 'order', $orderId, $commanData);
        broadcast(new BuyerNotificationEvent());

        //email to buyer
        dispatch(new SendOrderItemStatusMailToBuyerJob($orderItem));
        //email to supplier
        dispatch(new SendOrderItemStatusMailToSupplierJob($orderItem));

        $orderItemTrack->created_at_proper = changeDateTimeFormat($orderItemTrack->created_at,'d-m-Y H:i:s');

        $response = array('success' => true);
        if (!empty($otherData)) {
            $orderItems = $order->orderItems()->get();
            $totalOrderItems = $orderItems->count();
            $totalDeliveredItems = $order->orderItems()->where(['order_item_status_id'=>10])->count();
            $isAllDelivered = 0;
            $order->payment_due_date = null;
            if ($totalOrderItems==$totalDeliveredItems) {//all items delivered
                if ($order->is_credit) {
                    if ($order->is_credit==ORDER_IS_CREDIT['CREDIT']) {
                        $xendit = new XenditController;
                        $xendit->createInvoice($order);
                        $ocd = $order->orderCreditDay()->first(['approved_days']);
                        $order->payment_due_date = date('Y-m-d', strtotime('+' . $ocd->approved_days . ' days'));
                        $order->save();
                        $orderMainStatus = 8;//Payment Due
                        if($lastOrderItemStatus==10){
                            $userData = User::find($order->user_id);
                            $smsData['order_number'] = $order->order_number;
                            $sendMsg = $this->verify->sendMsg($userData->firstname,$userData->lastname,'order_payment_pending',$userData->phone_code,$userData->mobile,$smsData);
                        }
                        $this->setOrderStatusChange($orderMainStatus, $orderId, []);
                    }elseif ($order->is_credit==ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                        $loanController = new LoanController;
                        $loanController->loanConfirmation($order);
                    }
                    $response['orderStatusHtml'] = $this->getOrderStatusDetails($orderId);
                }
                $isAllDelivered = 1;
            }
            $order->save();
            if ($otherData['is_backend_request']==1){
                //credit order and all order item status delivered
                if ($order->is_credit && $isAllDelivered){
                    $response['orderStatusDropDownHtml'] = $this->getOrderStatusDropDown($order->refresh());
                }elseif ($order->is_credit==0){//for advance order
                    $response['orderStatusDropDownHtml'] = $this->getOrderStatusDropDown($order->refresh());
                }
                $response['paymentDetailHtml'] = $this->getPaymentDetailsTab($order);
                $response['orderBatchesHtml'] = $this->getOrderBatchesData($order);
            } else {
                $dashboardController = new DashboardController;
                $refreshData = $dashboardController->refreshDashboardSingleOrderItemStatus($orderItemId)->getData();
                $response['is_all_item_delivered'] = $isAllDelivered;
                $response['orderItemStatusHtml'] = $refreshData->html;
                $response['orderItemTrack'] = $orderItemTrack;
                $response['order_status_name'] = $statusName;
            }
        }
        $this->adminUpdateNotificationChange($orderId, $order->supplier_id, $selectedStatusID, $lastOrderItemStatus, 'Change Order Item Status', 'order_item_status_change_notification', $order->is_credit, $orderItem->order_item_number, $authUserId, $authUserRoleId);
        return response()->json($response);
    }

    //delete previous item status
    public function deletePreviousItemStatus($orderItemId, $sort)
    {
        DB::table('order_item_tracks')
            ->join('order_item_status', 'order_item_tracks.status_id', '=', 'order_item_status.id')
            ->where('order_item_status.sort', '>=', $sort)
            ->where('order_item_tracks.order_item_id', $orderItemId)
            ->delete();
    }
    //add All Missing item Status
    public function addAllMissingItemStatus($orderId, $orderItemId, $currentOrderStatus, $orderTrackUserId)
    {
        $orderTracks = DB::table('order_item_tracks')
            ->where('order_item_tracks.order_item_id', $orderItemId)
            ->join('order_item_status', 'order_item_tracks.status_id', '=', 'order_item_status.id')
            ->orderBy('order_item_status.sort', 'desc')
            ->get(['order_item_tracks.*', 'order_item_status.sort']);
        $sort = 0;
        if (count($orderTracks) == $currentOrderStatus->sort){
            $sort = $orderTracks[0]->sort;
        }
        //dd(count($orderTracks));
        $order_returned = [8];//QC Passed
        for ($i = $sort + 1; $i < $currentOrderStatus->sort; $i++) {

            $orderStatus = DB::table('order_item_status')
                ->where('order_item_status.sort', $i)
                ->first();
            //'Order Troubleshooting' && $order_returned
            if (($currentOrderStatus->id == 9) && in_array($orderStatus->id,$order_returned)){
                continue;
            }
            //'QC Failed' && Order Troubleshooting
            if ($orderStatus->id != 7 && $orderStatus->id != 9) {
                OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'status_id'=>$orderStatus->id,'user_id'=>$orderTrackUserId,'user_type'=>User::class]);
            }
        }
        /*$orderTracks = DB::table('order_item_tracks')
            ->where(['order_item_tracks.order_item_id'=> $orderItemId])
            ->join('order_item_status', 'order_item_tracks.status_id', '=', 'order_item_status.id')
            ->orderBy('order_item_status.sort', 'desc')
            ->get(['order_item_tracks.*', 'order_item_status.sort']);

        for ($i = 1; $i < $currentOrderStatus->sort; $i++) {

            $orderStatus = DB::table('order_item_status')
                ->where('order_item_status.sort', $i)
                ->first(['id']);
            $data = ['order_id'=>$orderId,'order_item_id'=>$orderItemId,'status_id'=>$orderStatus->id,'user_id'=>$orderTrackUserId,'user_type'=>User::class];

            //if order track exist on deleted record then update created at time
            if ($orderTracks->where('status_id',$orderStatus->id)->whereNotNull('deleted_at')->count()){
                $data['created_at'] = Carbon::now();
            }
            //'Order Troubleshooting' && QC Passed
            if ($currentOrderStatus->id == 9 && $orderStatus->id==8){
                //$data['deleted_at'] = Carbon::now();
                continue;
            }elseif ($currentOrderStatus->id == 10 && $orderStatus->id==7){
                continue;
            }elseif ($currentOrderStatus->id == 10 && $orderTracks->where('status_id',9)->whereNull('deleted_at')->count() && $orderStatus->id==8){
                continue;
            }elseif ($currentOrderStatus->id == 10 && $orderTracks->where('status_id',8)->whereNull('deleted_at')->count() && $orderStatus->id==9){
                continue;
            }
            OrderItemTracks::createOrUpdateOrderItemTrack($data);
        }*/
    }

    //Generate AirWayBill Number
    public function generateAirWayBillNumber(Request $request) {
        $orderId = $request->orderId;
        $batchId = $request->batch_id;
        $pickupDate = changeDateTimeFormat($request->pickup_date,'d-m-Y');
        $pickupTime = changeDateTimeFormat($request->pickup_date,'H:i');
        $order = Order::find($orderId);

        $data = Order::join('quotes','quotes.id','=','orders.quote_id')
            ->join('suppliers','suppliers.id','=','quotes.supplier_id')
            ->join('user_companies','user_companies.user_id','=','orders.user_id')
            ->join('companies','companies.id','=','user_companies.company_id')
            ->join('users','users.id','=','orders.user_id')
            ->join('rfq_products','rfq_products.rfq_id','=','quotes.rfq_id')
            ->join('rfqs','rfqs.id','=','rfq_products.rfq_id')
            ->join('quote_items','quotes.id','=','quote_items.quote_id')
            ->where('orders.id',$orderId)
            ->first(['quotes.supplier_id','suppliers.name as supplierName','quotes.address_name as supp_address_name','quotes.address_line_1 as supp_address1','quotes.address_line_2 as supp_address2','quotes.district as supp_district','quotes.sub_district as supp_sub_district','quotes.city_id as supp_city','quotes.state_id as supp_provinces','quotes.pincode as supp_pincode','quotes.weights','quotes.dimensions',DB::raw("CONCAT(COALESCE(suppliers.contact_person_name,  ' '), ' ', COALESCE(suppliers.contact_person_last_name,  ' ')) AS supplierPicName"),'orders.order_number','companies.id as companyId','companies.name as companyName','suppliers.address as shipperAddress','suppliers.contact_person_phone as shipperNumber','suppliers.email as shipperEmail','rfqs.firstname','rfqs.lastname',
            'rfqs.address_name','rfqs.address_line_1','rfqs.address_line_2','rfqs.city','rfqs.state','rfqs.city_id','rfqs.state_id','rfqs.sub_district','rfqs.district','rfqs.pincode as recipientPincode','rfqs.mobile as recipientNumber','rfqs.email as recipientEmail','rfq_products.quantity','rfq_products.product_description','quotes.supplier_final_amount','quote_items.length','quote_items.width','quote_items.height','quote_items.logistics_service_code','quote_items.insurance_flag','quote_items.wood_packing','quote_items.pickup_service','quote_items.pickup_fleet']);

        $orderItems = $order->orderItems()->where('order_batch_id',$batchId)->where('is_in_batch',1)->orderBy('order_item_number')->get();
        $orderBatch = OrderBatch::find($batchId);
        $data->wood_packing = ($data->wood_packing == 1) ? "YES" : "NO";
        $data->insurance_flag = ($data->insurance_flag == 0) ? 1 : $data->insurance_flag;
        $detailCharged = [];$internalRef = '';$productAmount = 0;$orderItemsBatch=[];
        foreach($orderItems as $orderItem){
            $orderItemsBatch[] = implode(',',(array)$orderItem->id);
             $internalRef =  $internalRef != '' ? $internalRef.','.$orderItem->id : $orderItem->id;
            $quoteItem = $orderItem->quoteItem()->first();
            $quoteItem->length = ($quoteItem->length == 0) ? 1 : $quoteItem->length;
            $quoteItem->height = ($quoteItem->height == 0) ? 1 : $quoteItem->height;
            $quoteItem->width = ($quoteItem->width == 0) ? 1 : $quoteItem->width;
            $product_description = $orderItem->id.'-'.get_product_desc_by_id($orderItem->rfq_product_id);

            $detailCharged[] = ["weight" => $quoteItem->weights, "quantity" => $quoteItem->product_quantity, "length" => $quoteItem->length, "width" => $quoteItem->width, "height" => $quoteItem->height, "description" => strlen($product_description) > 60 ? substr($product_description, 0, 58) : $product_description];
            $productAmount = $productAmount + ($quoteItem->product_amount);
        }
        if (config('app.env') == "live"){
            $apiKey = '10e800c1-576a-4ecd-96fb-7e363c706181';
            $companyId = "CGKN00063";
        }else{
            $apiKey = 'a33ad093-b123-4a81-8ded-ac276e8a98dc';
            $companyId = "CGKN00062";
        }
        $goodsDescription = strlen($data->product_description) > 60 ? substr($data->product_description, 0, 58) : $data->product_description;
        $goodsDescriptionReplaceString = str_ireplace(array('"', "'","enter"), ' ',$goodsDescription);

        if ($orderBatch->user_address_id != '' && (Auth::user()->hasRole('buyer') || Auth::user()->hasRole('sub-buyer'))){

            $orderBatch->receiver_name = $request->get('receiver_name');
            $orderBatch->receiver_company_name = $request->get('receiver_company_name');
            $orderBatch->receiver_email_address = $request->get('receiver_email_address');
            $orderBatch->receiver_pic_phone = $request->get('receiver_pic_phone');
            $orderBatch->save();

            $orderBatch->refresh();

            $supplierAddress = $orderBatch->getUserAddress()->first();
            $cityName = $supplierAddress->getCity()->name ? $supplierAddress->getCity()->name : '';
            $stateName = $supplierAddress->getState()->name ?$supplierAddress->getState()->name:'';
            $requestData = [
                'headers' => [
                    'api-key' => $apiKey,
                ],
                'form_params' => [
                    "3pl" => "JNE",
                    "unique_reference_no" => $data->order_number,
                    "internal_reference" => $internalRef,
                    "company_id" => $companyId,
                    "supplier_company_name" => $data->companyName,
                    "supplier_pic" => $data->firstname . ' ' . $data->lastname,
                    "supplier_pic_phone" => $data->recipientNumber,
                    "supplier_address" => $supplierAddress->address_name. ',' . $supplierAddress->address_line_1 . ', ' . $supplierAddress->address_line_2 . ', ' . $supplierAddress->district . ', ' . $supplierAddress->sub_district . ', ' . $cityName . ', ' . $stateName,
                    "pickup_origin_code" => $supplierAddress->pincode,
                    "pickup_date" => $pickupDate,
                    "pickup_time" => $pickupTime,
                    "pickup_services" => $data->pickup_service,
                    "pickup_fleet" => $data->pickup_fleet,
                    "shipper_company_name" => $data->companyName,
                    "shipper_address" => $supplierAddress->address_name. ',' . $supplierAddress->address_line_1 . ', ' . $supplierAddress->address_line_2 . ', ' . $supplierAddress->district . ', ' . $supplierAddress->sub_district . ', ' . $cityName . ', ' . $stateName,
                    "shipper_postal_code" => $supplierAddress->pincode,
                    "shipper_email_address" => $data->recipientEmail,
                    "shipper_handphone_number" => $data->recipientNumber,
                    "shipper_address_longitude" => "",
                    "shipper_address_latitude" => "",
                    "city_origin_code" => $supplierAddress->pincode,
                    "recipient_company_name" => $request->get('receiver_company_name'),
                    "recipient_address" => $data->supp_address_name . ', ' . $data->supp_address1 . ', ' . $data->supp_address2 .  ',' . $data->supp_sub_district . ',' . $data->supp_district . ',' . getCityName($data->supp_city) . ', ' . getStateName($data->supp_provinces) ,
                    "recipient_postal_code" => $data->supp_pincode,
                    "recipient_email_address" => $request->get('receiver_email_address'),
                    "recipient_handphone_number" => $request->get('receiver_pic_phone'),
                    "recipient_pic_name" => $request->get('receiver_pic'),
                    "recipient_address_longitude" => "",
                    "recipient_address_latitude" => "",
                    "city_destination_code" => $data->supp_pincode,
                    "service_code" => $data->logistics_service_code,
                    "cn_goods_type" => "SHTPC",
                    "insurance_flag" => $data->insurance_flag,
                    "goods_value_are" => $productAmount,
                    "goods_description" => $goodsDescriptionReplaceString,
                    "goods_description_full" => '',
                    "wood_packing" => $data->wood_packing,
                    "detail_charged" => $detailCharged
                ]
            ];
        }else{
            $supplierAddress = $orderBatch->getSupplierAddress()->first();
            $cityName = $supplierAddress->getCity()->name ? $supplierAddress->getCity()->name : '';
            $stateName = $supplierAddress->getState()->name ?$supplierAddress->getState()->name:'';
            $requestData = [
                'headers' => [
                    'api-key' => $apiKey,
                ],
                'form_params' => [
                    "3pl" => "JNE",
                    "unique_reference_no" => $data->order_number,
                    "internal_reference" => $internalRef,
                    "company_id" => $companyId,
                    "supplier_company_name" => $data->supplierName,
                    "supplier_pic" => $data->supplierPicName,
                    "supplier_pic_phone" => $data->shipperNumber,
                    "supplier_address" => $supplierAddress->address_name. ',' . $supplierAddress->address_line_1 . ', ' . $supplierAddress->address_line_2 . ', ' . $supplierAddress->district . ', ' . $supplierAddress->sub_district . ', ' . $cityName . ', ' . $stateName,
                    "pickup_origin_code" => $supplierAddress->pincode,
                    "pickup_date" => $pickupDate,
                    "pickup_time" => $pickupTime,
                    "pickup_services" => $data->pickup_service,
                    "pickup_fleet" => $data->pickup_fleet,
                    "shipper_company_name" => $data->supplierName,
                    "shipper_address" => $supplierAddress->address_name. ',' . $supplierAddress->address_line_1 . ', ' . $supplierAddress->address_line_2 . ', ' . $supplierAddress->district . ', ' . $supplierAddress->sub_district . ', ' . $cityName . ', ' . $stateName,
                    "shipper_postal_code" => $supplierAddress->pincode,
                    "shipper_email_address" => $data->shipperEmail,
                    "shipper_handphone_number" => $data->shipperNumber,
                    "shipper_address_longitude" => "",
                    "shipper_address_latitude" => "",
                    "city_origin_code" => $supplierAddress->pincode,
                    "recipient_company_name" => $data->companyName,
                    "recipient_address" => $data->address_name . ', ' . $data->address_line_1 . ', ' . $data->address_line_2 . ', ' . $data->sub_district . ',' . $data->district . ',' . getCityName($data->city_id)  . ', ' . getStateName($data->state_id),
                    "recipient_postal_code" => $data->recipientPincode,
                    "recipient_email_address" => $data->recipientEmail,
                    "recipient_handphone_number" => $data->recipientNumber,
                    "recipient_pic_name" => $data->firstname . ' ' . $data->lastname,
                    "recipient_address_longitude" => "",
                    "recipient_address_latitude" => "",
                    "city_destination_code" => $data->recipientPincode,
                    "service_code" => $data->logistics_service_code,
                    "cn_goods_type" => "SHTPC",
                    "insurance_flag" => $data->insurance_flag,
                    "goods_value_are" => $productAmount,
                    "goods_description" => $goodsDescriptionReplaceString,
                    "goods_description_full" => '',
                    "wood_packing" => $data->wood_packing,
                    "detail_charged" => $detailCharged
                ]
            ];
        }
        $client = new Client();
        try {
            $response = $client->post('https://blitznet.omile.id/blitznet/restapi/basic/raw_data/shipment_text_gen', $requestData);
            $response = $response->getBody()->getContents();
            //Store Airwaybill number request and response (Ronak M - 06/09/2022)
            $logistics_provider_id = 1;  // 1 => quincus
            storeLogisticsAPIRequestResponse($logistics_provider_id, Auth::user()->id, $requestData, $responseCode = null, $response);
            $isError = json_decode($response,true);
            $isErrorMsg = $isError['status']['error'];

             if ($isErrorMsg==''){
                //Create AirWayBill number
                return $this->store_airwaybill_number($order,$batchId,$response);
            }else{
                if ($isErrorMsg !='Charged weight should not be more than 1999 kg'){
                    $updateOrderIeamData = ['batch_id'=>null,'is_in_batch'=>0,'orderItemsIds'=>$orderItemsBatch];
                    $UpdateBatchOrderDelivery  = OrderItem::UpdateOrderDelivery($updateOrderIeamData);
                    OrderBatch::where('id', $batchId)->delete();
                }
                return response()->json(array('success' => false,'msg'=> $isErrorMsg));
            }
        } catch (GuzzleException $e)  {
            $isResponse = $e->hasResponse();
            if ($isResponse==true){
                $updateOrderIeamData = ['batch_id'=>null,'is_in_batch'=>0,'orderItemsIds'=>$orderItemsBatch];
                $UpdateBatchOrderDelivery  = OrderItem::UpdateOrderDelivery($updateOrderIeamData);
                OrderBatch::where('id', $batchId)->delete();
                //Store Airwaybill number request and response (Ronak M - 06/09/2022)
                $logistics_provider_id = 1;  // 1 => quincus
                storeLogisticsAPIRequestResponse($logistics_provider_id, Auth::user()->id, $requestData, $responseCode = null, $e);
                return response()->json(array('success' => false,'msg'=>__('admin.something_error_message')));
            }
        }
    }

    //Store order id and airwaybill number in database
    public function store_airwaybill_number($order,$batchId,$response) {
        $orderId = $order->id;
        $airWayBill = json_decode($response,true);
        $awb_number = $airWayBill['status']['awb'];
        $awb_msg = $airWayBill['status']['msg'];
        $airWayBill = new AirWayBillNumber();
        $airWayBill->order_id = $orderId;
        $airWayBill->airwaybill_number = $awb_number;
        $airWayBill->airwaybill_status = $awb_msg;
        $airWayBill->save();
        $this->service->sendAirwayBillNotification($order,$batchId,$awb_number);
        $updateBatchIdData = ['awb_id' => $airWayBill->id, 'batch_id' => $batchId];

        //Update batch id in "airwaybill_number" table
        $updateAwbData = AirWayBillNumber::updateAwbData($updateBatchIdData);

        //Update airwaybill_id in "order_batches" table
        OrderBatch::updateorderBatchData($updateBatchIdData);
        return $this->shipmentTrackingByAWBNumber($batchId,$awb_number);
    }

    //Get shipment tracking by airwaybill number
    public function shipmentTrackingByAWBNumber($orderId,$awb_number) {
        $client = new Client();
        if (config('app.env') == "live"){
            $apiKey = '10e800c1-576a-4ecd-96fb-7e363c706181';
        }else{
            $apiKey = 'a33ad093-b123-4a81-8ded-ac276e8a98dc';
        }
        $requestData = [
            'headers' => [
                'api-key' => $apiKey,
            ],
            'query' => [
                'id' => $awb_number,
            ],
        ];
        try {
            $response = $client->get('https://blitznet.omile.id/blitznet/restapi/basic/tracking/list', $requestData);
            $responseBody = $response->getBody()->getContents();
            $logistics_provider_id = 1;  // 1 => quincus
            storeLogisticsAPIRequestResponse($logistics_provider_id, Auth::user()->id, $requestData, $responseCode = null, $responseBody);
            return response()->json(array('success' => true,'awb_number'=>$awb_number,'msg'=>__('admin.airwaybill_generated_successfully')));
        } catch (RequestException $e) {
            $hasresponse =$e->hasResponse();
            if ($hasresponse==true){
                return response()->json(array('success' => false,'awb_number'=>'','msg'=>__('admin.something_error_message')));
            }
        }
    }

    function downloadImageAdmin(Request $request){
        $image = Order::where('id', $request->id)->pluck($request->fieldName)->first();
        if (!empty($image)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $image, '', $headers);
        }
        return response()->json(array('success' => false));
    }

    public function downloadOrderLatter(Request $request){
        $image = OrderItem::where('id', $request->id)->pluck('order_latter')->first();
        if (!empty($image)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $image, '', $headers);
        }
        return response()->json(array('success' => false));
    }

    public function update(Request $request){
        $authUserId = Auth::check()? Auth::id(): 1;
        $authUserRoleId = Auth::check()? Auth::user()->role_id : 1;
        $order = Order::find($request->id);
        $orderId = $order->id;

        $key = array_keys($request->file())[0];
        //foreach ($request->file() as $key => $value){
        Storage::delete('/public/' . $request->input('old'.$key));
        $logoFileName = Str::random(10) . '_' . time().'_'.$key.'_'.$request->file($key)->getClientOriginalName();
        $logoFilePath = $request->file($key)->storeAs('uploads/'.$key,$logoFileName, 'public');

        $order->update([$key => $logoFilePath]);
        $count = $key == 'tax_receipt'? 12 : 8;
        $oldFileName = substr($request->input('old'.$key), stripos($request->input('old'.$key), $key."_") + $count);
        $activity_name = ($key == 'tax_receipt')? 'Upload Tex Receipt' : 'Upload Invoice';
        OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderId,'user_id'=>Auth::id(),'user_type'=>User::class,'key_name'=>$key, 'new_value' => $request->file($key)->getClientOriginalName(), 'old_value' => $oldFileName]);

        $this->adminUpdateNotificationChange($orderId, $order->supplier_id, 0, 0, $activity_name, $key.'_notification', 0, '', $authUserId, $authUserRoleId);
        $html = $this->getActivityDetailsTab($order);
        return response()->json(array('success' => true,'activityDetailHtml' => $html));
    }

    public function fileDelete(Request $request)
    {
        $order = Order::find($request->id);
        if ($request->fileName == "tax_receiptFile") {
            $order->tax_receipt = '';
        } else if ($request->fileName == "invoiceFile") {
            $order->invoice = '';
        }
        $order->save();
        Storage::delete('/public/' . $request->filePath);
        return response()->json(array('success' => true));
    }

    public function orderLatterUpload(Request $request){
        $authUserId = Auth::check()? Auth::id(): 1;
        $authUserRoleId = Auth::check()? Auth::user()->role_id : 1;
        $orderItemId = $request->id;
        $orderItem = OrderItem::find($orderItemId);

        if (empty($orderItem)){
            return response()->json(array('success' => false));
        }
        $key = 'order_latter';

        Storage::delete('/public/' . $request->input('oldorder_latter'));
        $logoFileName = Str::random(10) . '_' . time().'_'.$key.'_'.$request->file($key)->getClientOriginalName();
        $logoFilePath = $request->file($key)->storeAs('uploads/'.$key,$logoFileName, 'public');
        if (isset($request->is_all_upload) && $request->is_all_upload==1){
            $orderItem->where('order_id',$orderItem->order_id)->update([$key => $logoFilePath]);
            $multipleOrderLetterNumber = '';
            //$translationKey = 'upload_all_order_letter_notification';
        }else if (isset($request->is_in_batch) && $request->is_in_batch==1){
            $orderItem->where('order_batch_id',$request->batch_id)->where('order_id',$orderItem->order_id)->update([$key => $logoFilePath]);
            $multipleOrderLetterNumber = '';
            //$translationKey = 'upload_all_order_letter_notification';
        }else {
            $orderItem->update([$key => $logoFilePath]);
            $multipleOrderLetterNumber = $orderItem->order_item_number;
        }
        $count = 13;
        $oldFileName = substr($request->input('old'.$key), stripos($request->input('old'.$key), $key."_") + $count);
        OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderItem->order_id,'order_item_id'=>$orderItemId,'user_id'=>Auth::id(),'user_type'=>User::class,'key_name'=>$key, 'new_value' => $request->file($key)->getClientOriginalName(), 'old_value' => $oldFileName]);
        $this->adminUpdateNotificationChange($orderItem->order_id, $orderItem->order->supplier_id, 0, 0, 'Upload Order Letter', 'upload_order_letter_notification', 0, $multipleOrderLetterNumber, $authUserId, $authUserRoleId);
        return response()->json(array('success' => true,'paymentDetailHtml'=>$this->getPaymentDetailsTab($orderItem->order()->first()),'orderBatchesHtml' =>$this->getOrderBatchesData($orderItem->order()->first())));
    }

    public function deleteOrderLatter(Request $request)
    {
        $orderItem = OrderItem::find($request->id);
        if (empty($orderItem)){
            return response()->json(array('success' => false));
        }
        $filePath = $orderItem->order_latter;
        if (isset($request->is_all_remove) && $request->is_all_remove==1){
            $orderItem->where('order_id',$orderItem->order_id)->update(['order_latter' => '']);
        }else {
            $orderItem->update(['order_latter' => '']);
        }
        Storage::delete('/public/' . $filePath);
        return response()->json(array('success' => true,'paymentDetailHtml'=>$this->getPaymentDetailsTab($orderItem->order()->first()),'orderBatchesHtml'=>$this->getOrderBatchesData($orderItem->order()->first())));
    }


    public function orderactivity($id){
        $order = Order::where('id',$id)->where('is_deleted','0')->first();
        $activities = $order->orderActivities()->where(['is_deleted'=>0])->latest()->get();
        $orderStatus = OrderStatus::pluck('name', 'id')->toArray();
        $orderItemStatus = OrderItemStatus::pluck('name', 'id')->toArray();
        $activityhtml =  view('admin/order/order_activities', ['orderactivities' => $activities, 'order' => $order, 'orderStatus' => $orderStatus,'orderItemStatus'=>$orderItemStatus])->render();
        return response()->json(array('success' => true, 'activityhtml' => $activityhtml, 'orderStatus' => $orderStatus));
    }

    public function viewAcceptOrderDetails($id){
        $orderId = Crypt::decrypt($id);
        $order = $this->getOrder($orderId);
        $orderItems = QuoteItem::where('quote_id', $order->quote_id)->get();
        $charge_amount = DB::table('orders')
            ->join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
            ->where('orders.id', $orderId)
            ->where('quotes_charges_with_amounts.charge_type', 0)
            ->orderBy('quotes_charges_with_amounts.created_at', 'desc')
            ->get();
        if ($order->order_status == 1){
            return view('dashboard/supplier_accept_details', ['order' => $order, 'quotes_charges_with_amounts' => $charge_amount, 'orderItems' => $orderItems]);
        } else {
            return view('dashboard/supplier_order_status', ['order' => $order, 'accepted' => 0]);
        }
    }

    public function supplierOtp(Request $request){
        $order = Order::find($request->order_id);
        if (!empty($order) && $order->otp_supplier != $request->otp_supplier){
            return response()->json(array('ErrorOTP' => true, 'ErrorMessage' => 'Invalid Otp'));
        }
        if ($request->otp_supplier == $order->otp_supplier && $order->order_status == 1){
            $this->setOrderStatusChange(2,$request->order_id,[], $order->supplier_id);
            return response()->json(array('ErrorOTP' => false, 'url' =>route('supplier-order-status', ['id' => Crypt::encrypt($request->order_id), 'status' => 1])));
        } elseif($order->order_status > 1 ) {
            return response()->json(array('ErrorOTP' => false, 'url' =>route('supplier-order-status', ['id' => Crypt::encrypt($request->order_id), 'status' => 0])));
        }
    }

    public function supplierOtpCheck(Request $request){
        $order = Order::find($request->order_id);
        if (!empty($order) && $order->otp_supplier != $request->otp_supplier){
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
    }

    public function supplierOrderStatus($id, $status){
        $orderId = Crypt::decrypt($id);
        $order = $this->getOrder($orderId);
        if (empty($order)){
            return redirect("admin/login")->with('error', __('order.order_not_found'));
        }
        return view('dashboard/supplier_order_status', ['order' => $order, 'accepted' => $status]);
    }

    //Store order pickup date and time
    public function orderPickupDateTime(Request $request) {
        $order = Order::find($request->order_id);
        $order->pickup_date = Date('Y-m-d',strtotime($request->pickup_date));
        $order->pickup_time = $request->pickup_time;
        $order->save();
        if($order->save() == true) {
            return response()->json(array('success' => true, 'msg' => 'Pick up date & time updated successfully'));
        } else {
            return response()->json(array('success' => false, 'msg' => 'Something went wrong !'));
        }
    }

    /**
     *
     * Update order adjustment amount - Troubleshooting
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdjustmentAmount(Request $request)
    {
        try {

            $request->id = Crypt::decrypt($request->id);

            $order = Order::with('loanApply', 'quote')->where('id', $request->id)->first();

            $adjustedAmount = str_replace(",","",$request->amount);

            // Update adjusted amount
            $order->adjustment_amount = (float)$adjustedAmount;
            $order->save();

            if ($order->is_credit == 2) {
                if ((float)$adjustedAmount > (float)$order->quote->final_amount) {
                    return response()->json(['success' => false, 'message' => __('admin.adjustment_amount_should_not_exceed')]);
                }

                $comparedAmount = (float)$order->quote->final_amount - (float)$adjustedAmount;

                $loanConfirmAmount = $order->loanApply->loan_amount - $comparedAmount;


                LoanApply::where('id', $order->loanApply->id)->update([
                    'loan_confirm_amount' => $loanConfirmAmount
                ]);

                /**begin: System activities**/
                LoanApply::bootSystemActivities();
                /**end: System activities**/
            }

            return response()->json(['success' => true, 'message' => 'success']);

        } catch (\Exception $exception) {

            Log::critical('Code - 503 | ErrorCode:B004 Order Troubleshoot Amount');

            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

        }

        Log::error('Code - 400 | ErrorCode:B005 Order Troubleshoot Amount');

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

    }

    /**
     * Order amount adjusted or not - Troubleshooting
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isAmountAdjusted(Request $request)
    {
        try {

            $id = Crypt::decrypt($request->id);

            $query = Order::with('orderItems','orderItems.orderItemTracks')->where('id',$id);

            $order = clone $query;
            $order = $order->first();

            if ($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']) {
                $orderItemStatus = $query->whereHas('orderItems.orderItemTracks', function($query){
                    return $query->where('status_id',9);
                });

                if ($orderItemStatus->count() > 0) {
                    return !empty($order->adjustment_amount) ? response()->json(['success' => false, 'message' => __('admin.success')]) : response()->json(['success' => true, 'message' => __('admin.adjustment_amount_pending')]);

                }
            } else {
                return response()->json(['success' => false, 'message' => __('admin.success')]);
            }

        } catch (\Exception $exception) {

            Log::critical('Code - 500 | ErrorCode:B006 Order Troubleshoot');

            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

    }

    //Store order pickup date and time
    public function orderPickupBatch(Request $request) {
        $pickupData = $request->all();
        $orderCnt = OrderBatch::where('order_id',$pickupData['order_id'])->count();
        $orderBatchName = 'BORN-'.$pickupData['order_id'].'/'.((int)$orderCnt + 1);

        //If supplier select "other" then new record will be inserted
        if((auth()->user()->role_id == 1 || auth()->user()->role_id == 3) && $request->supplier_address_id == 0) {
            $supplierAddress = new SupplierAddress();
            $supplierAddress->supplier_id = $pickupData['supplier_id'];
            $supplierAddress->address_name = $request->address_name;
            $supplierAddress->address_line_1 = $request->address_line_1;
            $supplierAddress->address_line_2 = $request->address_line_2 ? $request->address_line_2 : '';
            $supplierAddress->pincode = $request->pincode;
            $supplierAddress->city = $request->cityId > 0 ? '' : $request->city;
            $supplierAddress->state = $request->stateId > 0 ? '' : $request->provinces;
            $supplierAddress->city_id =  $request->cityId;
            $supplierAddress->state_id =  $request->stateId;
            $supplierAddress->sub_district = $request->sub_district;
            $supplierAddress->district = $request->district;
            $supplierAddress->default_address = $request->default_address == true ? 1 : 0;
            $supplierAddress->save();
        }elseif (Auth::user()->hasRole('buyer') || Auth::user()->hasRole('sub-buyer')){
            $supplierAddress = new UserAddresse();
        }
        //End
        if (Auth::user()->hasRole('buyer') || Auth::user()->hasRole('sub-buyer')){
            $orderItemIDs = $pickupData['order_item_ids'];
            $updateOrderItemIds = json_decode($orderItemIDs);
            $buyerAddress = $pickupData['supplier_address_id'] != 0 ? $pickupData['supplier_address_id'] : $supplierAddress->id;
            $supplierAddress = null;
        }else{
            $orderItemIDs = json_encode(array_map('intval', explode(',', $pickupData['order_item_ids'])));
            $updateOrderItemIds = array_map('intval', explode(',', $pickupData['order_item_ids']));
            $buyerAddress= null;
            $supplierAddress = $pickupData['supplier_address_id'] != 0 ? $pickupData['supplier_address_id'] : $supplierAddress->id;
        }
        $data = array(
            'order_id'=>$pickupData['order_id'],
            'airwaybill_id'=>null,
            'supplier_address_id' => $supplierAddress,
            'user_address_id' => $buyerAddress,
            'order_batch' => $orderBatchName,
            'order_item_ids' => $orderItemIDs ,
            'order_pickup' => Carbon::parse($pickupData['pickup_datetime'])->format('Y-m-d H:i:s'),
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        );

        $orderPickupData = OrderBatch::createOrUpdateOrderBatch($data);
        $orderItemsSupplier = OrderItem::whereIn('id',explode(",",$pickupData['order_item_ids']))->pluck('quote_item_id');
        $quoteItemsSupplier = QuoteItem::whereIn('id', $orderItemsSupplier)->where('logistic_check',1)->where('logistic_provided',1)->get();
        $orderPickupData['isSupplierProvideLogistics'] = count($quoteItemsSupplier)>0 ? 1 : 0;
        if ($orderPickupData->id !=""){
            $orderPickupData['success'] =TRUE;
            $orderPickupData['msg'] = __('admin.airwaybill_generated_successfully');
        }else{
            $orderPickupData['success'] = FALSE;
            $orderPickupData['msg'] = __('admin.something_error_message');
        }
        $updateOrderItemData = ['batch_id'=>$orderPickupData->id,'is_in_batch'=>1,'orderItemsIds'=>$updateOrderItemIds];
        $manageOrderSeparately = OrderItem::UpdateOrderDelivery($updateOrderItemData);
        return $orderPickupData;
    }

    //Get order items details by order id
    public function orderItemDetails(Request $request) {
        $order = Order::find($request->orderId);
        $orderItemsDetails = $order->orderItems()->whereIn('id',explode(",",$request->selectedItemIds))->get();
        $orderItemsHTML = view('admin.order.order_items.order_items_details', ['orderItems' => $orderItemsDetails])->render();
        return response()->json(array('success' => true,'orderItemsDetails' => $orderItemsHTML));
    }

    // upload Lc Or SKBDn
    public function uploadOrderDoc(Request $request){
        $path = $this->destinationPath . Auth::id();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0775, true, true);
        }
        $imageFilename = $request->file('orderdoc')->getClientOriginalName();
        $image = uploadAlldocs($request->file('orderdoc'),$path,$imageFilename);
        $order = Order::find($request->orderdata);
        $order->upload_order_doc =  $image;
        $order->upload_order_doc_filename = $imageFilename;
        $order->save();
        return response()->json(['success' => true, 'name'=>$imageFilename]);
    }

}
