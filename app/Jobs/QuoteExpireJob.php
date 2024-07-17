<?php

namespace App\Jobs;

use App\Exports\ExportQuoteExpire;
use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class QuoteExpireJob implements ShouldQueue
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
        $quoteExpireDatas = Quote::with(['supplier:id,name', 'state_name:id,name'])
                            ->where('status_id', 3)
                            ->whereBetween('valid_till', [Carbon::now()->toDateString(), Carbon::now()->addDays(7)->toDateString()])
                            ->get()->count();

        $quoteExpireDatasList = [];
        if ($quoteExpireDatas > 0) {
            $quoteExpireDatasList = Excel::download(new ExportQuoteExpire, 'QyoteExpire_'.$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $quoteExpireDatasPath = $quoteExpireDatasList->getFile()->getRealPath();
        } else {
            Mail::send('emails.operational_notif.rqonotrespond', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('List of Quote which are expire within week.');
                }
                else {
                    $message->to(TEST_EMAILS)->subject('List of Quote which are expire within week.');
                }
            });
        }

        Mail::send('emails.operational_notif.quoteExpire', [], function($message) use($quoteExpireDatasPath, $date) {
            if (config('app.env') == "live") {
                $message->to(OPRATION_EMAILS)->subject('List of Quote which are expire within week.');
            }else {
                $message->to(TEST_EMAILS)->subject('List of Quote which are expire within week.');
            }
            $message->attach($quoteExpireDatasPath, ['as' => 'QyoteExpire_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
        });


        if (file_exists($quoteExpireDatasPath)) {
            unlink($quoteExpireDatasPath);
        }
    }
}
