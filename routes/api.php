<?php

use App\Models\XenSubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\XenditController;
use App\Http\Controllers\API\UserApiAuthController;
use App\Http\Controllers\API\CommonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login',[UserApiAuthController::class,'login']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('get-invoice', [XenditController::class, 'getInvoice']);
Route::post('get-disbursement', [XenditController::class, 'getDisbursements']);
Route::get('success-invoice-payment/{id}', [XenditController::class, 'successInvoicePayment']);
Route::get('fail-invoice-payment/{id}', [XenditController::class, 'failInvoicePayment']);
Route::post('invoice-callback', [XenditController::class, 'invoiceCallback']);
Route::post('fva-callback', [XenditController::class, 'fvaCallback']);
Route::post('fva-paid-callback', [XenditController::class, 'fvaPaidCallback']);
Route::post('xp-callback', [XenditController::class, 'xenPlatformCallback']);
Route::post('pl-callback', [XenditController::class, 'payLaterCallback']);
Route::post('dis-callback', [XenditController::class, 'disbursementCallback']);
Route::get('get-callback-file/{id}', [XenditController::class, 'getCallback']);
Route::get('get-files', [XenditController::class, 'getFiles']);
Route::get('create-fee-rule', [XenditController::class, 'createFeeRule']);
Route::get('create-disbursements', [XenditController::class, 'createDisbursements']);
Route::get('create-batchDisburesments', [XenditController::class, 'batchDisburesments']);
Route::post('get-XenAccount', [XenditController::class, 'getXenAccount']);
Route::get('xenTransfer', [XenditController::class, 'xenTransfer']);

Route::post('get-invoice-callback', [XenditController::class, 'regerateInvoiceCallback']);

Route::post('set-callback-urls', [XenditController::class, 'setCallbackUrls']);


Route::post('test', function (Request $request){

    $quoteEndOfDay   = strtotime("tomorrow",strtotime('2022-01-30')) - 3;
    $invoiceDuration = $quoteEndOfDay-(strtotime('now')+20);
    echo $invoiceDuration;
    /*echo date_default_timezone_get();
    echo '<br>';
    echo date('Y-m-d H:i:s T');
    echo '<br>-------------------<br>';
    $dt = new DateTimeImmutable($request->time, new DateTimeZone('UTC'));

    echo $dt->setTimezone(new DateTimeZone('Asia/Kolkata'))->format('Y-m-d H:i:s T');
    echo '<br>';
// Format $dt in Madrid timezone
    echo $dt->setTimezone(new DateTimeZone('Asia/Jakarta'))->format('Y-m-d H:i:s T');
    echo '<br>';
// Format $dt in Local server timezone
    echo $dt->setTimezone((new DateTime())->getTimezone())->format('Y-m-d H:i:s T');
    echo '<br>-----------------------<br>';
    echo date_default_timezone_get();
    echo '<br>';
    echo date('Y-m-d H:i:s T');
    echo '<br>';*/
});

Route::get('/get-supplier-xenaccount/{id}', function ($id){
    $supplier = \App\Models\Supplier::where('id',$id)->first(['id','name','email','mobile','contact_person_name','contact_person_email','xen_platform_id']);
    return response()->json($supplier);
});

Route::post('/set-supplier-xenaccount', function (Request $request){
    $supplier = \App\Models\Supplier::where('id',$request->id)->first(['id','name','email','mobile','contact_person_name','contact_person_email','xen_platform_id']);
    $supplier->xen_platform_id = $request->xen_platform_id??'';
    $supplier->save();
    $xendit = new XenditController;
    $xenAccount = $xendit->getAccount($supplier->xen_platform_id);
    $xenAccount['supplier_id'] = $supplier->id;
    XenSubAccount::createOrUpdateXenAccount($xenAccount);
    return response()->json($supplier);
});

Route::post('update-order-status-quincus', [CommonController::class, 'updateOrderStatusQuincus']);

Route::post('quincus-shipment-tracking', [CommonController::class, 'QuincusShipmentTracking']);

Route::post('check-price', [CommonController::class, 'CheckPrice']);


