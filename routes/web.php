<?php

use App\Exports\ExportBuyerNotPlaceRFQ;
use App\Exports\ExportOrderNotRespond;
use App\Exports\ExportQuoteExpire;
use App\Exports\ExportQuoteNotRespond;
use App\Exports\ExportRfqNotRespond;
use App\Http\Controllers\Buyer\Credit\CreditController;
use App\Http\Controllers\Buyer\Credit\CreditTransactionController;
use App\Http\Controllers\Buyer\Xendit\LoanTransaction\XenditLoanTransactionController;
use App\Http\Controllers\LoanApplicationsController;
use App\Http\Controllers\Buyer\Address\UserAddressesController;
use App\Http\Controllers\AdminFeedbackController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\Loan\LoanController;
use App\Http\Controllers\Buyer\Credit\LoanController as BuyerLoanController;
use App\Http\Controllers\Admin\GroupTransactionController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\API\QuincusController;
use App\Http\Controllers\BuyerNotificationController;
use App\Http\Controllers\Credit\KoinWorks\KoinWorkController;
use App\Http\Controllers\Credit\Common\LimitController;
use App\Http\Controllers\Credit\Common\CreditApplicationController;
use App\Http\Controllers\Credit\Common\AdminDisbursementController;
use App\Http\Controllers\Credit\Common\DueDateController;
use App\Http\Controllers\Admin\Backoffice\BackOfficeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\testsController;
use App\Models\BulkPayments;
use App\Models\Rfq;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\OrderController;
use App\Mail\OrderStatusUpdate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminSupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminRfqController;
use App\Http\Controllers\rfqCallController;
use App\Http\Controllers\OtherChargeController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminPaymentGroupController;
use App\Http\Controllers\AdminPaymentTermController;
use App\Http\Controllers\Payment\XenditController;
use App\Http\Controllers\AdminDepartmentController;
use App\Http\Controllers\AdminDesignationController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\DisbursementController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\GroupRFQController;
use App\Http\Controllers\AdminGroupController;
use App\Http\Controllers\AdminBuyerController;
use App\Http\Controllers\Admin\Buyer\AdminBuyerCompanyController;
use App\Http\Controllers\GroupTradingController;
use App\Http\Controllers\PreferredSuppliersController;
use App\Http\Controllers\TermsConditionsController;
use App\Models\PreferredSuppliersRfq;
use App\Models\SupplierProduct;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\Payment\PaymentController;
use App\Http\Controllers\API\Xendit\XenditPaymentInvoiceController;
use App\Http\Controllers\Buyer\Approvals\BuyerApprovalController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\Buyer\Rfqs\RfqsController;
use App\Http\Controllers\Buyer\Orders\OrdersLivewireController;
use App\Http\Controllers\SupplierProductController;
use App\Http\Controllers\Admin\Supplier\SupplierController AS AdminSuppliersController;
use App\Models\Company;
use App\Models\CountryOne;
use App\Models\CustomRoles;
use App\Models\Order;
use App\Models\Quote;
use App\Models\SystemActivity;
use App\Models\SystemRole;
use App\Models\User;
use App\Models\UserCompanies;
use App\Models\UserRfq;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return 'clear ok';
});
Route::get('/check-phpi', function () {
    echo phpinfo();
});
Route::get('check-mongo-connection', [ChatController::class, 'checkConnection']);
Route::get('calendly/register', [HomeController::class, 'calendlyWebhookRegister']);
Route::get('calendly_response', [HomeController::class, 'calendlyWebhook']);
Route::get('lang/{locale}', [HomeController::class, 'lang']);
//Route::get('/', function () {
//    return view('home/home');
//    // return view('comigSoon');
//});

Route::get('/home', function () {
    return view('home/home2', ['calendlyMeetingLink' => env('CALENDLY_MEETING_LINK')]);
    // return view('comigSoon');
})->name('latest-home');

Route::get('/terms-and-condition', function () {
    return view('home/tearms_and_condition');
    // return view('comigSoon');
})->name('terms-and-condition');

Route::get('/faq', function(){
    return view('home.faq');
})->name('faq');

Route::get('/contact-mail-test', function () {
    return view('contact-mail-test');
});

Route::get('/buyers', function () {
     return view('home/buyers', ['calendlyMeetingLink' => env('CALENDLY_MEETING_LINK')]);
})->name('buyers');

Route::get('/suppliers', function () {
    return view('home/suppliers', ['calendlyMeetingLink' => env('CALENDLY_MEETING_LINK')]);
})->name('suppliers');

Route::get('/contact-us', function () {
    return view('home/contact_us', ['googleCaptcha' => env('NOCAPTCHA_SITEKEY')]);
})->name('contact-us');
Route::middleware('auth.ajax')->group(function(){
    Route::post('/contact-us', [UserController::class, 'addContactUs'])->name('contact-us-ajax');
});
Route::get('/about-us', function () {
    return view('home/about_us');
})->name('about-us');
Route::get('/test-notification', function () {
    return view('welcome');
});
Route::get('/holiday-landing', function () {
    return view('home/holiday_landing');
})->name('holiday-landing');
Route::get('mail-test',function (){
    Mail::raw('Sending emails with smtp and Laravel ', function($message)
    {
        $message->subject('Mail Testing');
        $message->from('no-reply@website_name.com', 'Website Name');
        $message->to('munir@yopmail.com');
        $message->bcc('munir2@yopmail.com');
    });
});


Route::get('redis-flush/{id}',function ($id){
    \Illuminate\Support\Facades\Redis::del('data'.$id);
});


Route::get('code-test',function (){
    testHO();
});

Route::get('koinwork/test', [KoinWorkController::class, 'getLimit']);

//social login
Route::get('signin/{social}', [UserController::class, 'socialLogin'])->where('social','twitter|facebook|linkedin|google|github|bitbucket');
Route::get('signin/{social}/callback',[UserController::class, 'handleProviderCallback'])->where('social','twitter|facebook|linkedin|google|github|bitbucket');

Route::get('xendit/create-invoice', [XenditController::class, 'createInvoice']);
Route::get('xendit/get-invoice', [XenditController::class, 'getInvoice']);
Route::get('xendit/get-all-invoice', [XenditController::class, 'getAllInvoice']);
Route::get('xendit/getAvailableBanks', [XenditController::class, 'getAvailableBanks']);
Route::get('/rfq-print/{id}', [DashboardController::class, 'getPrintRfqs'])->name('dashboard-get-rfq-print');
Route::get('/quote-print/{id}', [DashboardController::class, 'getPrintRfqQuote'])->name('dashboard-get-rfq-quote-print');
Route::get('/order-print/{id}', [DashboardController::class, 'getPrintOrder'])->name('dashboard-print-order');

//Export Routes
Route::get('/admin/rfq-excel-export', [AdminRfqController::class, 'rfqExportExxcel'])->name('export-excel-rfq-ajax');
Route::get('/admin/rfq-quotes-excel-export', [AdminRfqController::class, 'rfqquotesExportExxcel'])->name('export-excel-rfq-quotes-ajax');
Route::get('/admin/order-excel-export', [OrderController::class, 'orderExportExxcel'])->name('export-excel-ajax');
//vrutika
Route::post('/admin/supplier-excel-export', [AdminSupplierController::class, 'supplierExportExxcel'])->name('export-excel-supplier-ajax');

Route::get('/admin/changemobile',[AdminDashboardController::class, 'changemobile'])->name('changemobile');
Route::get('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin-logout');
Route::post('/admin/checkmobileexist', [AdminLoginController::class, 'checkMobileExist'])->name('check-mobile-exist');
Route::post('/admin/getotphtml', [UserController::class, 'getOtpModel'])->name('getotphtml');
Route::post('/admin/verifyotp', [UserController::class, 'verifyOtp'])->name('verifyotp');
Route::post('/admin/mobilevarify', [UserController::class, 'mobilevarify'])->name('mobilevarify');
Route::get('/admin/emails', [UserController::class, 'getEmails'])->name('getemails');
Route::get('/emails', [UserController::class, 'getEmails'])->name('getbuyeremails');

// Admin Routes
Route::group(['middleware' => ['auth', 'is_admin']], function () {
    Route::get('/admin/translations/view', [TranslationController::class, 'view'])->name('translations-view');
    // define your route, route groups here
    //  Route::view('/admin/dashboard', '/admin/dashboard')->name('admin-dashboard');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin-dashboard');
    Route::get('/admin/change-password', [AdminDashboardController::class, 'changePasswordView'])->name('admin-change-password');
    Route::post('/admin/admin-change-password-update', [AdminDashboardController::class, 'updatePassword'])->name('admin-change-password-update');
    //supplier Module
    //Route::get('/admin/supplier', [AdminSupplierController::class, 'list'])->name('supplier-list');
    // Route::view('/admin/supplier-add', 'admin/supplier/supplierAdd')->name('supplier-add');
    Route::get('/admin/get-company-members', [AdminSupplierController::class, 'getCompanyMembers'])->name('get-company-members');
    Route::get('/admin/supplier-add', [AdminSupplierController::class, 'viewSupplierAdd'])->name('supplier-add');
    Route::post('/admin/check-xen-email-exist', [AdminSupplierController::class, 'isEmailExist'])->name('check-xen-email-exist');
    Route::post('/admin/check-user-email-exist', [AdminSupplierController::class, 'isUserEmailExist'])->name('check-user-email-exist');
    Route::get('/admin/supplier-edit/{id}', [AdminSupplierController::class, 'edit'])->name('supplier-edit');
    Route::post('/admin/supplier-update', [AdminSupplierController::class, 'update'])->name('supplier-update');
    Route::post('/admin/supplier-file-delete', [AdminSupplierController::class, 'fileDelete'])->name('supplier-file-delete-ajax');
    Route::post('/admin/supplier-charges-create-ajax', [AdminSupplierController::class, 'chargesCreateAjax'])->name('supplier-charges-create-ajax');
    Route::post('/admin/supplier-get-all-category', [AdminSupplierController::class, 'getAllCategory'])->name('supplier-get-all-category-ajax');
    Route::post('/admin/supplier-product-check', [AdminSupplierController::class, 'supplierProductCheck'])->name('supplier-product-check-ajax');
    //Route::post('/admin/supplier-status-change', [AdminSupplierController::class, 'changeStatus'])->name('supplier-status-change-ajax');
    Route::post('/admin/supplier-file-download', [AdminSupplierController::class, 'downloadSupplierImageAdmin'])->name('supplier-download-image-ajax');
    Route::post('/admin/supplier-product-image-download', [ProductController::class, 'downloadSupplierProductImageAdmin'])->name('supplier-download-product-image-ajax');
    Route::post('/admin/supplier-product-image-delete', [ProductController::class, 'productImageDelete'])->name('supplier-product-image-delete-ajax');
    Route::post('/admin/supplier-bank-delete', [AdminSupplierController::class, 'bankDelete'])->name('supplier-bank-delete');
    Route::get('/get-supplier-bank/{id}', [AdminSupplierController::class, 'getSupplierBank'])->name('get-supplier-bank');
    Route::post('/admin/save-supplier-bank', [AdminSupplierController::class, 'saveSupplierBank'])->name('save-supplier-bank');
    Route::post('/admin/supplier-bank-status-update', [AdminSupplierController::class, 'supplierBankStatusUpdate'])->name('supplier-bank-status-update');
    Route::get('/admin/supplier-xenaccount-exist/{id}', [AdminSupplierController::class, 'isSupplierXenAccountExist'])->name('supplier-xenaccount-exist');
    Route::get('/admin/get-xen-balance/{id}', [AdminSupplierController::class, 'getXenBalance'])->name('xen-balance');
    Route::get('/admin/get-loan-balance/{id}', [AdminSupplierController::class, 'getLoanBalance'])->name('loan-balance');
    Route::get('/admin/get-xenaccount-details/{id}', [AdminSupplierController::class, 'getXenAccount'])->name('xen-account-details');
    Route::get('/admin/get-supplier-details/{id}', [AdminSupplierController::class, 'getSupplierDetails'])->name('supplier-details');
    Route::post('/admin/create-supplier-xenaccount', [AdminSupplierController::class, 'createSupplierXenAccount'])->name('create-xen-account');
    Route::post('/admin/update-supplier-xenaccount', [AdminSupplierController::class, 'updateSupplierXenAccount'])->name('update-xen-account');
    Route::get('/admin/invite-as-buyer/{id}', [AdminSupplierController::class, 'inviteAsBuyer'])->name('invite-as-buyer');
    Route::get('/admin/get-supplier-category/{supplierId}', [AdminSupplierController::class, 'gatSupplierWiseCategoryList'])->name('get-supplier-category-ajax');
    //supplier Profile
    Route::get('/admin/supplier-profile', [AdminSupplierController::class, 'edit'])->name('supplier-profile');

    //Order Pickup Route
    Route::post('/admin/order-pickup-datetime', [OrderController::class, 'orderPickupDateTime'])->name('order-pickup-datetime-ajax');
    Route::post('/admin/order-pickup-batch', [OrderController::class, 'orderPickupBatch'])->name('order-pickup-batch-ajax');


    //Brands Module

    Route::get('/admin/brands', [BrandController::class, 'list'])->name('brands-list');
    Route::view('/admin/brand-add', 'admin/brandAdd')->name('brand-add');
    Route::post('/admin/brand-create', [BrandController::class, 'create'])->name('brand-create');
    Route::get('/admin/brand-edit/{id}', [BrandController::class, 'edit'])->name('brand-edit');
    Route::post('/admin/brand-update', [BrandController::class, 'update'])->name('brand-update');
    Route::post('/admin/brand-delete', [BrandController::class, 'delete'])->name('brand-delete');

    //Grades Module

    Route::get('/admin/grades', [GradesController::class, 'list'])->name('grades-list');
    Route::view('/admin/grade-add', 'admin/gradeAdd')->name('grade-add');
    Route::post('/admin/grade-create', [GradesController::class, 'create'])->name('grade-create');
    Route::get('/admin/grade-edit/{id}', [GradesController::class, 'edit'])->name('grade-edit');
    Route::post('/admin/grade-update', [GradesController::class, 'update'])->name('grade-update');
    Route::post('/admin/grade-delete', [GradesController::class, 'delete'])->name('grade-delete');

    //Units Module

    Route::get('/admin/units', [UnitsController::class, 'list'])->name('units-list');
    Route::view('/admin/unit-add', 'admin/unitAdd')->name('unit-add');
    Route::post('/admin/unit-create', [UnitsController::class, 'create'])->name('unit-create');
    Route::get('/admin/unit-edit/{id}', [UnitsController::class, 'edit'])->name('unit-edit');
    Route::post('/admin/unit-update', [UnitsController::class, 'update'])->name('unit-update');
    Route::post('/admin/unit-delete', [UnitsController::class, 'delete'])->name('unit-delete');

    //Categories Module

    Route::get('/admin/categories', [CategoryController::class, 'list'])->name('categories-list');
    Route::get('/admin/category-add', [CategoryController::class, 'categoryAdd'])->name('category-add');
    Route::post('/admin/category-create', [CategoryController::class, 'create'])->name('category-create');
    Route::get('/admin/category-edit/{id}', [CategoryController::class, 'edit'])->name('category-edit');
    Route::post('/admin/category-update', [CategoryController::class, 'update'])->name('category-update');
    Route::post('/admin/category-delete', [CategoryController::class, 'delete'])->name('category-delete');

    //Sub Categories Module

    Route::get('/admin/sub-categories', [SubCategoryController::class, 'list'])->name('sub-categories-list');
    Route::get('/admin/sub-category-add', [SubCategoryController::class, 'subCategoryAdd'])->name('sub-category-add');
    Route::post('/admin/sub-category-create', [SubCategoryController::class, 'create'])->name('sub-category-create');
    Route::get('/admin/sub-category-edit/{id}', [SubCategoryController::class, 'edit'])->name('sub-category-edit');
    Route::post('/admin/sub-category-update', [SubCategoryController::class, 'update'])->name('sub-category-update');
    Route::post('/admin/sub-category-delete', [SubCategoryController::class, 'delete'])->name('sub-category-delete');

    //Product Module


    Route::get('/admin/products', [ProductController::class, 'list'])->name('products-list');
    Route::get('/admin/productList', [SupplierProductController::class, 'list'])->name('supplier-porduct-list');

    Route::get('/admin/product-add', [ProductController::class, 'productAdd'])->name('product-add');
    Route::post('/admin/product-create', [ProductController::class, 'create'])->name('product-create');
    Route::get('/admin/product-edit/{id}', [ProductController::class, 'edit'])->name('product-edit');
    Route::post('/admin/product-update', [ProductController::class, 'update'])->name('product-update');
    Route::post('/admin/product-delete', [ProductController::class, 'delete'])->name('product-delete');

    //Supplier Product Routes
    Route::get('/admin/add-product', [AdminSupplierController::class, 'addSupplierProduct'])->name('add-supplier-product');
    Route::get('/admin/edit-product/{id}', [AdminSupplierController::class, 'editSupplierProduct'])->name('edit-supplier-product');
    Route::post('/admin/update-supplier-product', [AdminSupplierController::class, 'updateSupplierProduct'])->name('update-supplier-product-data');

    //Charges Module

    Route::get('/admin/charges', [OtherChargeController::class, 'list'])->name('charges-list');
    Route::view('/admin/charge-add', 'admin/chargesAdd')->name('charge-add');
    Route::post('/admin/charge-create', [OtherChargeController::class, 'create'])->name('charge-create');
    Route::get('/admin/charge-edit/{id}', [OtherChargeController::class, 'edit'])->name('charge-edit');
    Route::post('/admin/charge-update', [OtherChargeController::class, 'update'])->name('charge-update');
    Route::post('/admin/charge-delete', [OtherChargeController::class, 'delete'])->name('charge-delete');

    //Rfq Module
    Route::get('/admin/rfq', [AdminRfqController::class, 'list'])->name('rfq-list');
	Route::get('/admin/rfq/ajax', [AdminRfqController::class, 'listAjax'])->name('rfq-list-ajax');
    Route::post('/admin/rfq/product-search', [AdminRfqController::class, 'productSearch'])->name('admin-rfq-product-search');
    Route::get('/getSub/{id}', [AdminRfqController::class, 'getSub'])->name('get-sub-cat');
    Route::get('/admin/rfq-edit/{id}', [AdminRfqController::class, 'edit'])->name('rfq-edit');
    Route::post('/admin/rfq-update', [AdminRfqController::class, 'update'])->name('rfq-update');
    Route::get('/admin/rfq-detail/{id}', [AdminRfqController::class, 'rfqDetail'])->name('rfq-detail');
    Route::get('/admin/rfq-reply/{id}', [AdminRfqController::class, 'rfqReply'])->name('rfq-reply');
    Route::get('/admin/get-rfq-product-reply/{supplier_id}/{rfq_id}/{rfq_product}', [AdminRfqController::class, 'getrfqProductReply'])->name('get-rfq-product-reply');
    Route::get('/admin/rfq-activity/{id}', [AdminRfqController::class, 'rfqactivity'])->name('admin-get-rfq-activity-ajax');
    Route::POST('/admin/search-product', [AdminRfqController::class, 'searchProduct'])->name('admin-search-product-ajax');
    Route::post('/admin/download-rfq-attachment', [AdminRfqController::class, 'downloadRfqAttachment'])->name('download-rfq-attachment');

    Route::post('admin/rfq-attachment-document', [AdminRfqController::class, 'downloadRfqAttachmentFile'])->name('rfq-attachment-document-ajax');
    Route::post('/admin/rfq-attachment-delete', [AdminRfqController::class, 'deleteRfqAttachment'])->name('rfq-document-delete-ajax');
    //Vrutika for cancel rfq
    Route::post('admin/rfq-cancel', [AdminRfqController::class, 'cancelRfq'])->name('rfq-cancel-ajax');

    //Group RFQ Module
    Route::get('/admin/group-rfq', [GroupRFQController::class, 'groupRFQlist'])->name('group-rfq-list');

    //Rfq Call comments
    Route::post('/admin/rfq-call-comment', [rfqCallController::class, 'create'])->name('rfq-call-comment');

    //@vrutika terms-conditions
    Route::get('/admin/terms-conditions', [TermsConditionsController::class, 'index'])->name('terms-conditions');
    Route::post('/admin/tcdoc-update', [TermsConditionsController::class, 'updateDefaultFiles'])->name('tcdoc-update');
    Route::post('/admin/tc-document-delete', [TermsConditionsController::class, 'deleteDefaultTCFile'])->name('tc-document-delete-ajax');

    //@vrutika check-price(quincus)
    Route::get('/admin/check-price', [QuincusController::class, 'index'])->name('check-price');
    Route::post('/admin/send-price-data', [QuincusController::class, 'checkPrice'])->name('send-price-data');
    //@vrutika shipping-label(quincus)
    Route::get('/admin/shipping-label', [QuincusController::class, 'showShippingLabel'])->name('shipping-label');
    Route::get('/admin/get-shipping-label/{id}', [QuincusController::class, 'getShippingLabel'])->name('get-shipping-label');
    Route::get('/admin/delete-shipping-label/{id}', [QuincusController::class, 'deleteShippingLabel'])->name('delete-shipping-label');
    //Order Module
    Route::get('/admin/order', [OrderController::class, 'index'])->name('order-list');
    Route::get('/admin/order/ajax', [OrderController::class, 'listAjax'])->name('order-list-ajax');
    Route::get('/admin/order-edit/{id}', [OrderController::class, 'edit'])->name('order-edit');
    Route::get('/admin/download-airwaybill/{id}', [OrderController::class, 'downloadAirwayBill'])->name('order-edit-download-airway-bill');
    Route::post('/admin/uploadorderdoc', [OrderController::class, 'uploadOrderDoc'])->name('upload-order-doc-ajx');
    Route::post('/admin/order-status-change', [OrderController::class, 'orderStatusChange'])->name('order-status-change-ajax');
    Route::post('/admin/credit-order-status-change', [OrderController::class, 'creditOrderStatusChange'])->name('credit-order-status-change-ajax');
    Route::post('/admin/profile-company-file-delete', [ProfileController::class, 'companyFileDelete'])->name('profile-company-file-delete-ajax-admin');
    Route::post('/admin/order-item-status-change', [OrderController::class, 'orderItemStatusChange'])->name('order-item-status-change-ajax');
    Route::post('/admin/get-order-item-status', [OrderController::class, 'getOrderItemStatusDetails'])->name('order-item-status-ajax');
    Route::get('/admin/manage-order-items-separately/{id}', [OrderController::class, 'manageOrderItemsSeparately'])->where('id', '[0-9]+')->name('manage-order-items-separately-ajax');
    Route::get('/admin/manage-batch-items-separately', [OrderController::class, 'manageBatchItemsSeparately'])->name('manage-batch-items-separately-ajax'); /* Vrutika Rana (05-09-2022) */
    Route::get('/admin/manage-order-delivery-separately/{id}', [OrderController::class, 'manageOrderDeliverySeparately'])->name('manage-order-delivery-separately-ajax');
    Route::post('/admin/order-items-details', [OrderController::class, 'orderItemDetails'])->name('order-items-details-ajax');

    Route::post('/admin/order-update', [OrderController::class, 'update'])->name('order-update');
    Route::post('/admin/order-latter-upload', [OrderController::class, 'orderLatterUpload'])->name('order-latter-upload');
    Route::get('admin/get-order-details/{id}', [OrderController::class, 'getOrderDetails'])->name('get-order-details-ajax');
    Route::get('admin/get-single-order-detail/{id}', [OrderController::class, 'getSingleOrderDetail'])->name('get-single-order-detail-ajax');
    Route::post('admin/send-po-to-supplier', [OrderController::class, 'sendPoToSupplier'])->name('send-po-to-supplier-ajax');
    Route::get('admin/get-order-status-details/{id}', [OrderController::class, 'getOrderStatusDetails'])->name('get-order-status-details-ajax');
    Route::get('admin/download-po-pdf/{id}', [OrderController::class, 'downloadPoPdf'])->name('download-po-pdf');
    Route::get('admin/download-buyer-po-pdf/{id}', [OrderController::class, 'downloadBuyerPoPdf'])->name('download-buyer-po-pdf');
    Route::get('admin/download-blitznet-invoice-pdf/{id}', [OrderController::class, 'downloadblitznetInvoicePdf'])->name('download-blitznet-invoice-pdf');
    Route::post('/admin/order-file-delete', [OrderController::class, 'fileDelete'])->name('order-file-delete-ajax');
    Route::post('/admin/delete-order-latter', [OrderController::class, 'deleteOrderLatter'])->name('delete-order-latter-ajax');
    Route::get('/admin/order-activity/{id}', [OrderController::class, 'orderactivity'])->name('admin-get-order-activity-ajax');
    Route::post('/admin/order-file-download', [OrderController::class, 'downloadImageAdmin'])->name('download-image-ajax');
    Route::post('/admin/download-order-latter', [OrderController::class, 'downloadOrderLatter'])->name('download-order-latter-ajax');

    //Transactions Group Module
    Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('transactions-list');
    Route::get('/admin/cancel-invoice/{id}', [TransactionController::class, 'cancelInvoice'])->name('cancel-invoice');
    Route::get('/generate-pay-link-track-status/{orderId}', [TransactionController::class, 'generatePayLinkFromTrackStatus'])->name('generate-pay-link-track-status');

    //Group Transactions Group Module
    Route::get('/admin/group-transactions', [GroupTransactionController::class, 'index'])->name('group-transactions-list');
    //Available Banks Group Module
    Route::get('/admin/banks', [BankController::class, 'index'])->name('banks-list');
    Route::get('/admin/banks-sync', [BankController::class, 'sync'])->name('bank-sync');
    Route::get('/admin/bank-edit/{id}', [BankController::class, 'edit'])->name('bank-edit');
    Route::post('/admin/bank-logo-upload', [BankController::class, 'uploadLogo'])->name('bank-logo-upload');

    //Disbursements Group Module
    Route::get('/admin/disbursements', [DisbursementController::class, 'index'])->name('disbursements-list');
    Route::post('/admin/settlement', [DisbursementController::class, 'settlement'])->name('settlement');
    Route::post('/admin/group-settlement', [DisbursementController::class, 'groupSupplierSettlement'])->name('group-settlement');
    Route::post('/admin/buyer-refund', [DisbursementController::class, 'buyerRefund'])->name('buyer-refund');
    Route::get('/admin/get-order-charges/{id}', [DisbursementController::class, 'getOrderChargesDetails'])->name('order-charges');
    Route::post('/admin/get-group-order-charges', [DisbursementController::class, 'getGroupOrderChargesDetails'])->name('group-order-charges');

    Route::get('/admin/supplier-charge-list', [AdminSupplierController::class, 'supplierTransactionList'])->name('supplier-charge-list');
    //Payment Group Module

    Route::get('/admin/payment-groups', [AdminPaymentGroupController::class, 'list'])->name('payment-group-list');
    Route::get('/admin/payment-group-add', [AdminPaymentGroupController::class, 'paymentGroupAdd'])->name('payment-group-add');
    Route::post('/admin/payment-group-create', [AdminPaymentGroupController::class, 'create'])->name('payment-group-create');
    Route::get('/admin/payment-group-edit/{id}', [AdminPaymentGroupController::class, 'edit'])->name('payment-group-edit');
    Route::post('/admin/payment-group-update', [AdminPaymentGroupController::class, 'update'])->name('payment-group-update');
    Route::post('/admin/payment-group-delete', [AdminPaymentGroupController::class, 'delete'])->name('payment-group-delete');

    //Payment Term Module

    Route::get('/admin/payment-terms', [AdminPaymentTermController::class, 'list'])->name('payment-term-list');
    Route::get('/admin/payment-term-add', [AdminPaymentTermController::class, 'paymentTermAdd'])->name('payment-term-add');
    Route::post('/admin/payment-term-create', [AdminPaymentTermController::class, 'create'])->name('payment-term-create');
    Route::get('/admin/payment-term-edit/{id}', [AdminPaymentTermController::class, 'edit'])->name('payment-term-edit');
    Route::post('/admin/payment-term-update', [AdminPaymentTermController::class, 'update'])->name('payment-term-update');
    Route::post('/admin/payment-term-delete', [AdminPaymentTermController::class, 'delete'])->name('payment-term-delete');

    // Department Module

    Route::get('/admin/departments', [AdminDepartmentController::class, 'list'])->name('department-list');
    Route::get('/admin/department-add', [AdminDepartmentController::class, 'departmentAdd'])->name('department-add');
    Route::post('/admin/department-create', [AdminDepartmentController::class, 'create'])->name('department-create');
    Route::get('/admin/department-edit/{id}', [AdminDepartmentController::class, 'edit'])->name('department-edit');
    Route::post('/admin/department-update', [AdminDepartmentController::class, 'update'])->name('department-update');
    Route::post('/admin/department-delete', [AdminDepartmentController::class, 'delete'])->name('department-delete');

    //Designation Module

    Route::get('/admin/designations', [AdminDesignationController::class, 'list'])->name('designation-list');
    Route::get('/admin/designation-add', [AdminDesignationController::class, 'designationAdd'])->name('designation-add');
    Route::post('/admin/designation-create', [AdminDesignationController::class, 'create'])->name('designation-create');
    Route::get('/admin/designation-edit/{id}', [AdminDesignationController::class, 'edit'])->name('designation-edit');
    Route::post('/admin/designation-update', [AdminDesignationController::class, 'update'])->name('designation-update');
    Route::post('/admin/designation-delete', [AdminDesignationController::class, 'delete'])->name('designation-delete');

    //Quote Module
    Route::post('/admin/quote-create', [QuoteController::class, 'create'])->name('quote-create');
    Route::get('/admin/quotes', [QuoteController::class, 'list'])->name('quotes-list');
    Route::get('/admin/quotes/ajax', [QuoteController::class, 'listAjax'])->name('quote-list-ajax');

    Route::get('/admin/quotes-edit/{id}', [QuoteController::class, 'edit'])->name('quotes-edit');
    Route::post('/admin/quotes-update', [QuoteController::class, 'update'])->name('quotes-update');
    Route::get('/admin/quote-detail/{id}', [QuoteController::class, 'quoteDetail'])->name('quote-detail');
    Route::post('/admin/quote-downloadCertificate', [QuoteController::class, 'downloadCertificate'])->name('quote-download-certificate-ajax');
    Route::get('/admin/quote-activity/{id}', [QuoteController::class, 'quoteActivity'])->name('admin-get-quote-activity-ajax');
    Route::post('/admin/quote-removeCertificate', [QuoteController::class, 'removeCertificate'])->name('quote-certificate-file-delete-ajax');
    Route::post('/admin/quote-product-certificate-zip-download-ajax', [DashboardController::class, 'downloadQuoteProductCertificateFile'])->name('quote-product-certificate-zip-download-ajax');
    Route::post('/admin/quote-removeTermsConditionFile', [QuoteController::class, 'removeTermsConditionFile'])->name('quote-tc-file-delete-ajax');
    //User Module
    Route::get('/admin/users', [AdminUserController::class, 'list'])->name('user-list');
    Route::get('/admin/subscriber-users', [AdminUserController::class, 'subscriberUsersList'])->name('subscriber-list');


    //newsletter Module
    Route::get('/admin/newsletter-list', [NewsletterController::class, 'list'])->name('newsletter-list');
    Route::get('/admin/newsletter-edit/{id}', [NewsletterController::class, 'edit'])->name('newsletter-edit');
    Route::post('/admin/newsletter-update', [NewsletterController::class, 'update'])->name('newsletter-update');

    //reset password
    Route::get('/admin/reset-password', [AdminDashboardController::class, 'resetPasswordView'])->name('reset-password');
    Route::post('/admin/profile-update', [AdminDashboardController::class, 'adminProfileUpdate'])->name('admin-profile-update');

    //Groups Routes
    Route::get('/admin/groups-list', [AdminGroupController::class, 'list'])->name('groups-list');
    Route::get('/admin/group-detail/{id}', [AdminGroupController::class, 'getGroupDetails'])->name('group-detail');
    Route::get('/admin/group-add', [AdminGroupController::class, 'groupAdd'])->name('group-add');
    Route::post('/admin/group-create', [AdminGroupController::class, 'create'])->name('group-create');
    Route::get('/admin/group-edit/{id}', [AdminGroupController::class, 'groupEdit'])->name('group-edit');
    Route::post('/admin/group-update', [AdminGroupController::class, 'update'])->name('group-update');
    Route::post('/admin/group-addtime-update', [AdminGroupController::class, 'add_update'])->name('group-addtime-update');
    Route::post('/admin/group-update-images', [AdminGroupController::class, 'groupImagesUpdate'])->name('group-update-images');
    Route::post('/admin/group-images-delete', [AdminGroupController::class, 'groupImagesDelete'])->name('group-images-delete');
    Route::get('/admin/get-groups-images-ajax/{groupId}', [AdminGroupController::class, 'getGroupsImages'])->name('get-groups-images-ajax');
    Route::get('/admin/group-detail/{id}', [AdminGroupController::class, 'getGroupDetails'])->name('group-detail');
    Route::post('/admin/group-delete', [AdminGroupController::class, 'delete'])->name('group-delete');
    Route::post('/admin/group-member-delete', [AdminGroupController::class, 'deleteGroupMember'])->name('group-member-delete');
    Route::get('/admin/group-activity/{id}', [AdminGroupController::class, 'groupActivity'])->name('admin-get-group-activity-ajax');
    Route::get('/admin/get-supplier-product-range/{productId}/{supplierId}', [AdminGroupController::class, 'supplierProductRangeSetinGroup'])->name('get-supplier-product-range-ajax');
    Route::post('/admin/checkGroupName', [AdminGroupController::class, 'checkGroupNameExist'])->name('check-groupname-exist-ajax');
    Route::get('admin/get-buyer-refund-detail/{id}', [AdminGroupController::class, 'getBuyerRefundDetail'])->name('get-buyer-refund-detail-ajax');
    // Contact Routes
    Route::get('/admin/contact', [AdminDashboardController::class, 'contactList'])->name('contact');
    Route::post('/admin/block-contact', [AdminDashboardController::class, 'blockContact'])->name('block-contact-ajax');
    Route::post('/admin/unblock-contact', [AdminDashboardController::class, 'unblockContact'])->name('unblock-contact-ajax');

    // Admin/Supplier Notification
    Route::get('/admin/notifications-list', [NotificationController::class, 'list'])->name('notifications-list');
    Route::get('/admin/notifications-show', [NotificationController::class, 'getNotificationCount'])->name('get-notification-count-ajax');
    Route::get('/admin/notificationsSeprate', [NotificationController::class, 'getSeprateNotification'])->name('get-seprate-notification-ajax');
    Route::get('/admin/notifications-remove-count', [NotificationController::class, 'removeNotificationCount'])->name('remove-notification-count-ajax');
    Route::get('/admin/get-side-count/{count}', [NotificationController::class, 'getSideCount'])->name('get-side-count-ajax');
    Route::post('/admin/notifications-filter-data', [NotificationController::class, 'getNotificationFilterData'])->name('get-notification-filter-data-ajax');
    Route::get('/admin/notificationsAirwaybill', [NotificationController::class, 'getAirwayNotification'])->name('get-airway-notification-ajax');


    //Limit Routes
    Route::get('/admin/limits', [LimitController::class, 'index'])->name('limits-index');
    Route::post('/admin/limit-resent-application', [LimitController::class, 'resentCreditApplication'])->name('limit-resent-application-ajax');
    Route::get('/admin/limit-edit/{id}', [LimitController::class, 'edit'])->name('limit-edit');
    Route::get('/admin/limit-application-detail-view/{id}', [LimitController::class, 'limitApplicationDetailsView'])->name('limit-application-detail-view');
    Route::post('/admin/limit-update', [LimitController::class, 'update'])->name('limit-update');
    //Loan Routes
    Route::get('/admin/loans', [LoanController::class, 'index'])->name('loans-index');
    Route::get('/admin/loan-view/{id}', [LoanController::class, 'view'])->name('loan-view');
    Route::get('/admin/loan-edit/{id}', [LoanController::class, 'edit'])->name('loan-edit');
    Route::get('/admin/loan-repay/{loanApplyId}', [LoanController::class, 'checkRepayAmout'])->name('check-loan-repay-calculation-ajax');

    /// Admin Chat route
    Route::get('/admin/chat-view', [AdminChatController::class, 'adminChatView'])->name('chat-view-ajax');
    Route::post('/admin/chat-wise-view', [AdminChatController::class, 'adminChatWiseView'])->name('chat-wise-view-ajax');
    Route::post('/admin/get-admin-new-chat-list-ajax', [AdminChatController::class, 'getNewChatList'])->name('get-admin-new-chat-list-ajax');
    Route::post('/admin/get-more-info-list-ajax', [AdminChatController::class, 'getMoreInfoList'])->name('get-more-info-list-ajax');
    Route::post('/admin/get-chat-data', [AdminChatController::class, 'getChatDataView'])->name('get-chat-data-ajax');
    Route::post('/admin/chat-create-backend', [AdminChatController::class, 'createChatBackend'])->name('chat-create-backend-ajax');
    Route::post('/admin/get-front-search-data', [AdminChatController::class, 'getFrontSearchData'])->name('get-front-search-chat-view-ajax');
    Route::post('/admin/get-new-search-back-chat-view', [AdminChatController::class, 'getNewBackSearchData'])->name('get-new-search-back-chat-view-ajax');
    Route::post('/admin/get-back-chat-data', [AdminChatController::class, 'adminBackGetDataRfq'])->name('get-back-chat-data-ajax');

    //Admin - support chat
    Route::post('/admin/get-support-chat-data', [AdminChatController::class, 'getSupportChatDataView'])->name('get-support-chat-data-ajax');
    Route::post('/admin/support-chat-create-backend', [AdminChatController::class, 'createSupportChatBackend'])->name('support-chat-create-backend-ajax');
    Route::post('/admin/get-support-search-data', [AdminChatController::class, 'getSearchSupportChatData'])->name('get-support-search-chat-view-ajax');
    // Admin - Preferred Suppliers route
    Route::post('/admin/get-preferred-suppliers', [PreferredSuppliersController::class, 'getPreferredSuppliersData'])->name('get-preferred-suppliers-ajax');

    //Admin feedback route
    Route::get('admin/get-feedback/{id}/{type}', [AdminFeedbackController::class, 'getFeedbackList'])->name('get-feedback-ajax');
    Route::post('/admin/store-edit-feedback', [AdminFeedbackController::class, 'addUpdateFeedback'])->name('store-edit-feedback');
    Route::get('/admin/get-single-feedback/{id}', [AdminFeedbackController::class, 'editFeedback'])->name('get-single-feedback-ajax');
    Route::post('/admin/delete-feedback', [AdminFeedbackController::class, 'deleteFeedback'])->name('delete-feedback-ajax');
    //Admin
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

        Route::prefix('user')->name('user.')->group(function () {
            /********************begin: Backoffice Ajax request - User *******************/
            Route::middleware('auth.ajax')->group(function(){
                Route::post('/check/role', [AdminUserController::class, 'checkRole'])->name('check.role');
            });
            /********************end: Backoffice Ajax request - User *******************/
        });

        /*********************begin: Admin Finance***********************/
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\Finance\AdminFinanceController::class, 'index'])->name('index');

            Route::post('/json', [App\Http\Controllers\Admin\Finance\AdminFinanceController::class, 'listJson'])->name('index.json')->middleware('auth.ajax');

            Route::get('/export', [App\Http\Controllers\Admin\Finance\AdminFinanceController::class, 'financeExport'])->name('index.export');

            Route::post('/attachment/zip', [App\Http\Controllers\Admin\Finance\AdminFinanceController::class, 'zipAttachment'])->name('attachment.zip.download');

            Route::get('/xendit/balance', [App\Http\Controllers\Admin\Finance\AdminFinanceController::class, 'getXenditBalance'])->name('xendit.balance');

        });
        /*********************end: Admin Finance***********************/

        Route::prefix('credit')->name('credit.')->group(function () {
            //loan cancel
            Route::post('loan/cancel', [LoanController::class, 'loanCancel'])->name('loan.cancel')->middleware('auth.ajax');

            // disbursement list
            Route::get('disbursement', [PaymentController::class, 'disburseList'])->name('disbursement'); //credit/disbursement

            //supplier disbursement
            Route::get('supplier-disbursement/{id}', [PaymentController::class, 'supplierDisburse'])->name('supplier.disbursement');

        });

        Route::prefix('payments')->name('payments.')->group(function () {
            //upcoming payments
            Route::get('upcoming-payments', [PaymentController::class, 'paymentDueList'])->name('upcoming.payments'); //payments/upcoming-payments
            Route::post('build', [CreditController::class,'getPaymentLink'])->name('link.build')->middleware('auth.ajax');
        });

        /******************begin: Admin Quote**************************/
        Route::prefix('quote')->name('quote.')->group(function () {
           Route::post('/verify/loan-amount', [QuoteController::class, 'checkLoanOrderAmountExceed'])->name('verify.loan.amount')->middleware('auth.ajax');
        });
        /******************end: Admin Quote**************************/

        /******************begin: Admin Order**************************/
        Route::prefix('order')->name('order.')->group(function () {
            Route::post('/adjustment-amount/update', [OrderController::class, 'updateAdjustmentAmount'])->name('adjustment.amount.update')->middleware('auth.ajax');
            Route::post('/adjustment-amount/verify', [OrderController::class, 'isAmountAdjusted'])->name('adjustment.amount.verify')->middleware('auth.ajax');

        });
        /******************end: Admin Order**************************/

        /****************Begin:Admin Buyer's Routes *********************/
        Route::prefix('buyer')->name('buyer.')->group(function () {
            /****************Begin: Buyer's company list - Mittal*********************/
            Route::prefix('company')->name('company.')->group(function () {
                Route::get('/', [AdminBuyerCompanyController::class, 'list'])->name('list');
                Route::post('/json', [AdminBuyerCompanyController::class, 'listJson'])->name('list.json')->middleware('auth.ajax');
                Route::post('/viewDetails', [AdminBuyerCompanyController::class, 'buyerDetailsView'])->name('view.company');
                Route::get('/edit/{id}', [AdminBuyerCompanyController::class, 'edit'])->name('list.company.edit');
                Route::post('/update/status', [AdminBuyerCompanyController::class, 'changeStatus'])->name('list.update.status');
                Route::post('/update/company', [AdminBuyerCompanyController::class, 'update'])->name('list.update.company');
                Route::post('/remove/company', [AdminBuyerCompanyController::class, 'delete'])->name('list.remove'); // need to verify delete functionlity
                Route::post('/companyFileDelete', [AdminBuyerCompanyController::class, 'buyerCompanyFileDelete'])->name('list.companyFileDelete');
                Route::post('/downloadBuyerImageAdmin', [AdminBuyerCompanyController::class, 'downloadBuyerImageAdmin'])->name('list.downloadBuyerImageAdmin');
                Route::post('/userslist', [App\Http\Controllers\Admin\Buyer\AdminBuyerCompanyController::class, 'userListJson'])->name('companyUserList');
                Route::post('/companyUserList/edit', [AdminBuyerCompanyController::class, 'viewCompanyRolePopup'])->name('companyUserList.edit');
                Route::post('/companyUserList/update',[AdminBuyerCompanyController::class,'updateUserRolesDetails'])->name('companyUserList.update');
                Route::post('/companyUserList/delete', [AdminBuyerCompanyController::class, 'companyUsersDelete'])->name('companyUserList.delete');
                Route::post('/Flatfee/update', [AdminBuyerCompanyController::class, 'flatFeeUpdate'])->name('companyFlatFee.update');
                Route::get('/company/excel', [AdminBuyerCompanyController::class, 'CompanyExportExxcel'])->name('list.company.excel');
            });
            /***************end: Buyer's company list ************************/
        });
        /******************end: Admin Buyer's**************************/


        /******************begin: Admin Supplier**************************/
        Route::prefix('supplier')->name('supplier.')->group(function () {
            Route::get('edit/{id}', [AdminSupplierController::class, 'supplierEdit'])->name('edit');
            Route::post('/gallery-update-images', [AdminSupplierController::class, 'supplierGalleryImagesUpdate'])->name('gallaryImage');
            Route::get('/get-supplier-images-ajax/{id}', [AdminSupplierController::class, 'getSupplierImages'])->name('get-supplier-images-ajax');
            Route::post('/gallery-delete', [AdminSupplierController::class, 'galleryImageDelete'])->name('galleryImageDelete');
            Route::post('/update', [AdminSupplierController::class, 'update'])->name('update');
            Route::get('/create', [AdminSupplierController::class, 'supplierCreate'])->name('create');
            Route::post('/gallery-update-images', [AdminSupplierController::class, 'supplierGalleryImagesUpdate'])->name('gallaryImage');
            Route::post('/gallery-delete', [AdminSupplierController::class, 'galleryImageDelete'])->name('galleryImageDelete');
            Route::post('/storeSupplier', [AdminSupplierController::class, 'storeSupplier'])->name('storeSupplier');
            Route::get('/', [AdminSuppliersController::class, 'index'])->name('index');
            Route::get('/list/json', [AdminSuppliersController::class, 'listJson'])->name('list.json')->middleware('auth.ajax');
            Route::post('/delete', [AdminSuppliersController::class, 'delete'])->name('delete')->middleware('auth.ajax');
            Route::post('/getSupplierSubCategoryByCategory', [AdminSuppliersController::class, 'getSupplierSubCategoryByCategory'])->name('getSupplierSubCategoryByCategory.ajax')->middleware('auth.ajax');
            Route::post('/changeStatus', [AdminSuppliersController::class, 'changeStatus'])->name('changeStatus.ajax')->middleware('auth.ajax');
            Route::post('/getProductBySubcategory', [AdminSuppliersController::class, 'getProductBySubcategory'])->name('getProductBySubcategory.ajax')->middleware('auth.ajax');
        });

        /******************end: Admin Supplier**************************/

        Route::prefix('rfq')->name('rfq.')->group(function (){
            Route::get('list/json',[AdminRfqController::class,'listJson'])->name('list.json')->middleware('auth.ajax');
        });

    });



    //Suppier
    Route::prefix('supplier')->name('supplier.')->group(function() {


        Route::prefix('profile')->name('profile.')->group(function() {
            Route::get('/', [\App\Http\Controllers\Supplier\SupplierController::class, 'index'])->name('index');
            Route::post('/update/{id}', [\App\Http\Controllers\Supplier\SupplierController::class, 'update'])->name('update')->middleware('auth.ajax');

        });

        Route::prefix('members')->name('members.')->group(function() {
            Route::post('update/{id?}', [\App\Http\Controllers\Supplier\SupplierCompanyMembersController::class, 'update'])->name('update');
            Route::post('store/{id?}', [\App\Http\Controllers\Supplier\SupplierCompanyMembersController::class, 'store'])->name('store');
            Route::get('edit/{id}', [\App\Http\Controllers\Supplier\SupplierCompanyMembersController::class, 'edit'])->name('edit');
            Route::get('/', [\App\Http\Controllers\Supplier\SupplierCompanyMembersController::class, 'index'])->name('index');
            Route::post('delete', [\App\Http\Controllers\Supplier\SupplierCompanyMembersController::class, 'delete'])->name('delete');
            Route::get('list', [\App\Http\Controllers\Supplier\SupplierCompanyMembersController::class, 'getList'])->name('list.ajax');
            Route::post('deleteOrDownloadImages', [\App\Http\Controllers\Supplier\SupplierCompanyMembersController::class, 'deleteOrDownloadImages'])->name('deletedownloadimages');
        });

        Route::resource('business',\Supplier\SupplierCompanyDetailsController::class);


        Route::prefix('highlight')->name('highlight.')->group(function() {
            Route::post('update/{id?}', [\App\Http\Controllers\Supplier\SupplierCompanyHighlightsController::class, 'update'])->name('update');
            Route::post('store/{id?}', [\App\Http\Controllers\Supplier\SupplierCompanyHighlightsController::class, 'store'])->name('store');
            Route::get('edit/{id}', [\App\Http\Controllers\Supplier\SupplierCompanyHighlightsController::class, 'edit'])->name('edit');
            Route::get('/', [\App\Http\Controllers\Supplier\SupplierCompanyHighlightsController::class, 'index'])->name('index');
            Route::get('list',[\App\Http\Controllers\Supplier\SupplierCompanyHighlightsController::class,'getList'])->name('list.ajax');
            Route::post('deleteOrDownloadImages', [\App\Http\Controllers\Supplier\SupplierCompanyHighlightsController::class, 'deleteOrDownloadImages'])->name('deletedownloadimages');
        });


        Route::post('company/details/{id}',[\App\Http\Controllers\Supplier\SupplierCompanyDetailsController::class, 'updateSupplierBasicDetail'])->name('basic.details.update');

    });

    // Admin - call AirWayBill number function
    Route::post('/admin/generate-airwaybill', [OrderController::class, 'generateAirWayBillNumber'])->name('generate-airwaybill-ajax');

    // Download documents for supplier
    Route::get('supplier_doc/{file_name}', [AdminSupplierController::class, 'downloadSupplierDoc'])->name('download-supplier-doc');
    
    //Supplier product tagging
    Route::post('/supplier-product-tagging', [AdminSupplierController::class, 'supplierProductTagging'])->name('supplier-prod-tagging-ajax');

    });

Route::get('/admin', function () {
    return redirect('admin/login');
});
Route::get('/admin/login', [AdminLoginController::class, 'index'])->name('admin-login-form');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin-login');
Route::get('/admin/forget-password', [AdminLoginController::class, 'showForgetPasswordForm'])->name('admin-forget-password-get');
Route::post('/admin/forget-password', [AdminLoginController::class, 'submitForgetPasswordForm'])->name('admin-forget-password-post');
Route::get('/admin/reset-password/{token}', [AdminLoginController::class, 'showResetPasswordForm'])->name('admin-reset-password-get');
Route::post('/admin/reset-password', [AdminLoginController::class, 'submitResetPasswordForm'])->name('admin-reset-password-post');

Route::get('/forget-password', [UserController::class, 'showForgetPasswordForm'])->name('forget-password-get');
Route::post('/forget-password', [UserController::class, 'submitForgetPasswordForm'])->name('forget-password-post');
Route::get('/reset-password/{token}', [UserController::class, 'showResetPasswordForm'])->name('reset-password-get');
Route::post('/reset-password', [UserController::class, 'submitResetPasswordForm'])->name('reset-password-post'); // front user
//Route::view('/supplier-add', 'admin/supplier/supplierAdd')->name('supplier-add-without-login');
Route::get('/supplier-add', [AdminSupplierController::class, 'viewSupplierAddWithoutLogin'])->name('supplier-add-without-login');

// add country route
Route::get('/admin/get-country', [AdminSupplierController::class, 'getCountry'])->name('get-country');

Route::post('/admin/supplier-create-ajax', [AdminSupplierController::class, 'createAjax'])->name('supplier-create-ajax');
Route::post('/admin/supplier-product-create-ajax', [AdminSupplierController::class, 'productCreateAjax'])->name('supplier-product-create-ajax');
Route::post('/admin/supplier-product-update-ajax', [AdminSupplierController::class, 'productUpdateAjax'])->name('supplier-product-update-ajax');
Route::post('/admin/supplier-product-delete-ajax', [AdminSupplierController::class, 'productDeleteAjax'])->name('supplier-product-delete-ajax');
Route::get('/admin/supplier-get-brand-grade-product/{subCategoryId}', [AdminSupplierController::class, 'getBrandGradeProduct'])->name('get-brand-grade-product-ajax');
Route::get('/get-supplier-product-ajax/{supplierProductId}', [AdminSupplierController::class, 'getSupplierProductAjax'])->name('get-supplier-product-ajax');
Route::get('/supplier-term-and-condition/{name}/{companyName}', [AdminSupplierController::class, 'viewTermAndCondition'])->name('supplier-term-and-condition');
Route::get('/supplier-term-and-condition-blank', [AdminSupplierController::class, 'viewTermAndConditionBlank'])->name('supplier-term-and-condition-blank');
Route::get('/admin/supplier-detail-view/{id}', [AdminSupplierController::class, 'supplierDetailsView'])->name('supplier-detail-view');
Route::get('/admin/get-supplier-product-list/{subCategoryId}/{supplierId}', [AdminSupplierController::class, 'getSupplierProductListAjax'])->name('get-supplier-product-list-ajax');
//filter subcategoryByCategory ---vrutika---
//Route::post('/admin/get-supplier-product-by-subcategory', [AdminSupplierController::class, 'getProductBySubcategory'])->name('get-supplier-product-by-subcategory-ajax');
//invite buyer ---ekta----
Route::get('/admin/invite-buyer-list', [AdminSupplierController::class, 'inviteBuyerList'])->name('invite-buyer-list');
Route::post('/admin/check-invite-user-email-exist', [AdminSupplierController::class, 'checkUserEmailExist'])->name('check-invite-user-email-exist');
Route::get('/admin/invite-buyer-add', [AdminSupplierController::class, 'inviteBuyerAdd'])->name('invite-buyer-add');
Route::post('/admin/invite-buyer-create', [AdminSupplierController::class, 'inviteBuyerCreate'])->name('invite-buyer-create');
Route::get('/admin/invite-buyer-edit/{id}', [AdminSupplierController::class, 'inviteBuyerEdit'])->name('invite-buyer-edit');
Route::post('/admin/check-invite-user-email-edit-exist', [AdminSupplierController::class, 'checkUserEmailEditExist'])->name('check-invite-user-email-edit-exist');
Route::post('/admin/invite-buyer-update', [AdminSupplierController::class, 'inviteBuyerUpdate'])->name('invite-buyer-update');
Route::post('/admin/invite-buyer-resend', [AdminSupplierController::class, 'inviteBuyerResend'])->name('invite-buyer-resend');
Route::get('/admin/get-supplier-buyer-ajax/{radioValue}', [AdminSupplierController::class, 'getSupplierBuyerList'])->name('get-supplier-buyer-ajax');

//invite supplier ---ekta----
Route::get('/admin/invite-supplier-list', [AdminSupplierController::class, 'inviteSupplierList'])->name('invite-supplier-list');
Route::get('/admin/invite-supplier-add', [AdminSupplierController::class, 'inviteSupplierAdd'])->name('invite-supplier-add');
Route::post('/admin/invite-supplier-create', [AdminSupplierController::class, 'inviteSupplierCreate'])->name('invite-supplier-create');
Route::get('/admin/invite-supplier-edit/{id}', [AdminSupplierController::class, 'inviteSupplierEdit'])->name('invite-supplier-edit');
Route::post('/admin/invite-supplier-update', [AdminSupplierController::class, 'inviteSupplierUpdate'])->name('invite-supplier-update');

//Supplier Address Routes
Route::get('/admin/supplier-address-list', [AdminSupplierController::class, 'supplierAddressList'])->name('supplier-address-list');
Route::get('/admin/add-supplier-address', [AdminSupplierController::class, 'addSupplierAddress'])->name('add-address');
Route::get('/admin/supplier-address-detail/{id}', [AdminSupplierController::class, 'getSupplierAddress'])->name('supplier-address-detail');
Route::post('/admin/supplier-address-create', [AdminSupplierController::class, 'createSupplierAddress'])->name('supplier-address-create');
Route::get('/admin/supplier-address-edit/{id}', [AdminSupplierController::class, 'editSupplierAddress'])->name('supplier-address-edit');
Route::post('/admin/supplier-address-update', [AdminSupplierController::class, 'updateSupplierAddress'])->name('supplier-address-update');
Route::post('/admin/supplier-address-status-update', [AdminSupplierController::class, 'supplierAddressStatusUpdate'])->name('supplier-address-status-update');
Route::post('/admin/supplier-address-delete', [AdminSupplierController::class, 'deleteSupplierAddress'])->name('supplier-address-delete');
Route::get('/admin/getSupplierAddressById/{id}', [AdminSupplierController::class, 'getSupplierAddressById'])->name('getSupplierAddressById');

//City Routes
Route::post('/admin/state/{id}/city', [\App\Http\Controllers\CityController::class, 'getCityByState'])->name('admin.city.by.state');
Route::post('/admin/country/{id}/state', [StateController::class, 'getStateByCountry'])->name('admin.state.by.country');

Route::get('/getstatescountrywise/', [\App\Http\Controllers\StateController::class, 'getDropdownData'])->name('state.getdropdowndata');

// Added By Sachin for Supplier Professional Profile Routes Start
Route::post('/admin/update-supplier-company-highlights',[AdminSupplierController::class,'updateSupplierCompanyHighlights'])->name('update-supplier-company-highlights');
Route::post('/admin/delete-download-supplier-images',[AdminSupplierController::class,'deleteOrDownloadSupplierImages'])->name('delete-download-supplier-images');
Route::post('/admin/update-supplier-company-members',[AdminSupplierController::class,'updateSupplierCompanyMembers'])->name('update-supplier-company-members');
Route::post('/admin/checkProfileUsernameUnique',[\App\Http\Controllers\Supplier\SupplierController::class, 'checkProfileUsernameUnique'])->name('admin.supplier.checkProfileUsernameUnique');
// Added By Sachin for Supplier Professional Profile Routes End

// End Admin Routese

// End Admin Routes
//Credit Applictaion start
Route::get('/admin/credit-application', [CreditApplicationController::class, 'index'])->name('credit-application-index');
Route::get('/admin/disbursement', [AdminDisbursementController::class, 'index'])->name('disbursement-index');
Route::get('/admin/due-date', [DueDateController::class, 'index'])->name('due-date-index');


//end

Route::get('/generate-pay-link/{orderId}', [TransactionController::class, 'generatePayLink'])->name('generate-pay-link');

//Front Routes
Route::view('/', 'home/home2', ['calendlyMeetingLink' => env('CALENDLY_MEETING_LINK')])->name('home');
Route::view('/signup', 'signup')->name('signup');
Route::view('/signin', 'signin')->name('signin');
Route::view('/rfqmodal', 'rfqModal/rfqModal')->name('rfqmodal');
Route::post('/getotphtml', [UserController::class, 'getOtpModel'])->name('getotphtml');
Route::post('/verifyotp', [UserController::class, 'verifyOtp'])->name('verifyotp');
Route::post('/userLogin', [UserController::class, 'login'])->name('login');
Route::get('/user-logout', [UserController::class, 'logout'])->name('logout');
Route::get('/activate-account', [UserController::class, 'activateAccount'])->name('activateAccount');
Route::get('/signup/{email}/{token}', [UserController::class, 'showSignupUser'])->name('signup-user');
Route::view('/link-expired', 'linkExpired')->name('link-expired');
Route::view('/supplier/signup', 'signupSupplier')->name('signup-supplier');
Route::post('/supplier/getotphtml', [UserController::class, 'getOtpModel'])->name('getotphtml');
Route::post('/supplier/verifyotp', [UserController::class, 'verifyOtp'])->name('verifyotp');
Route::post('/supplier/checkmobileexist', [AdminLoginController::class, 'checkMobileExist'])->name('check-mobile-exist');
Route::post('/supplier/checkEmailExist', [UserController::class, 'checkEmailExist'])->name('check-email-exist');
Route::post('/supplier/profile-invite-supplier', [ProfileController::class, 'inviteSupplierFriend'])->name('profile-invite-supplier-verify');
Route::get('/supplier/signup/{email}/{token}', [UserController::class, 'signupSupplier'])->name('signup-supplier-invited');
Route::view('/thankyou', 'thankyou')->name('thankyou');
//Route::post('/custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::post('/loginwithotp', [UserController::class, 'loginWithOtp'])->name('loginwithotp');
Route::post('/admin/loginwithotp', [UserController::class, 'supplierloginWithOtp'])->name('supplierloginwithotp');
Route::get('/chooseemail/{id}', [UserController::class, 'chooseemail'])->name('chooseemail');
Route::post('/checkmobilenotexist', [UserController::class, 'checkmobilenotexist'])->name('check-mobile-not-exist');
Route::post('admin/checkmobilenotexist', [UserController::class, 'checkmobilenotexist'])->name('check-mobile-not-exist');

Route::post('/checkEmailExist', [UserController::class, 'checkEmailExist'])->name('check-email-exist');
Route::post('/checkmobileexist', [UserController::class, 'checkMobileExist'])->name('check-mobile-exist');
Route::post('/addUser', [UserController::class, 'addUser'])->name('addUser');
Route::post('/checkSupplierEmailExist', [UserController::class, 'checkSupplierEmailExist'])->name('check-supplier-registration-email-exist');
Route::post('/addSupplier', [UserController::class, 'addSupplier'])->name('add-supplier');
Route::get('/contact/search-category/{category}', [CategoryController::class, 'searchCategory'])->name('search-category-ajax');
Route::POST('/contact/search-product', [CategoryController::class, 'searchProduct'])->name('search-product-ajax');
Route::POST('/contact/search-product-description', [CategoryController::class, 'searchProductDescription'])->name('search-product-description-ajax');
Route::get('/contact/get-subcategory/{categoryId}', [CategoryController::class, 'getSubCategory'])->name('get-subcategory-ajax');
Route::POST('/contact/get-brand-and-grade', [CategoryController::class, 'getBrandAndGrade'])->name('get-brand-and-grade-ajax');
Route::POST('/contact/add-full-rfq', [RfqController::class, 'addFullRfq'])->name('add-full-rfq-ajax');
Route::POST('/contact/get-unit', [CategoryController::class, 'getUnit'])->name('get-unit-ajax');
Route::get('/view-accept-order-details/{id}', [OrderController::class, 'viewAcceptOrderDetails'])->name('view-accept-order-details');
Route::POST('/supplier-otp', [OrderController::class, 'supplierOtp'])->name("supplier-otp");
Route::POST('/supplier-otp-check', [OrderController::class, 'supplierOtpCheck'])->name("check-supplier-otp");
Route::get('/supplier-order-status/{id}{status}', [OrderController::class, 'supplierOrderStatus'])->name("supplier-order-status");
Route::get('/contact/get-product-image/{id}', [ProductController::class, 'getProductImage'])->name('get-product-image-ajax');
Route::get('/get-category-with-subcat', [CategoryController::class, 'getCategoryWithSubcat'])->name('get-category-with-subcat-ajax');
Route::post('/get-subcategory-by-category', [CategoryController::class, 'getAllSubCategoriesByCatId'])->name('get-subcategory-by-category-ajax');
Route::post('/get-product-by-category-subcategory', [CategoryController::class, 'getProductByCatSubcatId'])->name('get-product-by-category-subcategory-ajax');
Route::get('/achieveqty-group-details/{groupId}', [RfqController::class, 'getAchieveGroupQty'])->name('achieveqty-group-details');

Route::post('/quick-rfq', [RfqController::class, 'createQuickRfq'])->name('quick-rfq-ajax');
Route::post('/quick-editrfq', [RfqController::class, 'editQuickRfq'])->name('quick-editrfq-post-ajax');
Route::post('/quick-rfq-post', [RfqController::class, 'createQuickRfqPost'])->name('quick-rfq-post-ajax');
Route::post('/add-subsribe-user', [UserController::class, 'addSubsribeUser'])->name('add-subsribe-user-ajax');
Route::post('/subsribe-user', [UserController::class, 'subsribeUser'])->name('subsribe-user-ajax');
Route::post('/add-newsletter', [NewsletterController::class, 'add'])->name('add-newsletter');
//vrutika cancel rfq
Route::post('/cancel-rfq', [RfqController::class, 'cancelQuickRfq'])->name('cancel-rfq-ajax');
Route::post('/favourite-rfq', [RfqController::class, 'favouriteQuickRfq'])->name('favourite-rfq-ajax');
Route::get('/quote-details/{id}/{userId}/{latestOTP}', [DashboardController::class, 'quoteDetailsByQuoteId'])->name('quote-details');
Route::POST('/verify-feedback-otp', [DashboardController::class, 'verifyFeedbackOTP'])->name("verify-feedback-otp");
Route::get('/feedback-thank-you', [DashboardController::class, 'feedbackThankYou'])->name('feedback-thank-you');
Route::get('/feedback-submitted', [DashboardController::class, 'feedbackSubmitted'])->name('feedback-submitted');
//profile % - update profile focuse route
Route::POST('/get-profile-pending-inputlist', [DashboardController::class, 'getPendingfirstInputName'])->name('get-profile-pending-inputlist-ajax')->middleware('auth.ajax');;
//end

//Group Trading Routes

Route::get('/fetch-groupby-product', [GroupTradingController::class, 'fetchGroupbyProduct'])->name('fetch-groupby-product');
Route::get('/group-trading', [GroupTradingController::class, 'index'])->name('group-trading');
Route::get('group-trading-front', [GroupTradingController::class, 'frontPage'])->name('group-trading-front');
Route::post('/group-trading-ajax', [GroupTradingController::class, 'groupsData'])->name('group-trading-ajax');
Route::post('/group-category-filter', [GroupTradingController::class, 'groupsCategoryFilters'])->name('group-category-filter-ajax');
Route::get('/group-details/{id}', [GroupTradingController::class, 'groupDetails'])->name('group-details');

//chat
Route::post('/group-message-read-ajax', [ChatController::class, 'messageRead'])->name('group-message-read-ajax');

Route::get('/mobileverification',[UserController::class, 'mobileVarification'])->name('mobileVarification');
Route::post('/mobilevarify', [UserController::class, 'mobilevarify'])->name('mobilevarify');
Route::group(['middleware' => ['auth', 'is_user']], function () {

    Route::post('/sendemailvarification', [UserController::class, 'sendEmailVarification'])->name('sendemailvarification');
    Route::get('/changemobile',[UserController::class, 'changemobile'])->name('changemobile');
    Route::get('/remove/htaccess',[\App\Http\Controllers\Production\LiveController::class,'removeHtaccess']);
    Route::get('/get-a-quote', [RfqController::class, 'viewFullFormRfq'])->name('get-a-quote');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-default', [DashboardController::class, 'getDashboardDefaultData'])->name('dashboard-default-ajax');

    Route::get('download-user-po-pdf/{id}', [OrderController::class, 'downloadBuyerPoPdf'])->name('download-user-po-pdf');
    Route::get('download-user-blitznet-invoice-pdf/{id}', [OrderController::class, 'downloadblitznetInvoicePdf'])->name('download-user-blitznet-invoice-pdf');
    //activity route
    Route::get('/dashboard-user-activity', [DashboardController::class, 'getDashboardUserActivityData'])->name('dashboard-user-activity-ajax');
    Route::get('/dashboard-user-activity-new-data-count', [DashboardController::class, 'getDashboardUserActivityNewDataCount'])->name('dashboard-user-activity-new-data-count-ajax');

    //address route
    Route::get('/dashboard-list-address', [DashboardController::class, 'getDashboardAddress'])->name('dashboard-list-address-ajax');
    Route::get('/dashboard-edit-address/{id}', [DashboardController::class, 'editDashboardAddress'])->name('dashboard-edit-address-ajax');
    Route::post('/dashboard-update-address', [DashboardController::class, 'updateDashboardAddress'])->name('dashboard-update-address-ajax');
    Route::get('/dashboard-delete-address/{id}', [DashboardController::class, 'deleteDashboardAddress'])->name('dashboard-delete-address-ajax');
    Route::post('/dashboard-create-address', [DashboardController::class, 'createDashboardAddress'])->name('dashboard-create-address-ajax');
	Route::post('/address-status-update', [DashboardController::class, 'addressStatusUpdate'])->name('address-status-update');



    //payment route
    Route::get('/dashboard-list-payment', [DashboardController::class, 'getDashboardPayment'])->name('dashboard-list-payment-ajax');
    Route::post('/dashboard-bulk-payment', [DashboardController::class, 'setBulkPayment'])->name('dashboard-bulk-payment-ajax');
    Route::post('/dashboard-cancel-payment', [DashboardController::class, 'getDashboardCancelPayment'])->name('dashboard-cancel-payment-ajax');

    //Buyer's Groups Routes
    Route::get('/dashboard-group-listing', [DashboardController::class, 'DashboardGroupListing'])->name('dashboard-group-listing');
    Route::post('/dashboard-list-groups', [DashboardController::class, 'getDashboardGroups'])->name('dashboard-list-groups-ajax');

    // rfq route
    Route::get('/dashboard-list-rfq', [DashboardController::class, 'getDashboardRfq'])->name('dashboard-list-rfq-ajax');
    Route::get('/dashboard-get-rfq-quotes', [DashboardController::class, 'getRfqQuotes'])->name('dashboard-get-rfq-quotes-ajax');

    Route::get('/dashboard-get-rfq-edit/{id}', [DashboardController::class, 'geteditRfqs'])->name('dashboard-get-rfq-editmodal-ajax');
    Route::get('/dashboard-get-rfq-details/{id}', [DashboardController::class, 'getRfqDetails'])->name('dashboard-get-rfq-details-ajax');
    Route::get('/dashboard-get-rfq-quotes-details/{id}', [DashboardController::class, 'getRfqQuoteDetails'])->name('dashboard-get-rfq-quotes-details-ajax');
    Route::get('/dashboard-get-repeat-rfq-list', [DashboardController::class, 'getRepeatRfqlist'])->name('dashboard-get-repeat-rfq-list-ajax');
    Route::get('/dashboard/rfq-activity/{id}', [DashboardController::class, 'rfqactivity'])->name('dashboard-get-rfq-activity-ajax');
    Route::get('/dashboard-get-order-quote-details/{id}', [DashboardController::class, 'getOrderQuoteDetails'])->name('dashboard-get-order-quote-details-ajax');
    Route::post('/dashboard-order-pickup-batch-ajax', [OrderController::class, 'orderPickupBatch'])->name('dashboard-order-pickup-batch-ajax');
    Route::post('/dashboard-generate-airwaybill', [OrderController::class, 'generateAirWayBillNumber'])->name('dashboard-generate-airwaybill-ajax');
    Route::get('/get-shipping-label/{id}', [QuincusController::class, 'getShippingLabel'])->name('dashboard-get-shipping-label');
    Route::get('download-rfq-document/{id}', [AdminRfqController::class, 'downloadRfqAttachment'])->name('download-rfq-document');
    Route::post('/rfq-attachment-delete', [AdminRfqController::class, 'deleteRfqAttachment'])->name('rfq-attachment-delete-ajax');
    //order route
    Route::get('/dashboard-list-order', [DashboardController::class, 'getDashboardOrders'])->name('dashboard-list-order-ajax');
    Route::get('/dashboard-get-place-order-details/{id}', [DashboardController::class, 'getRfqPlaceOrderDetails'])->name('dashboard-get-place-order-details-ajax');
    Route::get('/dashboard-get-place-order-details-next', [DashboardController::class, 'getRfqPlaceOrderLoanDetails'])->name('dashboard-get-place-order-details-next-ajax');
    Route::post('/dashboard-place-order', [DashboardController::class, 'placeOrder'])->name('dashboard-place-order-ajax');
    Route::post('/uploadorderdoc', [OrderController::class, 'uploadOrderDoc'])->name('upload-order-doc');
    Route::get('/dashboard-refresh-order/{orderId}', [DashboardController::class, 'refreshDashboardOrder'])->name('dashboard-refresh-order');
    Route::get('/dashboard-refresh-order-sub-status/{orderId}', [DashboardController::class, 'refreshDashboardOrderSubStatus'])->name('dashboard-refresh-order-sub-status');
    Route::get('/dashboard-refresh-single-order-item-status/{orderItemId}', [DashboardController::class, 'refreshDashboardSingleOrderItemStatus'])->name('dashboard-refresh-single-order-item-status');
    Route::post('/dashboard-order-file-download', [DashboardController::class, 'downloadImage'])->name('dashboard-download-image-ajax');
    Route::post('/dashboard-certificate-file-download', [DashboardController::class, 'downloadCertificate'])->name('dashboard-download-certificate-ajax');
    Route::post('/order/order-status-change', [OrderController::class, 'orderStatusChange'])->name('buyer-order-status-change-ajax');
    Route::post('/order/credit-order-status-change', [OrderController::class, 'creditOrderStatusChange'])->name('buyer-credit-order-status-change-ajax');
    Route::post('/order/order-item-status-change', [OrderController::class, 'orderItemStatusChange'])->name('dashboard-order-item-status-change-ajax');
    Route::post('/generate-airwaybill', [OrderController::class, 'generateAirWayBillNumber'])->name('generate-airwaybill-buyer-ajax');
    Route::post('/order-pickup-batch', [OrderController::class, 'orderPickupBatch'])->name('order-pickup-batch-buyer-ajax');
    //payment route
    Route::get('success-invoice-payment/{id}', [DashboardController::class, 'successInvoicePayment']);
    Route::get('fail-invoice-payment/{id}', [DashboardController::class, 'failInvoicePayment']);

    //group payment route
    Route::post('dashboard-group-place-order', [GroupTradingController::class, 'groupPlaceOrder'])->name('dashboard-group-place-order-ajax');
    Route::get('success-group-payment/{id}', [GroupTradingController::class, 'successInvoicePayment']);
    Route::get('fail-group-payment/{id}', [GroupTradingController::class, 'failInvoicePayment']);

    //user Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile-update-personal-info', [ProfileController::class, 'updatePersonalInfo'])->name('profile-update-personal-info-ajax');
    Route::post('/profile-update-payment-info', [ProfileController::class, 'updatePaymentInfo'])->name('profile-update-user-payment-term-ajax');
    Route::post('/profile-change-password', [ProfileController::class, 'changePassword'])->name('profile-change-password-ajax');
    Route::post('/profile-change-companyinfo', [ProfileController::class, 'changeCompanyDetail'])->name('profile-change-companyinfo-ajax');
    Route::post('/profile-update-user-language-currency', [ProfileController::class, 'updatelangcurrency'])->name('profile-update-user-language-currency');

    //Invite User Routes
    Route::post('/profile-user-invitation', [ProfileController::class, 'userInvitation'])->name('profile-user-invitation-ajax');
    Route::get('/user-detail/{id}', [ProfileController::class, 'userDetails'])->name('user-detail');
    Route::post('/update-user-info', [ProfileController::class, 'updateInvitedUserInfo'])->name('update-user-info-ajax');
    Route::get('/delete-invited-user/{id}', [ProfileController::class, 'deleteInvitedUser'])->name('delete-invited-user-ajax');
    Route::get('/accept-user-invitation/{id}', [ProfileController::class, 'acceptUserInvitation'])->name('accept-user-invitation');
    Route::POST('/verify-user-otp', [ProfileController::class, 'verifyUserOTP'])->name("verify-user-otp");
    Route::get('/thank-you', [ProfileController::class, 'thankYou'])->name('thank-you');
    Route::get('/already-accepted', [ProfileController::class, 'alreadyAccepted'])->name('already-accepted');

    // Invite Supplier and friend routes
    Route::post('/profile-invite-supplier', [ProfileController::class, 'inviteSupplierFriend'])->name('profile-invite-supplier-ajax');
    Route::get('/profile-invite-supplier-edit/{id}', [ProfileController::class, 'inviteSupplierFriendEdit'])->name('profile-invite-supplier-edit-ajax');
    Route::post('/update-invite-supplier', [ProfileController::class, 'inviteSupplierFriendUpdate'])->name('update-invite-supplier-ajax');

    //Approval Process Toggle Value Route
    Route::get('/approval-process-value', [DashboardController::class, 'getapprovalProcessValue'])->name('approval-process-value-ajax');
    Route::POST('/update-approval-process', [DashboardController::class, 'UpdateToggleValue'])->name("update-approval-process-ajax");

    //Approval Configuration Routes
    Route::post('/profile-approval-config', [ProfileController::class, 'userApprovalConfiguration'])->name('profile-approval-config-ajax');
    Route::get('/approval-user-detail/{id}', [ProfileController::class, 'approvalUserDetails'])->name('approval-user-detail');
    Route::post('/update-usertype', [ProfileController::class, 'updateApprovalUserInfo'])->name('update-usertype-ajax');
    Route::get('/delete-config-user/{id}', [ProfileController::class, 'deleteApprovalUser'])->name('delete-config-user-ajax');

    //Approval Quote Details
    Route::get('/approval-quotes-details/{id}', [BuyerApprovalController::class, 'getQuoteDetails'])->name('approval-quotes-details');

    //Send Quote Approval Routes
    Route::post('/send-quote-configure-users', [DashboardController::class, 'sendQuoteForApproval'])->name('send-quote-configure-users-ajax');
    Route::post('/quote-feedback-data', [DashboardController::class, 'getQuoteFeedbackData'])->name('quote-feedback-data-ajax');
    Route::get('/configure-user-resend-mail/{userId}/{quoteId}/{pending_resend}', [DashboardController::class, 'configureUserResendMail'])->name('configure-user-resend-mail');

    Route::post('/profile-company-file-delete', [ProfileController::class, 'companyFileDelete'])->name('profile-company-file-delete-ajax');
    Route::post('/profile-company-file-download', [ProfileController::class, 'companyFileDownload'])->name('profile-company-download-image-ajax');
    Route::post('/rfq-attachment-download', [DashboardController::class, 'downloadRfqAttachmentFile'])->name('rfq-attachment-download-ajax');
    Route::post('/rfq-single-attachment-download', [DashboardController::class, 'downloadRfqAttachment'])->name('rfq-single-attachment-document-ajax');
    Route::post('/rfq-attachment-delete', [DashboardController::class, 'deleteRfqAttachmentFile'])->name('rfq-attachment-delete-ajax');
    Route::post('/quote-attachment-download-ajax', [DashboardController::class, 'downloadQuoteProductCertificateFile'])->name('quote-attachment-download-ajax');


    Route::post('/check-customer-refId', [DashboardController::class, 'checkCustomerRefIdExist'])->name('check-customer-refId-ajax');

    Route::post('/leave-group-ajax', [GroupTradingController::class, 'leaveGroupTrading'])->name('leave-group-ajax');

    //User Bank Details
    Route::resource('/bank-details', BuyerBanksController::class);
    Route::post('bank-details/update-primary',[App\Http\Controllers\BuyerBanksController::class,'updatePrimaryBank'])->name('bank-details.update.primary.bank');
    Route::post('bank-details/exist-primary',[App\Http\Controllers\BuyerBanksController::class,'existPrimaryBank'])->name('bank-details.exist.primary.bank');

    /**********************begin: Buyer Admin Settings ************************************/
    Route::prefix('settings')->name('settings.')->group(function () {

        /*********************begin: Buyer Roles***********************/
        Route::resource('roles', CustomRolesController::class);
        //Buyer User Controller

        Route::prefix('roles')->name('roles.')->group(function () {

            Route::post('/assigned', [App\Http\Controllers\CustomRolesController::class, 'checkRoleAssigend'])->name('assigned');

            Route::post('/validate-approver', [App\Http\Controllers\CustomRolesController::class, 'checkApproverExists'])->name('validate-approver');

        });
        /*********************end: Buyer Roles***********************/

        /*********************begin: Buyer Users***********************/
        Route::prefix('users')->name('users.')->group(function () {

            Route::get('/{id}/permission', [App\Http\Controllers\UserController::class, 'editPermission'])->name('permission');
            Route::post('/permission/update', [App\Http\Controllers\UserController::class, 'updatePermission'])->name('permission.update');
            Route::post('/rolePermissionPopup/{id}',[UserController::class,'rolePermissionPopup'])->name('rolePermissionPopup');

        });
        /*********************end: Buyer Users***********************/

        /*********************begin: Buyer Company***********************/
        Route::prefix('company')->name('company.')->group(function () {

            /**begin: buyer user company switch*/
            Route::post('/multiple-list', [ProfileController::class, 'getCompanyList'])->name('list');
            Route::post('/switch', [ProfileController::class, 'changeDefaultCompanyId'])->name('switch');
            /***end: buyer user company switch*/

        });
        /*********************end: Buyer Company***********************/

        Route::resource('/buyer-user', BuyerUserController::class);


        /********************begin: Buyer Credits *******************/
        Route::prefix('credit')->name('credit.')->group(function () {

            /********************begin: Credit AJAX request *******************/
            Route::middleware('auth.ajax')->group(function(){
                Route::post('/credit-limit/otp/resend', [LoanApplicationsController::class, 'requestLimitOTP'])->name('credit-limit.otp.resend');
            });
            /********************end: Credit AJAX request *******************/
             //Monika done on 11-09-2022 for create loan
            Route::post('/loan/create/json', [BuyerLoanController::class, 'createLoan'])->name('loan.create.json');
            Route::get('/loan/apply/calculation/{id}', [BuyerLoanController::class, 'getCreateLoanCalculation'])->name('loan.apply.calculation.json');
            Route::post('/loan/verify/otp', [BuyerLoanController::class, 'verifyOtpWithPlaceOrder'])->name('loan.verify.otp.json');
            Route::get('loan-view-calculation/{id}', [BuyerLoanController::class, 'transactionView'])->name('loan-view-calculation');

        });
        /********************end: Buyer Credits *******************/


        Route::get('/credit-apply',[LoanApplicationsController::class,'index'])->name('credit.apply.step1');

        Route::get('/credit-show',[LoanApplicationsController::class,'creditShow'])->name('credit.show');

        Route::post('/loan-application', [LoanApplicationsController::class,'create'])->name('loan-application-ajax');
        Route::post('/loan-application-confirm', [LoanApplicationsController::class,'store'])->name('loan-application-confirm-ajax');
        Route::post('/loan-application-download', [LoanApplicationsController::class, 'downloadAttachmentFile'])->name('loan-application-download-ajax');
        Route::get('/limit-reupload-document/{id}', [LoanApplicationsController::class, 'reuploadDocument'])->name('limit-reupload-document');
        Route::post('/limit-reupload-document', [LoanApplicationsController::class, 'limitReuploadDocument'])->name('limit-reupload-document-ajax');
        Route::get('/credit-thank-you/{id}', [LoanApplicationsController::class, 'thankYou'])->name('credit-thank-you');
        Route::get('/limit-otp/{id}', [LoanApplicationsController::class, 'limitOtp'])->name('limit-otp');
        Route::post('/limit-otp-verify', [LoanApplicationsController::class, 'verifyLimitOTP'])->name('limit-otp-verify-ajax');
        Route::get('/credit', [LoanApplicationsController::class, 'creditDashboard'])->name('credit');

        Route::get('/loan-application-edit', [LoanApplicationsController::class, 'edit'])->name('loan-application-edit');
        Route::get('/credit-reject/{id}', [LoanApplicationsController::class, 'creditReject'])->name('credit-reject');

        Route::get('/limit-contract/{id}', [LoanApplicationsController::class, 'limitContract'])->name('credit-limit-contract');
        Route::post('/limit-contract-upload', [LoanApplicationsController::class, 'limitContractUpload'])->name('limit-contract-upload-ajax');

        Route::get('/credit-limit-status/{id}', [LoanApplicationsController::class, 'creditLimitStatus'])->name('credit-limit-status');
        Route::get('/get-mylimit/{application_id}', [LoanApplicationsController::class, 'getUpdateUserLimit'])->name('get-mylimit');


    });

    /**********************end: Buyer Admin Settings ************************************/

    /**********************begin: Buyer Profile ************************************/
    Route::prefix('profile')->name('profile.')->group(function () {

        /*********************begin: Profile Ajax***********************/

        Route::post('/redirection', [App\Http\Controllers\ProfileController::class, 'getProfileRedirectionElements'])->name('redirection');

        /*********************end: Profile Ajax***********************/

    });
    /**********************end: Buyer Profile ************************************/

    /**********************begin: Buyer Company Credit************************************/
    Route::prefix('credit/wallet')->name('credit.wallet.')->group(function () {

        Route::get('/', [CreditController::class,'index'])->name('index');

        Route::post('/payment/build', [CreditController::class,'getPaymentLink'])->name('payment.link.build')->middleware('auth.ajax');

        Route::get('/transactions', [CreditTransactionController::class,'index'])->name('transactions');

        Route::post('/transactions/json', [CreditTransactionController::class, 'listJson'])->name('transactions.json')->middleware('auth.ajax');

    });
    /**********************end: Buyer Company Credit************************************/


    /*********************begin: Xendit***********************************************/
    Route::prefix('xendit')->name('xendit.')->group(function () {

        Route::get('/payment/{status}/{model}/{model_id}', [XenditLoanTransactionController::class, 'syncXenditPaymentStatus'])->name('payment.status.callback');

        Route::get('/payment/disbursements', [XenditLoanTransactionController::class,'disbursementToKoinworks'])->name('payment.transactions');


    });
    /*********************begin: Xendit***********************************************/


    /********************begin: Buyer Addresses *******************/
    Route::prefix('address')->name('address.')->group(function () {

        Route::post('/belongs', [UserAddressesController::class, 'isAddressBelongsTo'])->name('belongs')->middleware('auth.ajax');

    });
    /********************end: Buyer Credits *******************/
    Route::get('rfqs',[RfqsController::class,'rfqs'])->name('rfqs-ls');
    Route::get('orders',[OrdersLivewireController::class,'orders'])->name('order-ls');

    /**********************begin: Approvals tab permission ************************************/
    Route::prefix('approvals')->name('approvals.')->group(function () {

        Route::get('/', [BuyerApprovalController::class,'index'])->name('index');
        Route::post('/feedback', [BuyerApprovalController::class, 'approvalFeedback'])->name('approvalFeedback');
        Route::post('/revoke_feedback', [BuyerApprovalController::class, 'revokeApprovalFeedback'])->name('revokeApprovalFeedback');
        Route::post('/quote_feedback', [BuyerApprovalController::class, 'triggerQuoteCollapse'])->name('triggerQuoteCollapse');
        Route::post('/approval_reason', [BuyerApprovalController::class, 'insertApprovalFeedback'])->name('insertApprovalFeedback');
        Route::post('/approval_otp', [BuyerApprovalController::class, 'getApprovalOtp'])->name('getApprovalOtp');
        Route::post('/getotphtml', [UserController::class, 'getOtpModel'])->name('getotphtml');

    });
    /**********************end: Approvals tab permission ************************************/

    /****************Begin: RFN list - Vrutika *********************/
    Route::prefix('rfn')->name('rfn.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Buyer\RFN\RfnController::class, 'index'])->name('index');
        Route::get('/global', [\App\Http\Controllers\Buyer\RFN\GlobalRfnController::class, 'index'])->name('global');
    });
    /***************end: RFN list ************************/

    Route::prefix('buyer')->name('buyer.')->group(function () {


        /**********************begin: User Permission Ajax ************************************/

        Route::POST('/permission', [App\Http\Controllers\Buyer\Permissions\PermissionController::class,'hasPermission'])->name('has.permission')->middleware('auth.ajax');
        Route::post('/permission/dependent',[App\Http\Controllers\PermissionsGroupController::class,'getDependencyPermissions'])->name('has.dependent.permission')->middleware('auth.ajax');
        /**********************end: User Permission Ajax ************************************/

    });



    //User Notification Routes
    Route::get('/notification', [BuyerNotificationController::class, 'index'])->name('notification');
    Route::get('/buyer-notification', [BuyerNotificationController::class, 'buyerNotification'])->name('get-buyer-notification-count-ajax');
    Route::get('/get-side-buyer-count/{count}', [BuyerNotificationController::class, 'buyerSideCountNotification'])->name('get-side-buyer-count-ajax');
    Route::get('/buyer-mark-as-all', [BuyerNotificationController::class, 'markAsAll'])->name('buyer-mark-as-all-ajax');
    Route::post('/admin/buyer-notifications-filter-data', [BuyerNotificationController::class, 'getBuyerNotificationFilterData'])->name('get-buyer-notification-filter-data-ajax');
    Route::get('/get-side-buyer-indicator/{count}', [BuyerNotificationController::class, 'buyerSideIndicatorNotification'])->name('get-side-buyer-indicator-ajax');
    Route::get('/buyer-mark-as-single/{id}', [BuyerNotificationController::class, 'buyerSingleMark'])->name('buyer-mark-as-single-ajax');
    //get-buyer-notification-filter-data-ajax

    //Chat Route
    Route::post('/group-chat-list-ajax', [ChatController::class, 'groupChatList'])->name('group-chat-list-ajax');
    Route::post('/new-chat-list-ajax', [ChatController::class, 'getNewChatList'])->name('get-new-chat-list-ajax');
    Route::post('/new-chat-create', [ChatController::class, 'createNewChatView'])->name('new-chat-create-view');
    Route::post('/chat-create', [ChatController::class, 'createChat'])->name('chat-create-ajax');
    Route::post('/rfq-product-details', [ChatController::class, 'getRfqproductDetails'])->name('rfq-product-details-ajax');
    Route::post('/group-chattype-count', [ChatController::class, 'getGroupChattypeCount'])->name('group-chattype-count-ajax');
    Route::post('/get-search-data', [ChatController::class, 'getSearchData'])->name('get-search-chat-view-ajax');
    Route::post('/get-new-search-data', [ChatController::class, 'getNewSearchData'])->name('get-new-search-chat-view-ajax');
    Route::post('/get-chat-quote-history', [ChatController::class, 'getChatQuoteHistoryData'])->name('get-chat-quote-history-ajax');
    //Route::post('/chat-messages-list', [ChatController::class, 'getChatMessagesList'])->name('chat-messages-list-ajax');
    // support chat Route
    Route::post('/support-chat-create-view', [ChatController::class, 'supportChatList'])->name('support-chat-create-view');
    Route::post('/support-chat-create', [ChatController::class, 'createSupportChat'])->name('support-chat-create-ajax');
    //Preferred Suppliers Routes
    Route::post('/add-preferred-supplier', [PreferredSuppliersController::class, 'addPreferredSuppliers'])->name('add-preferred-supplier-ajax');
    Route::post('/get-selected-preferred-suppliers', [PreferredSuppliersController::class, 'getAllPreferredSuppliers'])->name('get-selected-preferred-suppliers-ajax');
    Route::get('/get-preferred-suppliers-by-category/{categoryId}', [PreferredSuppliersController::class, 'getPreferredSuppliersByCategory'])->name('get-preferred-suppliers-by-category-ajax');
    Route::post('/update-preferred-supplier-status',[PreferredSuppliersController::class,'updatePreferredSupplierStatus'])->name('update-preferred-supplier-status-ajax');
    Route::get('/delete-preferred-supplier/{id}', [PreferredSuppliersController::class, 'deletePreferredSupplier'])->name('delete-preferred-supplier-ajax');
    Route::get('/get-preferred-suppliers-by-rfqId/{rfqId}/{preferredCategoryId}', [PreferredSuppliersController::class, 'getPreferredSuppliersByRfqId'])->name('get-preferred-suppliers-by-rfqId-ajax');

});
// End Front Routes //

Route::group(['middleware' => ['auth']], function (){

    Route::get('/{username}',[\App\Http\Controllers\Supplier\SupplierController::class, 'supplierProfile'])->name('supplier.professional.profile');

    /**begin: Search - Product */
    Route::post('/search-product',[ProductController::class,'searchProduct'])->name('search.product')->middleware('auth.ajax');
    /**end: Search - Product */

});

// End Front Routes
//Testing

//extra test routes

