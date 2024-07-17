<?php

namespace App\Console\Commands;

use App\Exports\ExportQuoteExpire;
use App\Jobs\QuoteExpireJob;
use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class QuoteExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'QuoteExpire:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of Quote Expire within a week.';

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
        $date = Carbon::today()->toDateString();
        $from = Carbon::now()->toDateString();
        $to = Carbon::now()->subDays(7)->toDateString();
        $quoteExpireDatas = Quote::with(['supplier:id,name', 'state_name:id,name'])
                                    ->where('valid_till', '<', $from)
                                    ->where('valid_till', '>=', $to)
                                    ->where('status_id', 3)
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
