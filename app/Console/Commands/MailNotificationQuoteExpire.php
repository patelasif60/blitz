<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Admin\BackOffice\MailNotification\QuoteExpireJob;

class MailNotificationQuoteExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MailNotificationBackoffice:QouteExpire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MailNotifications for Quote Expire';

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
        dispatch(new QuoteExpireJob());
    }
}
