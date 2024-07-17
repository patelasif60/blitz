<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Buyer\Credit\LoanController;
use App\Models\LoanApplication;
use App\Models\MongoDB\ChatGroup;
use App\Events\BuyerNotificationEvent;
use App\Events\BuyerOrderNotificationEvent;
use App\Events\ordersCountEvent;
use App\Events\rfqsEvent;
use App\Http\Controllers\Admin\GroupTransactionController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Payment\XenditController;
use App\Jobs\GroupCloseBuyerJob;
use App\Jobs\GroupCloseJob;
use App\Jobs\SendOrderConfirmMailToBuyerJob;
use App\Jobs\SendOrderConfirmMailToSupplierJob;
use App\Models\BankDetails;
use App\Models\GroupPlaceOrderLog;
use App\Models\BuyerNotification;
use App\Models\MongoDB\SupportChat;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\QuoteItem;
use App\Models\RfqProduct;
use App\Models\Role;
use App\Models\RfqAttachment;
use App\Models\SystemActivity;
use Carbon\Carbon;
use App\Models\TermsCondition;
use Dompdf\Exception;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\BulkPayments;
use App\Models\BulkOrderPayments;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Grade;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\RfqActivity;
use App\Models\UserAddresse;
use App\Models\UserActivity;
use App\Models\Rfq;
use App\Models\UserRfq;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\CreditDays;
use App\Models\GroupChat;
use App\Models\OrderCreditDays;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderPlacedConfirmation;
use App\Mail\OrderPlacedConfirmationToSupplier;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Mail;
use App\Models\Languages;
use App\Models\UserCompanies;
use App;
use App\Jobs\QuoteApprovalMailJob;
use App\Mail\QuoteApprovalMail;
use App\Models\ApprovalRejectReason;
use App\Models\Company;
use App\Models\GroupActivity;
use App\Models\GroupMember;
use App\Models\GroupMembersDiscount;
use App\Models\Groups;
use App\Models\GroupSupplierDiscountOption;
use App\Models\OrderPo;
use App\Models\QuoteChargeWithAmount;
use App\Models\UserQuoteFeedback;
use App\Models\ModelHasCustomPermission;
// use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Crypt;
use App\Models\State;
use App\Models\CountryOne;
use App\Models\PreferredSupplier;
use App\Models\PreferredSuppliersRfq;
use App\Models\Settings;
use PHPUnit\Framework\Constraint\Count;
use App\Models\CustomPermission;
use App\Models\CustomRoles;
use App\Models\GetApprovalReason;
use App\Models\QuotesMeta;
use App\Models\ModuleInputField;
use App\Models\RfqStatus;
use App\Models\OrderStatus;
use App\Models\Product;
use Xendit\Customers;
use App\Models\Supplier;
use ZipArchive;
use File;
use Illuminate\Support\Arr;
use URL;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Null_;
use Spatie\Permission\Models\Permission;
use App\Twilloverify\TwilloService;

class DashboardController extends Controller
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $verify;
    
    /**
     * Create a new command instance.
     *
     * @return void
    */

    public function __construct()
    {
        $this->verify = app('App\Twilloverify\TwilloService');
    }
    //
    function index()
    {
        /*********begin: Display Role of a buyer***********/
        $user = Auth::user()->load('defaultCompany');
        $buyerRole = null;
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true){
            $buyerRole = CustomRoles::ADMINNAME;
        }else{
            $modalPermissionData = ModelHasCustomPermission::with('customPermission')->where('model_type',User::class)->where('model_id',Auth::user()->id)->get()->first(); //->pluck('custom_permission_id')
            $buyerRole = getRolePermissionAttribute(Auth::user()->id)['role'] ?? null;
        }
        /*********end: Display Role of a buyer***********/

        if($user->language_id){
            $langs = Languages::find($user->language_id);
        }
        if (session()->has('locale')) {

        }else{
            if($langs){
                App::setLocale(strtolower($langs->name));
            }
        }
        $usercompany = $user->companies;

        //$userActivity = UserActivity::all()->where('user_id', Auth::user()->id)->where('is_deleted', 0)->sortByDesc('id');
        $userNotification = BuyerNotification::join('users', 'buyer_notifications.user_id', '=', 'users.id')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get(['buyer_notifications.*', 'users.firstname','users.lastname']);
        $userDetails = DB::table('user_companies')
            ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->where('user_id', Auth::user()->id)
            ->get(['companies.name as company_name']);

        $userDetails = $userDetails->isEmpty() ? $userDetails : $userDetails[0];

        $sectionName = app('App\Http\Controllers\UserController')->getRedirectRoute();

        $creditDetail = LoanApplication::getCreditDatail();

        if(!empty($creditDetail) && isset($creditDetail->provider_application_id)){
            $showCredit = 1;
        }else{
            $showCredit = 0;
        }
        $dots = getUnreadMessageAlert();
        $supportChatId = SupportChat::where(['user_id'=> (int)Auth::user()->id, 'company_id'=>(int)Auth::user()->default_company])->pluck('_id')->first();

        /********** Get company background color set in colorpicker ************/
        $backgroundColorPicker = "";
        if ($usercompany->background_colorpicker) {
            $backgroundColorPicker = $usercompany->background_colorpicker ;
        }
        return view('dashboard/layout/index', ['usercompany'=>$usercompany,'userActivity' => $userNotification, 'userDetails' => $userDetails, 'userNotification' => ChangeCount($userNotification->where('is_multiple_show', 0)->count()), 'user' => $user, 'buyerRole' => $buyerRole, 'sectionName' => $sectionName, 'showCredit' => $showCredit, 'dots' => $dots, 'supportChatId' => $supportChatId]);
    }

    function getDashboardDefaultData(Request $request)
    {
        // $returnHTML = view('job.userjobs')->with('userjobs', $userjobs)->render();
        $user = Auth::user();
        $langs = '';
        if($user->language_id){
            $langs = Languages::find($user->language_id);
        }
        if (session()->has('locale')) {

        }else{
            App::setLocale(strtolower($langs->name));
        }

        // address query changed based on roles and permission changes
        $userAddress = UserAddresse::getCompanyBuyerAddress();

        //Get company wise address in RFQ place
        $companyAddress = UserAddresse::companyWiseAddress();

        $defaultAddressId = null;
        $defaultAddress = getUserPrimaryAddress();
        if($defaultAddress){
            $defaultAddressId = $defaultAddress->id;
        }
        // Get user Rfq count based on user permissions.
        $authUser = Auth::user();
        $rfq = DB::table('user_rfqs')
            ->join('rfqs', 'user_rfqs.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id') // Added for role & permissions.
            ->where('rfqs.is_deleted', 0)
            ->orderBy('rfqs.id');

            /**********begin: set permissions based on custom role******/
            $isOwner = User::checkCompanyOwner();
            if($isOwner == true || $authUser->hasPermissionTo('list-all buyer rfqs')){
                $rfq = $rfq->where('rfqs.company_id', $authUser->default_company);
            }else {
                $rfq = $rfq->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);;
            }
            /**********end: set permissions based on custom role******/


        $rfq = $rfq->distinct('rfq_products.rfq_id')->count();

        // orders count based on roles and permission
        $orders = DB::table('orders')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->leftjoin('order_pos','orders.id','=','order_pos.order_id');

        /*********begin: set permissions based on custom role.**************/
        $isOwner = User::checkCompanyOwner();
        if (Auth::user()->hasPermissionTo('list-all buyer orders') || $isOwner == true) {
            $orders->where('rfqs.company_id', Auth::user()->default_company);
        }else {
            $orders->where('orders.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);
        }
        /*********end: set permissions based on custom role.**************/

        $orders = $orders->where('orders.is_deleted', 0)
            ->orderBy('orders.id', 'desc')
            ->groupBy('orders.id')
            ->get(['orders.id']);
        $ordersCount = $orders->count();
        // orders count based on roles and permission


        $userActivity = BuyerNotification::all()->where('user_id', Auth::user()->id)->where('is_deleted', 0)->sortByDesc('id');
        $category = DB::table('categories')
            ->where(['categories.is_deleted' => 0, 'categories.status' => 1])
            ->orderBy('name', 'ASC')
            ->get();
        $unit = DB::table('units')
            ->where('units.is_deleted', 0)
            ->orderBy('units.name', 'desc')
            ->select('units.id', 'units.name')
            ->get();

        $supplierPayment = DB::table('orders')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id');
            /*********begin:Payment set permissions based on custom role.**************/
            $isOwner = User::checkCompanyOwner();
            if (Auth::user()->hasPermissionTo('list-all buyer payments') || $isOwner == true) {
                $supplierPayment = $supplierPayment->where('orders.company_id', Auth::user()->default_company);
            }else {
                $supplierPayment = $supplierPayment->where('orders.user_id', Auth::user()->id)->where('orders.company_id', Auth::user()->default_company);
            }
             /*********end: Payment set permissions based on custom role.**************/
            $supplierPayment = $supplierPayment->where(
                function($query) {
                    return $query->where(function($query) {
                        return $query->where(['orders.is_credit'=>0,'order_status'=>2]);
                    })->orWhere(function($query) {
                        return $query->where(['orders.is_credit'=>1,'order_status'=>8]);
                    });
                })
            ->orderBy('suppliers.name', 'ASC')
            ->groupBy('quote_items.supplier_id')->get();
        /* $buyerGroups = GroupMember::join('groups','group_members.group_id','=','groups.id')
        ->join('group_supplier_discount_options','groups.id','=','group_supplier_discount_options.group_id')
        ->leftjoin('rfqs','groups.id','=','rfqs.group_id'); // added for roles and permission

        if($user->buyer_admin==1 || $user->hasPermissionTo('list-all buyer groups')){
            $buyerGroups = $buyerGroups->where('rfqs.company_id', $user->default_company);
        }else if ($user->hasPermissionTo('publish buyer groups')){
            $buyerGroups = $buyerGroups->where('group_members.user_id', Auth::user()->id);
        }

        $buyerGroups = $buyerGroups->orderBy('groups.id', 'desc')
        ->groupBy('group_members.group_id')
        ->get(['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.reached_quantity', 'groups.end_date', DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount')]);
        */

        $buyerGroups = GroupMember::leftjoin('groups','group_members.group_id','=','groups.id')
        ->leftjoin('group_images','groups.id','=','group_images.group_id')
        ->leftjoin('units','groups.unit_id','=','units.id')
        ->leftjoin('products','groups.product_id','=','products.id')
        ->leftjoin('rfqs','groups.id','=','rfqs.group_id'); // added for roles and permission

        // change query based on role and permissions
        $authUser = Auth::user();//dd($authUser->default_company);
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer groups')){
            $buyerGroups = $buyerGroups->where('rfqs.company_id', $authUser->default_company);
        }else {
            $buyerGroups = $buyerGroups->where('group_members.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);
        }

        $buyerGroups = $buyerGroups->where('group_members.is_deleted', 0)
        ->orderBy('groups.id', 'desc')
        ->groupBy('groups.id')
        ->get(['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.reached_quantity', 'groups.end_date', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName','groups.group_status']);


        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();

        $userDetails = User::with('defaultCompany')->where('id',Auth::user()->id)->first();

        $preferredSupplierCount = PreferredSupplier::where('user_id',Auth::user()->id)->count();

        $preferredSuppliers = PreferredSupplier::leftJoin('user_suppliers','preferred_suppliers.supplier_id','=','user_suppliers.supplier_id')
        ->leftJoin('user_companies','user_suppliers.user_id','=','user_companies.user_id')
        ->leftJoin('companies','user_companies.company_id','=','companies.id')
        ->leftJoin('suppliers','preferred_suppliers.supplier_id','=','suppliers.id')
        ->where('preferred_suppliers.user_id', Auth::user()->id)
        ->where('preferred_suppliers.deleted_at', null)
        ->orderBy('preferred_suppliers.id','DESC')
        ->get(['suppliers.name as companyName','suppliers.contact_person_email','suppliers.interested_in','preferred_suppliers.is_active','suppliers.id as preferredSuppId']);

        $multiple_pro = Settings::where('key', 'multiple_rfq_max_added_product')->first()->value;
        //find maximum number of attachments to be added
        $max_attachments = Settings::where('key', 'multiple_rfq_attachments')->first()->value;

        /**
         * Get Count of rfq_quotes for approval process (Ronak M)
         */
        $rfqWithQuoteApproval = UserQuoteFeedback::getAllRfqQuoteApproval()->groupBy('user_quote_feedbacks.rfq_id')->get()->count();

        //End
        $creditDays = CreditDays::getAllActiveCreditDays();

        $emailvarify = Auth::user()->is_active;
        $repeatRfqId = $request->isRepeatRfqId;
        /*********begin:Buyer RFN set permissions based on custom role.**************/
        if ($authUser->hasPermissionTo('buyer rfn list-all')) {
            $rfnList = App\Models\Rfn::where('company_id', $authUser->default_company)->where('type', 1)->whereNull('deleted_at')->get();
        }else {
            $rfnList = App\Models\Rfn::where('user_id', $authUser->id)->where('user_type', User::class)->where('company_id', $authUser->default_company)->where('type', 1)->whereNull('deleted_at')->get();

        }

        if (isset($repeatRfqId)){
            $getRepeatRfq = $this->geteditRfqs($repeatRfqId)->getData()->postRfqhtml;
            $returnHTML = $getRepeatRfq;
        }else if (session()->has('isRfn') && session()->has('isRfn')==true){ // RFN to RFQ Flow working from here

            $rfn = session('rfn');
            $subcategories = SubCategory::where('category_id',$rfn->item_category_id)->get();
            $returnHTML = view('dashboard/home', ['addressCount' => $userAddress->count(),'userAddress'=>$companyAddress, 'rfq' => $rfq, 'ordersCount' => $ordersCount, 'category' => $category, 'units' => $unit, 'defaultAddressId' => $defaultAddressId, 'states' => $states, 'userDetails'=>$userDetails, 'preferredSupplierCount' => $preferredSupplierCount, 'preferredSuppliers' => $preferredSuppliers, 'max_product' => $multiple_pro,'max_attachments' =>$max_attachments,'emailvarify'=>$emailvarify,'creditDays'=>$creditDays, 'rfn' => $rfn, 'subcategories' => $subcategories, 'rfnList' => $rfnList->count()])->render();
        }else{
            $returnHTML = view('dashboard/home', ['addressCount' => $userAddress->count(),'userAddress'=>$companyAddress, 'rfq' => $rfq, 'ordersCount' => $ordersCount, 'category' => $category, 'units' => $unit, 'defaultAddressId' => $defaultAddressId, 'states' => $states, 'userDetails'=>$userDetails, 'preferredSupplierCount' => $preferredSupplierCount, 'preferredSuppliers' => $preferredSuppliers, 'max_product' => $multiple_pro,'max_attachments' =>$max_attachments,'emailvarify'=>$emailvarify,'creditDays'=>$creditDays, 'rfnList' => $rfnList->count()])->render();
        }
        $userActivityHtml = view('dashboard/activity', ['userActivity' => $userActivity])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML, 'userActivityHtml' => $userActivityHtml, 'addressCount' => $userAddress->count(), 'rfqCount' => $rfq, 'ordersCount' => $ordersCount,'supplierPaymentCount'=>count($supplierPayment), 'buyerGroupsCount'=>count($buyerGroups),'approvalTabCount' => $rfqWithQuoteApproval, 'rfnList' => $rfnList->count()));
    }

    function getDashboardUserActivityData()
    {
        BuyerNotification::where('user_id', Auth::user()->id)->update(['is_multiple_show' => 1]);
        //$userActivity = UserActivity::all()->where('user_id', Auth::user()->id)->where('is_deleted', 0)->sortByDesc('id');
        $userActivity = BuyerNotification::join('users', 'buyer_notifications.user_id', '=', 'users.id')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get(['buyer_notifications.*', 'users.firstname','users.lastname']);
        $userActivityHtml = view('dashboard/activity', ['userActivity' => $userActivity])->render();
        return response()->json(array('success' => true, 'userActivityHtml' => $userActivityHtml));
    }

    function getDashboardUserActivityNewDataCount()
    {
        $userActivityNewDataCount = BuyerNotification::where(['user_id' => Auth::user()->id, 'is_multiple_show' => 0])->get()->count();
        return response()->json(array('success' => true, 'userActivityNewDataCount' => ChangeCount($userActivityNewDataCount)));
    }

    function getDashboardAddress()
    {

       // $userAddress = UserAddresse::getCompanyBuyerAddress();
        $userAddress = UserAddresse::getUserwiseAddress(); // login user addresses
        $otherUserAddress = UserAddresse::getOtherUserAddress(); // other user's addresses expect login user
        $userPrimaryAddress = getUserPrimaryAddress();
        $primaryAddressId = (isset($userPrimaryAddress) && !empty($userPrimaryAddress)) ? $userPrimaryAddress->id : '';
        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();

        /**begin: System Activity*/
        UserAddresse::bootSystemView(new UserAddresse(),'Buyer - Address');
        /**end: System Activity*/

        $returnHTML = view('dashboard/address/address', ['userAddress' => $userAddress, 'states' => $states,'primaryAddressId'=>$primaryAddressId,'otherUserAddress'=>$otherUserAddress])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getDashboardPayment()
    {
        $userId = Auth::user()->id;
        $bulkPayments = BulkPayments::join('order_transactions','order_transactions.id','=','bulk_payments.order_transaction_id')
                //->where(['bulk_payments.user_id'=>$userId])
                ->where(['status'=>'PENDING'])
                ->join('orders', 'order_transactions.order_id', '=', 'orders.id') // added for manage permission wise data
                ->join('rfqs', 'orders.rfq_id', '=', 'rfqs.id') // added for manage permission wise data
                ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id'); // added for manage permission wise data
            /*********begin:Payment set permissions based on custom role.**************/
            $isOwner = User::checkCompanyOwner();
            if (Auth::user()->hasPermissionTo('list-all buyer payments') || $isOwner == true) {
                $bulkPayments = $bulkPayments->where('rfqs.company_id', Auth::user()->default_company);
            }else {
                $bulkPayments = $bulkPayments->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', Auth::user()->default_company);
            }
            /*********end: Payment set permissions based on custom role.**************/
        $bulkPayments = $bulkPayments->get(['bulk_payments.id','bulk_payments.supplier_id','order_transactions.invoice_url']);

        $bulkOrderIds = [];
        $bulkSupplierPayments = [];
        foreach ($bulkPayments as $i=>$bulkPayment){
            $createdBulkOrders = $bulkPayment->bulkOrderPayments()
                ->leftJoin('order_transactions', function($query) {
                    $query->on('order_transactions.order_id','=','bulk_order_payments.order_id')
                        ->whereRaw('order_transactions.id IN (select MAX(ot.id) from order_transactions as ot join bulk_order_payments as bop on bop.order_id = ot.order_id group by bop.order_id)');
                })
                ->groupBy('bulk_order_payments.order_id')
                ->get(['bulk_order_payments.order_id','order_transactions.status']);

            if ($createdBulkOrders->where('status','PAID')->count()){
                unset($bulkPayments[$i]);
            }else{
                $singleBulkOrderIds = [];
                $singleBulkOrderIds = array_merge($singleBulkOrderIds,$createdBulkOrders->pluck('order_id')->all());
                $bulkOrderIds = array_merge($bulkOrderIds,$singleBulkOrderIds);
                $bulkSupplierPayments[$bulkPayment->supplier_id][$bulkPayment->id]['invoice_url'] = $bulkPayment->invoice_url;
                $bulkSupplierPayments[$bulkPayment->supplier_id][$bulkPayment->id]['created_bulk_orders'] = Order::whereIn('id',$singleBulkOrderIds)
                                                     ->get(['id', 'quote_id', 'order_number', 'is_credit', 'payment_due_date', 'supplier_id', 'payment_amount']);
            }
        }

        $suppliers = DB::table('orders')
            ->join('suppliers', 'orders.supplier_id', '=', 'suppliers.id');
            /*********begin:Payment set permissions based on custom role.**************/
            $isOwner = User::checkCompanyOwner();
            if (Auth::user()->hasPermissionTo('list-all buyer payments') || $isOwner == true) {
                $suppliers = $suppliers->where('orders.company_id', Auth::user()->default_company);
            }else {
                $suppliers = $suppliers->where('orders.user_id', Auth::user()->id)->where('orders.company_id', Auth::user()->default_company);
            }
            /*********end: Payment set permissions based on custom role.**************/
        $suppliers = $suppliers->where(
                function($query) {
                    return $query->where(function($query) {
                        return $query->where(['orders.is_credit'=>0,'order_status'=>2]);
                    })->orWhere(function($query) {
                        return $query->where(['orders.is_credit'=>1,'order_status'=>8]);
                    });
                })
            ->orderBy('suppliers.name', 'ASC')
            ->groupBy('supplier_id')
            ->get(['orders.supplier_id', 'suppliers.name as supplier_company_name']);

        foreach ($suppliers as $i=>$supplier) {
            $suppliers[$i]->orders = Order::with(['quote','quote.quoteChargesWithAmounts' ])
                ->whereNotIn('id',$bulkOrderIds);
                                   // ->where(['user_id'=>Auth::user()->id,'supplier_id' => $supplier->supplier_id])
                        /*********begin:Payment set permissions based on custom role.**************/
                        $isOwner = User::checkCompanyOwner();
                        if (Auth::user()->hasPermissionTo('list-all buyer payments') || $isOwner == true) {
                             $suppliers[$i]->orders->where('orders.company_id', Auth::user()->default_company);
                        }else {
                            $suppliers[$i]->orders->where('user_id', Auth::user()->id)->where('orders.company_id', Auth::user()->default_company);
                        }
                        /*********end: Payment set permissions based on custom role.**************/
            $suppliers[$i]->orders = $suppliers[$i]->orders->where(['supplier_id' => $supplier->supplier_id])
                                    ->where(
                                        function($query) {
                                            return $query->where(function($query) {
                                                return $query->where(['is_credit'=>0,'order_status'=>2]);
                                            })->orWhere(function($query) {
                                                return $query->where(['is_credit'=>1,'order_status'=>8]);
                                            });
                                        })
                                    ->orderBy('id', 'desc')
                                    ->get(['id', 'quote_id', 'order_number', 'is_credit', 'payment_due_date', 'supplier_id', 'payment_amount']);


        }
        $transactionsCharges = getRecordsByCondition('other_charges',['id'=>10],'charges_value',1);

        //$returnHTML = '';
        $returnHTML = view('dashboard/payment/index', ['suppliers' => $suppliers,'bulkSupplierPayments'=>$bulkSupplierPayments,'transactionsCharges'=>$transactionsCharges])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML,'suppliers' => $suppliers,'bulkSupplierPayments'=>$bulkSupplierPayments,'supplierPaymentCount'=>count($suppliers)));
    }

    public function getDashboardCancelPayment(Request $request)
    {
        $inputs = $request->all();
        //$result = BulkPayments::where(['id'=>$inputs['bulk_payment_id'],'supplier_id'=>$inputs['supplier_id'],'user_id'=>Auth::user()->id])->first();
        $result =  BulkPayments::join('order_transactions','order_transactions.id','=','bulk_payments.order_transaction_id')
                    ->join('orders', 'order_transactions.order_id', '=', 'orders.id') // added for manage permission wise data
                    ->join('rfqs', 'orders.rfq_id', '=', 'rfqs.id') // added for manage permission wise data
                    ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')// added for manage permission wise data
                    ->where('bulk_payments.id',$inputs['bulk_payment_id'])
                    ->where('bulk_payments.supplier_id',$inputs['supplier_id']);
        /*********begin:Payment set permissions based on custom role.**************/
        $isOwner = User::checkCompanyOwner();
        if (Auth::user()->hasPermissionTo('list-all buyer payments') || $isOwner == true) {
            $result = $result->where('rfqs.company_id', Auth::user()->default_company);
        }else {
            $result = $result->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', Auth::user()->default_company);
        }
        /*********end: Payment set permissions based on custom role.**************/
        $result = $result->first();
        if ($result){
            $result->delete();
            return $this->getDashboardPayment();
        }
        return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
    }

    public function setBulkPayment(Request $request)
    {
        $inputs = $request->all();

        $orders = Order::select(['orders.id', 'is_credit','payment_due_date', 'payment_amount', 'orders.supplier_id', 'quote_id', 'rfq_id', 'suppliers.name as supplier_company_name'])
            ->join('suppliers', 'orders.supplier_id', '=', 'suppliers.id');

             /*********begin:Payment set permissions based on custom role.**************/
            $isOwner = User::checkCompanyOwner();
            if (Auth::user()->hasPermissionTo('list-all buyer payments') || $isOwner == true) {
                $orders = $orders->where('orders.company_id', Auth::user()->default_company);
            }else {
                $orders = $orders->where('orders.user_id', Auth::user()->id)->where('orders.company_id', Auth::user()->default_company);
            }
            /*********end: Payment set permissions based on custom role.**************/
        $orders = $orders->where('supplier_id',$inputs['supplier_id'])
            ->whereIn('orders.id',$inputs['order_ids'])
            ->where(function($query) {
                        return $query->where('order_status', 2)->orWhere('order_status',8);
                    })
            ->orderBy('orders.id', 'ASC')
            ->get();
        $orderCount = $orders->count();
        $paymentUrl = '';
        if ($orderCount===0) {
            return response()->json(array('success' => false, 'message' => __('order.order_not_found')));
        }elseif ($orderCount>1) {
            $transactionCharges = $this->calculateBulkDiscountById($inputs['order_ids'])->getContent();
            $discount = isset((json_decode($transactionCharges))->data->charges) ? round((json_decode($transactionCharges))->data->charges) : 0.00;
            $perOrderDiscount = round($discount/$orderCount);
            $totalPerOrderDiscount = $perOrderDiscount*$orderCount;
            $discountDiff = $discount-$totalPerOrderDiscount;
            $subTotal = round($orders->sum('payment_amount'));
            $total = round($subTotal-$discount);
            $orderIds = $orders->pluck('id')->all();
            $bulkOrderNumber = getBulkOrderNumber($orderIds);
            $bulkPaymentData = array(
                'bulk_payment_number'=>$bulkOrderNumber,
                'user_id'=>Auth::user()->id,
                'supplier_id'=>$inputs['supplier_id'],
                'total_amount'=>$subTotal,
                'total_discounted_amount'=>$discount,
                'payable_amount'=>$total,
                'description'=>'',
                'deleted_at'=>null
            );

            $bulkPayment = BulkPayments::createOrUpdateBulkPayment($bulkPaymentData);
            foreach ($orders as $i=>$order){
                if ($i===($orderCount-1) && $discountDiff!==0){//add discount difference on last order
                    $perOrderDiscount = $perOrderDiscount+$discountDiff;
                }
                $bulkOrderPaymentData = array(
                    'bulk_payment_id'=>$bulkPayment->id,
                    'order_id'=>$order->id,
                    'quote_id'=>$order->quote_id,
                    'rfq_id'=>$order->rfq_id,
                    'discounted_amount'=>$perOrderDiscount
                );
                BulkOrderPayments::createOrUpdateBulkOrderPayment($bulkOrderPaymentData);
            }
            $paymentUrl = $bulkPayment->orderTransaction()->where('status','!=','EXPIRED')->pluck('invoice_url')->first();
            if (empty($paymentUrl)) {
                $xendit = new XenditController;
                $orderTransaction = $xendit->createBulkInvoice($bulkPayment);
                $bulkPayment->order_transaction_id = $orderTransaction->id;
                $bulkPayment->save();

                $paymentUrl = $orderTransaction->invoice_url;
            }
        }else{
            $orderTransaction = $orders[0]->orderTransaction()->latest()->first(['invoice_url','status']);
            $paymentUrl = isset($orderTransaction->invoice_url)?$orderTransaction->invoice_url:'';
            //if invoice link is expired then regenerate links
            if (empty($paymentUrl) || $orderTransaction->status=='EXPIRED'){
                $data['for_bulk'] = 1;
                $transaction = new TransactionController;
                $tResult = $transaction->generatePayLink($orders[0]->id,$data);
                $tResult = (array)$tResult->getData();
                if (isset($tResult['success']) && $tResult['success']==false){
                    return response()->json(array('success' => false, 'message' => $tResult['message']));
                }
                $paymentUrl = $tResult['data']->invoice_url;
            }
        }
        if (empty($paymentUrl)){
            return response()->json(array('success' => false, 'message' => __('signup.something_wrong_went')));
        }
        $html = $this->getDashboardPayment()->getData()->html;
        return response()->json(array('success' => true, 'payment_url'=>$paymentUrl, 'html'=>$html));
    }

    /**
     * Calculate bulk payment Discount before payment
     *
     * @param $orderIds
     * @return float|\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function calculateBulkDiscountById($orderIds)
    {
        try {
            $orders = Order::with(['quote','quote.quoteChargesWithAmounts' => function($query) {
                return $query->where('charge_id', App\Models\OtherCharge::XENDIT);
            }])
                ->whereIn('id',$orderIds)->get();

            $totalAmount = 0.00;
            foreach ($orders as $order) {
                $totalAmount = $totalAmount + (isset($order->quote->quoteChargesWithAmounts->first()->charge_amount) ? $order->quote->quoteChargesWithAmounts->first()->charge_amount : 0.00);
            }

            $averageDiscount = $totalAmount / $orders->count();
            $totalAmount = $totalAmount - $averageDiscount;

            return response()->json(['success' => true, 'message' => __('admin.data_fetched'), 'data' => ['charges' => $totalAmount]]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'), 'data' => ['charges' => 0.00]]);
        }

    }

    function createDashboardAddress(Request $request)
    {
        $inputs = $request->all();
        $authUser = Auth::user();
        $userIdArr = [];
        if(isset($inputs['default_address'])){
            /* Begin for remove and update primary address set for user */
            $userAddressList = UserAddresse::isPrimaryAddress();
            $userIdArr = !empty($userAddressList->is_user_primary) ? json_decode($userAddressList->is_user_primary) : [];
            $authUserId = $authUser->id;
            $diffUserId = array_filter($userIdArr, function ($userId) use ($authUserId) {
                return $userId !== $authUserId;
            });
            $newUserId = [];
            foreach ($diffUserId as $users) {
                array_push($newUserId, $users);
            }
            $primaryAddressSet =  !empty($newUserId) ? json_encode($newUserId): [];
            $addressId = !empty($userAddressList->id) ? $userAddressList->id : '';
            UserAddresse::updatePrimaryAddress($primaryAddressSet,$addressId); // remove primary address from other address
            /* end for remove and update primary address set for user */
            //$inputs['default_address'] = 1;
            $userIdArr = [$authUser->id];
        }
        $inputs['is_user_primary'] = json_encode($userIdArr);
        $inputs['company_id'] = Auth::user()->default_company;
        $inputs['user_id'] = Auth::user()->id;
        $userAddress = UserAddresse::createOrUpdateUserAddress($inputs);

        /*if (Auth::user()->id) {
            UserActivity::createOrUpdateUserActivity(['user_id'=>Auth::user()->id,'activity'=>'Address Created','type'=>'address','record_id'=>$userAddress->id]);
        }*/

        if (Auth::user()->id) {
            $commanData = array( 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-truck', 'address_name' => $inputs['address_name']);
            buyerNotificationInsert(Auth::user()->id, 'Address Created', 'buyer_address_created_notification', 'other', $userAddress->id, $commanData);
            broadcast(new BuyerNotificationEvent());
        }

        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
        $userAddress = UserAddresse::getCompanyBuyerAddress();
        $userAddresses = UserAddresse::getUserwiseAddress(); // login user addresses
        $otherUserAddress = UserAddresse::getOtherUserAddress(); // other user's addresses expect login user
        $userPrimaryAddress = getUserPrimaryAddress();
        $primaryAddressId = (isset($userPrimaryAddress) && !empty($userPrimaryAddress)) ? $userPrimaryAddress->id : '';
        $returnHTML = view('dashboard/address/address', ['userAddress' => $userAddresses, 'states' => $states,'primaryAddressId' => $primaryAddressId,'otherUserAddress'=>$otherUserAddress])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML, 'userAddressCount' => count($userAddress)));
    }

    function editDashboardAddress($id)
    {
        $userAddress = UserAddresse::find($id);
        $userPrimaryAddress = getUserPrimaryAddress();
        $primaryAddressId = (isset($userPrimaryAddress) && !empty($userPrimaryAddress)) ? $userPrimaryAddress->id : '';
        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();

        /**begin: System Activity */
        UserAddresse::bootSystemView(new UserAddresse(), 'Buyer - Address', SystemActivity::EDITVIEW,$id);
        /**end: System Activity */

        $returnHTML = view('dashboard/address/editAddress', ['userAddress' => $userAddress, 'states' => $states,'primaryAddressId' => $primaryAddressId])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * New Update User address to primary address
     */
    function addressStatusUpdate(Request $request){
        $inputs = $request->all();
        $authUser = Auth::user();
        $userIdArr = [];
        if (isset($inputs['is_primary'])) {

            /* Begin for remove and update primary address set for user */
            $userAddressList = UserAddresse::isPrimaryAddress();
            $userIdArr = !empty($userAddressList->is_user_primary) ? json_decode($userAddressList->is_user_primary) : [];
            $authUserId = $authUser->id;
            $diffUserId = array_filter($userIdArr, function ($userId) use ($authUserId) {
                return $userId !== $authUserId;
            });
            $newUserId = [];
            foreach ($diffUserId as $users) {
                array_push($newUserId, $users);
            }
            $primaryAddressSet =  !empty($newUserId) ? json_encode($newUserId): [];
            $addressId = !empty($userAddressList->id) ? $userAddressList->id : '';
            UserAddresse::updatePrimaryAddress($primaryAddressSet,$addressId); // remove primary address from other address
            /* end for remove and update primary address set for user */

            $userIdArr = [$authUser->id];
            /* Begin : Code for check other user has already set primary to this address */
            $checkPrimaryAdd = UserAddresse::checkPrimaryUserAddress($inputs['id']);
            $childUserIdArr = !empty($checkPrimaryAdd->is_user_primary) ? json_decode($checkPrimaryAdd->is_user_primary) : [];
            $childUserId =[];
            foreach ($childUserIdArr as $usersId) {
                array_push($userIdArr, $usersId);
            }
            /* end : Code for check other user has already set primary to this address */
            // New address primary set for User
            UserAddresse::updatePrimaryAddress($userIdArr,$inputs['id']);
            if (isset($request->changesUserNotification)) {
                buyerNotificationInsert(Auth::user()->id, 'Address Updated Status', 'buyer_user_address_updated_status', 'other', $inputs['id'], ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear', 'address_name' => $userAddressList->address_name]);
                broadcast(new BuyerNotificationEvent());
            }
            return response()->json(array('success' => true));
        }
        return response()->json(array('success' => false));
    }

    function updateDashboardAddress(Request $request)
    {
        $inputs = $request->all();
        //$inputs['user_id'] = Auth::user()->id;
        $authUser = Auth::user();
        $userIdArr = [];
        $addressDetails = UserAddresse::getAddressById($inputs['id']);
        if(isset($inputs['default_address']) && (Auth::user()->id == $addressDetails->user_id) ){ // primary address toggle checked and login user id can change his primary  address only
            /* Begin for remove and update primary address set for user */
            $userAddressList = UserAddresse::isPrimaryAddress();
            $userIdArr = !empty($userAddressList->is_user_primary) ? json_decode($userAddressList->is_user_primary) : [];
            $authUserId = $authUser->id;
            $diffUserId = array_filter($userIdArr, function ($userId) use ($authUserId) {
                return $userId !== $authUserId;
            });
            $newUserId = [];
            foreach ($diffUserId as $users) {
                array_push($newUserId, $users);
            }
            $primaryAddressSet =  !empty($newUserId) ? json_encode($newUserId): [];
            $addressId = !empty($userAddressList->id) ? $userAddressList->id : '';
            UserAddresse::updatePrimaryAddress($primaryAddressSet,$addressId); // remove primary address from other address
           /* end for remove and update primary address set for user */
            $inputs['default_address'] = 0;
            $userIdArr = [$authUser->id];
            /* Begin : Code for check other user has already set primary to this address */
            $checkPrimaryAdd = UserAddresse::checkPrimaryUserAddress($inputs['id']);
            $childUserIdArr = !empty($checkPrimaryAdd->is_user_primary) ? json_decode($checkPrimaryAdd->is_user_primary) : [];
            $childUserId =[];
            foreach ($childUserIdArr as $usersId) {
                array_push($userIdArr, $usersId);
            }
            /* end : Code for check other user has already set primary to this address */
        }
        if(Auth::user()->id == $addressDetails->user_id) {
            $inputs['is_user_primary'] = json_encode($userIdArr);
        }
        $userAddress = UserAddresse::createOrUpdateUserAddress($inputs);
        $userPrimaryAddress = getUserPrimaryAddress();
        $primaryAddressId = (isset($userPrimaryAddress) && !empty($userPrimaryAddress)) ? $userPrimaryAddress->id : '';
        if (Auth::user()->id) {
            //UserActivity::createOrUpdateUserActivity(['user_id'=>Auth::user()->id,'activity'=>'Address Updated','type'=>'address','record_id'=>$userAddress->id]);
            buyerNotificationInsert(Auth::user()->id, 'Address Updated Status', 'buyer_user_address_updated_notification', 'other', $userAddress->id, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear', 'address_name' => $request->address_name]);
            broadcast(new BuyerNotificationEvent());
        }
        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
        $userAddresses = UserAddresse::getUserwiseAddress(); // login user addresses
        $otherUserAddress = UserAddresse::getOtherUserAddress(); // other user's addresses expect login user

        $returnHTML = view('dashboard/address/address', ['userAddress' => $userAddresses, 'states' => $states,'primaryAddressId' => $primaryAddressId,'otherUserAddress'=>$otherUserAddress])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Delete address functionality
     * @param $id -Address id
     * @return \Illuminate\Http\JsonResponse
     */

    function deleteDashboardAddress($id)
    {
        $authUser = Auth::user();
        $userAddress = UserAddresse::find($id);
        $userAddressDetails = UserAddresse::checkPrimaryUserAddress($id);
        $primaryAddressId = !empty($userAddressDetails->is_user_primary) ? json_decode($userAddressDetails->is_user_primary) : [];

        if(isset($primaryAddressId) && !empty($primaryAddressId)) { // check if primary address is set for this address
            /* Begin for remove and update primary address set for user */
            $addressUserId = $userAddressDetails->user_id;
            $diffUserId = array_filter($primaryAddressId, function ($userId) use ($addressUserId) {
                return $userId !== $addressUserId;
            });
            $newUserId = [];
            foreach ($diffUserId as $users) {
                array_push($newUserId, $users);
            }
            $primaryAddressSet = !empty($newUserId) ? json_encode($newUserId) : [];
            $addressId = !empty($userAddressDetails->id) ? $userAddressDetails->id : '';
            UserAddresse::updatePrimaryAddress($primaryAddressSet, $addressId,$userAddressDetails->user_id); // remove primary address from other address
            /* end for remove and update primary address set for user */
        }
        $userAddress->is_deleted = 1;
        $userAddress->save();
        if (Auth::user()->id) {
            //UserActivity::createOrUpdateUserActivity(['user_id'=>Auth::user()->id,'activity'=>'Address Deleted','type'=>'address','record_id'=>$id]);
            buyerNotificationInsert(Auth::user()->id, 'Address Delete', 'buyer_user_address_delete_notification', 'other', $id, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
            broadcast(new BuyerNotificationEvent());
        }

        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();

        $userAddress = UserAddresse::getCompanyBuyerAddress();
        $userAddresses = UserAddresse::getUserwiseAddress(); // login user addresses
        $otherUserAddress = UserAddresse::getOtherUserAddress(); // other user's addresses expect login user
        $userPrimaryAddress = getUserPrimaryAddress();
        $primaryAddressId = (isset($userPrimaryAddress) && !empty($userPrimaryAddress)) ? $userPrimaryAddress->id : '';
        $returnHTML = view('dashboard/address/address', ['userAddress' => $userAddresses, 'states' => $states,'otherUserAddress'=>$otherUserAddress,'primaryAddressId'=>$primaryAddressId])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML, 'userAddressCount' => count($userAddress)));
    }

    function getDashboardRfq(Request $request)
    {

        $authUser = Auth::user();
        $userRfq = DB::table('user_rfqs')
            ->join('rfqs', 'user_rfqs.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->leftJoin('groups','rfqs.group_id','=','groups.id')
            ->leftjoin('quotes','rfqs.id','=','quotes.rfq_id')
            ->where('rfqs.is_deleted', 0);
        $condition = [];
        if($request->favoriteRfq){
                $userRfq = $userRfq->where('rfqs.is_favourite', $request->favoriteRfq);
        }
        if($request->isFavourite){
            if ($request->isFavourite == 1) {
                $userRfq = $userRfq->where('rfqs.is_favourite', $request->isFavourite);
            }
        }
        if($request->categoryId){
            $userRfq = $userRfq->where('rfq_products.category_id', $request->categoryId);
        }
        if($request->subCategoryId){
            $userRfq = $userRfq->where('rfq_products.sub_category_id', $request->subCategoryId);
        }
        if($request->productId){
            $userRfq = $userRfq->where('rfq_products.product_id', $request->productId);
        }
        if($request->searchText){
            $userRfq = $userRfq->where('rfqs.reference_number', 'LIKE', "%$request->searchText%")->orWhere('rfq_products.category', 'LIKE', "%$request->searchText%")
                                ->orWhere('rfq_products.sub_category', 'LIKE', "%$request->searchText%")
                                ->orWhere('rfq_products.product', 'LIKE', "%$request->searchText%");
        }
        /*********begin: set permissions based on custom role.**************/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer rfqs')){
            $userRfq = $userRfq->where('rfqs.company_id', $authUser->default_company);
        }else {
            $userRfq = $userRfq->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);
        }
        /*********begin: set permissions based on custom role.**************/
        /** Search for RFQ status  */
        $statusSelected = 0;
        if (!empty($request->customStatusSearch) && trim($request->customStatusSearch) != 'all' ) {
            $statusSelected = $request->customStatusSearch;
            $userRfq = $userRfq->where('rfqs.status_id','=',$request->customStatusSearch);
        }
        /**
         * Custom search for rfq and quote
         */
        $customSearch =" ";
        if (!empty($request->customSearch)) {
            $customSearch = $request->customSearch;
            $userRfq = $userRfq->where(
                function($query) use($request){
                    $query->where(function($query) use($request){
                        $query->where('rfqs.id','LIKE','%'.$request->customSearch.'%');
                    })->orWhere(function($query) use($request) {
                        $query->where('quotes.id','LIKE','%'.$request->customSearch.'%');
                    })->orWhere(function($query) use($request) {
                        $query->where('rfq_products.category','LIKE','%'.$request->customSearch.'%');
                    })->orWhere(function($query) use($request) {
                        $query->where('rfq_products.sub_category','LIKE','%'.$request->customSearch.'%');
                    })->orWhere(function($query) use($request) {
                        $query->where('rfq_products.product','LIKE','%'.$request->customSearch.'%');
                    });
                });
        }

        $userRfq = $userRfq->orderBy('rfqs.id', 'desc')
        ->groupBy('rfqs.id')
        ->get(['rfqs.id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.created_at', 'rfqs.reference_number', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product as product_name', 'rfq_products.product_description', 'rfq_status.name as status_name', 'rfqs.address_line_1', 'rfqs.address_line_2', 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state', 'rfqs.state_id', 'rfqs.city_id', 'rfqs.pincode', 'rfq_products.quantity', 'units.name as unit_name', 'rfq_products.expected_date', 'rfq_products.comment', 'rfqs.rental_forklift', 'rfqs.unloading_services', 'rfqs.is_require_credit', 'rfqs.attached_document','rfqs.status_id','rfqs.group_id as groupId','groups.name as groupName', 'user_rfqs.user_id as created_by', 'rfqs.company_id','rfqs.payment_type','rfqs.credit_days', 'rfqs.is_favourite']);
        $companyData = Company::join('user_companies','companies.id','=','user_companies.company_id')
        ->join('users','user_companies.user_id','=','users.id')
        ->where('users.id',Auth::user()->id)
        ->first(['companies.registrantion_NIB','companies.npwp']);

        $companyData = Company::where('id', Auth::user()->default_company)->first(['companies.registrantion_NIB','companies.npwp']);
        $category = Category::where(['categories.is_deleted' => 0, 'categories.status' => 1])->orderBy('name', 'ASC')->get();
        $buyerRfqCountWhere = ['user_id' => Auth::user()->id, 'user_activity' => 'RFQ Created', 'notification_type' => 'rfq', 'side_count_show' => 0];
        BuyerNotification::where($buyerRfqCountWhere)->update(['side_count_show' => 1]);
        $buyerQuoteCountWhere = ['user_id' => Auth::user()->id, 'user_activity' => 'Quote Create', 'notification_type' => 'quote', 'side_count_show' => 0];
        BuyerNotification::where($buyerQuoteCountWhere)->update(['side_count_show' => 1]);
        $dots = getUnreadMessageAlert();
        /*** Get All status of RFQ */
        $rfqStatus =RfqStatus::all();
        $returnHTML = view('dashboard/rfq/viewRfq', ['userRfq' => $userRfq, 'companyData' => $companyData ,'rfqCount' => $userRfq->count(),'dots' => $dots,'isOwner'=>$isOwner,'rfqStatus'=>$rfqStatus,'statusSelected'=>$statusSelected,'customSearch'=>$customSearch])->render();
        $returnRepeatRfqHTML = view('dashboard/rfq/repeatRfq', ['userRfq' => $userRfq,'category'=>$category])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML, 'returnRepeatRfqHTML' => $returnRepeatRfqHTML, 'searchDataCount' => count($userRfq)));
    }

    function getDashboardOrders(Request $request)
    {
        $orders = DB::table('orders')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
//            ->join('cities', 'rfqs.city_id', '=', 'cities.id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            // ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->join('companies', 'orders.company_id', '=', 'companies.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->leftjoin('order_pos','orders.id','=','order_pos.order_id');

        /*********begin: set permissions based on custom role.**************/
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        if (Auth::user()->hasPermissionTo('list-all buyer orders') || $isOwner == true) {
            $orders->where('rfqs.company_id', Auth::user()->default_company);
        }else {
            $orders->where('orders.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);
        }
        /*********end: set permissions based on custom role.**************/
        /** Search for Order status  */
        $statusSelected = 0;
        if (!empty($request->customStatusSearch) && trim($request->customStatusSearch) != 'all' ) {
            $statusSelected = $request->customStatusSearch;
            $orders = $orders->where('orders.order_status','=',$request->customStatusSearch);
        }
        /**
         * Custom search for order
         */
        $customSearch = "";
        if (!empty($request->customSearch)) {
            $customSearch = $request->customSearch;

            $orders = $orders->where(
            function($query) use($request) {
                $query->where(function($query) use($request){
                    $query->where('orders.id','LIKE','%'.$request->customSearch.'%');
                })->orWhere(function($query) use($request) {
                    $query->where('rfq_products.category','LIKE','%'.$request->customSearch.'%');
                })->orWhere(function($query) use($request) {
                    $query->where('rfq_products.sub_category','LIKE','%'.$request->customSearch.'%');
                })->orWhere(function($query) use($request) {
                    $query->where('rfq_products.product','LIKE','%'.$request->customSearch.'%');
                });
            });
        }

        $orders = $orders->where('orders.is_deleted', 0)
            ->orderBy('orders.id', 'desc')
            ->groupBy('orders.id')
            ->get(['orders.*', 'ocd.request_days', 'ocd.approved_days', 'ocd.status as request_days_status',
                'quotes.quote_number', 'quotes.valid_till', 'rfq_products.product as product_name',
                'rfq_products.product_description as product_description', 'rfq_products.sub_category as sub_category_name',
                'rfq_products.category as category_name', 'order_status.name as order_status_name',
                'companies.name as company_name', 'users.firstname', 'users.lastname', 'rfqs.mobile as rfq_mobile',
                'rfqs.phone_code', 'rfq_products.quantity as product_quantity', 'units.name as product_unit',
                'quotes.address_name as quotesAddressName','quotes.address_line_1 as quotesAddressLineOne',
                'quotes.address_line_2 as quotesAddressLineTwo','quotes.district as quotesDistrict',
                'quotes.sub_district as quotesSubDistrict','quotes.city_id as quotesCity',
                'quotes.provinces as quotesProvinces','quotes.pincode as quotesPincode',
                'quotes.final_amount as product_final_amount','quotes.tax','quotes.tax_value',
                'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name',
                'suppliers.profile_username as supplier_profile_username','order_pos.po_number as po_number',
                'order_pos.inv_number as inv_number', 'orders.user_id as assigned_to',
            ]);


        foreach ($orders as $order) {
            $orderProducts = collect();
            $quoteProductIds = collect();

            $orderId = $order->id;
            $orderobj = Order::find($order->id);
            $order->full_quote_by = $orderobj->quote->getUser->role_id ?? 0;
            $order->bulk_discount = getBulkOrderDiscount($order->id) ?? 0;

            $quotesProducts = QuoteItem::where('quote_id',$order->quote_id)->get();
            $quoteProductIds = $quoteProductIds->push($order->quote_id);

            foreach ($quotesProducts as $quotesProduct){

                $productDetail = App\Models\Product::where('id',$quotesProduct->product_id)->first();

                $orderProducts = $orderProducts->push([
                    'id' => $quotesProduct->id ? $quotesProduct->id : null,
                    'quoteItemNumber' => $quotesProduct->quote_item_number ? $quotesProduct->quote_item_number : null,
                    'productQuantity' => $quotesProduct->product_quantity ? $quotesProduct->product_quantity : null,
                    'weight' => $quotesProduct->weights ? $quotesProduct->weights : null,
                    'length' => $quotesProduct->length,
                    'width' => $quotesProduct->width,
                    'height' => $quotesProduct->height,
                    'description' => $productDetail->description,
                    'name' => $productDetail->name
                ]);
            }

            $orderStatus = DB::table('order_status')
                ->leftJoin('order_tracks', function ($join) use ($orderId) {
                    $join->on('order_status.id', '=', 'order_tracks.status_id')
                        ->where('order_tracks.order_id', $orderId);
                })
                ->where('order_status.is_deleted', 0)
                ->groupBy('order_status.name')
                //->orderBy('order_status.show_order_id', 'asc')
                ->get(['order_status.id as order_status_id', 'order_status.name as status_name', 'order_tracks.created_at', 'order_tracks.id as order_track_id', 'order_status.show_order_id', 'order_status.credit_sorting']);
            $order->orderTracks = OrderTrack::where('order_id', $orderId)->pluck('status_id');
            $order->orderItems = OrderItem::where('order_id', $orderId)->get();
            $order->invoice_status = '';
            $order->invoice_url = '';
            $order->expiry_date = '';
            $transactions = getRecordsByCondition('order_transactions',['order_id'=>$orderId],'invoice_url,expiry_date,status',0,'id DESC');
            $order->invoice_status = $transactions['status']??'';
            $order->invoice_url = $transactions['invoice_url']??'';
            $order->expiry_date = $transactions['expiry_date']??'';
            $order->quotesProducts = $orderProducts;
            $order->quoteProductIds = $quoteProductIds;
            if ($order->payment_type == 1 || $order->payment_type == 2) {
                if($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                    $whereNotIn = [8];
                    if (!empty($order->orderTracks->contains(9))){//if credit approved
                        $whereNotIn = array_merge($whereNotIn,[10]);
                    }
                    $orderStatus = $orderStatus->whereNotIn('order_status_id',$whereNotIn);
                }
                $order->orderAllStatus = $orderStatus->sortBy('credit_sorting')->values()->all();
            }
            else{
                $order->orderAllStatus = $orderStatus->sortBy('show_order_id')->take(10)->values();
            }
            $quotes_charges_with_amounts = DB::table('orders')
                ->join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
                ->where('orders.id', $orderId)
                ->orderBy('quotes_charges_with_amounts.created_at', 'desc')
                ->get();
            $order->quotes_charges_with_amounts = $quotes_charges_with_amounts;
            $order->logistics_service_code = $orderobj->orderItems->first() ? $orderobj->orderItems->first()->logistics_service_code : null;
            $order->pickup_service = $orderobj->orderItems->first() ? $orderobj->orderItems->first()->pickup_service : null;
            $order->pickup_fleet = $orderobj->orderItems->first() ? $orderobj->orderItems->first()->pickup_fleet : null;
            $order->insurance_flag = $orderobj->orderItems->first() ? $orderobj->orderItems->first()->insurance_flag : null;
            $order->closeflag = $orderobj->orderItems->first()->order_item_status_id ?? 0;
            //fetch group member discounts
            if ($order->group_id){
                $order->group_members_discount = DB::table('group_members_discounts')->where(['group_id'=>$order->group_id,'order_id'=>$order->id])->first();
                $order->product_total_amount = DB::table('order_items')->where(['order_id'=>$order->id])->pluck('product_amount')->first();
            }
            /*********begin:Payment set permissions based on custom role.**************/
            $isOwner = User::checkCompanyOwner();
            if (Auth::user()->hasPermissionTo('list-all buyer payments') || $isOwner == true) { // check list all and owner Admin condition
                $payNowPermission = 1; // set flag to 1 for Pay NOW option
            }else {
                if (Auth::user()->id == $order->user_id && Auth::user()->hasPermissionTo('create buyer payments')) { // user id and order user id  is same then display Pay Now
                    $payNowPermission = 1;  // set flag to 1 for Pay NOW option
                }else{
                    $payNowPermission = 0; // set flag For not display Pay NOW option
                }
            }
            /*********end: Payment set permissions based on custom role.**************/
            $order->payNowPermission = $payNowPermission;  // create new properties for check Pay NOW option
        }
        $buyerOrderCountWhere = ['user_id' => Auth::user()->id, 'user_activity' => 'Order Placed', 'notification_type' => 'order', 'side_count_show' => 0];
        BuyerNotification::where($buyerOrderCountWhere)->update(['side_count_show' => 1]);

        $allOrderStatus = OrderStatus::orderBy('show_order_id',"ASC")->get()->whereNotIn('id',[6,9]);
        $returnHTML = view('dashboard/order/viewOrder', ['orders' => $orders,'bankDetails'=>BankDetails::getBankDetails(),'allOrderStatus'=>$allOrderStatus,'statusSelected'=>$statusSelected,'customSearch'=>$customSearch])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML, 'searchDataCount' => count($orders)));
    }

    function refreshDashboardOrder($orderId)
    {
        $order = DB::table('orders')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'orders.company_id', '=', 'companies.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->leftjoin('order_pos','orders.id','=','order_pos.order_id')
            ->where('orders.id', $orderId)
            ->where('orders.is_deleted', 0)
            ->orderBy('orders.id', 'desc')
            ->first(['orders.*', 'ocd.request_days', 'ocd.approved_days', 'ocd.status as request_days_status', 'quotes.quote_number', 'quotes.valid_till', 'rfq_products.product as product_name', 'rfq_products.product_description as product_description', 'rfq_products.sub_category as sub_category_name', 'rfq_products.category as category_name', 'order_status.name as order_status_name', 'companies.name as company_name', 'users.firstname', 'users.lastname', 'rfqs.mobile as rfq_mobile', 'rfq_products.quantity as product_quantity', 'units.name as product_unit', 'quote_items.product_amount as product_amount', 'quotes.final_amount as product_final_amount','order_pos.po_number as po_number','order_pos.inv_number as inv_number','quotes.full_quote_by']);

            $orderStatus = DB::table('order_status')
                ->leftJoin('order_tracks', function ($join) use ($orderId) {
                    $join->on('order_status.id', '=', 'order_tracks.status_id')
                        ->where('order_tracks.order_id', $orderId);
                })
                ->where('order_status.is_deleted', 0)
                ->groupBy('order_status.name')
                //->orderBy('order_status.show_order_id', 'asc')
                ->get(['order_status.id as order_status_id', 'order_status.name as status_name', 'order_tracks.created_at', 'order_tracks.id as order_track_id', 'order_status.show_order_id', 'order_status.credit_sorting']);

        $order->invoice_status = '';
        $order->invoice_url = '';
        $order->expiry_date = '';
        $transactions = getRecordsByCondition('order_transactions',['order_id'=>$orderId],'invoice_url,expiry_date,status',0,'id DESC');
        $order->invoice_status = $transactions['status']??'';
        $order->invoice_url = $transactions['invoice_url']??'';
        $order->expiry_date = $transactions['expiry_date']??'';
        $orderTracks = OrderTrack::where('order_id', $orderId)->pluck('status_id');
        $orderItems = OrderItem::where('order_id', $orderId)->get();
        /*********begin:Payment set permissions based on custom role.**************/
        $isOwner = User::checkCompanyOwner();
        if (Auth::user()->hasPermissionTo('list-all buyer payments') || $isOwner == true) { // check list all and owner Admin condition
            $payNowPermission = 1; // set flag to 1 for Pay NOW option
        }else {
            if (Auth::user()->id == $order->user_id && Auth::user()->hasPermissionTo('create buyer payments')) { // user id and order user id  is same then display Pay Now
                $payNowPermission = 1;  // set flag to 1 for Pay NOW option
            }else{
                $payNowPermission = 0; // set flag For not display Pay NOW option
            }
        }

        /*********end: Payment set permissions based on custom role.**************/
        $order->payNowPermission = $payNowPermission;  // create new properties for check Pay NOW option
        if ($order->payment_type == 1 || $order->payment_type == 2) {
            $orderobj = Order::find($order->id);
            $orderItemCategory = $orderobj->orderItems->first()->quoteItem->rfqProduct->category;
            $orderItemCategoryId = $orderobj->orderItems->first()->quoteItem->rfqProduct->category_id;
            $orderlogisticProvided = $orderobj->orderItems->first()->quoteItem;
            if ($order->is_credit) {
                if ($order->order_status == 8) {//Payment Due on %s
                    $order->invoice_status = $transactions['status']??'';
                    $order->invoice_url = $transactions['invoice_url']??'';
                    $order->expiry_date = $transactions['expiry_date']??'';
                }

                if($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                    $whereNotIn = [8];
                    if (!empty($orderTracks->contains(9))){//if credit approved
                        $whereNotIn = array_merge($whereNotIn,[10]);
                    }
                    $orderStatus = $orderStatus->whereNotIn('order_status_id',$whereNotIn);
                }
                $order->orderAllStatus = $orderStatus->sortBy('credit_sorting')->values()->all();
                $returnHTML = view('dashboard/order/creditOrderStatusRefresh', ['order' => $order , 'orderTracks'=>$orderTracks, 'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided])->render();
            }
        }else{
            if ($order->order_status == 2 || $order->order_status == 10) {//Order Confirmed & Payment Pending  || Credit Rejected
                $order->invoice_status = $transactions['status']??'';
                $order->invoice_url = $transactions['invoice_url']??'';
                $order->expiry_date = $transactions['expiry_date']??'';
            }
            $orderobj = Order::find($order->id);
            $order->closeflag = $orderobj->orderItems->first()->order_item_status_id ?? 0;
            $orderItemCategory = $orderobj->orderItems->first()->quoteItem->rfqProduct->category;
            $orderItemCategoryId = $orderobj->orderItems->first()->quoteItem->rfqProduct->category_id;
            $orderlogisticProvided = $orderobj->orderItems->first()->quoteItem;
            $order->full_quote_by = $orderobj->quote->getUser->role_id ?? 0;
            $order->orderAllStatus = $orderStatus->sortBy('show_order_id')->take(10)->values();
            $returnHTML = view('dashboard/order/orderStatusRefresh', ['order' => $order, 'orderTracks'=>$orderTracks, 'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided])->render();
        }
        $status_name = __('order.' . trim($order->order_status_name));
        if ($order->order_status == 8) {//'Payment Due DD/MM/YYYY'
            $status_name = sprintf($status_name,changeDateFormat($order->payment_due_date,'d/m/Y'));
        }
        $payHref = '';
        if ($order->order_status == 2 || $order->order_status == 10) {
            $order->invoice_url = getRecordsByCondition('order_transactions',['order_id'=>$orderId],'invoice_url',1);
            if ($order->invoice_url) {
                $payHref = $order->invoice_url;
            }
        }
        return response()->json(array('success' => true, 'html' => $returnHTML, 'order_status' => $order->order_status, 'order_status_name' => $status_name, 'order_number' => $order->order_number,'pay_href'=>$payHref));
    }

    function getRfqQuotes(Request $request)
    {
        $rfqId = $request->rfqId;
        $quotes = Quote::join('rfqs', function($join) {
            $join->on('rfqs.id', '=', 'quotes.rfq_id');
        })->join('rfq_products', function($join) {
            $join->on('rfqs.id', '=', 'rfq_products.rfq_id');
        })->join('rfq_status', function($join) {
            $join->on('rfqs.status_id', '=', 'rfq_status.id');
        })->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
        ->leftJoin('approval_reject_reasons', 'approval_reject_reasons.quote_id','=','quotes.id')
        ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
        ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id') // added for manage permission wise data
        ->where('quotes.rfq_id', $rfqId)
        ->where('quotes.status_id', '<>', 5);

        /***********************begin: Quotes by permission set******************/
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        if ($isOwner == true || $authUser->hasPermissionTo('list-all buyer quotes')) {
            $quotes = $quotes->where('rfqs.company_id', $authUser->default_company);
        }elseif($authUser->hasPermissionTo('publish buyer quotes')){
            $quotes = $quotes->where('user_rfqs.user_id', $authUser->id)->where('rfqs.company_id', $authUser->default_company);
        }else{
            $quotes = $quotes->where('rfqs.company_id','<>', $authUser->default_company);
        }
        /**********************end: Quotes by permission set******************/
        /**
         * get custom search based quote
         */
        $customSearch =" ";
        if (!empty($request->customSearch)) {
            $customSearch = $request->customSearch;
            //$quotes = $quotes->Where('quotes.id','LIKE','%'.$request->customSearch.'%')->orwhere('rfqs.id','LIKE','%'.$request->customSearch.'%');
            $quotes = $quotes->where(
                function($query) use($request){
                    $query->where(function($query) use($request){
                         $query->where('quotes.id','LIKE','%'.$request->customSearch.'%');
                    })->orWhere(function($query) use($request) {
                         $query->where('rfqs.id','LIKE','%'.$request->customSearch.'%');
                    })->orWhere(function($query) use($request) {
                        $query->where('rfq_products.category','LIKE','%'.$request->customSearch.'%');
                    })->orWhere(function($query) use($request) {
                        $query->where('rfq_products.sub_category','LIKE','%'.$request->customSearch.'%');
                    })->orWhere(function($query) use($request) {
                        $query->where('rfq_products.product','LIKE','%'.$request->customSearch.'%');
                    });
                });
        }

       $quotes = $quotes->orderBy('quotes.id', 'desc')->groupBy('quotes.id')
            ->get(['quotes.id as quotes_id', 'quotes.quote_number', 'quotes.created_at', 'quotes.valid_till', 'quotes.final_amount', 'rfq_status.name as status_name', 'rfqs.reference_number as rfq_reference_number', 'rfq_products.product', 'rfq_products.product_description','quotes.status_id','quotes.termsconditions_file','suppliers.id as supplier_id','suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name','suppliers.profile_username as supplier_profile_username','rfqs.id as rfqId','quote_items.certificate','approval_reject_reasons.approval_person_id','approval_reject_reasons.reason_text','quote_items.logistic_check','quote_items.logistic_provided']);

        //get approval reject reason
        $approvalRejectReason = ApprovalRejectReason::with('getApprovalRejectReasons')->get();

        foreach ($quotes as $quote) {
            $orderCount = DB::table('orders')
                ->where('quote_id', $quote->quotes_id)
                ->count();
            if($quote->quoteStatus){
                $quote->quote_status_name = $quote->quoteStatus->name;
            }
            if ($orderCount > 0) {
                $quote->orderPlaced = true;
            } else {
                $quote->orderPlaced = false;
            }
            if($quote->termsconditions_file){
                $quotes->termsconditions_file = $quote->termsconditions_file;
            }else{
                $quotes->termsconditions_file = "";
            }
            $certificate_attachment = QuoteItem::where('quote_id', $quote->quotes_id)->where('certificate','!=','')->count();
            if($certificate_attachment > 0){
                $quote->certificate_attachment = $certificate_attachment;
            }
        }
        $compareQuote = Quote::join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
                ->selectRaw('count(quote_items.id) as number_of_products, quotes.id as id,quotes.final_amount,quote_items.min_delivery_days,quote_items.max_delivery_days,suppliers.name as supplier_company_name,quotes.status_id,valid_till,quote_items.logistic_check,quote_items.logistic_provided')
                ->where('quotes.rfq_id', $rfqId)->where('quotes.status_id', '<>', 5)
                ->groupBy('quotes.id')
                ->orderBy('number_of_products','desc')
                ->orderBy('quotes.final_amount','asc')
                ->orderBy('max_delivery_days','asc')
                ->get();
        $userRfq = DB::table('user_rfqs')
        ->join('rfqs', 'user_rfqs.rfq_id', '=', 'rfqs.id')
        ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
        ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
        ->join('units', 'rfq_products.unit_id', '=', 'units.id')
        ->leftJoin('groups','rfqs.group_id','=','groups.id');

        /***********************begin: User RFQ by permission set******************/
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();

        if ($isOwner == true || $authUser->hasPermissionTo('list-all buyer rfqs')) {

            $userRfq = $userRfq->where('rfqs.company_id', $authUser->default_company);

        }else {

            $userRfq = $userRfq->where('user_rfqs.user_id', $authUser->id)->where('rfqs.company_id', $authUser->default_company);

        }
        /***********************end: User RFQ by permission set******************/

        $userRfq = $userRfq->where('rfqs.is_deleted', 0)
        ->where('rfqs.id', $rfqId)
        ->orderBy('rfqs.id', 'desc')
        ->first(['rfqs.id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.email', 'rfqs.mobile', 'rfqs.created_at', 'rfqs.reference_number', 'rfqs.is_require_credit', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product as product_name', 'rfq_products.product_description', 'rfq_status.id as status_id', 'rfq_status.name as status_name', 'rfqs.address_line_1', 'rfqs.address_line_2', 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state', 'rfqs.city_id', 'rfqs.state_id', 'rfqs.pincode', 'rfq_products.quantity', 'units.id as unit_id','units.name as unit_name', 'rfq_products.expected_date', 'rfq_products.comment', 'rfqs.rental_forklift', 'rfqs.unloading_services','rfqs.attached_document','rfqs.group_id','groups.name as groupName','rfqs.termsconditions_file', 'user_rfqs.user_id as created_by', 'rfqs.company_id','rfqs.payment_type', 'rfqs.credit_days','rfqs.is_favourite']);

        $activities = RfqActivity::where('rfq_id',$rfqId)->where('is_deleted','0')->orderBy('id','DESC')->get();

        $user_rfqs = UserRfq::where('rfq_id',$rfqId)->where('is_deleted','0')->first();

        $category = Category::all()->where('is_deleted', 0);
        $subCategory = SubCategory::all()->where('is_deleted', 0);
        $brand = Brand::all()->where('is_deleted', 0);
        $grade = Grade::all()->where('is_deleted', 0);
        $unit = Unit::all()->where('is_deleted', 0);

        $companyDetails = UserCompanies::where('user_id',Auth::user()->id)->first(['user_companies.company_id']);

        // $approvalConfigUsers = User::join('user_companies','users.id','=','user_companies.user_id')
        // ->join('user_approval_configs', 'users.id', '=', 'user_approval_configs.user_id')
        // ->leftJoin('designations', 'users.designation', '=', 'designations.id')
        // //->where('users.id','!=',Auth::user()->id)
        // ->where('users.is_delete',0)->where('user_companies.company_id',$companyDetails->company_id)
        // ->orderBy('users.id','DESC')
        // ->get(['users.id as id','users.firstname as firstname','users.lastname as lastname','users.email as email','users.mobile as mobile','users.role_id as role_id','users.is_active as is_active','users.created_at as created_at','user_approval_configs.user_type as user_type','user_approval_configs.created_at as app_created_at','designations.name as designation']);

        // $approver = "Approver";
        // $approvalConfigUsers = CustomRoles::getUserByCustomRole(Auth::user()->default_company, $approver);

        //---- Get approval users count and permission
        $approvalUsers = collect();
        $isOwner = User::checkOwnerByCompanyId(Auth::user()->default_company);
        $approvalUsersByCompany = User::whereJsonContains('assigned_companies',Auth::user()->default_company)->get();
        foreach($approvalUsersByCompany as $cmpUser) {
            $approverPermissionId = Permission::findByName('approval buyer approval configurations')->id;       //Get permission id by permission name
            $permissions = getRolePermissionAttribute($cmpUser->id ?? null)['permissions'];                     //Get Role & permission by user id

            if (!empty($permissions)) {
                if (in_array($approverPermissionId, $permissions)) {
                    $approvalUsers->push($cmpUser);
                }
            }
        }

        $isAuthOwner = User::checkCompanyOwner();

        /*********begin: set permissions based on custom role.**************/
        if ((Auth::user()->hasPermissionTo('toggle buyer approval configurations') == true) || $isOwner == true) {
            $appProcessValue['approval_process'] = 1;
        } else {
            $appProcessValue['approval_process'] = 0;
        }
        /*********end: set permissions based on custom role.**************/

        //---- Get approval users count and permission End---//

        $companyData = Company::join('user_companies','companies.id','=','user_companies.company_id')
        ->join('users','user_companies.user_id','=','users.id')
        ->where('users.id',Auth::user()->id)
        ->first(['companies.registrantion_NIB','companies.npwp']);
        $all_rfqs = RfqProduct::where('rfq_id', $rfqId)->join('units', 'rfq_products.unit_id', '=', 'units.id')->get(['rfq_products.id as product_id','rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.quantity', 'rfq_products.product_description', 'units.name as unit_name']);
        $all_rfqsAttachment = RfqAttachment::where('rfq_id', $rfqId)->get();

        $rfqhtml = view('dashboard/rfq/viewidRfq', ['rfq' => $userRfq,'category' => $category, 'subCategory' => $subCategory, 'brand' => $brand, 'grade' => $grade, 'unit' => $unit,'rfqactivities' => $activities,'user_rfqs'=>$user_rfqs, 'all_products' => $all_rfqs,'rfq_attachments' => $all_rfqsAttachment,'quotes' => $quotes])->render();
        // return response()->json(array('success' => true, 'html' => $returnHTML));

        /**********begin:Preferred Suppliers set permissions based on custom role******/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer preferred supplier')){
            $allSuppliersIds = PreferredSupplier::where('company_id',Auth::user()->default_company)->get()->pluck(['supplier_id']);
        }else {
            $allSuppliersIds = PreferredSupplier::where('user_id',Auth::user()->id)->where('company_id', Auth::user()->default_company)->get()->pluck(['supplier_id']);
        }
        /**********end:Preferred Suppliers set permissions based on custom role******/

        $returnHTML = view('dashboard/rfq/listQuote', ['quotes' => $quotes, 'approvalConfigUsers' => $approvalUsers, 'countConfigUsers' => count($approvalUsers), 'companyData' => $companyData, 'all_products' => $all_rfqs, 'allSuppliersIds' => $allSuppliersIds,'processValue' => $appProcessValue, 'isAuthUser' => $isAuthOwner, 'approvalRejectReason' => $approvalRejectReason])->render();
        foreach($compareQuote as $key=>$val)
        {

            $val->orderPlaced = \App\Models\Order::where('quote_id', $val->id)->count();

            $query = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')->where('quote_id',$val->id)->where('user_quote_feedbacks.is_deleted',0);

            $totalData = $query->get(['users.id','users.email','user_quote_feedbacks.feedback','user_quote_feedbacks.rfq_id','user_quote_feedbacks.quote_id']);
            $acceptedFeedback = $query->where('user_quote_feedbacks.feedback',1)->count();
            $rejectedFeedback = $query->where('user_quote_feedbacks.feedback',2)->count();
            $val->percCount = $totalData->count() == 0 ? 0 : number_format($acceptedFeedback / $totalData->count() * 100, 0) . '%';
            $val->rejectPercCount = $totalData->count() == 0 ? 0 : number_format($rejectedFeedback / $totalData->count() * 100, 0) . '%';
            $val->getQuoteProducts = QuoteItem::where('quote_id', $val->id)->get(['rfq_product_id'])->toArray();

            $val->userFeedCount = $acceptedFeedback + $rejectedFeedback;
            $val->userData = $totalData;
            $val->totalUser = $totalData->count();
            $val->accepted = $acceptedFeedback;
            $val->rejected = $rejectedFeedback;
            $val->quote_status_name = $val->quoteStatus->backofflice_name;
        }
        $returnCompareHTML = view('dashboard/rfq/compareQuote',['compareQuote'=>$compareQuote,'rfqId'=>$rfqId,'isAuthUser' => $isAuthOwner,'approvalProcess'=>$appProcessValue['approval_process'],'all_products' => $all_rfqs])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML,'rfqhtml'=>$rfqhtml,'rfq' => $userRfq,'returnCompareHTML' => $returnCompareHTML,'flagCount'=>$compareQuote->count()));

    }

    //Get Approval Process Value
    /*public function getapprovalProcessValue() {
        // $appProcessValue = Company::join('user_companies','companies.id','=','user_companies.company_id')
        // ->where('user_companies.user_id',Auth::user()->id)->first(['companies.approval_process']);
        $approvalUsers = collect();
        $isOwner = User::checkOwnerByCompanyId(Auth::user()->default_company);
        $usersRolePermission = [];
        //$approver = "Approver";
        //$approversCount = CustomRoles::getUserByCustomRole(Auth::user()->default_company, $approver);

        $approvalUsersByCompany = User::whereJsonContains('assigned_companies',Auth::user()->default_company)->get();
        foreach($approvalUsersByCompany as $cmpUser) {
            $approverPermissionId = Permission::findByName('approval buyer approval configurations')->id;       //Get permission id by permission name
            $permissions = getRolePermissionAttribute($cmpUser->id ?? null)['permissions'];                     //Get Role & permission by user id

            if (!empty($permissions)) {
                if (in_array($approverPermissionId, $permissions)) {
                    $approvalUsers->push($cmpUser);
                }
            }
        }

        /*********begin: set permissions based on custom role.**************
        if ((Auth::user()->hasPermissionTo('toggle buyer approval configurations') == true && count($approvalUsers) != 0) || $isOwner == true) {
            // $appProcessValue = Company::join('users','companies.id','=','users.default_company')
            // ->where('users.id',Auth::user()->id)->first(['companies.approval_process']);
            $appProcessValue['approval_process'] = 1;
        } else {
            $appProcessValue['approval_process'] = 0;
        }
        /*********end: set permissions based on custom role.**************
        return response()->json(array('success' => true, 'processValue' => $appProcessValue));
    }
    */

    public function UpdateToggleValue(Request $request) {
        $updateValue = UserCompanies::join('users','users.id','=','user_companies.user_id')
        ->join('companies','companies.id','=','user_companies.company_id')
        ->where('users.id',Auth::user()->id)
        ->update(["companies.approval_process" => $request->toggleValue]);
        return response()->json(array('success' => true));
    }

    // Send quote for approval to configure users
    // @parameter : quoteId
    // @devloped by : Ronak M
    public function sendQuoteForApproval(Request $request) {
        $authUser = Auth::user();
        $company = User::select('default_company')->where('id',Auth::user()->id)->first();

        $permissionId = Permission::findByName('approval buyer approval configurations')->id;
        $permissionsUserIds = CustomPermission::getUserByCustomPermissionId(Auth::user()->default_company, $permissionId);
        // $approver = "Approver";
        // $approverIds = CustomRoles::getUserByCustomRole(Auth::user()->default_company, $approver);

        $userIds = array_column($permissionsUserIds->toArray(), 'model_id');
        // $quote = Quote::join('user_rfqs', function($join) {
        //     $join->on('user_rfqs.rfq_id', '=', 'quotes.rfq_id');
        // })->join('rfq_products', function($join) {
        //     $join->on('user_rfqs.rfq_id', '=', 'rfq_products.rfq_id');
        // })->join('rfqs', function($join) {
        //     $join->on('user_rfqs.rfq_id', '=' ,'rfqs.id' );
        // });

        $quote = Quote::join('user_rfqs', 'user_rfqs.rfq_id', '=', 'quotes.rfq_id')
        ->join('rfq_products', 'user_rfqs.rfq_id', '=', 'rfq_products.rfq_id')
        ->join('rfqs', 'user_rfqs.rfq_id', '=' ,'rfqs.id');

        /***********************begin: User RFQ by permission set******************/
        $isOwner = User::checkCompanyOwner();
        if ($isOwner == true || $authUser->hasPermissionTo('list-all buyer quotes')) {

            $quote = $quote->where('rfqs.company_id', $authUser->default_company);

        }else {
            $quote = $quote->where('user_rfqs.user_id', $authUser->id)->where('rfqs.company_id', $authUser->default_company);

        }
        /***********************end: User RFQ by permission set******************/

        $quote = $quote->where('quotes.id',$request->quoteId)
        ->first(['quotes.id','quotes.quote_number','quotes.rfq_id','quotes.final_amount']);

        $quote_items = QuoteItem::where('quote_id', $request->quoteId)->get()->toArray();

        /** begin : check approval toggle and approver permission of User  */
        if ($authUser->hasPermissionTo('approval buyer approval configurations') && $authUser->hasPermissionTo('toggle buyer approval configurations')) {
            $userIds =array_diff($userIds,[Auth::user()->id]); // remove userid that has both permission of approval process
            /** Add entry into quote feedback table of that users who has both permission of approval process */
            UserQuoteFeedback::insert([
                'user_id' => $authUser->id,
                'rfq_id' => $quote->rfq_id,
                'quote_id' => $quote->id,
                'security_code' => rand(100000, 999999),
                'approval_person_id' => $authUser->id,
                'feedback'=>1, // Self approved
                'company_id' => $authUser->default_company
            ]);
        }
        /** end : check approval toggle and approver permission of User */
        $configureUsers = User::whereIn('users.id',$userIds)->get(['users.id as id','users.firstname','users.lastname','users.email']);

        foreach($configureUsers as $user) {
            $dataExist = UserQuoteFeedback::where('user_id',$user->id)->where('rfq_id',$quote->rfq_id)->where('quote_id',$quote->id)->first();
            if(isset($dataExist) && !empty($dataExist)) {
                continue;
                //$msg = 'Mail Already Sent For This Quote';
            } else {
                $otp = rand(100000, 999999);
                UserQuoteFeedback::insert([
                    'user_id' => $user->id,
                    'rfq_id' => $quote->rfq_id,
                    'quote_id' => $quote->id,
                    'security_code' => $otp,
                    'approval_person_id' => $authUser->id,
                    'company_id' => $authUser->default_company
                ]);

                $useremail = [
                    'user' => $user,
                    'quote' => $quote,
                    'quote_items' => $quote_items,
                    'url' => route('quote-details', ['id' => Crypt::encrypt($request->quoteId),'userId' => $user->id,'latestOTP' => $otp]),
                    'otp' => $otp
                ];
                //Send quote approval mail to configure users
                try {
                    dispatch(new QuoteApprovalMailJob($useremail));
                } catch (\Exception $e) {
                    dd($e);
                }
            }
            $smsData['rfq_number'] = 'BRFQ-'.$quote->rfq_id;
            $smsData['quote_number'] = 'BQTN-' . $quote->id;
            $sendMsg = $this->verify->sendMsg($user->firstname,$user->lastname,'quote_received_for_approval',$user->phone_code,$user->mobile,$smsData);
        }

        //Insert data in "quotes_meta" table (Ronak M - 10/10/2022)
        $quotesMetaData = new QuotesMeta();
        $quotesMetaData->quote_id = $quote->id;
        $quotesMetaData->user_type = User::class;
        $quotesMetaData->user_id = $authUser->id;
        $quotesMetaData->approval_process = 1;              // 1 = On
        $quotesMetaData->approval_process_complete = 0;     // 0 = In progress , 1 = Completed
        $quotesMetaData->save();
        //End

        $msg = 'Quote Approval Mail Send Successfully';
        return response()->json(array('success' => true, 'msg' => $msg));
    }

    //Get quote details by quote id
    // @parameter : quoteId
    // @devloped by : Ronak
    public function quoteDetailsByQuoteId($quoteId,$userId,$latestOTP) {
        $quoteId = Crypt::decrypt($quoteId);
        $userData = UserQuoteFeedback::select('feedback','resend_mail','security_code')->where('user_id',$userId)->where('quote_id',$quoteId)->where('resend_mail',0)->first();
        $quote = Quote::join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
            ->join('rfq_status', 'rfq_status.id', '=', 'rfqs.status_id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
            ->join('user_companies', 'user_rfqs.user_id', '=', 'user_companies.user_id')
            ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
            ->where('quotes.id', $quoteId)
            ->first(['quotes.id as quotes_id', 'quotes.quote_number', 'quotes.created_at', 'quotes.valid_till', 'quotes.status_id', 'rfqs.is_require_credit', 'quotes.final_amount', 'rfq_status.name as status_name', 'rfqs.reference_number as rfq_reference_number', 'rfq_products.product as product_name', 'rfq_products.product_description as product_description', 'rfqs.firstname as rfq_firstname', 'rfqs.lastname as rfq_lastname', 'rfqs.pincode as rfq_pincode', 'rfq_products.sub_category as sub_category_name', 'rfq_products.category as category_name', 'rfqs.mobile as rfq_mobile',  'quotes.note', 'quotes.tax', 'quotes.tax_value', 'companies.name as user_company_name', 'quotes.certificate', 'quotes.comment', 'rfq_products.expected_date', 'rfq_products.comment as rfq_comment', 'rfqs.id as rfq_id', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name']);

        $quote_items = QuoteItem::where('quote_id', $quoteId)->get()->toArray();
        $quotesCharges = QuoteChargeWithAmount::where('quotes_charges_with_amounts.quote_id', $quoteId)->orderBy('charge_type', 'asc')->get();

        if(isset($userData) && $userData->feedback == 0 && $userData->resend_mail == 0) {
            return view('profile/quote_details', ['quote' => $quote, 'quote_items' => $quote_items,'quotesCharges' => $quotesCharges, 'userData'=> $userData, 'latestOTP' => $latestOTP]);
        } else if(isset($userData) && $userData->feedback == 1) {
            return view('profile/feedback_thankyou', ['quote' => $quote,'quotesCharges' => $quotesCharges]);
        } else {
            return view('profile/feedback_submitted', ['quote' => $quote,'quotesCharges' => $quotesCharges]);
        }

    }

    //Verify feedback otp
    function verifyFeedbackOTP(Request $request) {
        $quote_details_pending = UserQuoteFeedback::where('quote_id',$request->quoteId)->where('resend_mail',0)->where('security_code',$request->feedback_otp)->first();
        if(empty($quote_details_pending)){
            return response()->json(array('ErrorOTP' => true, 'ErrorMessage' => 'Invalid Otp'));
        }
        $quote_details = UserQuoteFeedback::where('quote_id',$request->quoteId)->where('security_code',$request->feedback_otp)->first();
        if (empty($quote_details) || $quote_details->security_code != $request->feedback_otp){
            return response()->json(array('ErrorOTP' => true, 'ErrorMessage' => 'Invalid Otp'));
        }
        if (empty($quote_details) || $quote_details->security_code != $request->feedback_otp){
            return response()->json(array('ErrorOTP' => true, 'ErrorMessage' => 'Invalid Otp'));
        }
        //Show Thank You page
        if ($request->feedback_otp == $quote_details->security_code && $quote_details->feedback == 0) {

            $userQuoteFeedback = UserQuoteFeedback::where(['quote_id' => $request->quoteId, 'security_code' => $request->feedback_otp])->first();
            $userQuoteFeedback->feedback = $request->feedback;
            $userQuoteFeedback->save();

            /**begin: system log for approval process feedback **/
            UserQuoteFeedback::bootSystemActivities();
            /**end: system log**/

            //Call this function to calculate percentage of accept or reject
            $this->getQuoteFeedbackData($request);

            return response()->json(array('ErrorOTP' => false, 'return_url' => route('feedback-thank-you')));

        } else {

            return response()->json(array('ErrorOTP' => false, 'return_url' => route('feedback-submitted')));
        }
    }

    //Approval Process Completer or not (Ronak M - 11/10/2022)
    public function isQuoteCompleteOrNot($quoteId, $percCount, $rejectPercCount, $userFeedCount) {

        //If $percCount = 100% then update approval_process_complete = 1 in quotes_meta table (Ronak M - 11/10/2022)
        if($percCount == "100%") {

            $QuotesMeta = QuotesMeta::where(['quote_id' => $quoteId])->first();
            $QuotesMeta->approval_process_complete = 1;
            $QuotesMeta->save();

            /**begin: system log for approval process completion **/
            QuotesMeta::bootSystemActivities();
            /**end: system log**/

        } else {

        }

    }

    public function feedbackThankYou() {
        return view('profile/thank_you_feedback');
    }

    public function feedbackSubmitted() {
        return view('profile/feedback_submitted');
    }

    //Get quote feedback data by quote id
    public function getQuoteFeedbackData(Request $request) {
        $totalData = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')
        //->join('user_approval_configs','user_approval_configs.user_id','=','users.id')
        ->where('quote_id',$request->quoteId)->where('user_quote_feedbacks.is_deleted','!=','1')
        ->get(['users.id','users.email','user_quote_feedbacks.feedback','user_quote_feedbacks.rfq_id','user_quote_feedbacks.quote_id']);

        $acceptedFeedback = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')
        //->join('user_approval_configs','user_approval_configs.user_id','=','users.id')
        ->where('quote_id',$request->quoteId)->where('user_quote_feedbacks.feedback',1)->where('user_quote_feedbacks.is_deleted',0)
        ->get();

        $rejectedFeedback = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')
        //->join('user_approval_configs','user_approval_configs.user_id','=','users.id')
        ->where('quote_id',$request->quoteId)->where('user_quote_feedbacks.feedback',2)->where('user_quote_feedbacks.is_deleted',0)
        ->get();

        $percCount = $totalData->count() == 0 ? 0 : number_format($acceptedFeedback->count() / $totalData->count() * 100, 0) . '%';
        $rejectPercCount = $totalData->count() == 0 ? 0 : number_format($rejectedFeedback->count() / $totalData->count() * 100, 0) . '%';

        $userFeedCount = $acceptedFeedback->count() + $rejectedFeedback->count();

        $this->isQuoteCompleteOrNot($request->quoteId, $percCount, $rejectPercCount, $userFeedCount);

        return response()->json(array('success' => true, 'userData' => $totalData, 'totalUser' => $totalData->count(), 'percCount' => $percCount, 'accepted' => $acceptedFeedback->count(), 'rejectPercCount' => $rejectPercCount, 'rejected' => $rejectedFeedback->count(), 'userFeedCount' =>  $userFeedCount ));
    }

    //Resend mail to configure user
    public function configureUserResendMail($userId, $quoteId, $pending_resend) {
        $configureUsers = User::where('users.id',$userId)->where('users.is_active',1)
        ->first(['users.id as id','users.firstname as firstname','users.lastname as lastname','users.email as email']);
        $quote = Quote::join('user_rfqs', function($join) {
            $join->on('user_rfqs.rfq_id', '=', 'quotes.rfq_id');
        })->join('rfq_products', function($join) {
            $join->on('user_rfqs.rfq_id', '=', 'rfq_products.rfq_id');
        })->join('rfqs', function($join) {
            $join->on('user_rfqs.rfq_id', '=' ,'rfqs.id' );
        });

        /***********************begin: User RFQ by permission set******************/
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        if ($isOwner == true || $authUser->hasPermissionTo('list-all buyer quotes')) {

            $quote = $quote->where('rfqs.company_id', $authUser->default_company);

        }else {
            $quote = $quote->where('user_rfqs.user_id', $authUser->id)->where('rfqs.company_id', $authUser->default_company);

        }
        /***********************end: User RFQ by permission set******************/

        $quote = $quote->where('quotes.id',$quoteId)
        ->first(['quotes.id','quotes.quote_number','quotes.rfq_id','quotes.final_amount']);
        $quote_items = QuoteItem::where('quote_id', $quoteId)->get()->toArray();
        $otp = rand(100000, 999999);

        //Update user data (set is_deleted = 1)
        if($pending_resend == 1){
            UserQuoteFeedback::where("user_id",$userId)->where("quote_id",$quoteId)->where("feedback",0)->where("resend_mail",0)->update(["resend_mail" => 1,"is_deleted" => 1]);
        }else{
            UserQuoteFeedback::where("user_id",$userId)->where("quote_id",$quoteId)->where("feedback",2)->where("resend_mail",0)->update(["resend_mail" => 1,"is_deleted" => 1]);
        }
        //Insert a new record for resend mail
        UserQuoteFeedback::insert([
            'user_id' => $configureUsers->id,
            'rfq_id' => $quote->rfq_id,
            'quote_id' => $quoteId,
            'security_code' => $otp,
        ]);
        $useremail = [
            'user' => $configureUsers,
            'quote' => $quote,
            'quote_items' => $quote_items,
            'url' => route('quote-details', ['id' => Crypt::encrypt($quoteId),'userId' => $userId ,'userType' => $configureUsers->user_type,'latestOTP' => $otp]),
            'otp' => $otp
        ];
        //Send quote approval mail to configure users
        try {
            dispatch(new QuoteApprovalMailJob($useremail));
        } catch (\Exception $e) {
            //dd($e);
        }
        return response()->json(array('success' => true, 'msg' => __('dashboard.resend_approval_mail')));
    }

    function getRfqDetails($rfqId)
    {
        $userRfq = DB::table('user_rfqs')
            ->join('rfqs', 'user_rfqs.rfq_id', '=', 'rfqs.id')
            ->leftjoin('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->leftjoin('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
            ->leftjoin('user_companies', 'user_rfqs.user_id', '=', 'user_companies.user_id')
            ->leftjoin('users', 'users.id', '=', 'user_rfqs.user_id')
            // ->leftjoin('companies', 'user_companies.company_id', '=', 'companies.id')
            ->join('companies', 'rfqs.company_id', '=', 'companies.id')
            ->leftjoin('units', 'rfq_products.unit_id', '=', 'units.id');

        /****************************begin: RFQs By Permission ************************************/
        if (Auth::user()->hasPermissionTo('list-all buyer rfqs') || Auth::user()->default_company) {
            $userRfq->where('rfqs.company_id', Auth::user()->default_company);
        }else{
            $userRfq->where('user_rfqs.user_id', Auth::user()->id);
        }
        /****************************end: RFQs By Permission ************************************/

        $userRfq = $userRfq->where('rfqs.id',$rfqId)
                ->where('rfqs.is_deleted', 0)
                ->orderBy('rfqs.id', 'desc')
                ->groupBy('rfqs.id')
                ->get(['rfqs.id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.created_at', 'rfqs.reference_number', 'rfqs.is_require_credit', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product as product_name', 'rfq_products.product_description', 'rfq_status.name as status_name', 'rfqs.address_line_1', 'rfqs.address_line_2', 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state', 'rfqs.city_id', 'rfqs.state_id', 'rfqs.pincode', 'companies.name as user_company_name','rfq_products.quantity', 'units.name as unit_name', 'rfq_products.expected_date', 'rfq_products.comment', 'rfqs.rental_forklift', 'rfqs.unloading_services', 'rfqs.mobile', 'rfqs.phone_code', 'rfq_products.comment as rfq_comment','rfqs.attached_document as attached_document','rfqs.termsconditions_file', 'users.phone_code as user_phone_code','rfqs.group_id','rfqs.payment_type','rfqs.credit_days']);
                $all_rfqs = RfqProduct::where('rfq_id', $rfqId)->join('units', 'rfq_products.unit_id', '=', 'units.id')->get(['rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.quantity', 'rfq_products.product_description', 'units.name as unit_name']);
        $all_rfqsAttachment = RfqAttachment::where('rfq_id', $rfqId)->get();
        $returnHTML = view('dashboard/rfq/rfqDetails', ['userRfq' => $userRfq, 'all_products' => $all_rfqs,'rfq_attachments' =>$all_rfqsAttachment])->render();
        //$returnHTML = view('dashboard/rfq/rfqDetails', ['userRfq' => $userRfq, 'all_products' => $all_rfqs])->render();

        /**begin: system log**/
        Rfq::bootSystemView(new Rfq());
        /**end:  system log**/
        return response()->json(array('success' => true, 'html' => $returnHTML));

    }

    function getRfqQuoteDetails($quoteId)
    {
        $quote = DB::table('quotes')
            ->join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
            ->join('rfq_status', 'rfq_status.id', '=', 'rfqs.status_id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
            ->join('user_companies', 'user_rfqs.user_id', '=', 'user_companies.user_id')
            ->join('users', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'rfqs.company_id', '=', 'companies.id')
            //->join('products', 'quotes.product_id', '=', 'products.id')
            //->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            //->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('units', 'quote_items.price_unit', '=', 'units.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->where('quotes.id', $quoteId)
            ->first(['quotes.id as quotes_id', 'quotes.quote_number', 'quotes.created_at', 'quotes.valid_till', 'quotes.status_id', 'rfqs.is_require_credit', 'quotes.final_amount','quotes.termsconditions_file', 'rfq_status.name as status_name', 'rfqs.reference_number as rfq_reference_number', 'rfq_products.product as product_name', 'rfq_products.product_description as product_description', 'rfqs.firstname as rfq_firstname', 'rfqs.lastname as rfq_lastname', 'rfqs.pincode as rfq_pincode', 'rfq_products.sub_category as sub_category_name', 'rfq_products.category as category_name', 'units.name as unit_name', 'rfqs.mobile as rfq_mobile', 'quotes.note', 'quotes.tax', 'quotes.tax_value', 'companies.name as user_company_name', 'quotes.certificate', 'quotes.comment', 'rfq_products.expected_date', 'rfq_products.comment as rfq_comment', 'rfqs.id as rfq_id', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name', 'suppliers.profile_username as supplier_profile_username','users.phone_code as user_phone_code','rfqs.group_id','quote_items.inclusive_tax_other','quote_items.inclusive_tax_logistic','quote_items.certificate','quotes.payment_type','quotes.credit_days']);


        //for checking order is placed or not
        $allQuotes = DB::table('quotes')
            ->join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
            ->join('rfq_status', 'rfq_status.id', '=', 'rfqs.status_id')
            //->join('products', 'quotes.product_id', '=', 'products.id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->where('quotes.rfq_id', $quote->rfq_id)
            ->orderBy('quotes.id', 'desc')
            ->get(['quotes.id as quotes_id', 'quotes.quote_number', 'quotes.created_at', 'quotes.valid_till', 'quotes.final_amount', 'rfq_status.name as status_name', 'rfqs.reference_number as rfq_reference_number', 'rfq_products.product', 'rfq_products.product_description',]);
        $multiple_quote = QuoteItem::where('quote_id', $quoteId)->join('units', 'quote_items.price_unit', '=', 'units.id')->get();
        $isOrderPlaced = false;
        foreach ($allQuotes as $singleQuote) {
            $orderCount = DB::table('orders')
                ->where('quote_id', $singleQuote->quotes_id)
                ->count();
            if ($orderCount > 0) {
                $isOrderPlaced = true;
                break;
            } else {
                $isOrderPlaced = false;
            }
        }
        $quote->orderPlaced = $isOrderPlaced;
        $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))
            ->where('quotes_charges_with_amounts.quote_id', $quoteId)
            ->orderBy('charge_type', 'asc')
            ->get();

        $approversList = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')
        ->leftJoin('designations','designations.id','=','users.designation')
        ->where('user_quote_feedbacks.quote_id',$quoteId)
        ->where('user_quote_feedbacks.resend_mail',0)
        ->get(['users.firstname','users.lastname','designations.name','user_quote_feedbacks.feedback','user_quote_feedbacks.user_id']);

        /*$approver = "Approver";
        $ConfigUsersCount = CustomRoles::getUserByCustomRole(Auth::user()->default_company, $approver);*/

        //---- Get approval users count and permission
        $approvalUsers = collect();
        $isOwner = User::checkOwnerByCompanyId(Auth::user()->default_company);

        $approvalUsersByCompany = User::whereJsonContains('assigned_companies',Auth::user()->default_company)->get();


        foreach($approvalUsersByCompany as $cmpUser) {
            $approverPermissionId = Permission::findByName('approval buyer approval configurations')->id;       //Get permission id by permission name
            $permissions = getRolePermissionAttribute($cmpUser->id ?? null)['permissions'];                     //Get Role & permission by user id

            if (!empty($permissions)) {
                if (in_array($approverPermissionId, $permissions)) {
                    $approvalUsers->push($cmpUser);
                }
            }
        }

        $quoteApproverUserId = array_column($approversList->toArray(), 'user_id'); // Get Quote base approver user id
        $approverUserId = array_column($approvalUsers->toArray(), 'id'); // Get Total approver user id
        $finalconfigUser =array_diff($approverUserId,$quoteApproverUserId); // remove approver who doesn't have permission for that Quote
        if (!empty($finalconfigUser)) {
            $approvalUsers = $finalconfigUser;
        }
        //End
        if(Auth::user()->hasRole('buyer') || Auth::user()->hasRole('sub-buyer')) {
            $isAuthOwner = User::checkCompanyOwner();

            $isOwner = User::checkOwnerByCompanyId(Auth::user()->default_company);

            if ((Auth::user()->hasPermissionTo('toggle buyer approval configurations') == true) || $isOwner == true) {
                $toggleSwitch['approval_process'] = 1;
            } else {
                $toggleSwitch['approval_process'] = 0;
            }
        } else {

            $toggleSwitch['approval_process'] = 0;
            $ConfigUsersCount = [];
            $isAuthOwner = null;
        }

        $companyDetails = UserCompanies::join('companies', 'user_companies.company_id', '=', 'companies.id')
        ->where('user_id', Auth::user()->id)->first(['companies.id as company_id']);

        $returnHTML = view('dashboard/rfq/quoteDetails', ['quote' => $quote, 'quotes_charges_with_amounts' => $quotes_charges_with_amounts, 'toggleSwitch' => $toggleSwitch,'approversList' => $approversList,'ConfigUsersCount' => count($approvalUsers), 'quote_items' => $multiple_quote, 'isAuthUser' => $isAuthOwner])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    function getRfqPlaceOrderDetails(Request $request, $quoteId)
    {
        $quote = DB::table('quotes')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
            ->join('rfq_status', 'rfq_status.id', '=', 'rfqs.status_id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('units', 'quote_items.price_unit', '=', 'units.id')
            ->where('quotes.id', $quoteId)
            ->first(['quotes.id as quotes_id', 'quotes.quote_number', 'quotes.created_at', 'quotes.valid_till', 'quotes.final_amount','quotes.termsconditions_file',  'rfq_status.name as status_name', 'rfqs.is_require_credit', 'rfqs.group_id', 'rfqs.reference_number as rfq_reference_number', 'rfq_products.product as product_name', 'rfq_products.product_description as product_description', 'rfqs.firstname as rfq_firstname', 'rfqs.lastname as rfq_lastname', 'rfqs.address_name as rfq_address_name', 'rfqs.address_line_1 as rfq_address_line_1', 'rfqs.address_line_2 as rfq_address_line_2', 'rfqs.sub_district as rfq_sub_district', 'rfqs.district as rfq_district', 'rfqs.city as rfq_city', 'rfqs.state as rfq_state', 'rfqs.city_id as rfq_city_id', 'rfqs.state_id as rfq_state_id',  'rfqs.pincode as rfq_pincode', 'rfq_products.sub_category as sub_category_name', 'rfq_products.category as category_name','rfq_products.category_id', 'units.name as unit_name', 'rfqs.mobile as rfq_mobile',  'quotes.note','quotes.payment_type','quotes.credit_days']);
        // $userAddress = UserAddresse::all()->where('user_id', Auth::user()->id)->where('is_deleted', 0)->sortByDesc('id');
        $userAddress = DB::table('user_addresses')
            ->join('users', 'user_addresses.user_id', '=', 'users.id')
            ->where('user_addresses.user_id', Auth::user()->id)
            ->where('user_addresses.is_deleted', 0)
            ->orderBy('user_addresses.id', 'desc')
            ->get();

        $tc_document = TermsCondition::first();
        $creditDays = CreditDays::getAllActiveCreditDays();
        $quote_items = QuoteItem::where('quote_id', $quoteId)->join('units', 'quote_items.price_unit', '=', 'units.id')->get();
        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
        $quoteGroupDiscount = 0;
        $group = null;
        if (!empty($quote->group_id)){
            $quoteGroupDiscount = QuoteChargeWithAmount::where(['quote_id'=>$quote->quotes_id,'charge_name'=>'Group Discount'])->pluck('charge_value')->first();
            $group = Groups::find($quote->group_id);
        }
        $loan = null;
        /***********************************begin: Koinworks by set Permissions*******************************************/
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        if (Auth::user()->hasPermissionTo('utilize buyer company credit') || $isOwner == true) {
            $loan = LoanApplication::where(['company_id'=>$authUser->default_company,'status_name'=>'Approved'])->pluck('id')->first();
        }
        /***********************************end: Koinworks by set Permissions*******************************************/
        $creditInfoDetail = LoanApplication::getCreditDatail();

        $returnHTML = view('dashboard/rfq/placeOrderDetails', ['quote' => $quote,'userAddress' => $userAddress,'creditDays'=>$creditDays, 'quote_items' => $quote_items, 'states' => $states,'tc_document' =>$tc_document, 'quoteGroupDiscount'=>$quoteGroupDiscount,'group'=>$group,'loan'=>$loan, 'creditInfoDetail' => $creditInfoDetail])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
    function geteditRfqs($rfqId)
    {
        $userRfq = DB::table('user_rfqs')
        ->join('rfqs', 'user_rfqs.rfq_id', '=', 'rfqs.id')
        ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
        ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
        ->leftjoin('rfq_maximum_products', 'rfqs.id', '=', 'rfq_maximum_products.rfq_id')
        ->join('units', 'rfq_products.unit_id', '=', 'units.id');

        /***********************************begin: RFQ by set Permissions*******************************************/
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        if (Auth::user()->hasPermissionTo('list-all buyer rfqs') || $isOwner == true) {
            $userRfq->where('rfqs.company_id', Auth::user()->default_company);

        } else {
            $userRfq->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);
        }
        /***********************************end: RFQ by set Permissions*******************************************/

        $userRfq = $userRfq->where('rfqs.is_deleted', 0)
        ->where('rfqs.id', $rfqId)
        ->orderBy('rfqs.id', 'desc')
        ->first(['rfqs.id', 'rfqs.group_id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.email', 'rfqs.phone_code', 'rfqs.mobile', 'rfqs.created_at', 'rfqs.reference_number', 'rfqs.is_require_credit', 'rfq_products.category','rfq_products.category_id', 'rfq_products.sub_category', 'rfq_products.product as product_name', 'rfq_products.product_description', 'rfq_status.id as status_id', 'rfq_status.name as status_name', 'rfqs.address_name', 'rfqs.address_line_1', 'rfqs.address_line_2', 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state', 'rfqs.state_id', 'rfqs.city_id', 'rfqs.pincode', 'rfq_products.quantity', 'units.id as unit_id','units.name as unit_name', 'rfq_products.expected_date', 'rfq_products.comment', 'rfqs.rental_forklift', 'rfqs.unloading_services','rfqs.attached_document','rfqs.termsconditions_file','rfq_maximum_products.max_products','rfqs.is_preferred_supplier','rfqs.is_favourite','rfqs.payment_type','rfqs.credit_days']);
        $userRfq = ($userRfq == null) ? null : $userRfq;


        $activities = RfqActivity::where('rfq_id',$rfqId)->where('is_deleted','0')->orderBy('id','DESC')->get();

        $user_rfqs = UserRfq::where('rfq_id',$rfqId)->where('is_deleted','0')->first();

        $category = Category::where(['categories.is_deleted' => 0, 'categories.status' => 1])->orderBy('name', 'ASC')->get();
        $subCategory = SubCategory::all()->where('is_deleted', 0);
        $brand = Brand::all()->where('is_deleted', 0);
        $grade = Grade::all()->where('is_deleted', 0);
        $unit = Unit::all()->where('is_deleted', 0);

        $userAddress = UserAddresse::all();

        /***********************************begin: Addresses by set Permissions*******************************************/
        //Get company wise address in RFQ place
        $companyAddress = UserAddresse::companyWiseAddress();
        /***********************************end: Addresses by set Permissions*******************************************/


        $all_rfqs = RfqProduct::where('rfq_id', $rfqId)->join('units', 'rfq_products.unit_id', '=', 'units.id')->get(['rfq_products.id as id', 'rfq_products.category', 'rfq_products.category_id', 'rfq_products.sub_category_id as product_sub_category_id', 'rfq_products.sub_category as product_sub_category', 'rfq_products.product_id', 'rfq_products.product as product_name', 'rfq_products.quantity', 'rfq_products.product_description', 'units.name as unit_name', 'units.id as unit']);

        $all_attachments = RfqAttachment::where('rfq_id', $rfqId)->get();
        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
        //get chat history data
        /*
        Ekta 06/07/22
        */
        $chatHistory = getChatHistoryRfqById($rfqId,'Rfq');


        $preferredSupplierCount = PreferredSupplier::where('user_id',Auth::user()->id)->count(); //dd($preferredSupplierCount);

        $preferredSuppliers = PreferredSupplier::leftJoin('user_suppliers','preferred_suppliers.supplier_id','=','user_suppliers.supplier_id')
        ->leftJoin('user_companies','user_suppliers.user_id','=','user_companies.user_id')
        ->leftJoin('companies','user_companies.company_id','=','companies.id')
        ->leftJoin('suppliers','preferred_suppliers.supplier_id','=','suppliers.id')
        ->where('preferred_suppliers.user_id', Auth::user()->id)
        ->where('preferred_suppliers.deleted_at', null)
        ->orderBy('preferred_suppliers.id','DESC')
        ->get(['suppliers.name as companyName','suppliers.contact_person_email','suppliers.interested_in','preferred_suppliers.is_active','suppliers.id as preferredSuppId']);

        //get preferred suppliers data if already exist in "preferred_suppliers_rfqs" table
        $existingPreferredSuppliers = PreferredSuppliersRfq::where('user_id',Auth::user()->id)->where('rfq_id',$rfqId)->pluck('supplier_id');

        //find maximum number of product to be added
        $max_product = Settings::where('key', 'multiple_rfq_max_added_product')->first()->value;
        if (!empty($userRfq->max_products)){
            $maximum_product = intval($max_product) <= $userRfq->max_products ? $userRfq->max_products : intval($max_product);
        } else {
            $maximum_product = intval($max_product) <= 5 ? 5 : intval($max_product);
        }

        //find maximum number of attachments to be added
        $max_attachments = Settings::where('key', 'multiple_rfq_attachments')->first()->value;

        $creditDays = CreditDays::getAllActiveCreditDays();
        $emailvarify = Auth::user()->is_active;
        $rfqhtml = view('dashboard/rfq/editRfqmodal', ['rfq' => $userRfq,'category' => $category, 'subCategory' => $subCategory, 'brand' => $brand, 'grade' => $grade, 'unit' => $unit,'rfqactivities' => $activities,'user_rfqs'=>$user_rfqs,'userAddress'=>$companyAddress, 'productRfq' => $all_rfqs, 'states' => $states, 'preferredSupplierCount' => $preferredSupplierCount, 'preferredSuppliers' => $preferredSuppliers, 'existingPreferredSuppliers' => $existingPreferredSuppliers, 'chatHistory' => $chatHistory, 'max_product' => $maximum_product,'max_attachments' =>$max_attachments,'rfq_attachments' =>$all_attachments,'creditDays'=>$creditDays])->render();
        $postRfqhtml = view('dashboard/home', ['rfqs' => $userRfq,'category' => $category, 'subCategory' => $subCategory, 'brand' => $brand, 'grade' => $grade, 'units' => $unit,'rfqactivities' => $activities,'user_rfqs'=>$user_rfqs,'userAddress'=>$companyAddress, 'productRfq' => $all_rfqs, 'states' => $states, 'preferredSupplierCount' => $preferredSupplierCount, 'preferredSuppliers' => $preferredSuppliers, 'existingPreferredSuppliers' => $existingPreferredSuppliers, 'chatHistory' => $chatHistory, 'max_product' => $maximum_product,'max_attachments' =>$max_attachments,'rfq_attachments' =>$all_attachments,'emailvarify'=>$emailvarify,'creditDays'=>$creditDays])->render();
        return response()->json(array('success' => true, 'html' => $rfqhtml,'postRfqhtml'=>$postRfqhtml));
    }
    /* start code
     * place order changes by Munir
     * date:-27/05/2022
     */
    public function placeOrder(Request $request)
    {
        $inputs = (object)$request->except('_token');
        $quote = Quote::find($inputs->quoteId);
        if (!empty($quote->group_id)){//if group order
            $groupTradingController = new GroupTradingController;
            return $groupTradingController->groupPlaceOrder($request);
        }
        //Order place checks for duplicate entries
        if ($quote->status_id == 2){//Quotation Accepted means order placed
            return response()->json(array('success' => false,'message'=>__('admin.quotation_accepted_n_order_placed')));
        }
        if (isset($inputs->is_credit)&&$inputs->is_credit==ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){//if place order using loan provider credit
            $loanController = new LoanController;
            return $loanController->placeOrder($request);
        }
        return $this->setOrder($inputs);
    }

    /*
     * This function created using placeOrder
     * code changes by munir
     * Date:-27/05/2022
     * */
    public function setOrder($data,$isGroupOrder=0) {
        if (empty($isGroupOrder)) {
            $userId = Auth::user()->id;
            $companyId = Auth::user()->default_company;
        }else{//for group order
            $authUser = User::find($data->user_id);
            $userId = $data->user_id;
            $companyId = $authUser->default_company;
        }
        $quote = Quote::where('quotes.id', $data->quoteId)->first(['quotes.id as quodashboard-place-order-ajaxtes_id', 'quotes.final_amount', 'quotes.rfq_id','group_id']);
        $groupId = $quote->group_id;
        $quote_items = QuoteItem::where('quote_id', $data->quoteId)->get();
        //update Other Quote status cancelled/
        //$quoteExpire = Quote::where('rfq_id', $quote->rfq_id)->Where('status_id', '<>', 2)->update(['status_id' => 4]);

        $min_delivery_date = Date('Y-m-d', strtotime('+' . $quote_items[0]->min_delivery_days . ' days'));
        $max_delivery_date = Date('Y-m-d', strtotime('+' . $quote_items[0]->max_delivery_days . ' days'));

        $order = new Order();
        $order->user_id = $userId;
        $order->group_id = $groupId??null;
        $order->quote_id = $data->quoteId;
        $order->order_status = 1;
        $order->is_credit = $data->is_credit??0;
        $order->payment_amount = $quote->final_amount;
        $order->payment_status = 0;
        $order->min_delivery_date = $min_delivery_date;
        $order->max_delivery_date = $max_delivery_date;
        $order->otp_supplier = '';
        $order->customer_reference_id = $data->cust_ref_id??'';
        $order->po_comment = $data->comment??'';
        $order->address_name = $data->address_name;
        $order->address_line_1 = $data->addressLine1;
        $order->address_line_2 = $data->addressLine2 ? $data->addressLine2 : '';
        $order->sub_district = $data->sub_district;
        $order->district = $data->district;
        $order->rfq_id = $quote->rfq_id;
        $order->supplier_id = $quote_items[0]->supplier_id;
        $order->pincode = $data->pincode;
        $order->state = $data->stateId > 0 ? '' : $data->state;
        $order->city = $data->cityId > 0 ? '' : $data->city;
        $order->state_id = $data->stateId;
        $order->city_id = $data->cityId;
        $order->company_id = $companyId;
        $order->payment_type = $data->is_credit;
        $order->credit_days = $data->credit_days_id??null;
        $order->save();
        $order->order_number = 'BORN-' . $order->id;
        $order->save();

        //after order created quote status set as Quotation Accepted
        $Statusupdate['status_id'] = 2;
        $quoteData = Quote::where('id',$data->quoteId)->update($Statusupdate);

        $i=101;
        foreach ($quote_items as $key => $value) {
            $order_items = new OrderItem();
            $order_items->order_id = $order->id;
            $order_items->quote_item_id = $value->id;
            $order_items->order_item_status_id = null;
            $order_items->product_amount = $value->product_amount;
            $order_items->min_delivery_date = $min_delivery_date;
            $order_items->max_delivery_date = $max_delivery_date;
            $order_items->product_id = $value->product_id;
            $order_items->rfq_product_id = $value->rfq_product_id;
            $order_items->save();
            $order_items->order_item_number = 'BORN-'.$order->id.'/'.$i;
            $order_items->save();
            $i++;
        }

        if(isset($groupId) && $groupId > 0  ) {

            //Group Members Discount Code (Ronak M - 16-05-2022)
            $grpMemberId = GroupMember::where('group_id',$groupId)->where('user_id',$userId)->where('rfq_id',$quote->rfq_id)->pluck('id')->first();

            $achievedQty = Groups::where('id',$groupId)->pluck('achieved_quantity')->first();
            $orderQty = RfqProduct::where('rfq_id',$quote->rfq_id)->pluck('quantity')->first();
            $totalQty = $achievedQty + $orderQty;
            $prospectDiscount =  GroupSupplierDiscountOption::where('group_id',$groupId)->max('discount');

            //Get Avail , Achieved and prospect discount as per the order range
            if(isset($groupId) && (isset($totalQty) && $totalQty > 0)) {
                $discountData = RfqProduct::join('rfqs','rfq_products.rfq_id','=','rfqs.id')
                    ->join('group_supplier_discount_options','rfqs.group_id','=','group_supplier_discount_options.group_id')
                    ->join('groups','group_supplier_discount_options.group_id','=','groups.id')
                    ->where('rfqs.group_id',$groupId)
                    ->where('group_supplier_discount_options.min_quantity','<=',$totalQty)
                    ->where('group_supplier_discount_options.max_quantity','>=',$totalQty)
                    ->first(['group_supplier_discount_options.discount_price','group_supplier_discount_options.discount','groups.price as original_price']);
            }

            //Insert group discount data in "group_members_discounts" table
            $existDiscountData = GroupMembersDiscount::where('group_id',$groupId)->get(['id','group_id','avail_discount','achieved_discount','prospect_discount','refund_discount'])->toArray();
            $updatedDiscountArr = [];
            $updateGroupIdsArr = [];
            //If data already exist in member_discount table, then achieved qty and refund discount will get updated
            if(count($existDiscountData) > 0 ) {
                foreach($existDiscountData as $disData) {
                    $updateGroupIdsArr[] = $disData['id'];
                    if(isset($discountData) && $disData['avail_discount'] != $discountData->discount) {
                        $disData['id'] = $disData['id'];
                        $disData['achieved_discount'] = $discountData->discount;
                        $disData['refund_discount'] = $disData['achieved_discount'] - $disData['avail_discount'];
                        // $disData['refund_discount'] = $discountData->discount - $disData['achieved_discount'];
                        $disData['updated_at'] = Carbon::now()->toDateTimeString();
                    }
                    //array_push($updatedDiscountArr,$disData);
                    GroupMembersDiscount::where('id',$disData['id'])->update($disData);
                }
            }

            $groupDiscountData = new GroupMembersDiscount();
            $groupDiscountData->group_member_id = $grpMemberId;
            $groupDiscountData->user_id = $userId;
            $groupDiscountData->rfq_id = $quote->rfq_id;
            $groupDiscountData->quote_id = $data->quoteId;
            $groupDiscountData->order_id = $order->id;
            $groupDiscountData->group_id = $groupId ?? null;
            $groupDiscountData->avail_discount = $discountData->discount ?? 0;
            $groupDiscountData->achieved_discount = $discountData->discount ?? 0;
            $groupDiscountData->prospect_discount = $prospectDiscount ?? 0;
            $groupDiscountData->save();
            //End

        }

        /* Addresses Added To Existing Address List */
        $userAddress = UserAddresse::firstOrNew([
            'user_id'   => $userId,
            'address_name' => $data->address_name,
            'address_line_1' => $data->addressLine1,
            'address_line_2' => $data->addressLine2 ? $data->addressLine2 : '',
            'sub_district' => $data->sub_district,
            'district' => $data->district,
            'city' => $data->cityId > 0 ? '' : $data->city,
            'pincode' => $data->pincode,
            'state' => $data->stateId > 0 ? '' : $data->city,
            'state_id'  => $data->stateId,
            'city_id'   => $data->cityId
        ]);
        $userAddress->save();

        //Update Rfq Status Id
        $rfq_id = Quote::where('id',$data->quoteId)->pluck('rfq_id')->first();
        $rfq = Rfq::find($rfq_id);
        $rfq->status_id = 5;
        $rfq->save();


        if($order->payment_type == 1){
            if ($order->is_credit==ORDER_IS_CREDIT['CREDIT']) {//if supplier given credit
                $cdRaw = CreditDays::getActiveCreditDaysbyId($data->credit_days_id);
                $orderCreditDays = new OrderCreditDays();
                $orderCreditDays->order_id = $order->id;
                $orderCreditDays->credit_days_id = $data->credit_days_id;
                $orderCreditDays->request_days = $cdRaw->days;
                $orderCreditDays->approved_days = $cdRaw->days;
                $order->credit_days = $cdRaw->days;
                $order->save();
                $orderCreditDays->save();
            }
        }

        OrderTrack::createOrUpdateOrderTrack(['order_id'=>$order->id,'status_id'=>1,'user_type'=>User::class]);
        /*--------------------------------------------*/

        if (Auth::check() && Auth::user()->id) {
            $commanData = array('order_number' => $order->order_number, 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-truck');
            buyerNotificationInsert(Auth::user()->id, 'Order Placed', 'buyer_place_order', 'order', $order->id, $commanData);
            broadcast(new BuyerNotificationEvent());
        }
        $smsData['order_number'] = $order->order_number;
        $sendMsg = $this->verify->sendMsg(Auth::user()->firstname,Auth::user()->lastname,'order_placed',Auth::user()->phone_code,Auth::user()->mobile,$smsData);

        //Store Group Activities and update rfq quantity in "groups" table. (Ronak Makwana - 25/04/2022)
        $rfqQty = Quote::join('group_members','quotes.rfq_id','=','group_members.rfq_id')
            ->join('rfq_products','group_members.rfq_id','=','rfq_products.rfq_id')
            ->where('rfq_products.rfq_id',$quote->rfq_id)
            ->first(['group_members.group_id','rfq_products.quantity']);

        if(isset($rfqQty->group_id)){
            $groupActivity = new GroupActivity();
            $groupActivity->user_id = $userId;
            $groupActivity->group_id = $rfqQty->group_id ?? null;
            $groupActivity->key_name = 'order_placed';
            $groupActivity->old_value = '';
            $groupActivity->new_value = 'Order Placed - '. $order->order_number;
            $groupActivity->user_type = User::class;
            $groupActivity->save();
            $group = Groups::find($rfqQty->group_id);
            $group->achieved_quantity = $group->achieved_quantity + $rfqQty->quantity;
            $group->save();

            /*
             * when max order quantity achieved then group closed
             * Munir
             * Date:-1/6/2022
             * */
            if ($group->achieved_quantity==$group->max_order_quantity){
                $group->group_status = 3;
                $group->save();

                /*
                 * when max order quantity achieved then group closed and send mail
                 * ekta * Date:-1/6/2022
                 * */
                $groupData = Groups::leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
                    ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                    ->leftjoin('categories', 'groups.category_id', '=', 'categories.id')
                    ->leftjoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
                    ->leftjoin('products', 'groups.product_id', '=', 'products.id')
                    ->leftjoin('units', 'groups.unit_id', '=', 'units.id')
                    ->where('groups.id',$rfqQty->group_id)
                    ->get(['groups.id', 'groups.name', 'groups.group_number', 'groups.target_quantity','groups.achieved_quantity','groups.end_date', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'products.name as product_name','units.name as unit_name']);
                $groupRanges = GroupSupplierDiscountOption::where('group_id', $rfqQty->group_id)->where('deleted_at', null)->get()->toArray();
                $url = route('group-details', ['id' => Crypt::encrypt($rfqQty->group_id)]);
                $shareLinks = getGroupsLinks($rfqQty->group_id);
                $groupsMailData = [
                    'group' => $groupData[0],
                    'groupRanges' => $groupRanges,
                    'url' => $url,
                    'shareLinks' =>$shareLinks,
                ];
                dispatch(new GroupCloseJob($groupsMailData,$groupData[0]->supplier_email)); //mail send admin and supplier
                dispatch(new GroupCloseBuyerJob($rfqQty->group_id,$url,$shareLinks)); // mail send buyer
            }
        }
        //End

        dispatch(new SendOrderConfirmMailToBuyerJob($order));

        dispatch(new SendOrderConfirmMailToSupplierJob($order));
        //send notification
        $supplierActivity = new Notification();
        $supplierActivity->user_id = $userId;
        $supplierActivity->supplier_id = $order->supplier_id;
        $supplierActivity->user_activity = 'Place Order';
        $supplierActivity->common_data = json_encode(['new_key' => 1, 'is_credit' => $order->is_credit]);
        $supplierActivity->translation_key = 'order_place_notification';
        $supplierActivity->notification_type = 'order';
        $supplierActivity->notification_type_id = $order->id;
        $supplierActivity->save();
        $getAllAdmin = getAllAdmin();
        $sendAdminNotification = [];
        if (!empty($getAllAdmin)){
            foreach ($getAllAdmin as $key => $value){
                $sendAdminNotification[] = array('user_id' => $userId, 'admin_id' => $value, 'user_activity' => 'Place Order', 'translation_key' => 'order_place_notification', 'notification_type' => 'order', 'notification_type_id'=> $order->id, 'common_data' => json_encode(['new_key' => 1, 'is_credit' => $order->is_credit]));
            }
            Notification::insert($sendAdminNotification);
        }
    broadcast(new rfqsEvent());
        broadcast(new ordersCountEvent());
        broadcast(new BuyerOrderNotificationEvent());
        if (empty($isGroupOrder) && $order->is_credit!=ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']) {
            $ordersCount = Order::all()->where('user_id', $userId)->where('is_deleted', 0)->count();
            $request = request();
            $orders = $this->getDashboardOrders($request)->getData()->html;
            return response()->json(array('success' => true, 'ordersCount' => $ordersCount, 'html' => $orders, 'lastOrderId' => $order->id));
        }else{
            return $order->refresh();
        }
    }

    /* end code
     * place order changes by Munir
     * date:-27/05/2022
     */
    function rfqactivity($id){
        $activities = RfqActivity::where('rfq_id',$id)->where('is_deleted','0')->orderBy('id','DESC')->get();
        $rfq = Rfq::where('id',$id)->where('is_deleted','0')->first();
        $user_rfqs = UserRfq::where('rfq_id',$id)->where('is_deleted','0')->first();

        $activityhtml =  view('dashboard/rfq/rfqactivities', ['rfqactivities' => $activities,'rfq'=>$rfq,'user_rfqs'=>$user_rfqs])->render();
        return response()->json(array('success' => true, 'activityhtml' => $activityhtml));
    }

    function getPrintRfqs($rfqId){
        $rfqId = Crypt::decrypt($rfqId);
        $rfq = DB::table('rfqs')
        ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
        ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
        ->join('units', 'rfq_products.unit_id', '=', 'units.id')
        ->leftJoin('groups','rfqs.group_id','=','groups.id')
        ->where('rfqs.is_deleted', 0)
        ->where('rfqs.id', $rfqId)
        ->orderBy('rfqs.id', 'desc')
        ->first(['rfqs.id','rfqs.company_id', 'rfqs.phone_code', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.email', 'rfqs.mobile', 'rfqs.created_at', 'rfqs.reference_number', 'rfqs.is_require_credit', 'rfq_products.category','rfq_products.category_id', 'rfq_products.sub_category', 'rfq_products.product as product_name', 'rfq_products.product_description', 'rfq_status.id as status_id', 'rfq_status.name as status_name', 'rfqs.address_line_1', 'rfqs.address_line_2', 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state', 'rfqs.city_id', 'rfqs.state_id', 'rfqs.pincode', 'rfq_products.quantity', 'units.id as unit_id','units.name as unit_name', 'rfq_products.expected_date', 'rfq_products.comment', 'rfqs.rental_forklift', 'rfqs.unloading_services','rfqs.group_id','rfqs.payment_type','rfqs.credit_days']);
        $user_rfq = DB::table('user_rfqs')
        ->where('rfq_id', $rfqId)
        ->get(['user_id']);

        $comp = '';
        if($user_rfq){
            $comp  = DB::table('rfqs')
                        ->join('user_rfqs', 'user_rfqs.rfq_id', '=', 'rfqs.id')
                        ->join('companies', 'companies.id', '=', 'rfqs.company_id')
                        ->select('companies.name as company_name')
                        ->where('rfqs.id', $rfqId)
                        ->first();
        }
        $all_products = RfqProduct::where('rfq_id', $rfqId)->join('units', 'rfq_products.unit_id', '=', 'units.id')->get(['rfq_products.category','rfq_products.category_id', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.quantity', 'rfq_products.product_description', 'units.name as unit_name']);
        $pdf = PDF::loadView('pdf.print-rfq',compact('rfq','comp', 'all_products'));
        return $pdf->stream($rfq->reference_number.'.pdf');
    }
    function getPrintRfqQuote($quoteid){
        $quoteid = Crypt::decrypt($quoteid);

        $quote12= Quote::join('rfqs', function($join) {
            $join->on('rfqs.id', '=', 'quotes.rfq_id');
        })->join('rfq_products', function($join) {
        $join->on('rfqs.id', '=', 'rfq_products.rfq_id');
        })->join('rfq_status', function($join) {
        $join->on('rfqs.status_id', '=', 'rfq_status.id');
        })->where('quotes.id', $quoteid)->orderBy('quotes.id', 'desc') ->get(['quotes.id as quotes_id', 'quotes.quote_number', 'quotes.created_at', 'quotes.valid_till', 'quotes.final_amount', 'rfq_status.name as status_name', 'rfqs.reference_number as rfq_reference_number', 'rfq_products.product', 'rfq_products.product_description','quotes.status_id','rfqs.firstname', 'rfqs.lastname', 'rfqs.email', 'rfqs.mobile', 'rfqs.rental_forklift', 'rfqs.unloading_services','rfq_products.comment','rfq_products.expected_date'])->first();

        $quote = DB::table('quotes')
          ->join('rfqs', 'rfqs.id', '=', 'quotes.rfq_id')
          ->join('rfq_status', 'rfq_status.id', '=', 'rfqs.status_id')
          ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
          ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
          ->join('user_companies', 'user_rfqs.user_id', '=', 'user_companies.user_id')
          ->join('users', 'users.id', '=', 'user_rfqs.user_id')
          ->join('companies', 'rfqs.company_id', '=', 'companies.id')
          ->leftJoin('groups','quotes.group_id','=','groups.id')
          //->join('products', 'quotes.product_id', '=', 'products.id')
          //->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
          //->join('categories', 'sub_categories.category_id', '=', 'categories.id')
          ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
          ->join('units', 'quote_items.price_unit', '=', 'units.id')
          ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
          ->where('quotes.id', $quoteid)
          ->select('quotes.id as quotes_id', 'quotes.quote_number', 'quotes.status_id', 'quotes.created_at', 'quotes.valid_till', 'rfqs.is_require_credit', 'quotes.final_amount', 'rfq_status.name as status_name', 'rfqs.reference_number as rfq_reference_number', 'rfq_products.product as product_name', 'rfq_products.product_description as product_description', 'rfqs.firstname as rfq_firstname', 'rfqs.lastname as rfq_lastname', 'rfqs.pincode as rfq_pincode', 'rfq_products.sub_category as sub_category_name', 'rfq_products.category as category_name', 'units.name as unit_name', 'rfqs.mobile as rfq_mobile', 'quotes.note', 'quotes.tax', 'quotes.tax_value', 'companies.name as user_company_name', 'quotes.certificate', 'quotes.comment', 'rfq_products.expected_date', 'rfq_products.comment as rfq_comment', 'rfqs.id as rfq_id', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name', 'suppliers.logo as supplier_logo', 'suppliers.contact_person_email as supplier_email', 'suppliers.contact_person_phone as supplier_phone', 'users.phone_code as user_phone_code', 'suppliers.cp_phone_code as supplier_phone_code','quotes.group_id','quote_items.inclusive_tax_other','quote_items.inclusive_tax_logistic')->first();

        if (auth()->user()->role_id != 3){
            $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))->where('quotes_charges_with_amounts.quote_id', $quoteid)->orderBy('charge_type', 'asc')->get();
        } else {
            $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))->where('quotes_charges_with_amounts.quote_id', $quoteid)->where('quotes_charges_with_amounts.charge_type', 0)->orderBy('charge_type', 'asc')->get();
        }
        $orderCount = DB::table('orders')
            ->where('quote_id', $quoteid)
            ->count();
        if($quote12->quoteStatus){
        // if($quote->status_id == 1){
            $quote->quote_status_name = $quote12->quoteStatus->name;
        }
        if ($orderCount > 0) {
            $quote->orderPlaced = true;
        } else {
            $quote->orderPlaced = false;
        }

        // $approver = "Approver";
        // $ConfigUsersCount = CustomRoles::getUserByCustomRole(Auth::user()->default_company, $approver);

        //---- Get approval users count and permission
        $approvalUsers = collect();
        $isOwner = User::checkOwnerByCompanyId(Auth::user()->default_company);

        $approvalUsersByCompany = User::whereJsonContains('assigned_companies',Auth::user()->default_company)->get();
        foreach($approvalUsersByCompany as $cmpUser) {
            $approverPermissionId = Permission::findByName('approval buyer approval configurations')->id;       //Get permission id by permission name
            $permissions = getRolePermissionAttribute($cmpUser->id ?? null)['permissions'];                     //Get Role & permission by user id

            if (!empty($permissions)) {
                if (in_array($approverPermissionId, $permissions)) {
                    $approvalUsers->push($cmpUser);
                }
            }
        }
        //End

        if(Auth::user()->hasRole('buyer') || Auth::user()->hasRole('sub-buyer')){
            $ConfigUsersCount = $approvalUsers;

            $isAuthOwner = User::checkCompanyOwner();

            $isOwner = User::checkOwnerByCompanyId(Auth::user()->default_company);

            if ((Auth::user()->hasPermissionTo('toggle buyer approval configurations') && count($approvalUsers) != 0) || $isOwner == true) {
                $toggleSwitch['approval_process'] = 1;
            }else {
                $toggleSwitch['approval_process'] = 0;
            }
        } else {
            $toggleSwitch['approval_process'] = 0;
            $ConfigUsersCount = [];
        }
        $approversList = UserQuoteFeedback::join('users','users.id','=','user_quote_feedbacks.user_id')
        ->leftJoin('designations','designations.id','=','users.designation')
        ->where('user_quote_feedbacks.quote_id',$quoteid)
        ->where('user_quote_feedbacks.resend_mail',0)
        ->get(['users.firstname','users.lastname','designations.name','user_quote_feedbacks.feedback']);

        $quote_items = QuoteItem::where('quote_id', $quoteid)->get();
        $pdf = PDF::loadView('pdf.print-rfq-quote',compact('quote','quotes_charges_with_amounts','quote12','toggleSwitch','approversList', 'ConfigUsersCount','quote_items'));
        return $pdf->stream($quote->quote_number.'.pdf');
    }
    function getPrintOrder($orderid){
        $orderid = Crypt::decrypt($orderid);
        $order = DB::table('orders')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'orders.company_id', '=', 'companies.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->leftJoin('groups','orders.group_id','=','groups.id')
            ->where('orders.id', $orderid)
            ->where('orders.is_deleted', 0)
            ->orderBy('orders.id', 'desc')
            ->select('orders.*', 'ocd.request_days', 'ocd.approved_days', 'ocd.status as request_days_status', 'quotes.quote_number', 'rfq_products.product as product_name', 'rfq_products.product_description as product_description', 'rfq_products.sub_category as sub_category_name', 'rfq_products.category as category_name','rfq_products.category_id', 'order_status.name as order_status_name', 'companies.name as company_name', 'users.firstname', 'users.lastname','users.email as user_email', 'rfqs.mobile as rfq_mobile', 'rfq_products.quantity as product_quantity', 'units.name as product_unit', 'quotes.final_amount as product_final_amount','quotes.tax','quotes.tax_value', 'rfqs.email as rfq_email', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name', 'suppliers.logo as supplier_logo', 'suppliers.contact_person_email as supplier_email', 'suppliers.contact_person_phone as supplier_phone', 'quote_items.product_amount as product_amount', 'quote_items.product_price_per_unit', 'quote_items.product_quantity as product_quantity_n', 'suppliers.cp_phone_code as supplier_phone_code', 'users.phone_code as user_phone_code')->first();

        $orderStatus = DB::table('order_status')
                ->leftJoin('order_tracks', function ($join) use ($orderid) {
                    $join->on('order_status.id', '=', 'order_tracks.status_id')
                        ->where('order_tracks.order_id', $orderid);
                })
                ->where('order_status.is_deleted', 0)
                ->groupBy('order_status.name')
                //->orderBy('order_status.show_order_id', 'asc')
                ->get(['order_status.id as order_status_id', 'order_status.name as status_name', 'order_tracks.created_at', 'order_tracks.id as order_track_id', 'order_status.show_order_id', 'order_status.credit_sorting']);
            if ($order->is_credit) {
                $order->orderAllStatus = $orderStatus->sortBy('credit_sorting')->values()->all();
            }else{
                $order->orderAllStatus = $orderStatus->sortBy('show_order_id')->take(8)->values();
            }
            if (auth()->user()->role_id != 3){
                $quotes_charges_with_amounts = DB::table('orders')->join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')->where('orders.id', $orderid)->orderBy('quotes_charges_with_amounts.created_at', 'desc')->get();
            } else {
                $quotes_charges_with_amounts = DB::table('orders')->join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')->where('orders.id', $orderid)->where('quotes_charges_with_amounts.charge_type', 0)->orderBy('quotes_charges_with_amounts.created_at', 'desc')->get();
            }
            $order->quotes_charges_with_amounts = $quotes_charges_with_amounts;
        $bankDetail = BankDetails::getBankDetails();

        $multiple_quote = QuoteItem::where('quote_id', $order->quote_id)->join('units', 'quote_items.price_unit', '=', 'units.id')->get();
        $pdf = PDF::loadView('pdf.print-order',compact('order','multiple_quote','bankDetail'));
        return $pdf->stream($order->order_number.'.pdf');
    }

    function downloadImage(Request $request){
        if ($request->fieldName == 'order_latter'){
            $image = OrderItem::where('id', $request->id)->pluck($request->fieldName)->first();
        } else {
            $image = Order::where('id', $request->id)->pluck($request->fieldName)->first();
        }
        if (!empty($image)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $image, '', $headers);
            //return response()->download(public_path('storage/'.$image));
        }
        return response()->json(array('success' => false));
    }

    function downloadCertificate(Request $request){
        $image = Quote::where('id', $request->id)->pluck($request->fieldName)->first();
        if (!empty($image)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $image, '', $headers);
            //return response()->download(public_path('storage/'.$image));
        }
        return response()->json(array('success' => false));
    }

    function successInvoicePayment($orderId)
    {
        $transaction = getRecordsByCondition('order_transactions',['order_id'=>$orderId],'id,order_id,status,customer,paid_amount,paid_at,payment_channel',0,'id DESC');

        if ($transaction['status']=='EXPIRED'){
            $transaction = (array)DB::table('order_transactions')
                ->join('bulk_payments', 'bulk_payments.order_transaction_id','=','order_transactions.id')
                ->join('bulk_order_payments', 'bulk_order_payments.bulk_payment_id','=','bulk_payments.id')
                ->where(['bulk_order_payments.order_id'=>$orderId,'order_transactions.status'=>'PAID'])
                ->first(['order_transactions.id','order_transactions.order_id','status','customer','paid_amount','paid_at','payment_channel']);
        }

        if (empty($transaction)){
            return redirect('dashboard');
        }
        return view('dashboard/payment/payment_success', ['transaction'=>$transaction]);
    }

    function failInvoicePayment($orderId)
    {
        $transaction = getRecordsByCondition('order_transactions',['order_id'=>$orderId]);
        if (empty($transaction)){
            return redirect('dashboard');
        }
        return view('dashboard/payment/link_expired', ['transaction'=>$transaction]);
    }

    //Download rfq attachment by rfq id
    public function downloadRfqAttachmentFile(Request $request) {
        $rfqFile = RfqAttachment::where('rfq_id', $request->rfq_id)->get();
        if ($rfqFile->isNotEmpty()){
            $zip = new ZipArchive;

            $fileName = 'public/uploads/rfq_docs/'.$request->ref_no.'.zip';
            //dd(Storage::path($fileName));
            if ($zip->open(Storage::path($fileName), ZipArchive::CREATE) === TRUE) {

                $files = File::files(Storage::path('public/uploads/rfq_docs/'.$request->ref_no));
                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
                $zip->close();
            }
            ob_end_clean();
            $headers = ["Content-Type"=>"application/zip"];
            return response()->download(Storage::path($fileName),$request->ref_no.'.zip', $headers)->deleteFileAfterSend(true);
        }else{
            return response()->json(array('success' => false));
        }
    }
    //Download RFQ Single Document
    function downloadRfqAttachment(Request $request){
        $rfqId = $request->rfq_id;
        if ($request->fieldName != 'attached_document'){
            $attachment = Rfq::where('id', $rfqId)->pluck($request->fieldName)->first();
        }else{
            $attachment = RfqAttachment::where('id', $rfqId)->pluck($request->fieldName)->first();
        }
        if (!empty($attachment)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $attachment, '', $headers);
            //return response()->download(public_path('storage/'.$image));
        }
        return response()->json(array('success' => false));
    }
    /* Download Quote Product certificate attachment by Quote id*/
    public function downloadQuoteProductCertificateFile(Request $request) {
        $cerificateFile = QuoteItem::where('quote_id', $request->quote_id)->where('certificate','!=','')->get('certificate');
        if ($cerificateFile->isNotEmpty()){
            $zip = new ZipArchive;
            $fileName = 'public/uploads/'.$request->ref_no.'.zip';
            if ($zip->open(Storage::path($fileName), ZipArchive::CREATE) === TRUE) {
                foreach ($cerificateFile as $certificate) {
                    $files = Storage::path('public/'.$certificate->certificate);
                    $relativeNameInZipFile = basename($files);
                    $zip->addFile($files, $relativeNameInZipFile);
                }
                $zip->close();
            }
            ob_end_clean();
            $headers = ["Content-Type"=>"application/zip"];
            return response()->download(Storage::path($fileName),$request->ref_no.'.zip', $headers)->deleteFileAfterSend(true);
        }else{
            return response()->json(array('success' => false));
        }
    }
    //Delete rfq attachment by rfq id
    public function deleteRfqAttachmentFile(Request $request) {
        $attachment = RfqAttachment::where('id',$request->id)->first();
        $columnName = $attachment->attached_document;
        if (isset($columnName) && !empty($columnName)) {
            Storage::delete('/public/' . $columnName);
            RfqAttachment::where('id', $request->id)->delete();
        }
        $rfqAttachments = RfqAttachment::where('rfq_id',$request->rfqId)->get();
        $rfqattachId = "";
        if(count($rfqAttachments)>1){
            $rfqattach = count($rfqAttachments).' Files';
        }else{
            $rfqFileTitle = Str::substr($rfqAttachments[0]->attached_document,43);
            $extension_rfq_file = getFileExtension($rfqFileTitle);
            $rfq_file_filename = getFileName($rfqFileTitle);
            if(strlen($rfq_file_filename) > 10){
                $rfqattach = Str::substr($rfq_file_filename,0,10).'...'.$extension_rfq_file;
            } else {
                $rfqattach = $rfq_file_filename.$extension_rfq_file;
            }
            $rfqattachId = $rfqAttachments[0]->id;
        }
        $countAttachments = count($rfqAttachments);
        return response()->json(array('success' => true,'rfqAttachments'=>$rfqattach,'rfqAttachmentsId'=>$rfqattachId,'countAttachments'=>$countAttachments));
    }
    //Check customer reference id is already exist or not
    public function checkCustomerRefIdExist(Request $request) {
        $custRefIds = Order::where('customer_reference_id','!=','')->pluck('customer_reference_id')->toArray();
        if (in_array($request->custRefId, $custRefIds)) {
            return response()->json(array('ref_exist' => true));
        } else {
            return response()->json(array('ref_exist' => false));
        }
    }

    //Group Listing
    public function DashboardGroupListing() {
        $userGroups = GroupMember::leftjoin('groups','group_members.group_id','=','groups.id')
        ->leftjoin('group_images','groups.id','=','group_images.group_id')
        ->leftjoin('units','groups.unit_id','=','units.id')
        ->leftjoin('products','groups.product_id','=','products.id')
        ->leftjoin('rfqs','groups.id','=','rfqs.group_id'); // added for roles and permission

        // change query based on role and permissions
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer groups')){
            $userGroups = $userGroups->where('rfqs.company_id', $authUser->default_company);
        }else {
            $userGroups = $userGroups->where('group_members.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);
        }

        $userGroups = $userGroups->where('group_members.is_deleted', 0)
        ->orderBy('groups.id', 'desc')
        ->groupBy('groups.id')
        ->get(['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.reached_quantity', 'groups.end_date', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName','groups.group_status']);
        $returnHTML = view('group_trading/dashboard_groups', ['groups' => $userGroups])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    //Get buyers address for dashboard (29-03-22 Ronak)
    public function getDashboardGroups(Request $request) {
        if($request->ajax()) {
            $authUser = Auth::user();
            $groups = '';
            $search = $request->get('query');
            if($search != '') {
                $userGroups = Groups::with('groupMembersMultiple')
                ->leftjoin('group_members','groups.id','=','group_members.group_id')
                ->leftjoin('group_images','groups.id','=','group_images.group_id')
                ->leftjoin('categories','groups.category_id','=','categories.id')
                ->leftjoin('sub_categories','groups.subCategory_id','=','sub_categories.id')
                ->leftjoin('products','groups.product_id','=','products.id')
                ->leftjoin('units','groups.unit_id','=','units.id')
                ->leftjoin('group_tags','groups.id','=','group_tags.group_id')
                ->leftjoin('group_supplier_discount_options','groups.id','=','group_supplier_discount_options.group_id')
                ->leftjoin('rfqs','groups.id','=','rfqs.group_id') // added for roles and permission
                ->where('categories.name', 'like', '%'.$search.'%');

                $userGroups = $userGroups->where(function ($query) use($search){
                    return $query->orWhere('sub_categories.name', 'like', '%'.$search.'%')
                        ->orWhere('products.name', 'like', '%'.$search.'%')
                        ->orWhere('group_tags.tag', 'like', '%'.$search.'%');
                });

                $selectParams = ['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.achieved_quantity', 'groups.reached_quantity', 'groups.end_date', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName',DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount'),'groups.group_status'];

            } else {
                $userGroups = Groups::with('groupMembersMultiple')
                ->leftjoin('group_members','groups.id','=','group_members.group_id')
                ->leftjoin('group_images','groups.id','=','group_images.group_id')
                ->leftjoin('units','groups.unit_id','=','units.id')
                ->leftjoin('products','groups.product_id','=','products.id')
                ->leftjoin('group_supplier_discount_options','groups.id','=','group_supplier_discount_options.group_id')
                ->leftjoin('rfqs','groups.id','=','rfqs.group_id') // added for roles and permission
                ->where('group_members.is_deleted', 0);
                $selectParams = ['rfqs.id as rfq_id','rfqs.company_id as rfq_cmp_id','groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.reached_quantity', 'groups.achieved_quantity', 'groups.end_date', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName',DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount'),'groups.group_status'];
            }

            // change query based on role and permissions
            $isOwner = User::checkCompanyOwner();
            if($isOwner == true || $authUser->hasPermissionTo('list-all buyer groups')){
                $userGroups = $userGroups->where('rfqs.company_id', $authUser->default_company);
            }else {
                $userGroups = $userGroups->where('group_members.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);
            }

            $userGroups = $userGroups
                ->orderBy('groups.id', 'desc')
                ->groupBy('groups.id')
                ->get($selectParams);
        }

        $returnHTML = view('dashboard/groups/buyer_groups', ['groups' => $userGroups, 'query' => $search])->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function refreshDashboardOrderSubStatus($orderId){
        //$order = Order::find($orderId);
        $order = DB::table('orders')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quote_items.supplier_id', '=', 'suppliers.id')
            ->leftjoin('order_pos','orders.id','=','order_pos.order_id')
            ->where('orders.id', $orderId)
            ->where('orders.is_deleted', 0)
            ->orderBy('orders.id', 'desc')
            ->groupBy('orders.id')
            ->first(['orders.*', 'ocd.request_days', 'ocd.approved_days', 'ocd.status as request_days_status', 'quotes.quote_number', 'quotes.valid_till', 'rfq_products.product as product_name', 'rfq_products.product_description as product_description', 'rfq_products.sub_category as sub_category_name', 'rfq_products.category as category_name','rfq_products.category_id', 'order_status.name as order_status_name', 'companies.name as company_name', 'users.firstname', 'users.lastname', 'rfqs.mobile as rfq_mobile', 'rfq_products.quantity as product_quantity', 'units.name as product_unit', 'quotes.final_amount as product_final_amount','quotes.tax','quotes.tax_value', 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name','order_pos.po_number as po_number']);
        $quotes_charges_with_amounts = DB::table('orders')
            ->join('quotes_charges_with_amounts', 'orders.quote_id', '=', 'quotes_charges_with_amounts.quote_id')
            ->where('orders.id', $orderId)
            ->orderBy('quotes_charges_with_amounts.charge_type', 'asc')
            ->get();
        $order->quotes_charges_with_amounts = $quotes_charges_with_amounts;

        $orderItemDetail = Order::find($orderId);
        $orderItemCategory = $orderItemDetail->orderItems->first()->quoteItem->rfqProduct->category;
        $orderItemCategoryId = $orderItemDetail->orderItems->first()->quoteItem->rfqProduct->category_id;
        $orderlogisticProvided = $orderItemDetail->orderItems->first()->quoteItem;
        $returnHTML = view('dashboard/order/viewOrderSubStatusAllRefresh', ['order' => $order,'orderItemCategory'=>$orderItemCategory,'orderItemCategoryId'=>$orderItemCategoryId,'orderlogisticProvided'=>$orderlogisticProvided])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function refreshDashboardSingleOrderItemStatus($orderItemId){
        $orderItem = OrderItem::where('id',$orderItemId)->first();
        $orderAirwayBill = $orderItem->orderAirwayBillNumber;
        $orderItemCategory = $orderItem->first()->quoteItem->rfqProduct->category;
        $orderlogisticProvided = $orderItem->first()->quoteItem;
        $orderItemCategoryId = $orderItem->first()->quoteItem->rfqProduct->category_id;
        $returnHTML = view('dashboard/order/singleOrderItemStatusRefresh', ['orderItem'=>$orderItem,'orderAirwayBill'=>$orderAirwayBill,'orderItemCategory'=>$orderItemCategory,'orderlogisticProvided'=>$orderlogisticProvided,'orderItemCategoryId'=>$orderItemCategoryId])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getRepeatRfqlist(Request $request){
        $returnHTML = $this->getDashboardRfq($request)->getData()->returnRepeatRfqHTML;
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getPendingfirstInputName(Request $request){
        $userID = $request->userId;
        $companyId = $request->companyId;
        $getAllMasterField = ModuleInputField::where(['deleted_at' => null])->select('table_name',DB::raw('group_concat(columns_name) as columns_name'),'getby_columnname')->groupBy('table_name')->get();
        $nullableColum = [];
        foreach ($getAllMasterField as $key => $value){
            $id = ($value['getby_columnname'] == 'id' || $value['getby_columnname'] == 'company_id') ? $companyId : $userID;
            $result = DB::table($value['table_name'])->where($value['getby_columnname'],$id)->first();
            if($value['table_name'] == 'company_consumptions' && $result == null){
                $result['product_cat_id'] = null;
            }
            $tableNullColumns = array_filter((array)$result, function($value) {
                return $value === null || $value == '';
            });
            $tableNullColumnsKey = array_keys($tableNullColumns);

            $masterColmnsInArray = explode(',', $value['columns_name']);
            $nullColumnList = array_intersect($tableNullColumnsKey,$masterColmnsInArray);

            $nullableColum = array_merge($nullableColum, $nullColumnList);
        }
        $getNullColumnData = ModuleInputField::whereIn('columns_name',$nullableColum)->select(['field_name','field_ids','priority']);
        $focusField = $getNullColumnData->orderBy('priority','ASC')->first();
        return response()->json(array('success' => true, 'data' => $focusField));
    }

    public function getOrderQuoteDetails($orderId){
        /*$order = Order::with('rfqProduct','QuoteItem','rfq','quote','supplier','orderItems');*/
        $order = Order::with(['rfq:id,address_id,address_name,address_line_1,address_line_2,city,city_id,sub_district,district,state,state_id,country_id,pincode,country_one_id'])
            ->with(['quote:id,address_name,address_line_1,address_line_2,district,sub_district,city,city_id,provinces,state_id,country_id,pincode,country_one_id'])
            ->with(['supplier:id,name,contact_person_name,contact_person_last_name'])
            ->with(['QuoteItem','orderItems'])->where('id',$orderId)->first();

        $returnHTML = view('dashboard/order/generateOrderAirwayBillNumber', ['order'=>$order])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
    public function getRfnData($rfnItemIds){
        $all_rfqs = App\Models\RfnItems::whereIn('id',$rfnItemIds)->first();dd($all_rfqs);
        $postRfqhtml = view('dashboard/home', ['productRfq' => $all_rfqs])->render();
    }
}