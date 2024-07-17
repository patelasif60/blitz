<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\XenditController;
use App\Models\Order;
use App\Models\OrderTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:create transaction list|edit transaction list|delete transaction list|publish transaction list|unpublish transaction list', ['only'=> ['index']]);
        $this->middleware('permission:create transaction list', ['only' => ['create']]);
        $this->middleware('permission:edit transaction list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete transaction list', ['only' => ['delete']]);
    }

    public function index(){
        $transactions = DB::select('SELECT (SELECT COUNT(id) FROM order_transactions where order_id=t1.order_id) as generated_link_count, t1.*,suppliers.name as supplier_company,orders.order_status,orders.is_credit,disbursements.status as disb_status FROM order_transactions t1
                                            LEFT JOIN orders ON t1.order_id = orders.id
                                            LEFT JOIN order_transactions t2 ON t1.id < t2.id AND t1.order_id = t2.order_id
                                            LEFT JOIN suppliers ON t1.user_id = suppliers.xen_platform_id
                                            LEFT JOIN disbursements ON t1.order_id = disbursements.order_id AND (disbursements.status="COMPLETED" || disbursements.status is NULL)
                                            WHERE t2.id is NULL;');

        $bulkPaymentOrderIds = DB::table('bulk_payments')
            ->join('bulk_order_payments', 'bulk_order_payments.bulk_payment_id','=','bulk_payments.id')
            ->join('order_transactions', 'order_transactions.id','=','bulk_payments.order_transaction_id')
            ->groupBy('order_transactions.order_id')
            ->where(function ($query) {
                return $query
                    ->where('order_transactions.status', '=', 'PAID')
                    ->orWhere('order_transactions.status', '=', 'SETTLED');
            })
            ->selectRaw('GROUP_CONCAT(bulk_order_payments.order_id) as order_id')
            ->first();
        $bulkPaymentOrderIds = isset($bulkPaymentOrderIds->order_id)?explode(',',$bulkPaymentOrderIds->order_id):[];

        $xenditInvoiceSettings = json_decode(getSettingValueByKey('xendit_invoice'),true);

        /**begin: system log**/
        OrderTransactions::bootSystemView(new OrderTransactions());
        /**end:  system log**/
        return view('admin/transaction/index', ['transactions' => $transactions,'bulkPaymentOrderIds'=>$bulkPaymentOrderIds,'xenditInvoiceSettings'=>$xenditInvoiceSettings]);//getAllRecordsByCondition('order_transactions')
    }

    public function cancelInvoice($orderId){
        $authUser = Auth::user();
        if($authUser->role_id!=1){
            return response()->json(array('success' => false, 'message' => __('admin.access_denied')));
        }
        $orderId = Crypt::decrypt($orderId);
        $order = Order::where(['id' => $orderId])->first();
        if (empty($order)){
            return response()->json(array('success' => false, 'message' => __('order.order_not_found')));
        }
        $invoice = $order->orderTransaction()->where('status','PENDING')->first(['invoice_id','user_id','order_id']);
        if (empty($invoice)){
            return response()->json(array('success' => false, 'message' => __('signup.something_wrong_went')));
        }

        $xendit = new XenditController;
        try {
            $xResult = $xendit->expireInvoice($invoice);
        } catch (\Exception $e) {
            return response()->json(array('success' => false, 'message' => __('signup.something_wrong_went')));
        }
        sleep(1);
        $orderController = new OrderController();
        $orderStatusHtml = $orderController->getOrderStatusDetails($invoice->order_id, 1);
        return response()->json(array('success' => true,'data'=>$xResult,'html'=>$orderStatusHtml));
    }

    public function generatePayLink($orderId,$data=null)
    {
        $authUser = Auth::user();

        if (empty($authUser)){
            return response()->json(array('success' => false, 'message' => __('signup.something_wrong_went')));
        }
        if ($authUser->role_id==2) {
            $order = Order::where(['id' => $orderId, 'user_id' => $authUser->id])->first();
        }elseif($authUser->role_id==1){
            $order = Order::where(['id' => $orderId])->first();
        }else{
            return response()->json(array('success' => false, 'message' => __('signup.something_wrong_went')));
        }
        $invoice = $order->orderTransaction()->where('status','PENDING')->first();

        if (!empty($invoice)){
            return response()->json(array('success' => true,'data'=>$invoice));
        }

        if (!is_null($data)){//for bulk payment single link generation
            $xendit = new XenditController;
            $xResult = $xendit->createSingleInvoiceFromBulk($order);
            return response()->json(array('success' => true,'data'=>$xResult));
        }elseif($order && $order->is_credit==0){//normal link generation
            $xendit = new XenditController;
            $xResult = $xendit->createInvoice($order);
            if (isset($xResult['status']) && $xResult['status']==false){
                return response()->json(array('success' => false, 'message' => $xResult['message']));
            }
            return response()->json(array('success' => true,'data'=>$xResult));
        }
        return response()->json(array('success' => false, 'message' => __('order.order_not_found')));
    }

    public function generatePayLinkFromTrackStatus($orderId)
    {
        $authUser = Auth::user();

        if ($authUser->role_id!=1){
            return response()->json(array('success' => false, 'message' => __('signup.something_wrong_went')));
        }
        $order = Order::where(['id' => $orderId])->first();
        if (empty($order)){
            return response()->json(array('success' => false, 'message' => __('order.order_not_found')));
        }
        $invoice = $order->orderTransaction()->where('status','PENDING')->first();

        if (!empty($invoice)){
            return response()->json(array('success' => false, 'message' => __('signup.something_wrong_went')));
        }

        $xendit = new XenditController;
        $xResult = $xendit->createInvoice($order);
        if (isset($xResult['status']) && $xResult['status']==false){
            return response()->json(array('success' => false, 'message' => $xResult['message']));
        }
        $orderController = new OrderController();
        $orderStatusHtml = $orderController->getOrderStatusDetails($orderId, 1);
        return response()->json(array('success' => true,'data'=>$xResult,'html'=>$orderStatusHtml));
    }

}
