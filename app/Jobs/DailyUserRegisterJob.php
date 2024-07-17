<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportDailyByrList;


class DailyUserRegisterJob implements ShouldQueue
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
        $supplierList = DB::table('suppliers')->where('created_at', '>=', Carbon::yesterday())->get()->count();
        $buyerLists = DB::table("users")->where('created_at', '>=', Carbon::yesterday())->where('role_id',2)->get()->count();
        if ($buyerLists > 0 || $supplierList > 0) {
            $buyerList = Excel::download(new ExportDailyByrList('buyer'), 'buyerList_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $buyerpath = $buyerList->getFile()->getRealPath();

            $supplierList = Excel::download(new ExportDailyByrList('supplier'), 'supplierList_'.$date.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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

            if (file_exists($buyerpath) || file_exists($supplierpath)) {
                unlink($buyerpath);
                unlink($supplierpath);
            }
        } else {
            Mail::send('emails.operational_notif.byrSpyList', [], function($message) {
                if (config('app.env') == "live") {
                        $message->to(OPRATION_EMAILS)->subject('User not Registered Yesterday');
                } else {
                        $message->to(TEST_EMAILS)->subject('User not Registered Yesterday.');
                    }
            });
        }
    }
}
