<?php

namespace App\Console\Commands;

use App\Models\InviteBuyer;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;
use Mail;
use Config;

class InviteBuyerResend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inviteBuyerResend:cron';

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
        $invite_buyer = DB::table('invite_buyer')->where('status', '0')->get();
        if (!empty($invite_buyer)){
            foreach ($invite_buyer as $value){
                $diff_days = Carbon::parse($value->date)->diffInDays(now()->format('Y-m-d'), true);
                if($diff_days >= 15){
                    $inviteBuyer = InviteBuyer::find($value->id);
                    $inviteBuyer->status = '2';
                    $inviteBuyer->token = NULL;
                    $inviteBuyer->save();
                }
            }
        }
    }
}
