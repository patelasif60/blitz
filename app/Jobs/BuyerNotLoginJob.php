<?php

namespace App\Jobs;

use App\Exports\ExportBuyerNotLogin;
use App\Exports\ExportBuyerNotPlaceRFQ;
use App\Models\SystemActivity;
use App\Models\User;
use App\Models\UserRfq;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class BuyerNotLoginJob implements ShouldQueue
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

        // User (buyer) not login from last 7 days list.
        $buyerNotlogins = SystemActivity::with('user:id,firstname,lastname')
                                        ->where('action', 'LOGGED IN')
                                        ->whereNotBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                                        ->get()->count();

        if ($buyerNotlogins > 0) {
            $buyerNotloginsList = Excel::download(new ExportBuyerNotLogin(), 'buyer_not_login_' .$date. '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $buyerNotloginsListPath = $buyerNotloginsList->getFile()->getRealPath();
        } else {
            Mail::send('emails.operational_notif.byrnotlogin', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
                } else {
                    $message->to(TEST_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
                }
            });
        }

        // Buyer not place any rfq from last 7 days.
        $userLists = UserRfq::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->pluck('user_id')->toArray();
        $notplaceRfqUserLists= User::with('defaultCompany:id,name')
                                    ->where('role_id', 2)
                                    ->whereNotIn('id', $userLists)
                                    ->get(['firstname', 'lastname', 'email', 'phone_code', 'mobile', 'assigned_companies', 'default_company'])
                                    ->count();

        if ($notplaceRfqUserLists > 0) {
            $notplaceRfqUserLists = Excel::download(new ExportBuyerNotPlaceRFQ(), 'RFQNotplace_' .$date. '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            $notplaceRfqUserListsPath = $notplaceRfqUserLists->getFile()->getRealPath();
        } else {
            Mail::send('emails.operational_notif.byrnotlogin', [], function($message) {
                if (config('app.env') == "live") {
                    $message->to(OPRATION_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
                } else {
                    $message->to(TEST_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
                }
            });
        }

        Mail::send('emails.operational_notif.byrnotlogin', [], function($message) use($buyerNotloginsListPath, $notplaceRfqUserListsPath, $date) {
            if (config('app.env') == "live") {
                $message->to(OPRATION_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
            }else {
                $message->to(TEST_EMAILS)->subject('List of Buyer which are not loged in since last 7 days.');
            }
            $message->attach($buyerNotloginsListPath, ['as' => 'buyer_not_login_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
            $message->attach($notplaceRfqUserListsPath, ['as' => 'RFQNotplace_'. $date .'.xlsx', \Maatwebsite\Excel\Excel::XLSX]);
        });

        if (file_exists($buyerNotloginsListPath)) {
            unlink($buyerNotloginsListPath);
        }

        if (file_exists($notplaceRfqUserListsPath)) {
            unlink($notplaceRfqUserListsPath);
        }
    }
}
