<?php

namespace App\Console\Commands;

use App\Mail\PaymentDueAdmin;
use App\Models\QuoteItem;
use App\Models\Settings;
use Illuminate\Console\Command;
use DB;
use Mail;
use Config;
use Carbon\Carbon;
use App\Mail\PaymentDueMail;
use App\Mail\PaymentDueAlreadyMail;

class PaymentDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymentDue:cron';

    /**
     * The console command description.4esz
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payment_due = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->where([['is_credit', '=', 1], ['payment_status', '=', 0], ['payment_due_date', '<>', null]])
            ->select('orders.order_number', 'orders.payment_due_date', 'orders.payment_amount','orders.quote_id', 'orders.user_id', 'users.email', 'users.firstname', 'users.lastname')->get()->toArray();
        $ccUsers = Config::get('static_arrays.bccusers');
        $admin_email = Settings::where('key', 'cron_admin_email')->pluck('value')->first();
        if (!empty($payment_due)){
            foreach ($payment_due as $value){
                $multiple_quote_items = QuoteItem::where('quote_id', $value->quote_id)->get(['rfq_product_id', 'product_quantity', 'price_unit'])->toArray();
                $data = array('firstname' => $value->firstname, 'lastname' => $value->lastname, 'order_no' => $value->order_number, 'amount' => number_format($value->payment_amount, 2, '.', ''), 'multipleProductDetails' => $multiple_quote_items);
                if(Carbon::now()->toDateString() > Carbon::parse($value->payment_due_date)->format('Y-m-d')){
                    //Mail::to($value->email)->send(new PaymentDueAlreadyMail($data));
                    continue;
                }
                $diff_days = Carbon::parse($value->payment_due_date)->diffInDays(now()->format('Y-m-d'), true);
                $days_list = Settings::where('key', 'payment_due')->pluck('value');
                if(in_array($diff_days, explode(',',$days_list))){
                    try {
                        Mail::to($value->email)->send(new PaymentDueMail($data));
                        //send to admin
                        Mail::to($admin_email)->bcc($ccUsers)->send(new PaymentDueAdmin($data));
                    } catch (\Exception $e) {
                        //echo 'Error - ' . $e;
                        continue;
                    }
                }
            }
        }

    }
}
