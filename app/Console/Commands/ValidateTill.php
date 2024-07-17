<?php

namespace App\Console\Commands;

use App\Events\BuyerNotificationEvent;
use App\Events\rfqsEvent;
use App\Jobs\ChangeStatusQuotesExpireJob;
use App\Jobs\QuotesValidateTillNotificationAdminJob;
use App\Jobs\QuotesValidateTillNotificationJob;
use App\Models\Notification;
use App\Models\QuoteItem;
use App\Models\Settings;
use Illuminate\Console\Command;
use App\Models\Quote;
use App\Models\Rfq;
use Carbon\Carbon;
use DB;
use Mail;
use Config;
use App\Mail\ChangeStatusQuotesExpire;
use App\Mail\QuotesValidateTillNotification;
use App\Mail\QuotesValidateTillNotificationAdmin;
use App\Models\BuyerNotification;
use App\Twilloverify\TwilloService;

class ValidateTill extends Command
{
    protected $verify;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validateTill:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail For Every user validate quote for till day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->verify = app('App\Twilloverify\TwilloService');
    }

    /**quote_number
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $valida_till = DB::table('quotes')
                    ->join('user_rfqs', 'quotes.rfq_id', '=', 'user_rfqs.rfq_id')
                    ->join('users', 'user_rfqs.user_id', '=', 'users.id')
                    ->where('status_id', 1)
                    ->select('quotes.valid_till', 'quotes.id', 'quotes.rfq_id', 'quotes.quote_number', 'user_rfqs.user_id', 'users.email', 'users.firstname', 'users.lastname', 'quotes.supplier_id')->get()->toArray();

        $email_Id=[];
        foreach($valida_till as $val){
            $email_Id[]  =   $val->email; // for mail id store
        }

        $ccUsers = Config::get('static_arrays.bccusers');
        $admin_email = Settings::where('key', 'cron_admin_email')->pluck('value')->first();
        if(!empty($valida_till)) {
            $i= 0;

            $limit = 100; // set limit for make chunk of 150
            $chunk = 100; // for separtion of chunk
            $minute = 60; // set minute for delay
            $min = 0;  //starting limit to make increment
            $mailLimit = 100; //mail limit
            foreach ($valida_till as $value) {
                $multipleProducts = QuoteItem::where('quote_id', $value->id)->get()->toArray();
                //Only use this query for first time run this cron after remove this query
                //$quoteExpire = Quote::where('rfq_id', $value->rfq_id)->where('status_id', NULL)->pluck('status_id')->toArray();
                /*if (in_array('3', $quoteExpire) || in_array('2', $quoteExpire)) {
                    $quoteExpire = Quote::where('rfq_id', $value->rfq_id)->Where('status_id', '<>', 2)->update(['status_id' => 3]);
                    continue;
                }*/
                // current date to greater date
                /** Logic for send mail in add 60 mint  */
                if($i >= $mailLimit ){
                    if ($i >= $chunk ){
                        $chunk += $limit; // for chunk separtion
                        $min += $minute; // for minute add
                    }
                }
                if (Carbon::now()->toDateString() > Carbon::parse($value->valid_till)->format('Y-m-d')) {
                    //change status of valid till date
                    $quote = Quote::find($value->id);
                   $quote->status_id = 3;
                    $quote_exp_mail = array('created' => $quote->created_at, 'firstname' => $value->firstname, 'lastname' => $value->lastname, 'quote_number' => $value->quote_number, 'rfq_id' => 'BRFQ-' . $value->rfq_id, 'multipleProductDetails' => $multipleProducts);
                    //send mail
                    if ($quote->save()) {
                        //create new notification
                        //admin
                        $getAllAdmin = getAllAdmin();
                        $sendAdminNotification = [];
                        if (!empty($getAllAdmin)){
                            foreach ($getAllAdmin as $key => $admin){
                                $sendAdminNotification[] = array('user_id' => $admin, 'admin_id' => $admin, 'user_activity' => 'Expire Quote', 'translation_key' => 'quote_expire_notification', 'notification_type' => 'quote', 'notification_type_id'=> $value->id, 'created_at' => Carbon::now());
                            }
                            Notification::insert($sendAdminNotification);
                        }
                        // supplier
                        $sendAdminNotification = array('user_id' => $value->user_id, 'supplier_id' => $value->supplier_id, 'user_activity' => 'Expire Quote', 'translation_key' => 'quote_expire_notification', 'notification_type' => 'quote', 'notification_type_id'=> $value->id, 'created_at' => Carbon::now());
                        Notification::insert($sendAdminNotification);

                        broadcast(new rfqsEvent());
                        //Buyer
                        $buyerCommanData = ['quote_number' => $value->quote_number, 'updated_by' => 'blitznet team', 'rfq' => $value->rfq_id, 'icons' => 'fa-check-square-o', 'valid_till_date' => changeDateFormat($value->valid_till,'d/m/Y')];
                        //buyerNotificationInsert($value->user_id, 'Quote Expire', 'buyer_quote_expire', 'quote', $value->id, $buyerCommanData);
                        $data = array('user_id' => $value->user_id, 'user_activity' => 'Quote Expire', 'translation_key' => 'buyer_quote_expire', 'notification_type' => 'quote', 'notification_type_id' => $value->id, 'common_data' => json_encode($buyerCommanData));
                        BuyerNotification::insert($data);
                        broadcast(new BuyerNotificationEvent());

                        try {
                            if($i < $mailLimit){
                                dispatch(new ChangeStatusQuotesExpireJob($value->email,$quote_exp_mail));
                                dispatch(new QuotesValidateTillNotificationAdminJob($admin_email,$ccUsers,$quote_exp_mail));  //send to admin
                            }else{
                                dispatch((new ChangeStatusQuotesExpireJob($value->email,$quote_exp_mail))->delay(now()->addMinutes($min)));
                                dispatch((new QuotesValidateTillNotificationAdminJob($admin_email,$ccUsers,$quote_exp_mail))->delay(now()->addMinutes($min))); // //send to admin
                            }
                        } catch (\Exception $e) {
                            echo 'Error - ' . $e;
                            continue;
                        }
                    }
                   // continue;
                }
                $diff_days = Carbon::parse($value->valid_till)->diffInDays(now()->format('Y-m-d'), true);
                $days_list = Settings::where('key', 'valid_till')->pluck('value');
                if (in_array($diff_days, explode(',', $days_list))) {
                    $data = array('diff_days' => $diff_days, 'firstname' => $value->firstname, 'lastname' => $value->lastname, 'quote_number' => $value->quote_number, 'rfq_id' => 'BRFQ-' . $value->rfq_id, 'multipleProductDetails' => $multipleProducts);
                    $rfqDetailssms = Rfq::find($value->rfq_id);
                    $data['rfq_number'] = $rfqDetailssms->reference_number;
                    $data['quote_number'] = $value->quote_number;
                    $sendMsg = $this->verify->sendMsg($rfqDetailssms->firstname,$rfqDetailssms->lastname,'quote_ending',$rfqDetailssms->phone_code,$rfqDetailssms->mobile,$data);  

                    try {
                        if($i < $mailLimit){
                            dispatch(new QuotesValidateTillNotificationJob($value->email,$data)); //send to customer
                            dispatch(new QuotesValidateTillNotificationAdminJob($admin_email,$ccUsers,$data));  //send to admin
                        }else{
                            dispatch((new QuotesValidateTillNotificationJob($value->email,$data))->delay(now()->addMinutes($min))); //send to customer
                            dispatch((new QuotesValidateTillNotificationAdminJob($admin_email,$ccUsers,$data))->delay(now()->addMinutes($min)));  //send to admin
                        }

                    } catch (\Exception $e) {
                        //echo 'Error - ' . $e;
                        continue;
                    }
               }
                $i++;
            }
        }
        $this->info('Successfully sent Day Notification.');
    }
}
