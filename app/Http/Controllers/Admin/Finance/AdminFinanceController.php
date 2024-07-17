<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Exports\Admin\Finance\AdminFinanceExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\XenditController;
use App\Models\Category;
use App\Models\Company;
use App\Models\Order;
use App\Models\Quote;
use App\Models\RfqAttachment;
use App\Models\Supplier;
use App\Models\SystemActivity;
use App\Models\UserSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use File;
use URL;
use Illuminate\Support\Facades\Log;

class AdminFinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authUser       = \Auth::user();

        $supplier_id    = $authUser->hasRole('supplier') ? UserSupplier::where('user_id', $authUser->id)->pluck('supplier_id')->first() : '';

        $categoriesId = collect();

        $query = Order::with('bulkOrderPayments','bulkOrderPayments.bulkPayment','bulkOrderPayments.bulkPayment.orderTransaction','orderItems','orderItems.rfqProduct','orderStatus','orderTransactions', 'orderCreditDay','companyDetails','quote')->where('is_deleted', 0)->distinct('id');

        //Records by role
        if ($authUser->hasRole('supplier')) {
            $query=$query->where('supplier_id', $supplier_id);
        }

        $orders     = $query->get();

        $companies  = $orders;

        $orders->map(function ($order) use($categoriesId) {
            //Get categories from Order
            if(!empty($order) && !empty($order->orderItems->first()) && !empty($order->orderItems->first()->rfqProduct)) {
                $rfqProduct = $order->orderItems->first()->rfqProduct;
                $categoriesId->push($rfqProduct->category_id);

            }
        });

        $categories = Category::whereIn('id',$categoriesId->unique())->get();

        $companies  = $companies->unique('company_id');

        $orders->pluck('quote.supplier_final_amount');

        //KPIs
        if ($authUser->hasRole('supplier')) {

            $totalOrderAmount       = $orders->sum('quote.supplier_final_amount');

            $totalAmountReceived    = $orders->whereIn('payment_status',[Order::ORDER_ONLINE_PAID,Order::ORDER_OFFLINE_PAID,Order::ORDER_LOAN_PAID])->sum('quote.supplier_final_amount');

            $totalAmountPending     = $orders->whereIn('payment_status',[Order::ORDER_UNPAID])->sum('quote.supplier_final_amount');

            $totalOverdueAmount     = $orders->whereIn('is_credit',[Order::SUPPLIER_CREDIT,Order::LENDER_CREDIT])->where('payment_due_date', '<', Carbon::now()->format('d-m-Y'))->sum('quote.supplier_final_amount');


        } else {
            $totalOrderAmount       = $orders->sum('payment_amount');

            $bulkOrderDiscuount     =  0.0;

            // Get bulk order Payment Discount
            $bulkOrderDiscuount = $this->getBulkOrderDiscount($orders);

            $totalOrderAmount       = $totalOrderAmount - $bulkOrderDiscuount;

            $totalAmountReceived    = $orders->whereIn('payment_status',[Order::ORDER_ONLINE_PAID,Order::ORDER_OFFLINE_PAID,Order::ORDER_LOAN_PAID])->sum('payment_amount');

            $totalAmountPending     = $orders->whereIn('payment_status',[Order::ORDER_UNPAID])->sum('payment_amount');

            $totalOverdueAmount     = $orders->whereIn('is_credit',[Order::SUPPLIER_CREDIT,Order::LENDER_CREDIT])->where('payment_due_date', '<', Carbon::now()->format('d-m-Y'))->sum('payment_amount');
        }

        /**begin: system log**/
        Order::bootSystemView(new Order(), 'Finance', SystemActivity::VIEW);
        /**end: system log**/

        return View::make('admin.finance.index')->with(compact(['orders','categories','companies','totalOrderAmount','totalAmountReceived','totalAmountPending','totalOverdueAmount']));
    }

    /**
     * Display a datatable listing of resource
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listJson(Request $request)
    {
        $authUser       = \Auth::user();

        $supplier_id    = $authUser->hasRole('supplier') ? UserSupplier::where('user_id', $authUser->id)->pluck('supplier_id')->first() : '';

        if ($request->ajax() && $request->get('draw')) {

            $draw               = $request->get('draw');
            $start              = $request->get("start");
            $length             = $request->get("length");
            $sort               = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
            $search             = !empty($request->get('search')) ? $request->get('search')['value'] : '';

            $columnIndex_arr    = $request->get('order');
            $columnName_arr     = $request->get('columns');
            $columnIndex        = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
            $column             = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';

            $query = Order::with('orderItems','orderStatus','orderTransactions', 'orderCreditDay','companyDetails','quote',
                'disbursements')->where('is_deleted', 0)->distinct('id');

            //Records by role
            if ($authUser->hasRole('supplier')) {
                $query->where('supplier_id', $supplier_id);
            }

            //Filters
            $query = $this->filter($request->filterData, $query);


            // Sorting
            $query = $this->sorting($column,$sort,$query,$authUser);

            $totalRecords = $query->count();

            // Server side search
            if ($search != "") {
                $query->where('order_number' , 'LIKE', "%$search%")
                    ->orWhereHas('companyDetails', function($query) use($search){
                        $query->where('name', 'LIKE',"%$search%");
                    });
            }
            // Total Display records
            $totalDisplayRecords = $query->count();

            $orders = $query->skip($start)->take($length)->get();

            // Order mapping
            $orders = $orders->map(function ($order) use($authUser, $request){

                $viewBtn        = '<a class="ps-2 cursor-pointer getSingleOrderDetail" data-id="'.$order->id.'" data-bs-toggle="modal" data-bs-target="#limitModal" data-toggle="tooltip" ata-placement="top" title="'.__('admin.view').'"><i class="fa fa-eye"></i></a>';
                $downloadBtn    = '<a class="show-icon ps-2 getAttachment" data-id="'.Crypt::encrypt($order->id).'" data-toggle="tooltip" ata-placement="top" title="'.__('admin.download').'"><i class="fa fa-download" aria-hidden="true"></i></a>';
                $action         = $viewBtn.$downloadBtn;

                $orderNumber    = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="limitModalView" data-bs-toggle="modal" data-bs-target="#limitModal" data-id="'.$order->id.'">'.$order->order_number.'</a>';

                //Check Credit
                $payment_terms = '';
                $source = '';
                if ($order->payment_type==1){
                    $creditDays     = !empty($order->orderCreditDay) ? $order->orderCreditDay->approved_days : 0;
                    $payment_terms  = "<span class='badge badge-pill badge-danger'>".__('admin.credit')." - ".$order->credit_days."</span>";
                    $source         = "<span class='badge badge-pill badge-danger'>".__('admin.credit')."</span>";

                } elseif($order->payment_type==2){
                    $payment_terms  = "<span class='badge badge-pill badge-success'>".__('admin.loan_provider_credit')."</span>";
                    $source         = "<span class='badge badge-pill badge-success'>".__('admin.credit')."</span>";
                }elseif($order->payment_type==0){
                    $payment_terms  = "<span class='badge badge-pill badge-success'>".__('admin.advanced')."</span>";
                    $source         = "<span class='badge badge-pill badge-success'>".__('admin.cash')."</span>";
                }elseif($order->payment_type==3){
                    $payment_terms  = "<span class='badge badge-pill badge-danger'>".__('admin.lc')."</span>";
                    $source         = "<span class='badge badge-pill badge-danger'>".__('admin.bank')."</span>";
                }
                else{
                    $payment_terms  = "<span class='badge badge-pill badge-danger'>".__('admin.skbdn')."</span>";
                    $source         = "<span class='badge badge-pill badge-danger'>".__('admin.bank')."</span>";
                }

                //Order Status
                $order_status = '-';
                if ($order->order_status == 10) {
                    $order_status = "<span class='badge badge-pill badge-danger'>".__('admin.cancelled')."</span>";

                } else if (!empty($order->payment_due_date) && $order->payment_status == 0) {
                    if (Carbon::now()->gt(Carbon::parse($order->payment_due_date))) {
                        $order_status = "<span class='badge badge-pill badge-danger'>".__('admin.overdue')."</span>";

                    } else {
                        $order_status = "<span class='badge badge-pill badge-danger'>".__('admin.payment_pending')."</span>";

                    }
                } else if ($order->payment_status == 0) {
                    $order_status = "<span class='badge badge-pill badge-danger'>".__('admin.payment_pending')."</span>";

                } else if ($order->payment_status == 1) {
                    if(!empty($order->disbursements->first())){
                        $disbursedOrder = $order->disbursements->where('status', 'COMPLETED')->pluck('id')->first();
                    }

                    if (!empty($disbursedOrder)) {
                        $order_status = "<span class='badge badge-pill badge-success'>".__('admin.paid_to_supplier')."</span>";
                    } else {
                        $order_status = "<span class='badge badge-pill badge-info'>".__('admin.disbursement_pending')."</span>";
                    }

                } else if ($order->payment_status == 2) {
                    $order_status = "<span class='badge badge-pill badge-success'>".__('admin.offline_paid')."</span>";

                } else {
                    $order_status = '-';
                }

                //Price calculation
                if ($authUser->hasRole('supplier')) {
                    $orderAmount        = $order->quote->supplier_final_amount;

                } else {
                    $orderBulkDiscount  = getBulkOrderDiscount($order->id);
                    $orderAmount        = $order->quote->final_amount - ($orderBulkDiscount > 0 ? $orderBulkDiscount : 0);

                }

                //Product
                if ($order->orderItems->count() == 0 && $order->orderItems->count() > 1 || empty($order->orderItems->first())){
                    $product            = $order->orderItems->count().' '.__('admin.products');

                } else {
                    $product            = get_product_name_by_id($order->orderItems->first()->rfq_product_id,1);

                }

                //Category
                $category           = "-";
                if ($order->orderItems->count() != 0) {
                    if (!empty($order->orderItems->first()->rfqProduct)) {
                        $category   = $order->orderItems->first()->rfqProduct->category;
                    }
                }

                return [
                    'order_number'      => $orderNumber,
                    'company_name'      => !empty($order->companyDetails) ? $order->companyDetails->name : '-',
                    'date'              => Carbon::parse($order->created_at)->format('d-m-Y'),
                    'product'           => $product,
                    'category'          => $category,
                    'price'             => "Rp ".number_format($orderAmount, 2),
                    'source'            => $source,
                    'payment_terms'     => $payment_terms,
                    'order_status'      => $order_status,
                    'overdue_date'      => !empty($order->payment_due_date) ? Carbon::parse($order->payment_due_date)->format('d-m-Y') : "-",
                    'actions'           => $action,
                ];
            });

            return response()->json([

                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $orders
            ]);
        }
    }

    /**
     * Get filters query for List AJAX of resource
     *
     * @param $filterData
     * @param $query
     * @return mixed
     */
    public function filter($filterData, $query)
    {
        if (!empty($filterData)) {

            //Price Range
            if (Arr::exists($filterData, 'min_price') && Arr::exists($filterData, 'max_price')) {
                $query->whereHas('quote', function($query) use($filterData){
                    $query->whereBetween('final_amount', [$filterData['min_price'], $filterData['max_price']]);
                });
            }

            //Order Number
            if (Arr::exists($filterData, 'order')) {
                $query->whereIn('id', $filterData['order']);
            }

            //Payment Status
            if (Arr::exists($filterData, 'payment_status')) {

                $clauseStart = true;

                // Disbursement Completed !Disbursement Pending
                if (in_array(Order::DISBURSMENTCOMPLETED, $filterData['payment_status']) && !in_array(Order::DISBURSMENTPENDING, $filterData['payment_status'])) {
                    $query->where('payment_status',1)->whereHas('disbursements', function($query){
                        $query->where('status', 'COMPLETED');
                    });

                    $clauseStart = false;

                }

                // Disbursement Pending !Disbursement Completed
                if (in_array(Order::DISBURSMENTPENDING, $filterData['payment_status']) && !in_array(Order::DISBURSMENTCOMPLETED, $filterData['payment_status'])) {

                    $clauseStart==true ? $query->where('payment_status', 1)->whereDoesntHave('disbursements') :
                        $query->orWhere('payment_status', 1)->WhereDoesntHave('disbursements');

                    $clauseStart = false;
                }

                // Disbursement Pending && Disbursement Completed
                if (in_array(Order::DISBURSMENTPENDING, $filterData['payment_status']) && in_array(Order::DISBURSMENTCOMPLETED, $filterData['payment_status'])) {

                    $clauseStart==true ? $query->where('payment_status', 1) :
                        $query->orWhere('payment_status', 1);

                    $clauseStart = false;
                }

                // Offline Paid
                if (in_array(Order::OFFLINEPAID, $filterData['payment_status'])) {

                    $clauseStart==true ? $query->where('payment_status', 2) :
                        $query->orWhere('payment_status', 2);

                    $clauseStart = false;

                }

                // Payment Pending && !Overdue
                if (in_array(Order::PAYMENTPENDING, $filterData['payment_status']) && !in_array(Order::PAYMENTOVERDUE, $filterData['payment_status'])) {

                    $clauseStart==true ? $query->where('payment_status', 0)->whereNull('payment_due_date')->orWhere('payment_due_date','>', Carbon::now()->format('Y-m-d')) :
                        $query->orWhere('payment_status', 0)->whereNull('payment_due_date')->orWhere('payment_due_date','>', Carbon::now()->format('Y-m-d'));

                    $clauseStart = false;

                }

                // !Payment Pending && Overdue
                if (!in_array(Order::PAYMENTPENDING, $filterData['payment_status']) && in_array(Order::PAYMENTOVERDUE, $filterData['payment_status'])) {

                    $clauseStart==true ? $query->where('payment_status', 0)->Where('payment_due_date','<', Carbon::now()->format('Y-m-d')) :
                        $query->orWhere('payment_status', 0)->where('payment_due_date','<', Carbon::now()->format('Y-m-d'));

                    $clauseStart = false;

                }

                // Payment Pending && Overdue
                if (in_array(Order::PAYMENTPENDING, $filterData['payment_status']) && in_array(Order::PAYMENTOVERDUE, $filterData['payment_status'])) {

                    $clauseStart==true ? $query->where('payment_status', 0) :
                        $query->orWhere('payment_status', 0);

                    $clauseStart = false;

                }

                // Order Cancelled
                if (in_array(Order::ORDERCANCELLED, $filterData['payment_status'])) {

                    $clauseStart==true ? $query->where('order_status', 10) :
                        $query->orWhere('order_status', 10);

                    $clauseStart = false;
                }


            }

            //Category
            if (Arr::exists($filterData, 'category')) {
                $query->whereHas('orderItems', function($query) use($filterData){
                    $query->join('rfq_products', 'rfq_products.id', '=', 'order_items.rfq_product_id')
                        ->whereIn('rfq_products.category_id', $filterData['category']);
                });

            }

            //Company
            if (Arr::exists($filterData, 'company')) {
                $query->whereIn('company_id', $filterData['company']);
            }

            //Order Date
            if (Arr::exists($filterData, 'start_date') && Arr::exists($filterData, 'end_date')) {
                $query->whereBetween('created_at', [Carbon::parse($filterData['start_date'])->startOfDay()->format('Y-m-d H:i:s'), Carbon::parse($filterData['end_date'])->endOfDay()->format('Y-m-d H:i:s')]);
            }

        }
        return $query;
    }

    /**
     * Get sorting query for List AJAX of resource
     *
     * @param $column
     * @param $sort
     * @param $query
     * @param $authUser
     * @return mixed
     */
    public function sorting($column,$sort,$query,$authUser)
    {
        if (!empty($column)) {

            // Order Number
            if ($column == 'order_number') {
                $query->orderBy('id', $sort);
            }

            // Company Name
            if ($column == 'company_name') {
                $query = $query->orderBy(Company::select('name')
                    ->whereColumn('companies.id', 'orders.company_id'),$sort);
            }

            // Order Date
            if ($column == 'date') {
                $query->orderBy('created_at', $sort);
            }

            // Order Price
            if ($column == 'price') {

                if ($authUser->hasRole('supplier')) {
                    $query = $query->orderBy(Quote::select('supplier_final_amount')
                        ->whereColumn('quotes.id', 'orders.quote_id'),$sort);

                } else {
                    $query = $query->orderBy(Quote::select('final_amount')
                        ->whereColumn('quotes.id', 'orders.quote_id'),$sort);

                }
            }

            // Order Overdue
            if ($column == 'overdue_date') {

                $query->orderBy('payment_due_date', $sort);

            }
        } else {
            $query->orderBy('id', $sort);
        }

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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

    /**
     * Finance Tab Cash Export.
     *
     * @return \Illuminate\Http\Response
     */
    public function financeExport()
    {
        ob_end_clean();
        ob_start();

        /**begin: system log**/
        Order::bootSystemView(new Order(), 'Finance Export', SystemActivity::DOWNLOAD);
        /**end: system log**/

        $financeName = 'finance'.Carbon::now()->format('dmyhis').'.xlsx';

        return Excel::download(new AdminFinanceExport, $financeName);
    }

    /**
     * Download zip attachment of Order/RFQ/Quote docs
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function zipAttachment(Request $request)
    {
        try {

            $request->id = Crypt::decrypt($request->id);

            $order      = Order::findOrFail($request->id);

            $orderItems = $order->orderItems;

            $rfq        = $order->rfq->where('id',$order->rfq_id)->first();

            $rfqFile    = RfqAttachment::where('rfq_id', $rfq->id)->get();

            $zip        = new ZipArchive;

            $filesExist = false;

            $zipFileName = 'public/uploads/rfq_docs/'.$rfq->reference_number.'.zip';

                if ($zip->open(Storage::path($zipFileName), ZipArchive::CREATE) === TRUE) {
                    // RFQ Attachments
                    if ($rfqFile->isNotEmpty()) {

                        $rfqFilesDirectory = 'public/uploads/rfq_docs/' . $rfq->reference_number;

                        if (Storage::exists($rfqFilesDirectory)) {
                            $rfqFiles = File::files(Storage::path($rfqFilesDirectory));
                            foreach ($rfqFiles as $key => $value) {
                                $relativeNameInZipFile = basename($value);
                                $zip->addFile($value, $relativeNameInZipFile);
                            }
                            $filesExist = true;
                        }

                    }

                    // Order Attachments - Invoice
                    if (!empty($order->invoice) && file_exists(Storage::path('public/'.$order->invoice))) {
                        $orderInvoice = 'public/'.$order->invoice;
                        $relativeNameInZipFile = basename($orderInvoice);
                        $zip->addFile(Storage::path($orderInvoice), $relativeNameInZipFile);
                        $filesExist = true;
                    }

                    // Order Attachments - Tax receipt
                    if (!empty($order->tax_receipt) && file_exists(Storage::path('public/'.$order->tax_receipt))) {
                        $orderReceipt = 'public/'.$order->tax_receipt;
                        $relativeNameInZipFile = basename($orderReceipt);
                        $zip->addFile(Storage::path($orderReceipt), $relativeNameInZipFile);
                        $filesExist = true;
                    }

                    // Order Attachments - Order Letter
                    if ($orderItems->count()>0) {

                        foreach ($orderItems as $item) {
                            if (!empty($item->order_latter) && file_exists(Storage::path('public/'.$item->order_latter))) {
                                $orderLetter = 'public/'.$item->order_latter;
                                $relativeNameInZipFile = basename($orderLetter);
                                $zip->addFile(Storage::path($orderLetter), $relativeNameInZipFile);
                                $filesExist = true;
                            }
                        }

                    }

                    $zip->close();
                    ob_end_clean();
                    $headers = ["Content-Type"=>"application/zip"];
                    if ($filesExist) {

                        /**begin: system log**/
                        Order::bootSystemView(new Order(), 'Finance Zip Attachment', SystemActivity::DOWNLOAD);
                        /**end: system log**/

                        return response()->download(Storage::path($zipFileName), $rfq->reference_number . '.zip', $headers)->deleteFileAfterSend(true);
                    } else {
                        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
                    }
                }


        } catch(\Exception $exception) {

            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
        }

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

    }

    /**
     * Get Xendit Balance by Role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getXenditBalance()
    {
        try {
            $suppliers = Supplier::all();
            $xenAccountBalance = 0;
            $xendit = new XenditController();

            if (\Auth::user()->hasRole('admin') || \Auth::user()->hasRole('finance')) {


                foreach ($suppliers as $supplier) {
                    $xenPlatformId = getXenPlatformIdBySupplierId($supplier->id);

                    if (!empty($xenPlatformId)) {
                        $xenAccountBalance = $xenAccountBalance + $xendit->getBalance($xenPlatformId)['balance'];

                    }
                }


            } else if (\Auth::user()->hasRole('supplier')) {
                $supplierId = UserSupplier::where('user_id', \Auth::user()->id)->pluck('supplier_id')->first();

                if (!empty($supplierId)) {
                    $xenPlatformId = getXenPlatformIdBySupplierId($supplierId);

                    if (!empty($xenPlatformId)) {
                        $xenAccountBalance = $xendit->getBalance($xenPlatformId)['balance'];

                    }
                }

            }

            $xenAccountBalance = number_format($xenAccountBalance, 2);


            return response()->json(['success' => true, 'data' => $xenAccountBalance]);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'data' => 0.0]);
            Log::critical('Code - 500 | ErrorCode:B036 - Finance Tab');
        }

    }

    /**
     * Get bulk Order Discount of all Orders
     * Order - Service
     *
     * @param $orders
     * @return float
     */
    public function getBulkOrderDiscount($orders)
    {
        $bulkOrderDiscuount = 0.0;

        try {
            $orders->map(function ($order) use(&$bulkOrderDiscuount){
                //Get categories from Order
                if(!empty($order) && isset($order->bulkOrderPayments) && !empty($order->bulkOrderPayments) && isset($order->bulkOrderPayments->first()->bulkPayment) && !empty($order->bulkOrderPayments->first()->bulkPayment)) {
                    foreach ($order->bulkOrderPayments as $bulkOrderPayment) {

                        if (isset($bulkOrderPayment->bulkPayment->orderTransaction) && !empty($bulkOrderPayment->bulkPayment->orderTransaction)) {

                            if ($bulkOrderPayment->bulkPayment->orderTransaction->status=='PAID')
                            {
                                $bulkOrderDiscuount += $bulkOrderPayment->discounted_amount;
                            }

                        }

                    }

                }
            });

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B035 - Finance Tab');
            return 0.0;
        }

        return $bulkOrderDiscuount;

    }
}
