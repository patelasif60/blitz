<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Admin\BackOffice\MailNotification\RfqQuoteOrderNotRespondReportOnMailJob;

class MailNotificationRfqQuoteOrderNotRespons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MailNotificationBackoffice:RfqQuoteOrderNotRespons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This cron job use Rfq Quote and Order Not Respons send report on mail';

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
        dispatch(new RfqQuoteOrderNotRespondReportOnMailJob());
    }
}
