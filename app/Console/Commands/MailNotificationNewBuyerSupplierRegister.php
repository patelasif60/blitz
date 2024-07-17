<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Admin\BackOffice\MailNotification\NewBuyerSupplierJob;

class MailNotificationNewBuyerSupplierRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MailNotificationBackoffice:NewBuyerSupplierRegister';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exicute for new buyer and supplier 1 day ago report send on mail';

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
        dispatch(new NewBuyerSupplierJob());
    }
}
