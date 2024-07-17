<?php

namespace App\Jobs;

use App\Exports\ExportOrderNotRespond;
use App\Exports\ExportQuoteNotRespond;
use App\Exports\ExportRfqNotRespond;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Rfq;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class RfqQuoteOrderNotRespondJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date = Carbon::today()->toDateString();
        // RFQ mail send
        $rfqs = Rfq::where('status_id',1)->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())
            ->get(['firstname', 'lastname', 'phone_code', 'mobile', 'email', 'billing_tax_option', 'address_name', 'address_line_1', 'address_line_2', 'city', 'sub_district', 'district', 'state', 'pincode', 'reference_number', 'is_preferred_supplier'])->count();
        if ($rfqs > 0) {
            $rfqLists = Excel::download(new ExportRfqNotRespond, 'RFQ_'.$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $rfqListsPath = $rfqLists->getFile()->getRealPath();
        } else {
            Mail::send('emails.operational_notif.rqonotrespond', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('Rfq, Quote and Order List which is not responded last 48hr.');
                }
                else {
                    $message->to(TEST_EMAILS)->subject('Rfq, Quote and Order List which is not responded last 48hr.');
                }
            });
        }

        // Quote mail send
        $quotes = Quote::with(['userName:id,firstname,lastname', 'supplier:id,contact_person_name,contact_person_last_name'])
                        ->where('status_id', 2)
                        ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())->get()->count();

        if ($quotes > 0) {
            $quoteLists = Excel::download(new ExportQuoteNotRespond, 'Quote_'.$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $quoteListsPath = $quoteLists->getFile()->getRealPath();
        } else {
            Mail::send('emails.operational_notif.rqonotrespond', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('Rfq, Quote and Order List which is not responded last 48hr.');
                } else {
                    $message->to(TEST_EMAILS)->subject('Rfq, Quote and Order List which is not responded last 48hr.');
                }
            });
        }

        // Order mail send and & Payment not done by buyer
        $orders = Order::with(['user:id,firstname,lastname', 'company:id,name', 'group:id,name', 'supplier:id,contact_person_name,contact_person_last_name'])
                        ->where('order_status', 1)
                        ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())
                        ->get()->count();

        if ($orders > 0) {
            $orderLists = Excel::download(new ExportOrderNotRespond, 'Order_' .$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $orderListsPath = $orderLists->getFile()->getRealPath();
        } else {
            Mail::send('emails.operational_notif.rqonotrespond', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('Rfq, Quote and Order List which is not responded last 48hr.');
                } else {
                    $message->to(TEST_EMAILS)->subject('Rfq, Quote and Order List which is not responded last 48hr.');
                }
            });
        }

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
}
