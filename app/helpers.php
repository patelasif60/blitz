<?php

use App\Models\BuyerNotification;
use App\Models\LoanApplication;
use App\Models\LoanApply;
use App\Models\LoanProviderCharges;
use App\Models\MongoDB\ChatGroup;
use App\Models\MongoDB\GroupChatMember;
use App\Models\CustomRoles;
use App\Models\ModelHasCustomPermission;
use App\Models\Notification;
use App\Models\City;
use App\Models\Country;
use App\Models\CustomPermission;
use App\Models\LogisticsApiResponse;
use App\Models\Order;
use App\Models\OrderItemStatus;
use App\Models\OrderItemTracks;
use App\Models\OrderStatus;
use App\Models\OrderTrack;
use App\Models\OrderTransactions;
use App\Models\OtherCharge;
use App\Models\PermissionsGroup;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\Role;
use App\Models\State;
use App\Models\SystemRole;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserAddresse;
use App\Models\UserCompanies;
use App\Models\UserSupplier;
use App\Models\XenditCommisionFee;
use App\Models\ModuleInputField;
use \Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Jorenvh\Share\Share;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use App\Models\MongoDB\SupportChatMember;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

if (!function_exists('preDump')){
    function preDump($q='',$isDie=1){
        echo "<pre>";
        print_r($q);
        if ($isDie){
            echo '</pre>';
            die;
        }
    }
}

if (!function_exists('changeDateTimeFormat')){
    function changeDateTimeFormat($date,$format=''){
        if (empty($format)){
            $dateTimeFormat = json_decode(getSettingValueByKey('system_date_time_format'),true);
            $format = $dateTimeFormat['date'].' '.$dateTimeFormat['time'];
        }
        $old_date_timestamp = strtotime($date);
        return date($format,$old_date_timestamp);
    }
}

if (!function_exists('changeDateFormat')){
    function changeDateFormat($date,$format=''){
        if (empty($format)){
            $dateTimeFormat = json_decode(getSettingValueByKey('system_date_time_format'),true);
            $format = $dateTimeFormat['date'];
        }
        $old_date_timestamp = strtotime($date);
        return date($format,$old_date_timestamp);
    }
}
if (!function_exists('newChangeDateTimeFormat')){
    function newChangeDateTimeFormat($date,$format=''){
        $type = json_decode($format);
        $types = $type->date.' '.$type->time;
        $old_date_timestamp = strtotime($date);
        return date($types,$old_date_timestamp);
    }
}

if (!function_exists('newChangeDateFormat')){
    function newChangeDateFormat($date,$format=''){
        $old_date_timestamp = strtotime($date);
        return date($format,$old_date_timestamp);
    }
}

if (!function_exists('changeTimeFormat')){
    function changeTimeFormat($date,$format=''){
        if (empty($format)){
            $dateTimeFormat = json_decode(getSettingValueByKey('system_date_time_format'),true);
            $format = $dateTimeFormat['time'];
        }
        $old_date_timestamp = strtotime($date);
        return date($format,$old_date_timestamp);
    }
}

if (!function_exists('getDaysByDates')){
    function getDaysByDates($startDate='',$endDate=''){
        if (empty($startDate)){
            $startDate = date('Y-m-d');
        }elseif (empty($endDate)){
            $endDate = date('Y-m-d');
        }
        $date1=date_create($startDate);
        $date2=date_create($endDate);
        if ($date1 <= $date2) {
            $interval = date_diff($date1, $date2);
            return $interval->format('%a');
        }else{//if start date is greater then end date
            return -1;
        }
    }
}

if (!function_exists('utcToLocalTime')){
    function utcToLocalTime($datetime){
        $dt = new DateTimeImmutable($datetime, new DateTimeZone('UTC'));
        return $dt->setTimezone((new DateTime())->getTimezone())->format('Y-m-d H:i:s');
    }
}

if (!function_exists('getSettingValueByKey')){
    function getSettingValueByKey($key){
        $result = DB::table('settings')->where(['key'=>$key,'status'=>1,'is_deleted'=>0])->first(['value']);
        if (isset($result->value)){
            return $result->value;
        }
        return null;
    }
}

if (!function_exists('objToArr')){
    function objToArr($data){
        return json_decode(json_encode($data),true);
    }
}

if (!function_exists('getRecordsByCondition')){
    function getRecordsByCondition($table,$where=[],$select='*',$isSingleColumn=0,$orderBy='1'){
        $result = DB::table($table)->where($where)->selectRaw($select)->orderByRaw($orderBy)->first();
        if ($isSingleColumn){
            return isset($result->$select)?$result->$select:'';
        }elseif (!empty($result)) {
            return objToArr($result);//return in array
        }
        return [];
    }
}

if (!function_exists('getAllRecordsByCondition')){
    function getAllRecordsByCondition($table,$where=[],$select='*',$orderBy='1'){
        $result = DB::table($table)->where($where)->selectRaw($select)->orderByRaw($orderBy)->get();
        if (!empty($result)) {
            return objToArr($result);//return in array
        }
        return [];
    }
}

if (!function_exists('getClientById')){
    function getClientById($id,$select='',$where=[]){
        if (empty($select)){
            $select = 'id,firstname,lastname,email,mobile,profile_pic,role_id,language_id,currency_id,is_active,designation,department,is_delete';//common column
        }
        return getRecordsByCondition('users',array_merge(['id'=>$id],$where),$select);
    }
}

if (!function_exists('getSupplierById')){
    function getSupplierById($id,$select='',$where=[]){
        if (empty($select)){//,commercialCondition
            $select = 'id,name,email,mobile,website,logo,description,address,contact_person_name,contact_person_email,contact_person_phone,catalog,pricing,product,accepted_terms,status,interested_in,is_deleted,updated_by,deleted_by';//common column
        }
        return getRecordsByCondition('suppliers',array_merge(['id'=>$id],$where),$select);
    }
}

if (!function_exists('getXenPlatformIdBySupplierId')) {
    function getXenPlatformIdBySupplierId($id)
    {
        return getRecordsByCondition('suppliers',['id'=>$id],'xen_platform_id',1);
    }
}

if (!function_exists('getOrderProductDetails')){

    function getOrderProductDetails($where=[],$select='',$inArray=0){
        if (empty($select)){
            $select = 'products.name as product_name,
            rfq_products.product_description as product_description,sub_categories.name as sub_category_name,categories.name as category_name';
        }
        $orders = DB::table('orders')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('products', 'quotes.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where($where)
            ->orderBy('orders.id', 'desc')
            ->selectRaw($select)
            ->first();
        if ($inArray){//return in array
            return objToArr($orders);
        }
        return $orders;
    }

}

if (!function_exists('setInvoice')){
    function setInvoice($order){
        $supplierXenId = $order->supplier()->value('xen_platform_id');
        $customer = objToArr($order->user()->first(['firstname','lastname','email','phone_code','mobile']));
        $xenditInvoiceSettings = json_decode(getSettingValueByKey('xendit_invoice'),true);
        $external_id = $order->order_number;
        $invoiceDuration = 0;
        if ($order->is_credit) {
            $invoiceDuration = 86400 * (int)($order->orderCreditDay()->value('approved_days')+$xenditInvoiceSettings['credit_invoice_extra_days']);//credit days + 7days
        }else {
            $cashInvoiceValidHoursArr = $xenditInvoiceSettings['cash_invoice_valid_hours'];
            $generatedLinkCount = $order->orderTransactions()->count();//count = index

            if (isset($cashInvoiceValidHoursArr[$generatedLinkCount])) {
                $invoiceValidHours = $cashInvoiceValidHoursArr[$generatedLinkCount];//get index wise invoice valid hours
                $invoiceDuration = $invoiceValidHours * 3600;//default invoice duration
            }
            if ($xenditInvoiceSettings['max_invoice_generate']<=$generatedLinkCount){
                if (Auth::user()->role_id==1){//for admin
                    $invoiceDuration = 86400 * (int)$xenditInvoiceSettings['admin_create_invoice_valid_hours'];
                    $external_id = $order->order_number.'-A'.($generatedLinkCount+1);
                }else {
                    return ['status' => false, 'message' => __('order.invoice_generate_limit_reached')];
                }
            }elseif ($generatedLinkCount>0) {
                $external_id = $order->order_number.'-'.($generatedLinkCount+1);
            }
        }
        $product = '';
        $quoteItems = $order->quote->quoteItems()->get(['rfq_product_id','product_quantity','price_unit']);
        foreach ($quoteItems as $quoteItem) {
            $product .= '|'.get_product_name_by_id($quoteItem->rfq_product_id,1).' - '.$quoteItem->product_quantity.' '.get_unit_name($quoteItem->price_unit);
        }
        $descr = substr($product,1);
        $params = [
            'external_id' => $external_id,
            'payer_email' => $customer['email'],
            'description' => $descr,
            'amount' => $order->payment_amount,
            'should_send_email' => true,
            'customer' => [
                'given_names' => $customer['firstname'].' '.$customer['lastname'],
                'email' => $customer['email'],
                'mobile_number' => $customer['phone_code'].$customer['mobile'],
                //'address' => ['110','Afrin Flats','Nawabwada','Raopura'],
            ],
            'invoice_duration' => $invoiceDuration,//86400 sec = 1 day
            'success_redirect_url' => url('success-invoice-payment/'.$order->id),
            'failure_redirect_url' => url('fail-invoice-payment/'.$order->id),
            'reminder_time_unit' => $xenditInvoiceSettings['invoice_reminder_time_unit'],
            'reminder_time' => (int)$xenditInvoiceSettings['invoice_reminder_time'],
        ];
        if ($supplierXenId){
            //payment goes to the supplier
            $params['for-user-id'] = $supplierXenId;
        }

        return $params;
    }
}

if (!function_exists('setGroupInvoice')){
    function setGroupInvoice($group,$quote,$groupFinalAmount){
        $rfqId = $quote->rfq_id;
        $quoteId = $quote->id;
        $supplierXenId = $group->groupSuppler->supplier->xen_platform_id;
        $customer = $group->groupMember()->where('rfq_id',$rfqId)->first()->user()->first(['firstname','lastname','email','phone_code','mobile']);
        $xenditInvoiceSettings = json_decode(getSettingValueByKey('xendit_group_invoice'),true);
        $external_id = $quote->quote_number;
        $invoiceDuration = $xenditInvoiceSettings['invoice_valid_hours'] * 3600;//default invoice duration (3600 = 1 hours)
        $generatedLinkCount = $quote->groupTransactions()->count();//count = index
        if ($generatedLinkCount>0) {
            $external_id = $quote->quote_number.'-'.($generatedLinkCount+1);
        }
        $quoteItem = $quote->quoteItems()->first(['rfq_product_id','product_quantity','price_unit']);
        $product = get_product_name_by_id($quoteItem->rfq_product_id,1).' - '.$quoteItem->product_quantity.' '.get_unit_name($quoteItem->price_unit);

        $params = [
            'external_id' => $external_id,
            'payer_email' => $customer['email'],
            'description' => $product,
            'amount' => $groupFinalAmount['final_amount'],
            'should_send_email' => true,
            'customer' => [
                'given_names' => $customer['firstname'].' '.$customer['lastname'],
                'email' => $customer['email'],
                'mobile_number' => $customer['phone_code'].$customer['mobile'],
                //'address' => ['110','Afrin Flats','Nawabwada','Raopura'],
            ],
            'invoice_duration' => $invoiceDuration,//86400 sec = 1 day
            'success_redirect_url' => url('success-group-payment/'.$quoteId),
            'failure_redirect_url' => url('fail-group-payment/'.$quoteId),
            'reminder_time_unit' => $xenditInvoiceSettings['invoice_reminder_time_unit'],
            'reminder_time' => (int)$xenditInvoiceSettings['invoice_reminder_time'],
        ];
        if ($supplierXenId){
            //payment goes to the supplier
            $params['for-user-id'] = $supplierXenId;
        }

        return $params;
    }
}

if (!function_exists('getSupplierTransactionFees')) {
    function getSupplierTransactionFees($lastSupplierTransactionDate)
    {
        $supplierTransactionFees = 0;
        if (empty($lastSupplierTransactionDate) || (!empty($lastSupplierTransactionDate) && date("Y-m",strtotime($lastSupplierTransactionDate))!==date("Y-m"))){
            $supplierTransactionFees = (float)getSettingValueByKey('supplier_transaction_charge');
        }

        return $supplierTransactionFees;
    }
}
/*
 * set supplier disbursement parameters for xendit
 * */
if (!function_exists('setDisbursement')) {
    function setDisbursement($order,$payableAmount)
    {
        $customerCompanyName = $order->user->company->name;
        $supplier = $order->supplier()->first(['name','contact_person_email','alternate_email','xen_platform_id']);
        $supplierBank = $order->supplier->supplierBank()->where('is_primary',1)->first(['bank_id','bank_account_name','bank_account_number']);
        $bankCode = $supplierBank->bankDetail()->value('code');
        $supplierBank = objToArr($supplierBank);
        if (empty($supplierBank) || empty($bankCode)){
            return false;
        }
        $params = [
            'external_id' => 'DISB-'.$order->id,
            'amount' => round($payableAmount),
            'bank_code' => $bankCode,
            'account_holder_name' => $supplierBank['bank_account_name'],
            'account_number' => $supplierBank['bank_account_number'],
            'description' => 'B'.$order->id.' '.$customerCompanyName,
            'email_to'=> [$supplier['contact_person_email']],
            //'email_bcc'=> ['munirbcc@yopmail.com']
        ];
        if ($order->group_id){
            $totalDisbursementCount = $order->disbursements()->where('status','COMPLETED')->whereNull('buyer_user_id')->count();
            $totalDisbursementCount = $totalDisbursementCount+1;
            $params['external_id'] = 'GDISB-'.$order->group_id.'/O'.$order->id.'/'.$totalDisbursementCount;
            $params['description'] = 'BG'.$order->group_id.'/'.$totalDisbursementCount.' '.$order->group->name;
        }
        if (!empty($supplier['alternate_email'])){
            $params['email_cc'] = [$supplier['alternate_email']];
        }
        if (!empty($supplier['xen_platform_id'])){
            //payment goes to the supplier
            $params['for-user-id'] = $supplier['xen_platform_id'];
        }
        return $params;
    }
}
/*
 * set buyer disbursement parameters for xendit
 * */
if (!function_exists('setBuyerDisbursement')) {
    function setBuyerDisbursement($order,$payableAmount)
    {
        $buyer = $order->user()->first(['id','firstname','lastname','email','phone_code','mobile']);
        $buyerCompany = $buyer->company()->first(['name','alternative_email']);
        $customerCompanyName = $buyerCompany->name;
        $supplier = $order->supplier()->first(['xen_platform_id']);

        $buyerBank = $buyer->buyerBank()->where('is_primary',1)->first(['bank_id','account_holder_name','account_number']);
        $bankCode = $buyerBank->AvailableBanks()->value('code');

        if (empty($buyerBank) || empty($bankCode) || empty($supplier->xen_platform_id)){
            return false;
        }
        $params = [
            'external_id' => 'GBRDISB-'.$order->group_id.'/O'.$order->id,
            'amount' => round($payableAmount),
            'bank_code' => $bankCode,
            'account_holder_name' => $buyerBank->account_holder_name,
            'account_number' => $buyerBank->account_number,
            'description' => 'BG'.$order->group_id.'/O'.$order->id.' '.$customerCompanyName,
            'email_to'=> [$buyer->email],
            //'email_bcc'=> ['munirbcc@yopmail.com']
        ];

        if (!empty($buyerCompany->alternative_email)){
            $params['email_cc'] = [$buyerCompany->alternative_email];
        }
        //payment goes to the supplier
        $params['for-user-id'] = $supplier->xen_platform_id;

        return $params;
    }
}

if (!function_exists('genrateComanyShortName')){
    function genrateComanyShortName($name){
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            if (count($words) == 2){
                $comapny_name= strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
            } else {
                $comapny_name = "";
                foreach ($words as $w) {
                    $comapny_name .= strtoupper($w[0]);
                }
            }
        } else {
            preg_match_all('#([A-Z]+)#', $name, $capitals);
            if (count($capitals[1]) >= 2) {
                $comapny_name = substr(implode('', $capitals[1]), 0, count($capitals[1]));
            } else {
                $comapny_name = strtoupper(substr($name, 0, 2));
            }
        }
        return $comapny_name;
    }
}

if (!function_exists('calculatePriceForSupplier')){
    function calculatePriceForSupplier($id, $finalAmount, $tax){
        $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))->where('quotes_charges_with_amounts.quote_id', $id)->where('quotes_charges_with_amounts.charge_type', 0)->orderBy('created_at', 'desc')->get();
        $finalAmount = $finalAmount;
        $discount = 0;
        foreach ($quotes_charges_with_amounts as $charges){
            if ($charges->charge_name != 'Discount') {
                if ($charges->addition_substraction == 0) {
                    $finalAmount = $finalAmount - $charges->charge_amount;
                } else {
                    $finalAmount = $finalAmount + $charges->charge_amount;
                }
            } else {
                $discount = $charges->charge_amount;
            }
        }
        $totalAmount = $finalAmount - $discount;
        $taxamount = ($totalAmount * $tax) / 100;
        return $payamount = $totalAmount + $taxamount;
    }
}

if (!function_exists('getFileExtension')) {
    function getFileExtension($fileName,$withDot=1){
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        return $withDot?('.'.$ext):$ext;
    }
}

if (!function_exists('getFileName')) {
    function getFileName($fileName){
        return pathinfo($fileName, PATHINFO_FILENAME);
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile($filepath){
        if (File::exists($filepath)) {
            File::delete($filepath);
        }
    }
}


if (!function_exists('getSupplierByLoginId')){
    function getSupplierByLoginId($id,$select='',$where=[]){
        $supplierId = getRecordsByCondition('user_suppliers', ['user_id' => $id], 'supplier_id', 1);
        if (empty($select)) {
            return $supplierId;
        }else{
            return getRecordsByCondition('suppliers',array_merge(['id'=>$supplierId],$where),$select);
        }
    }
}

if (!function_exists('getSupplierIdByUser')){
    function getSupplierIdByUser($id,$select='',$where=[]){
        $supplierId = getRecordsByCondition('user_suppliers', ['user_id' => $id], 'supplier_id', 1);
        if (empty($select)) {
            return $supplierId;
        }else{
            return getRecordsByCondition('suppliers',array_merge(['id'=>$supplierId],$where),$select);
        }
    }
}

if (!function_exists('getUserIdBySupplier')){
    function getUserIdBySupplier($id,$select='',$where=[]){
        $userId = getRecordsByCondition('user_suppliers', ['supplier_id' => $id], 'user_id', 1);
        if (empty($select)) {
            return $userId;
        }
        return 0;
    }
}

if (!function_exists('checkDuplication')) {
    function checkDuplication($db_name,$data,$data1 = '',$data2 = '',$data3 = '',$data4 = '') {
        //dd($db_name . ', '. $column_name. ', '.$column_name1);
        if($db_name == "categories") {
            $dataExist = DB::table($db_name)->where('name',$data)->first();
            if(!empty($dataExist)) {
                return false;
            } else {
                return true;
            }
        } elseif($db_name == "sub_categories") {
            $dataExist = DB::table($db_name)->where('name',$data)->where('category_id',$data1)->first();
            if(!empty($dataExist)) {
                return false;
            } else {
                return true;
            }
        } elseif($db_name == "brands") {
            $dataExist = DB::table($db_name)->where('name',$data)->first();
            if(!empty($dataExist)) {
                return false;
            } else {
                return true;
            }
        } elseif($db_name == "units") {
            $dataExist = DB::table($db_name)->where('name',$data)->first();
            if(!empty($dataExist)) {
                return false;
            } else {
                return true;
            }
        } elseif($db_name == "other_charges") {
            $dataExist = DB::table($db_name)->where('name',$data)->where('charges_value',$data1)->where('type',$data2)->where('charges_type',$data3)->where('addition_substraction',$data4)->where('is_deleted',0)->first();
            if(!empty($dataExist)) {
                return false;
            } else {
                return true;
            }
        }elseif($db_name == "products") {
            $dataExist = DB::table($db_name)->where('name',$data)->where('subcategory_id',$data1)->first();
            if(!empty($dataExist)) {
                return false;
            } else {
                return true;
            }
        }
    }
}

if (!function_exists('getBulkOrderNumber')) {
    function getBulkOrderNumber($orderIds){
        sort($orderIds);
        $bulkOrderNumber = '';
        foreach ($orderIds as $id){
            $bulkOrderNumber .= "|BORN-".$id;
        }
        return substr($bulkOrderNumber,1);
    }
}

if (!function_exists('getBulkOrderDiscount')) {
    function getBulkOrderDiscount($orderId){
        return (float)DB::table('bulk_order_payments')
            ->join('bulk_payments', 'bulk_payments.id', '=', 'bulk_order_payments.bulk_payment_id')
            ->join('order_transactions','order_transactions.id','=','bulk_payments.order_transaction_id')
            ->where(['bulk_order_payments.order_id'=> $orderId,'order_transactions.status'=>'PAID'])
            ->pluck('bulk_order_payments.discounted_amount')->first();
    }
}

if (!function_exists('get_quote_item_by_id')){
    function get_quote_item_by_id($id, $quote_id){
        $quote_item = QuoteItem::where('rfq_product_id',$id)->where('quote_id', $quote_id)->first();
        return $quote_item??[];
    }
}

if (!function_exists('get_product_name_by_id')){
    function get_product_name_by_id($id, $withDescription=0){
        $product_name = RfqProduct::where('id', $id)->first(['category', 'sub_category', 'product', 'product_description']);
        if (!empty($product_name)) {
            if ($withDescription == 0) {
                return $product_name->category . ' - ' . $product_name->sub_category . ' - ' . $product_name->product;
            } else {
                return $product_name->category . ' - ' . $product_name->sub_category . ' - ' . $product_name->product .' - '. $product_name->product_description;
            }
        }
        return '-';
    }
}

if (!function_exists('get_product_desc_by_id')){
    function get_product_desc_by_id($id){
        $product_name = RfqProduct::where('id', $id)->first(['product_description']);
        return $product_name->product_description;
    }
}

if (!function_exists('get_unit_name')){
    function get_unit_name($id){
        $unit = Unit::where('id', $id)->first();

        $name = !empty($unit) ? $unit->name : '';

        return $name;
    }
}

if (!function_exists('getCompanyByUserId')){
    function getCompanyByUserId($id,$onlyCompanyName=1){
        $company = DB::table('companies')
                    ->join('user_companies', 'user_companies.company_id', 'companies.id')
                    ->where('user_companies.user_id',$id)
                    ->first();
        if (!empty($company)){
            if ($onlyCompanyName){
                return $company->name;
            }
            return $company;
        }
        return '';
    }
}

if (!function_exists('paymentView')) {
    function paymentView($order)
    {
        $orderId = $order->id;
        if ($order->order_status==7){
            $statusCheck = OrderTrack::where('order_id', $orderId)->where('status_id', 7)->orderBy('id', 'desc')->first();

        }else{
            $statusCheck = OrderTrack::where('order_id', $orderId)->where('status_id', 3)->orderBy('id', 'desc')->first();
        }
        $check = !empty($statusCheck) ? 'checked="checked"' : '';
        $html = '<div class="d-table-cell align-top" style="padding-left:0.5rem">
                    <div class="form-check form-switch py-0 mt-0 mb-0" style="min-height: auto;">';
        if (auth()->user()->role_id == 1) {
            $transaction = OrderTransactions::where('order_id', $orderId)->latest()->first(['status','invoice_url']);
            if (!empty($transaction)) {
                $html .= '<img class="hidden" id="invoice-loader" height="16px" src="' . URL('front-assets/images/icons/timer.gif') . '" title="Loading">';
                if ($transaction->status == 'PENDING' && in_array($order->order_status, [2, 8, 10])) {
                    $html .= '<a class="text-dark bg_icon" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="'.__('admin.pay_now').'" aria-label="'.__('admin.pay_now').'" href="'.$transaction->invoice_url.'" target="_blank" style="cursor: pointer"><img src="'.URL::asset('front-assets/images/icons/credit-card.png').'" alt="paynow" srcset=""></a>
                    <a class="bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="'.__('order.cancel_invoice').'" aria-label="'.__('order.cancel_invoice').'" style="cursor: pointer" href="javascript:void(0);" onclick="cancelInvoice($(this),\''.Crypt::encrypt($orderId).'\')"><img src="'.URL::asset('assets/icons/cancelinvoice.png').'" alt="cancelinvoice" srcset=""></a>';
                } elseif ($transaction->status == 'EXPIRED' && in_array($order->order_status, [2, 8, 10])) {
                    $html .= '<a class="bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="'.__('admin.generate_link').'" aria-label="'.__('admin.generate_link').'" onclick="generatePayLink($(this),' . $order->id . ')" href="javascript:void(0);"><img src="'.URL::asset('assets/icons/generate_pay_link.png').'" alt="generate_pay_link" srcset=""></a>';
                }
            }
        }else{
            //$html .= '<input class="form-check-input" type="checkbox" data-order-id="' . $orderId . '" id="paymentDone" name="paymentDone" ' . $check . ' disabled="">';
        }
        $html .= '</div>
                </div>';
        return $html;
    }
}

if (!function_exists('underQcView')) {
    function underQcView($orderItemId)
    {
        $radio1 = '';
        $radio2 = '';
        $statusCheck = OrderItemTracks::where('order_item_id', $orderItemId)->whereIn('status_id', [7, 8])->orderBy('id', 'desc')->first();
        if (isset($statusCheck) && $statusCheck['status_id'] == 8) {//QC Pass
            $radio1 = 'checked';
        } else if (isset($statusCheck) && $statusCheck['status_id'] == 7) {//QC Failed
            $radio2 = 'checked';
        }
        return '<div class="ms-1 tracking d-table-cell align-top" style="">
                    <div class="d-table tracking w-auto">
                        <div class="form-check form-check-inline  my-0 tracking d-table-cell"
                            style="min-height: auto; width: 60px; ">
                            <input
                                class="form-check-input  radio-custom qcStatusUpdated" style="margin-left: 1em !important;"
                                type="radio" name="qcStatusOptions'.$orderItemId.'"
                                id="qcpass'.$orderItemId.'" data-orderitem-id="'.$orderItemId.'" value="1"
                                 ' . $radio1 . ' disabled>
                            <label
                                class="form-check-label radio-custom-label ps-1 ms-0"
                                for="qcpass'.$orderItemId.'1">'.__('order.pass').'</label>
                        </div>
                        <div class="form-check form-check-inline  my-0  tracking d-table-cell"
                            style="min-height: auto;">
                            <input
                                class="form-check-input radio-custom qcStatusUpdated" style="margin-left: 1em !important;"
                                type="radio" name="qcStatusOptions'.$orderItemId.'"
                                id="qcfail'.$orderItemId.'" data-orderitem-id="'.$orderItemId.'" value="1"
                                 ' . $radio2 . ' disabled>
                            <label
                                class="form-check-label radio-custom-label ps-1 ms-0"
                                for="qcfail'.$orderItemId.'0">'.__('order.fail').'</label>
                        </div>
                    </div>
                </div>';
    }
}

if (!function_exists('get_quote_items_order')){
    function get_quote_items_order($quote_id){
        return QuoteItem::where('quote_id', $quote_id)->join('units', 'quote_items.price_unit', '=', 'units.id')->get(['quote_items.*','units.name as unit_name']);
    }
}

if (!function_exists('get_common_order_info')){
    function get_common_order_info($order){
        $rfq = $order->rfq()->first();
        $quote = $order->quote()->first();
        $buyer = $order->user()->first();
        $buyer_company = $order->companyDetails()->first();
        $supplier = $order->supplier()->first();
        $disbursement = $order->disbursement()->where('status','FAILED')->first();
        $orderPo = $order->orderPo()->first();
        $orderCreditDay = $order->orderCreditDay()->first();
        $orderItems = $order->orderItems()->get();
        $orderStatus = $order->orderStatus()->first();
        return ['rfq' => $rfq, 'quote' => $quote, 'buyer' => $buyer, 'buyer_company' => $buyer_company, 'supplier' => $supplier, 'disbursement' => $disbursement, 'orderPo' => $orderPo, 'orderCreditDay' => $orderCreditDay, 'orderItems' => $orderItems, 'orderStatus' => $orderStatus];
    }
}

if (!function_exists('get_order_sub_status')){
    function get_order_sub_status($order_item_id){
        $inputs['order_item_id'] = $order_item_id;
        $orderStatus = DB::table('order_item_status')
            ->leftJoin('order_item_tracks', function ($join) use ($inputs) {
                $join->on('order_item_status.id', '=', 'order_item_tracks.status_id')
                    ->where(['order_item_tracks.order_item_id'=> $inputs['order_item_id']]);
            })
            ->where('order_item_status.status', 1)
            ->groupBy('order_item_status.name')
            ->orderBy('order_item_status.sort', 'asc')
            ->get(['order_item_status.id as order_item_status_id', 'order_item_status.name as status_name', 'order_item_tracks.created_at', 'order_item_tracks.id as order_track_id']);
        return $orderStatus;
    }
}

if (!function_exists('getOrderItemStatusNameById')){
    function getOrderItemStatusNameById($id){
        $name =  DB::table('order_item_status')->where('id', $id)->first();
        return !empty($name)? $name->name : 'Not Ready At';
    }
}

if (!function_exists('getOrderStatusNameAndOrderLetter')){
    function getOrderStatusNameAndOrderLetter($orderId, $productId){
        $statusId = DB::table('orders')->join('order_items', 'order_items.order_id', '=', 'orders.id')->where(['order_items.order_id' => $orderId, 'order_items.rfq_product_id' => $productId])->first(['order_items.id','order_items.order_item_status_id','order_items.order_latter']);
        $orderStatus = ' - ';
        if (!empty($statusId->order_item_status_id)){
            $orderStatus = DB::table('order_item_status')->where('id', $statusId->order_item_status_id)->first()->name;
        }
        return ['order_status_name' => $orderStatus, 'order_letter' => $statusId->order_latter, 'id' => $statusId->id];
    }
}

//Share group link to social media
if (!function_exists('shareGroupLink')) {
    function shareGroupLink($groupLink) {
        $share = \Share::page($groupLink)
        ->whatsapp()
        ->facebook()
        ->linkedin()
        ->twitter()
        ->telegram();
        return $share;
    }
}


//Ekta - get only socialite login user
if (!function_exists('checkUserLoginWithSocialite')){
    function checkUserLoginWithSocialite($id){
        $socialiteUser = DB::table('users')
            ->where('id', $id)
            ->where('is_delete', 0)
            ->where(function ($query) {
                $query->where('google_id','<>',null)
                    ->orWhere('fb_id','<>',null)
                    ->orWhere('linkedin_id','<>',null);
            })->first();
        return $socialiteUser;
        //dd($socialiteUser->toArray());
    }
}

//Munir - get all active bulk payment invoices by order ids
if (!function_exists('getActiveBulkPaymentInvoices')){
    function getActiveBulkPaymentInvoices($orderIds){
        if (!is_array($orderIds)){
            $orderIds = (array)$orderIds;
        }
        return DB::table('bulk_order_payments')
            ->join('bulk_payments','bulk_payments.id','=','bulk_order_payments.bulk_payment_id')
            ->join('order_transactions','order_transactions.id','=','bulk_payments.order_transaction_id')
            ->where(['order_transactions.status'=>'PENDING'])
            ->whereIn('bulk_order_payments.order_id',$orderIds)
            ->groupBy('order_transactions.id')
            ->get(['order_transactions.id','order_transactions.user_id','order_transactions.invoice_id']);
    }
}

if (!function_exists('countryCodeFormat')){
    function countryCodeFormat($code, $number){
        return $code.' '.$number;
    }
}

//munir - advance status change allow or not
if (!function_exists('isAdvanceStatusChangeAllow')){
    function isAdvanceStatusChangeAllow($orderStatus,$statusId,$sort){
            $isAllow = true;

            if(in_array($orderStatus,[1,2,10]) && $sort>4 && $sort!=8) {
                $isAllow = false;
            }elseif($orderStatus!=1 && $sort<=10) {
                if (auth()->user()->role_id == 1 || Auth::user()->hasRole('agent') || Auth::user()->hasRole('buyer')){
                    if ($orderStatus == 2 && $statusId == 3) {
                        $isAllow = true;
                    } elseif ($orderStatus == 3 && $statusId == 4) {
                        $isAllow = true;
                    } elseif ($orderStatus == 10 && $statusId == 3) {
                        $isAllow = true;
                    } elseif ($orderStatus == 4 && $statusId == 5) {
                        $isAllow = true;
                    }elseif ($sort == 8 && $orderStatus <=  3) {
                        $isAllow = true;
                    }if ($orderStatus == 11 && ($statusId == 3 || $statusId == 7)) {
                        $isAllow = true;
                    }
                }else{
                     $isAllow = false;
                }
            }elseif($orderStatus==5) {//--on Order Completed not allow any status to change
                $isAllow = false;
            }
            if(auth()->user()->role_id == 3 && in_array($statusId,[3,4,5,6,7])) {
                // on supplier login not allow to change following status Payment Done, Order in Progress, Order Completed, Order Returned, Order Cancelled
                $isAllow = false;
            }
            return $isAllow;
    }
}

//munir - credit status change allow or not
if (!function_exists('isCreditStatusChangeAllow')){
    function isCreditStatusChangeAllow($orderStatus,$statusId,$sort){
        $isAllow = true;
        if($orderStatus==9 && $sort<=3) {
            $isAllow = false;
        }elseif($orderStatus==8 && ($sort<=5 || $statusId==6 || $statusId==7)) {
            $isAllow = false;
        }elseif($orderStatus==3) {
            $isAllow = false;
            if (auth()->user()->role_id == 1 || Auth::user()->hasRole('agent')){
                if ($statusId == 5) {
                    $isAllow = true;
                }
            }
        }elseif ($orderStatus != 7 && ($sort<=5 || $statusId==6 || $statusId==7) && $orderStatus >= 3) {
            $isAllow = false;
        }
        elseif($orderStatus==5) {//--on Order Completed not allow any status to change
            $isAllow = false;
        }
        //for supplier
        if(auth()->user()->role_id == 3 && in_array($statusId,[3,4,5,6,7,8,9,10])) {
            // on supplier login not allow to change following status Payment Done, Order in Progress, Order Completed, Order Returned, Order Cancelled, Payment Due DD/MM/YYYY, credit approve, credit reject
            $isAllow = false;
        }
        return $isAllow;
    }
}

//munir - order item status change allow or not
if (!function_exists('isOrderItemStatusChangeAllow')){
    function isOrderItemStatusChangeAllow($statusId,$logisticProvided){
        $isAllow = true;

        if(auth()->user()->role_id == 3 && $logisticProvided==0 && !in_array($statusId,[1,2])) {
            // on supplier login and logistic not provided and Under Preparation & Ready to Dispatch except this status  not allow to change
            $isAllow = false;
        }

        if(auth()->user()->role_id == Role::SUPPLIER && $logisticProvided==1 && in_array($statusId,[7,8,9,10])) {
            // on supplier login and logistic provided and Under Preparation & Ready to Dispatch except this status  not allow to change
            $isAllow = false;
        }

        if (Auth::user()->hasRole('jne') && in_array($statusId,[1,2,7,8,9,10])) {
            //JNE role not allow to access/change to these - Under Preparation, Ready to Dispatch, QC Failed, QC Passed, Order Trouble Shooting, Delivered (1,2,7,8,9,10)
            $isAllow = false;

        }
        return $isAllow;
    }
}

if (!function_exists('countryCodeFormat')){
    function countryCodeFormat($code, $number){
        return $code.' '.$number;
    }
}

//munir - credit status change allow or not
if (!function_exists('setOrderPaymentStatus')){
    function setOrderPaymentStatus(Order $order){
        //normal order invoice payment or group invoice payment
        if($order->orderTransaction()->where('status','PAID')->value('id') || $order->groupTransaction()->where('status','PAID')->value('id')) {
            if ($order->payment_status!=1) {
                $order->payment_status = 1;//online paid
                $order->payment_date = Carbon::now();
                $order->save();
            }
            return true;
        }else{
            if ($order->bulkOrderPayment()->count()){
                $bulkPayment = $order->bulkOrderPayment->bulkPayment()->withTrashed()->first();
                if ($bulkPayment->orderTransaction()->where('status','PAID')->value('id')) {//bulk invoice payment
                    if ($order->payment_status!=1) {
                        $order->payment_status = 1;//online paid
                        $order->payment_date = Carbon::now();
                        $order->save();
                    }
                    return true;
                }
            }
            $order->payment_status = 2;//offline paid
            $order->payment_date = Carbon::now();
            $order->save();
            return true;
        }
    }
}

/**
 * Function for get city name
 *
 * @param int cityId
 */
function getCityName($cityId) {

    if (!empty($cityId)) {

        $cityName = City::where('id', $cityId)->pluck('name')->first();

        return $cityName;

    }
    return null;
}

/**
 * Function for get state name
 *
 * @param int stateId
 */
function getStateName($stateId) {

    if (!empty($stateId)) {

        $stateName = State::where('id', $stateId)->pluck('name')->first();

        return $stateName;

    }
    return null;
}

//03-05-2022 -Mittal - Function for added By and updated By column in user
if (!function_exists('addedByUpdatedByFun')) {
    function addedByUpdatedByFun($userid,$added_by = '',$updated_by = '') {
        $user = User::where('id', $userid)->first();

        if($user) {
            if(!empty($added_by)) {
                $user->added_by = $added_by;
            }
            if(!empty($updated_by)) {
                $user->updated_by = $updated_by;
            }
            $user->save();
            return true;
        }
    }
}

// get all admin list for notification
if (!function_exists('getAllAdmin')){
    function getAllAdmin(){
        return User::where('role_id', 1)->pluck('id')->toArray();
    }
}

if (!function_exists('getNotificationsCountAndView')){
    function getNotificationsCountAndView(){
        $where = [];
        if (Auth::user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', Auth::user()->id)->pluck('supplier_id')->first();;
            $where = ['supplier_id' => $supplier_id, 'admin_id' => 0];
        } else {
            $where = ['admin_id' => Auth::user()->id, 'supplier_id' => 0];
        }
        $notifications = Notification::join('users', 'notifications.user_id', '=', 'users.id')->where($where)->orderBy('id', 'desc')->orderBy('created_at', 'desc')->get(['notifications.*', 'users.firstname','users.lastname', 'users.role_id']);
        $orderStatus = OrderStatus::all();
        $orderItemStatus = OrderItemStatus::all();
        $notificationDropDownView =  view('admin/notification/dropdownNotification', ['notifications' => $notifications->take(5), 'orderStatus' => $orderStatus, 'OrderStatusItems' => $orderItemStatus])->render();
        $rfqArray = array_merge($where,['user_activity' => 'Generate RFQ', 'notification_type' => 'rfq', 'side_count_show' => 0]);
        $rfqCount = ChangeCount(Notification::where($rfqArray)->get()->count());
        $quoteArray = array_merge($where,['user_activity' => 'Create Quote', 'notification_type' => 'quote', 'side_count_show' => 0]);
        $quoteCount = ChangeCount(Notification::where($quoteArray)->count());
        $orderArray = array_merge($where,['user_activity' => 'Place Order', 'notification_type' => 'order', 'side_count_show' => 0]);
        $orderCount = ChangeCount(Notification::where($orderArray)->count());
        return ['notificationDropDownView' => $notificationDropDownView, 'counts' => $notifications->where('is_multiple_show', 0)->count(), 'rfqs' => $rfqCount, 'quotes' => $quoteCount, 'orders' => $orderCount];
    }
}

if (!function_exists('ChangeCount')){
    function ChangeCount($count){
        if ($count > 9){
            return '9+';
        } else {
            return $count;
        }
    }
}

if (!function_exists('buyerNotificationInsert')){
    function buyerNotificationInsert($user_id, $user_activity, $translation_key, $notification_type, $notification_type_id, $common_data = [])
    {
        if (Auth::user()->role_id == Role::ADMIN || Auth::user()->role_id == Role::BUYER || Auth::user()->role_id == Role::SUPPLIER) {
            $data = array('user_id' => $user_id, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => $notification_type, 'notification_type_id' => $notification_type_id, 'common_data' => json_encode($common_data));
            BuyerNotification::insert($data);
        }
        return true;
    }
}

if (!function_exists('buyerNotificationInsertWithoutAuth')){
    function buyerNotificationInsertWithoutAuth($user_id, $user_activity, $translation_key, $notification_type, $notification_type_id, $common_data = [])
    {
        $data = array('user_id' => $user_id, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => $notification_type, 'notification_type_id' => $notification_type_id, 'common_data' => json_encode($common_data));
        BuyerNotification::insert($data);
        return true;
    }
}

//@ekta 24/05/2022  get group social links from groupid
if (!function_exists('getGroupsLinks')) {
    function getGroupsLinks($groupid) {
        $shareLink = shareGroupLink(route('group-details',['id' => Crypt::encrypt($groupid)]));
        $str =  html_entity_decode($shareLink);
        $str = mb_convert_encoding($str, 'HTML-ENTITIES', 'UTF-8');
        // creating new document
        $doc = new \DOMDocument('1.0', 'utf-8');
        //turning off some errors
        libxml_use_internal_errors(true);
        // it loads the content without adding enclosing html/body tags and also the doctype declaration
        $doc->LoadHTML($str, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $output = [];
        foreach ($doc->getElementsByTagName('a') as $item) {
            $output[] = array (
                'str' => $doc->saveHTML($item),
                'href' => $item->getAttribute('href'),
                'anchorText' => $item->nodeValue
            );
        }
        $shareLink = [];
        if(sizeof($output) > 0){
            $shareLink = [
                'whatsapp' => $output[0]['href'],
                'facebook' => $output[1]['href'],
                'linkedin' => $output[2]['href'],
                'twitter' => $output[3]['href'],
                'telegram' => $output[4]['href']
            ];
        }
        return $shareLink;
    }
}

/*
 * Munir
 * Date:-04/06/2022
 * calculate product amount for group disbursement
 */
if (!function_exists('calcProductAmountForDisburse')){
    function calcProductAmountForDisburse($quoteItem,$tax=0){
        $totalProductAmount = ($quoteItem->product_price_per_unit * $quoteItem->product_quantity);
        if ($tax) {
            return $totalProductAmount+($totalProductAmount*$tax/100);
        }
        return $totalProductAmount;
    }
}

/*
 * Munir
 * Date:-04/06/2022
 * calculate tax for group disbursement
 */
if (!function_exists('setTaxForDisburse')) {
    function setTaxForDisburse($tax,$amount) {
        return $amount + (($amount * $tax) / 100);
    }
}

/*
 * Munir
 * Date:-04/06/2022
 * get minimum disbursement amount
 */
if (!function_exists('getMinDisbursementAmount')) {
    function getMinDisbursementAmount() {
        return (int) getSettingValueByKey('minimum_disbursement_amount')??0;
    }
}

/*
 * Munir
 * Date:-09/06/2022
 * get max disbursement amount for supplier in group trading
 */
/*
Total Disbursement: Sum of Product (total QTY* New price) + Sum of Platform charges + Tax based on new product price – (Blitznet commission if any)
Total Disbursement done till now: Sum of (Disbursement done till now)
Total Disbursement allowed: Total Disbursement – Total Disbursement done till now
*/
if (!function_exists('getMaxDisbursementAmountToSupplier')) {
    function getMaxDisbursementAmountForGroup($group) {
        $quoteWiseNewFinalAmount = getGroupOrderTotalByNewPrice($group);
        /*$totalQty = (int)$group->achieved_quantity;
        $newPrice = (int)$group->groupDiscountOption()->where('min_quantity','<=',$totalQty)->where('max_quantity','>=',$totalQty)->pluck('discount_price')->first()??$group->price;*/
        //preDump($totalQty * $newPrice);
        $totalGroupBlitznetCommissions = (int)$group->blitznetCommissions()->sum('paid_amount');
        $totalDisbursement = (int)$group->disbursements()->where('status','COMPLETED')->whereNull('buyer_user_id')->sum('amount');
        return (int) (($quoteWiseNewFinalAmount->sum('supplier_final_amount')) - ($totalDisbursement+$totalGroupBlitznetCommissions));//-$totalGroupBlitznetCommissions
    }
}


if (!function_exists('getGroupOrderTotalByNewPrice')) {
    function getGroupOrderTotalByNewPrice($group)
    {
        $data = [];
        foreach ($group->orders()->get('quote_id')->pluck('quote_id') as $quoteId){
            $data[$quoteId] = Quote::calculateGroupFinalAmount($quoteId,0);
        }
        return collect($data);
    }
}

/*
 * Munir
 * Date:-10/06/2022
 * get buyer refund amount in group trading
 */
if (!function_exists('getBuyerRefundAmount')) {
    function getBuyerRefundAmount($quoteId) {
        /*$refundDiscount = (float)DB::table('group_members_discounts')->where(['group_id'=>$groupId,'order_id'=>$orderId])->pluck('refund_discount')->first();
        $productTotalAmount = (int)DB::table('order_items')->where(['order_id'=>$orderId])->pluck('product_amount')->first();
        return (int)(($productTotalAmount*$refundDiscount)/100);*/
        $supplierFinalAmount = (int)DB::table('quotes')->where(['id'=>$quoteId])->pluck('supplier_final_amount')->first();
        $quoteWiseNewFinalAmount = (object)Quote::calculateGroupFinalAmount($quoteId,0);
        return (int)$supplierFinalAmount-$quoteWiseNewFinalAmount->supplier_final_amount;
    }
}

/*
 * Munir
 * Date:-11/06/2022
 * get disbursement charge
 */
if (!function_exists('getDisbursementCharge')) {
    function getDisbursementCharge() {
        return (float)getSettingValueByKey('disbursement_charge');
    }
}

/*
 * Munir
 * Date:-10/06/2022
 * get buyer refund amount in group trading
 */
if (!function_exists('getAllBuyerRefundAmountByGroup')) {
    function getAllBuyerRefundAmountByGroup($groupId) {
        $orders = DB::table('orders')->where(['group_id'=>$groupId])->get(['id']);
        $totalRefunAmount = 0;
        foreach ($orders as $order){
            $totalRefunAmount += getBuyerRefundAmount($groupId,$order->id);
        }
        return $totalRefunAmount;
    }
}

/*
 * Asif
 * Date:-22/0/2022
 * get states and state base on country and province
 */
if (!function_exists('getStateCountryWise')) {
    function getStateCountryWise($countryId) {
       $stateName = State::where('country_id', $countryId)->pluck('name','id')->toArray();
       return $stateName;
    }
}
if (!function_exists('getCityStateWise')) {
    function getCityStateWise($stateId)
    {
        $cityName = City::where('state_id', $stateId)->pluck('name', 'id')->toArray();
        return $cityName;
    }
}

/* Arun
 * Date: 24/6/22
 * get data according chat type like rfq, quote and order
 */
if (!function_exists('getDataFromChatType')) {
    function getDataFromChatType($type){
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        if ($type == 'Rfq'){
            // role wise show rfq
            if($isOwner == true || $authUser->hasPermissionTo('list-all buyer rfqs')){
                $dataList = Rfq::whereNotIn('rfqs.status_id', [3,4])->where(['rfqs.is_deleted' => 0 , 'rfqs.company_id' => $authUser->default_company])->with('chatGroupRfq:_id,chat_type,chat_id,group_name,company_id')->orderBy('id', 'desc')->get(['rfqs.id','rfqs.reference_number','rfqs.created_at', 'rfqs.company_id']);
                $dataList = array('rfqs' => $dataList??[]);
            } else {
                $dataList = User::where(['id'=>Auth::user()->id])->with(['rfqs'=>function($q){
                    $q->whereNotIn('rfqs.status_id', [3,4])->where(['rfqs.is_deleted' => 0, 'rfqs.company_id' => Auth::user()->default_company])->select(['rfqs.id','reference_number','rfqs.created_at', 'rfqs.company_id'])->with('chatGroupRfq:_id,chat_type,chat_id,group_name,company_id');
                }])->first(['id']);
            }
        }elseif($type == 'Quote'){
            if ($authUser->hasPermissionTo('list-all buyer rfqs') && $authUser->hasPermissionTo('list-all buyer quotes')){
                $dataList = Quote::join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')->where(['rfqs.company_id' => Auth::user()->default_company])->whereNotIn('quotes.status_id', [5,3,4])->with('rfq:reference_number,id,company_id')->with('chatGroupQuote:_id,chat_type,chat_id,group_name,company_id')->orderBy('id', 'desc')->get(['quotes.quote_number','quotes.id','quotes.rfq_id' ]);
                $dataList = array('quote' => $dataList??[]);
            } else {
                $dataList = User::where(['id'=>Auth::user()->id])->with(['quote'=>function($q){
                    $q->whereNotIn('quotes.status_id', [5,3,4])->select(['quote_number','quotes.id','quotes.rfq_id'])->with('rfq:reference_number,id,company_id')->with('chatGroupQuote:_id,chat_type,chat_id,group_name,company_id');
                }])->first(['id']);
            }
        }
        return $dataList??[];
    }
}

 /* Munir
 * Date:-20/06/2022
 * get buyer refund count in group trading
 */
if (!function_exists('getBuyerRefundCount')) {
    function getBuyerRefundCount($groupId) {
        return DB::table('disbursements')->where([['group_id','=',$groupId],['buyer_user_id','!=',null]])->count();
    }
}

/*
 * Munir
 * Date:-26/07/2022
 * set save path for loan application
 */
if (!function_exists('setFinalPathForLoanApplication')) {
    function setFinalPathForLoanApplication($userId,$companyId,$str) {
        $tempPath = config('settings.koinworks_temp_folder').$userId;
        $finalDestinationPath= config('settings.koinworks_confirm_folder').$companyId;
        $finalPath = str_replace($tempPath,$finalDestinationPath,$str);
        return str_replace('public','storage',$finalPath);
    }
}



/*
 * Date:-20/06/2022
 * get group buyer refund amount using order
 */
if (!function_exists('getBuyerRefundAmountByOrder')) {
    function getBuyerRefundAmountByOrder($groupId,$orderId,$userId) {
        return (int)DB::table('disbursements')->where(['group_id'=>$groupId,'order_id'=>$orderId,'buyer_user_id'=>$userId,'status'=>'COMPLETED'])->value('amount');
    }
}

/*
 * Arun
 * Get chat record with rfqid
 * Date : 6/7/2022
 * */
if (!function_exists('getChatDataForRfqById')) {
  function getChatDataForRfqById($id, $type){
      $chatCount = ChatGroup::with(['groupChatMember'=>function($q){
          $q->where('user_id', Auth::user()->id)->select(['group_chat_id','unread_message_count','user_id']);
      }])->where(["chat_type" => $type, 'chat_id' => $id])->orderBy('_id', 'desc')->first(['_id','chat_id','created_at','group_name']);

      if (!empty($chatCount['groupChatMember'])){
          return $chatCount['groupChatMember'];
      }
      return [];
  }
}

/*
 * Ekta
 * Get chat history record with rfqid
 * Date : 7/7/2022
 * */
if (!function_exists('getChatHistoryRfqById')) {
    function getChatHistoryRfqById($id, $type){
        $chatHistory = ChatGroup::with('groupChatMessage')->where(["chat_type" => $type, 'chat_id' => (int)$id])->get(['_id','chat_id','created_at','group_name']);
        //dd($chatHistory);
        if (!empty($chatHistory)){
            return $chatHistory;
        }
        return 0;
    }
}

/*
 * Ekta
 * Get Message alert
 * Date : 7/7/2022
 * */
if (!function_exists('getUnreadMessageAlert')) {
    function getUnreadMessageAlert(){
        $chatAlert = GroupChatMember::where('unread_message_count','>',0)->where(['user_id' => Auth::user()->id, 'check_permission' => 1])->count();
        $supportChat = SupportChatMember::where('unread_message_count','>',0)->where(['user_id' => Auth::user()->id, 'company_id' => (int)Auth::user()->default_company])->count();
        if($chatAlert > 0 || $supportChat > 0){
            return $dotCount = 1;
        }else{
            return $dotCount = 0;
        }
    }
}

if (!function_exists('getUnreadMessageAdminCount')) {
    function getUnreadMessageAdminCount(){
        $chatAlert = GroupChatMember::where('unread_message_count','>',0)
            ->where(['user_id' => Auth::user()->id])
            ->count();
        $supportChat = SupportChatMember::where('unread_message_count','>',0)->where(['user_id' => Auth::user()->id])->count();
        if($chatAlert > 0 || $supportChat > 0){
            return $dotCount = 1;
        }else{
            return $dotCount = 0;
        }
    }
}

/*
 * Arun
 * Date: 8/7/22
 * get data according chat type like rfq, quote and order
 */
if (!function_exists('getNewSearchDataFromChat')) {

    function getNewSearchDataFromChat($string, $type){
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        if ($type == 'Rfq'){
            if($isOwner == true || $authUser->hasPermissionTo('list-all buyer rfqs')){
                $dataRfqList = Rfq::whereNotIn('rfqs.status_id', [3,4])->where(['rfqs.is_deleted' => 0 , 'rfqs.company_id' => $authUser->default_company])->Where('reference_number', 'like', '%'.$string.'%')->with('chatGroupRfq:_id,chat_type,chat_id,group_name')->get(['rfqs.id','rfqs.reference_number','rfqs.created_at', 'rfqs.company_id']);
                $dataRfqList = array('rfqs' => $dataRfqList??[]);
            } else {
                $dataRfqList = User::where(['id'=>Auth::user()->id])->with(['rfqs'=>function($q) use ($string, $authUser){
                    $q->whereNotIn('rfqs.status_id', [3,4])->where('rfqs.reference_number', 'like', '%'.$string.'%')->where(['rfqs.is_deleted' => 0, 'rfqs.company_id' => $authUser->default_company])->select(['rfqs.id','reference_number','rfqs.created_at','rfqs.company_id'])->with('chatGroupRfq:_id,chat_type,chat_id,group_name');
                }])->first(['id']);
            }
            $dataList=$dataRfqList['rfqs'];
        }elseif ($type== 'Quote'){
            if ($authUser->hasPermissionTo('list-all buyer rfqs') && $authUser->hasPermissionTo('list-all buyer quotes')){
                $dataQuoteList = Quote::join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')->where('quotes.quote_number', 'like', '%'.$string.'%')->where(['rfqs.company_id' => $authUser->default_company])->whereNotIn('quotes.status_id', [5,3,4])->with('rfq:reference_number,id,company_id')->with('chatGroupQuote:_id,chat_type,chat_id,group_name,company_id')->get(['quotes.quote_number','quotes.id','quotes.rfq_id','rfqs.company_id' ]);
                $dataQuoteList = array('quote' => $dataQuoteList??[]);
            } else {
                $dataQuoteList = User::where(['id'=>Auth::user()->id])->with(['quote'=>function($q) use ($string, $authUser){
                    $q->whereNotIn('quotes.status_id', [5,3,4])->where('quote_number', 'like', '%'.$string.'%')->whereNotIn('status_id', [5,3,4])->select(['quote_number','quotes.id','quotes.rfq_id'])->with('rfq:reference_number,id')->with('chatGroupQuote:_id,chat_type,chat_id,group_name');
                }])->first(['id']);
            }
            $dataList=$dataQuoteList['quote'];
        }
        return $dataList ?? [];
    }
}

if (! function_exists('arr_compare')) {

    /**
     *
     * Array compare b/n two params
     *
     * @param null $arr1
     * @param null $arr2
     * @return bool
     */
    function arr_compare($arr1 = null, $arr2 = null)
    {
        $status = false;
        if (!empty($arr1) || !empty($arr2)) {

            $result = array_intersect($arr1, $arr2);

            if ($result === $arr1) {
                $status = true;
            }

        }
        return $status;

    }
}

if (! function_exists('getUserName')) {

    /**
     *
     * Get user resource name
     *
     * @param $id
     * @return string
     */
    function getUserName($id)
    {
        $user = User::findOrFail($id);
        return !empty($user) ? $user->full_name : '-';
    }
}

if (! function_exists('getRolePermissionAttribute')) {

    /**
     *
     * Get user resource custom role permission details
     *
     * @param $id
     * @return array|string[]
     */
    function getRolePermissionAttribute($id, $userCompany = null)
    {
       try {
            $userCompanyRole = collect();
            $userPermission = collect();
            $userCustomPermission = collect();
            $user = User::where('id', $id)->first();
            $userAssignedRoles = ModelHasCustomPermission::with('customPermission')
                ->whereHas('customPermission', function ($query) {
                    $query->where('model_type', '=', CustomRoles::class);
                })
                ->where('model_type', User::class)
                ->where('model_id', $id)->get();

            $userAssignedRoles->each(function ($role) use ($userCompanyRole, $userPermission, $user, $userCustomPermission, $userCompany) {
                if (!empty($userCompany)) {
                    $companyRole = CustomRoles::where('company_id', $userCompany)->where('id', $role->customPermission->value)->first();
                } else {
                    $companyRole = CustomRoles::where('company_id', Auth::user()->default_company)->where('id', $role->customPermission->value)->first();
                }

                if (!empty($companyRole)) {
                    $userPermission->push($role->custom_permissions);
                    $userCompanyRole->push($companyRole);
                    $userCustomPermission->push($role->customPermission);
                    $userCustomPermission->push($role->custom_permission_id);

                }

            });

            $role = $userCompanyRole->whereNotNull('name')->first();
            $permissions = !empty($userPermission->first()) ? Arr::flatten(json_decode($userPermission->first())) : '';

            $permissionGroup = PermissionsGroup::parent()->where('name', 'buyer')->first()->children();
            $roleGroup = CustomRoles::where('company_id', Auth::user()->default_company)->get();

            return [
                'role_id' => !empty($role) ? $role->id : '',
                'role' => !empty($role) ? $role->name : '',
                'permissions' => $permissions,
                'system_role_id' => $role->system_role_id,
                'custom_role_id' => $role->id,
                'custom_permission' => $userCustomPermission,
                'permissions_id' => $userCustomPermission->first()->id
            ];

      } catch (\Exception $e) {
            return [
                'role_id' => '',
                'role' => '',
                'permissions' => '',
                'custom_permission' => '',
                'custom_role_id' => ''
            ];
        }

    }
}

if (! function_exists('getUsersRolePermissionAttribute')) {

    /**
     *
     * Get all users resource custom role permission details by company
     *
     * @param $id
     * @return array|string[]
     */
    function getUsersRolePermissionAttribute($company)
    {

        try {
            $userCompanyRole = collect();
            $userPermission = collect();
            $userHasAssignedRole = '';
            $userHasRoles = array();

            $users = User::whereJsonContains('assigned_companies', [$company])->get();

            foreach ($users as $user) {
                $userAssignedRoles = ModelHasCustomPermission::with('customPermission')
                    ->whereHas('customPermission', function ($query) {
                        $query->where('model_type', '=', CustomRoles::class);
                    })
                    ->where('model_type', User::class)
                    ->where('model_id', $user->id)->get();


                foreach ($userAssignedRoles as $role) {
                    $companyRole = CustomRoles::where('company_id', Auth::user()->default_company)->where('id', $role->customPermission->value)->first();

                    if (isset($companyRole->id)) {
                        $userPermission->push($role->custom_permissions);
                        $userCompanyRole->push($companyRole);
                        $userHasRoles[] = $user->id;
                    }
                }


            }

            return $userHasRoles;

        } catch (\Exception $e) {
            return [];
        }

    }
}

if (! function_exists('assignCompanyToUserAttribute')) {

    /**
     *
     * Assign company to user
     *
     * @param null $user
     * @param null $company
     * @return bool
     */
    function assignCompanyToUserAttribute($user = null, $company = null, $default = false)
    {

        $user = !empty($user) ? $user : Auth::user();

        $assignedCompaniesArr = !empty($user->assigned_companies) ? json_decode($user->assigned_companies) : [];

        if (!in_array($company, $assignedCompaniesArr)) {
            array_push($assignedCompaniesArr, $company);
        }

        $response = User::where('id', $user->id)->update(['assigned_companies' => json_encode($assignedCompaniesArr)]);

        if ($default) {
            $response = User::where('id', $user->id)->update(['default_company' => $company]);
        }

        return !empty($response) ? true : false;

    }

    /**
     *
     * Get User's Designation (Ronak M)
     *
     * @param $userId
     * return designation_name
     */
    if (! function_exists('getUserDesignation')) {
        function getUserDesignation($userId) {
            $designation_name = User::with('user_designation')->where('users.id',$userId)->first(['designation']);
            return $designation_name->user_designation->name;
        }
    }

    /**
     *
     * Get User's Designation (Ronak M)
     *
     * @param $userId
     * return designation_name
     */
    if (! function_exists('getUserDepartment')) {
        function getUserDepartment($userId) {
            $designation_name = User::with('user_department')->where('users.id',$userId)->first(['department']);
            return $designation_name->user_department->name;
        }
    }

}


if (! function_exists('createDefaultAdminRole')) {

    /**
     *
     * Create Admin Default Role for new user register.
     *
     * @param null $user
     * @param null $company
     * @return bool
     */

     function createDefaultAdminRole($id, $company_id)
    {
        $permissions = CustomRoles::getAdminUserPermission();


        $customRole = CustomRoles::create([
            'name'              =>  'Admin',
            'permissions'       =>  json_encode($permissions),
            'guard'             =>  Auth::getDefaultDriver(),
            'model_type'        =>  User::class,
            'model_id'          =>  $id,
            'system_role_id'    =>  SystemRole::FRONTOFFICE,
            'company_id'        =>  $company_id,
            'system_default_role' => 1
        ]);

        if ($customRole) {

            $customPermission = CustomPermission::create([
                'name'              =>  'Custom Role',
                'model_type'        =>  CustomRoles::class,
                'value'             =>  $customRole->id,
                'system_role_id'    =>  SystemRole::FRONTOFFICE,
                'guard'             =>  Auth::getDefaultDriver()
            ]);
        }

    }
}

if (!function_exists('checkChatPermissionWiseAddMember')) {
    //add chat member using role and permission wise
    function checkChatPermissionWiseAddMember($company_id, $userId, $id, $chat_type){
        if(Auth::user()->hasPermissionTo('list-all buyer rfqs') || Auth::user()->hasPermissionTo('list-all buyer quotes') || in_array(Auth::user()->role_id, [Role::ADMIN, Role::SUPPLIER, Role::SUPER_ADMIN, Role::AGENT])){
            $getAllUserFromComapnys = UserCompanies::with('user')->where('company_id', $company_id)->whereHas('user')->pluck('user_id')->toArray();
            foreach ($getAllUserFromComapnys as $user){
                if ($userId !== $user){
                    if ($chat_type == 'Rfq'){
                        Log::info('Chat Permission RFQ'.$user);
                        $checkPermission = (User::find($user))->hasPermissionTo('list-all buyer rfqs');
                    }
                    if ($chat_type == 'Quote'){
                        Log::info('Chat Permission Quote'.$user);
                        $checkPermission = (User::find($user))->hasPermissionTo('list-all buyer quotes');
                    }
                    $unread_message_count = ($user == Auth::user()->id) ? 0 : 1;
                    GroupChatMember::create(['group_chat_id' => $id, 'user_role_id' => (int)Role::BUYER, 'user_id' => $user ,'unread_message_count'=>$unread_message_count, 'company_id' => $company_id, 'check_permission' => $checkPermission ? 1:0, 'is_owner' => 0]);
                }
            }
        }
        return true;
    }
}

if (! function_exists('numberToName')) {

    /**
     * Number convert with Number system name e.g Million | Billion | Trillion
     *
     * @param $number
     * @return int|string
     */
    function numberToName($number,$decimal = true)
    {
        $number = (float) str_replace(',', '', $number);
        if ($number < 1000) {
            // Anything less than a K
            $format = $decimal==true ? 'IDR ' .number_format($number, 2) : 'IDR ' .number_format($number);

        } else if ($number < 1000000) {
            // Anything less than a million
            $format = $decimal == true ? 'IDR ' .number_format($number / 1000, 2) .' '.__("admin.thousand_long") : 'IDR ' .number_format($number / 1000) .' '.__("admin.thousand_long");

        } else if ($number < 1000000000) {

            // Anything less than a billion
            $format = $decimal == true ? 'IDR ' .number_format($number / 1000000, 2) .' '.__("admin.million_long") : 'IDR ' .number_format($number / 1000000) .' '.__("admin.million_long");

        } else if ($number < 1000000000000) {

            // Anything less than a trillion
            $format = $decimal == true ? 'IDR ' .number_format($number / 1000000000, 2) .' '.__("admin.billion_long") : 'IDR ' .number_format($number / 1000000000) .' '.__("admin.billion_long");

        } else if ($number >= 1000000000000) {
            // Anything less than a quadrillion
            $format = $decimal == true ? 'IDR ' .number_format($number / 1000000000000, 2) .' '.__("admin.quadrillion_long") : 'IDR ' .number_format($number / 1000000000000) .' '.__("admin.quadrillion_long");

        }

        return $format ?? 0;
    }

}
if (! function_exists('getUserPrimaryAddress')) {

    /**
     *
     * Get user address primary by user logged in
     *
     */
    function getUserPrimaryAddress()
    {
        $primaryAddress = UserAddresse::whereJsonContains('is_user_primary', [Auth::user()->id])->where('company_id',Auth::user()->default_company)->where('is_deleted', 0)->first();
        return $primaryAddress;

    }
}
if (! function_exists('uploadAlldocs'))
{

     function uploadAlldocs($doc,$path,$name)
    {
        $imagefileExt = $doc->extension();
        $imagefile = $doc->storeAs($path,$name.Carbon::now()->format('ymdhis').'.'.$imagefileExt);
        return str_replace('public','storage',$imagefile);
    }
}
/**
 * Store logistics api request and response (Ronak M - 06/09/2022)
 * parameters : logistics_provider_id , request_data, response_code, response_data
 * other parameter : user_id
 */
function storeLogisticsAPIRequestResponse($logisticsProviderId, $authUserId, $requestData = null, $responseCode = null, $responseData = null) {
    $logisticsApiData = new LogisticsApiResponse();
    $logisticsApiData->logistics_provider_id = $logisticsProviderId;
    $logisticsApiData->user_id = $authUserId;
    $logisticsApiData->request_data = json_encode($requestData);
    $logisticsApiData->response_code = $responseCode;
    $logisticsApiData->response_data = $responseData;
    $logisticsApiData->save();
    return true;
}
if (! function_exists('getBuyerRole')) {

    /**
     * Get buyer role name
     *
     * @param $user
     * @return string
     */
    function getBuyerRole($user)
    {
        $isOwner = User::checkCompanyOwner();

        $buyerRole = $isOwner==true ? CustomRoles::ADMINNAME : (getRolePermissionAttribute($user->id)['role'] ?? 'Approval / consultant');

        return $buyerRole;
    }
}

if (! function_exists('isCreditApplied')) {

    /**
     * Is buyer applied for credit limit
     *
     * @return int
     */
    function isCreditApplied()
    {
        $creditDetail = LoanApplication::getCreditDatail();

        if(!empty($creditDetail) && isset($creditDetail->provider_application_id)){
            return 1;
        }else{
            return 0;
        }
    }
}

if (! function_exists('getLoanCalculation')) {

    /**
     * Get loan calculation
     *
     * @param $quoteId
     * @return array
     */
    function getLoanCalculation($quoteId,$loanId=0)
    {
        $quote = Quote::where('id',$quoteId)->first(['id','final_amount']);
        $totalAmount = (int)$quote->final_amount;
        $loanProviderChargeTypes = LoanProviderCharges::getAllLoanProviderCharges();

        $interestRate = $loanProviderChargeTypes->where('charges_type_id',LOAN_PROVIDER_CHARGE_TYPE['INTEREST'])->first();
        $loan = null;
        $loanTransaction = null;
        if (!empty($loanId)){
            $loan = LoanApply::where('id',$loanId)->first();
            $loanTransaction = $loan->loanTransaction->get(['charge_type_id','transaction_type_id','remarks','transaction_amount']);
        }

        //preDump($loanTransaction);
        $periodInDays = $interestRate->period_in_days??0;
        $periodInMonth = $interestRate->period_in_month??0;
        $interestRate = $interestRate->value??0;
        $interest = $loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['INTEREST'])->sum('transaction_amount'):round($totalAmount*$interestRate/100);
        $repaymentCharges = $loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['REPAYMENT_CHARGE'])->sum('transaction_amount'):$loanProviderChargeTypes->whereIn('charges_type_id',[LOAN_PROVIDER_CHARGE_TYPE['REPAYMENT_CHARGE'],LOAN_PROVIDER_CHARGE_TYPE['INTERNAL_TRANSFER_CHARGE']])->sum('value');
        $payableAmount = (isset($loan->loan_repay_amount)&&!empty($loan->loan_repay_amount))?$loan->loan_repay_amount:($totalAmount+$interest+$repaymentCharges);
        $data = [
            'total_amount'=>$quote->final_amount,
            'period_in_days'=>$periodInDays,
            'period_in_month'=>$periodInMonth,
            'interest_rate'=>$interestRate,
            'interest_amount'=>round($interest),
            'repayment_charges'=>round($repaymentCharges),
            'payable_amount'=>round($payableAmount),
            'internal_transfer_charge_count'=>$loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['INTERNAL_TRANSFER_CHARGE'])->count():0,
            'total_internal_transfer_charge'=>$loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['INTERNAL_TRANSFER_CHARGE'])->sum('transaction_amount'):0,
            'origination_charge_count'=>$loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['ORIGINATION_CHARGE'])->count():0,
            'total_origination_charge'=>$loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['ORIGINATION_CHARGE'])->sum('transaction_amount'):0,
            'vat'=>$loanProviderChargeTypes->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['VAT'])->pluck('value')->first()??0,
            'total_vat'=>$loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['VAT'])->sum('transaction_amount'):0,
            'late_fee_count'=>$loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['LATE_FEE'])->count():0,
            'total_late_fee'=>$loanTransaction?$loanTransaction->where('charge_type_id',LOAN_PROVIDER_CHARGE_TYPE['LATE_FEE'])->sum('transaction_amount'):0,
        ];
        return $data;
    }
}

if (!function_exists('addXenditCommisionFee')){
    function addXenditCommisionFee($companyId){
        $getAllOtherCharges = OtherCharge::where(['charges_type' => 2, 'is_deleted' => 0])->get();
        foreach ($getAllOtherCharges as $charge){
            XenditCommisionFee::updateOrCreate(['charge_id' => $charge['id'], 'company_id' => $companyId],['charge_id' => $charge['id'], 'company_id' => $companyId, 'type' => $charge['type'], 'charges_value' => $charge['charges_value'], 'charges_type' => $charge['charges_type'], 'addition_substraction' => $charge['addition_substraction'] ,'is_delete' => 0]);
        }
        return true;
    }
}

if(!function_exists('AddNewXenditCommisionFee')){
    function AddNewXenditCommisionFee($chargeId,$data=NULL){
        $getAllUserCompany = UserCompanies::groupBy('company_id')->get();
        foreach ($getAllUserCompany as $company){
            XenditCommisionFee::insert(['charge_id' => $chargeId, 'company_id' => $company->company_id, 'is_delete' => 0, 'type' => $data['type'], 'charges_value' => $data['charges_value'], 'charges_type' => $data['charges_type'], 'addition_substraction' => $data['addition_substraction']]);
        }
        return true;
    }
}

if(!function_exists('countSupportChatCompanyMes')){
    function countSupportChatCompanyMes($companyId){
         return SupportChatMember::where(['user_id' => Auth::user()->id, 'company_id' => $companyId ])->get()->sum('unread_message_count');
    }
}

//On create role, get approvers count as per the logged in users company id
if (! function_exists('getCompanyWiseApproverPermission')) {
    function getCompanyWiseApproverPermission() {
        return count(CustomRoles::companyApprovals());
    }
}

if (!function_exists('arraysToCollect')) {
    /**
     * Convert set of arrays into collection
     * @param json
     */
    function arraysToCollect($json) {
        $collect = collect();

        foreach ($json as $arr) {

            foreach (Arr::flatten(json_decode($arr, true)) as $array) {

                $collect->push($array);

            }
        }

        return $collect;
    }
}
/**
 * show user wise pending column percentage
 */
if (!function_exists('getUserWisePendingProfilePercentage')){
    function getUserWisePendingProfilePercentage($userID,$companyId){
        $companyConsumptionsPercentage = 0 ;
        $getAllMasterField = ModuleInputField::where(['deleted_at' => null])->select('table_name',DB::raw('group_concat(columns_name) as columns_name'),'getby_columnname')->groupBy('table_name')->get();
        $fillableColum = [];
        foreach ($getAllMasterField as $key => $value){
            $id = ($value['getby_columnname'] == 'id' || $value['getby_columnname'] =='company_id') ? $companyId : $userID;
            $result = DB::table($value['table_name'])->where($value['getby_columnname'],$id)->first();

            $tablefillColumns = array_filter((array)$result, function($value) {
                return !empty($value);
            });
            $tablefillColumnsKey = array_keys($tablefillColumns);

            $masterColmnsInArray = explode(',', $value['columns_name']);
            $fillColumnList = array_intersect($tablefillColumnsKey,$masterColmnsInArray);

            $fillableColum = array_merge($fillableColum, $fillColumnList);
        }

        $getfillColumnData = ModuleInputField::whereIn('columns_name',$fillableColum)->select('percentage')->get();
        $getfillColumnPercentage = array_sum($getfillColumnData->pluck('percentage')->toArray());
        return $getfillColumnPercentage.'%';
    }
}


if (!function_exists('get_rfqProducts')){
    function get_rfqProducts($rfq){
        $rfqProducts = RfqProduct::with('unit:id,name')->where('rfq_id',$rfq)->get();
        return $rfqProducts;
    }
}

if (!function_exists('get_product_category_by_id')){
    function get_product_category_by_id($id){
        $product_name = RfqProduct::where('id', $id)->first(['category']);
        return $product_name->category;
    }
}

/*this function is needed so plese dont remove* /
/*
if (!function_exists('getUserWisePendingProfilePercentage')){
    function getUserWisePendingProfilePercentage($userID,$companyId){
        $companyConsumptionsPercentage = 0 ;
        $getAllMasterField = ModuleInputField::where(['deleted_at' => null])->select('table_name',DB::raw('group_concat(columns_name) as columns_name'),'getby_columnname')->groupBy('table_name')->get();
        $nullableColum = [];
        foreach ($getAllMasterField as $key => $value){
            $id = ($value['getby_columnname'] == 'id' || $value['getby_columnname'] =='company_id') ? $companyId : $userID;
            $result = DB::table($value['table_name'])->where($value['getby_columnname'],$id)->first();

            if($value['table_name'] == 'company_consumptions' && $result == null){
                $companyConsumptionsPercentage = ModuleInputField::where('table_name','company_consumptions')->pluck('percentage')->first();
            }
            $tableNullColumns = array_filter((array)$result, function($value) {
                return $value === null || $value == '';
            });
            $tableNullColumnsKey = array_keys($tableNullColumns);

            $masterColmnsInArray = explode(',', $value['columns_name']);
            $nullColumnList = array_intersect($tableNullColumnsKey,$masterColmnsInArray);

            $nullableColum = array_merge($nullableColum, $nullColumnList);
        }

        $getNullColumnData = ModuleInputField::whereIn('columns_name',$nullableColum)->select('percentage')->get();
        $getNullColumnPercentage = array_sum($getNullColumnData->pluck('percentage')->toArray());
        $profilePercentage = 100 - $getNullColumnPercentage - $companyConsumptionsPercentage .'%';
        return $profilePercentage;
    }
}
*/
