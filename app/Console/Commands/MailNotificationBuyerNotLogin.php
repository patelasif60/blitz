<?php

namespace App\Console\Commands;
use App\Jobs\Admin\BackOffice\MailNotification\BuyerNotLoginRfqPlaceJob;
use Illuminate\Console\Command;

class MailNotificationBuyerNotLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MailNotificationBackoffice:BuyerNotLoginAndPlaceRfq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail Repoart for Rfq & Buyer not login';

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
        dispatch(new BuyerNotLoginRfqPlaceJob());
    }
}
