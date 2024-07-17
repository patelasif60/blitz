<?php

namespace App\Http\Controllers\Admin\Backoffice;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\User;
use App\Models\RfqProduct;
use App\Models\Rfq;
use App\Models\UserRfq;
use App\Models\Quote;
use App\Models\Order;
use App\Models\SystemActivity;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Admin\BackOffice\MailNotification\ExportNewBuyerSpplierReg;
use App\Exports\Admin\BackOffice\MailNotification\ExportMailRfqNotRespond;
use App\Exports\Admin\BackOffice\MailNotification\ExportMailQuoteNotRespond;
use App\Exports\Admin\BackOffice\MailNotification\ExportMailOrderNotRespond;
use App\Exports\Admin\BackOffice\MailNotification\ExportMailBuyerNotPlaceRFQ;
use App\Exports\Admin\BackOffice\MailNotification\ExportMailBuyerNotLogin;
use App\Exports\Admin\BackOffice\MailNotification\ExportMailQuoteExpire;


class BackOfficeController extends Controller
{
    // This method use to send new buyer & supplier register report in mail. 
    function newBuyerSupplierReg(){
        $date = Carbon::today()->toDateString();
        $supplierList = Supplier::where('created_at', '>=', Carbon::yesterday())->get()->count();
        $buyerLists = User::where('created_at', '>=', Carbon::yesterday())->where('role_id',2)->get()->count();

        if ($buyerLists > 0 || $supplierList > 0) {

            $buyerList = Excel::download(new ExportNewBuyerSpplierReg('buyer'), 'buyerList_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $buyerpath = $buyerList->getFile()->getRealPath();
             
            $supplierList = Excel::download(new ExportNewBuyerSpplierReg('supplier'), 'supplierList_'.$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $supplierpath = $supplierList->getFile()->getRealPath();
          
            Mail::send('emails.operational_notif.byrSpyList', [], function($message) use($buyerpath, $supplierpath, $date) {
                if (config('app.env') == "live") {
                        $message->to(OPRATION_EMAILS)->subject('New User Registered list of yesterday.');
                    }
                else {
                        $message->to(TEST_EMAILS)->subject('New User Registered list of yesterday.');
                    }
                $message->attach($buyerpath, ['as' => 'buyerList_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
                $message->attach($supplierpath, ['as' => 'supplierList_'.$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
            });

            if (file_exists($buyerpath) && file_exists($supplierpath)) {
                unlink($buyerpath);
                unlink($supplierpath);
            }

        } else {
            Mail::send('emails.operational_notif.byrSpyList', [], function($message) {
                if (config('app.env') == "live") {
                        $message->to(OPRATION_EMAILS)->subject('User not Registered Yesterday.');
                } else {
                        $message->to(TEST_EMAILS)->subject('User not Registered Yesterday.');
                    }
            });
        }
    }

    // This method use to send Rfq Quote & Order not respons report in mail.
    public function RfqQuoteOrderNotRespons(){
        $date = Carbon::today()->toDateString();

        //Rfqs mail send
        $rfqs = Rfq::where('status_id',1)->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())
            ->get()->count();
        
        $rfqLists = Excel::download(new ExportMailRfqNotRespond, 'RFQ_'.$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        $rfqListsPath = $rfqLists->getFile()->getRealPath();
        

        //Quote mail send
         $quotes = Quote::with(['userName:id,firstname,lastname', 'supplier:id,contact_person_name,contact_person_last_name'])
         ->where('status_id', 1)
         ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())->get()->count();
    
        $quoteLists = Excel::download(new ExportMailQuoteNotRespond, 'Quote_'.$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        $quoteListsPath = $quoteLists->getFile()->getRealPath();


        //Order mail send
        $orders = Order::with(['user:id,firstname,lastname', 'company:id,name', 'group:id,name', 'supplier:id,contact_person_name,contact_person_last_name'])
                        ->where('order_status', 1)
                        ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())
                        ->get()->count();

        $orderLists = Excel::download(new ExportMailOrderNotRespond, 'Order_' .$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        $orderListsPath = $orderLists->getFile()->getRealPath();
        

        if($orders > 0 || $quotes > 0 || $rfqs > 0){
            Mail::send('emails.operational_notif.rqonotrespond', [], function($message) use($rfqListsPath, $quoteListsPath, $orderListsPath, $date) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('Rfq, Quote and Order List which is not responded last 48hr.');
                }else {
                    $message->to(TEST_EMAILS)->subject('Rfq, Quote and Order List which is not responded last 48hr.');
                }
                $message->attach($rfqListsPath, ['as' => 'RFQ_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
                $message->attach($quoteListsPath, ['as' => 'Quote_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
                $message->attach($orderListsPath, ['as' => 'Order_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
            });
        }else{
            Mail::send('emails.operational_notif.rqonotrespond', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('Rfq, Quote and Order List which list Yesterday not found.');
                }else {
                    $message->to(TEST_EMAILS)->subject('Rfq, Quote and Order List which list Yesterday not found.');
                }
            });
        }

        if (file_exists($rfqListsPath)) {
            unlink($rfqListsPath);
        }
      
        if (file_exists($quoteListsPath)) {
            unlink($quoteListsPath);
        }
      
        if (file_exists($orderListsPath)) {
            unlink($orderListsPath);
        }
    
    }
   
    //buyer not login & palce rfq in cron jon calling  
    public function buyerNotLoginAndRfqPlace(){
        $date = Carbon::today()->toDateString();

        $buyerNotlogins = SystemActivity::with('user:id,firstname,lastname')
                                        ->where('action', 'LOGGED IN')
                                        ->whereNotBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                                        ->get()->count();

        
        $buyerNotloginsList = Excel::download(new ExportMailBuyerNotLogin(), 'buyer_not_login_' .$date. '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        $buyerNotloginsListPath = $buyerNotloginsList->getFile()->getRealPath();
       
       
        //Buyer not place any rfq from last 7 days.
        $userLists = UserRfq::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->pluck('user_id')->toArray();
        $notplaceRfqUserLists= User::with('defaultCompany:id,name')
                                    ->where('role_id', 2)
                                    ->whereNotIn('id', $userLists)
                                    ->get(['firstname', 'lastname', 'email', 'phone_code', 'mobile', 'assigned_companies', 'default_company'])
                                    ->count();

        $notplaceRfqUserLists = Excel::download(new ExportMailBuyerNotPlaceRFQ(), 'RFQNotplace_' .$date. '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        $notplaceRfqUserListsPath = $notplaceRfqUserLists->getFile()->getRealPath();
       
        
        if($userLists > 0 || $buyerNotlogins > 0){
            Mail::send('emails.operational_notif.byrnotlogin', [], function($message) use($buyerNotloginsListPath, $notplaceRfqUserListsPath, $date) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
                }else {
                    $message->to(TEST_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
                }
                $message->attach($buyerNotloginsListPath, ['as' => 'buyer_not_login_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
                $message->attach($notplaceRfqUserListsPath, ['as' => 'RFQNotplace_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
            });
        }else{
            Mail::send('emails.operational_notif.byrnotlogin', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
                } else {
                    $message->to(TEST_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
                }
            });
        }  

        if (file_exists($buyerNotloginsListPath)) {
            unlink($buyerNotloginsListPath);
        }

        if (file_exists($notplaceRfqUserListsPath)) {
            unlink($notplaceRfqUserListsPath);
        }
    }

    //Quote Expire in cron jon calling
    public function QuoteExpireSendMail(){
        $date = Carbon::today()->toDateString();
        $from = Carbon::now()->toDateString();
        $to = Carbon::now()->subDays(7)->toDateString();
        $quoteExpireDatas = Quote::where('valid_till', '<', $from)
                        ->where('valid_till', '>=', $to)
                        ->where('status_id', 3)
                        ->get()->count();
        $QuoteExpireLists = Excel::download(new ExportMailQuoteExpire(), 'expire_quote' .$date. '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        $QuoteExpireListsPath = $QuoteExpireLists->getFile()->getRealPath();
        if($quoteExpireDatas > 0){
            Mail::send('emails.operational_notif.quoteExpire', [], function($message) use($QuoteExpireListsPath, $date) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('List of quote expire last 7 days.');
                }else {
                    $message->to(TEST_EMAILS)->subject('List of quote which are expire last 7 days.');
                }
                $message->attach($QuoteExpireListsPath, ['as' => 'EXPIRE_QUOTE_WITHIN_7DAYS_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
            });
        }else{
            Mail::send('emails.operational_notif.quoteExpire', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('last 7 days not Expire Quota question date.');
                } else {
                    $message->to(TEST_EMAILS)->subject('last 7 days not Expire Quota question date.');
                }
            });
        }

        if (file_exists($QuoteExpireListsPath)) {
            unlink($QuoteExpireListsPath);
        }
    }
}
