<?php

namespace App\Console\Commands;

use App\Mail\DemoMail;
use App\Mail\SendActivationMailToUser;
use App\Models\Company;
use App\Models\OrderTrack;
use App\Models\Quote;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class DemoSendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
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
        $quotes = Quote::all();
        foreach ($quotes as $quote){
            $finalAmount = $quote->product_amount;
            $id = $quote->id;
            $tax = $quote->tax;
            $quotes_charges_with_amounts = DB::table(('quotes_charges_with_amounts'))
                ->where('quotes_charges_with_amounts.quote_id', $id)
                ->where('quotes_charges_with_amounts.charge_type', 0)
                ->orderBy('created_at', 'desc')->get();
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

            $calculate_supplier_final_amount = $totalAmount + $taxamount;
            $calculate_supplier_tax_value = $taxamount;

            Quote::where('id', $id)->update(['supplier_final_amount' => $calculate_supplier_final_amount, 'supplier_tex_value' => $calculate_supplier_tax_value]);
        }
    }
}
