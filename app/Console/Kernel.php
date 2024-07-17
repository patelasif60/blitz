<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ValidateTill::class,
        Commands\PaymentDue::class,
        Commands\DemoSendMail::class,
        Commands\InviteBuyerResend::class,
        Commands\SeedCity::class,
        Commands\SeedState::class,
        Commands\SeedCountry::class,
        Commands\KoinworksLoanLateFee::class,
        Commands\KoinworksRepaidLoanCheck::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // $schedule->command('inspire')->hourly();
        $schedule->command('queue:work --stop-when-empty --timeout=60 --tries=3')->everyMinute()->withoutOverlapping();
        $schedule->command('paymentDue:cron')->dailyAt('01:00')->timezone('Asia/Jakarta');
        $schedule->command('validateTill:cron')->dailyAt('02:00')->timezone('Asia/Jakarta');
        $schedule->command('InviteBuyerResend:cron')->dailyAt('03:00')->timezone('Asia/Jakarta');

        /******begin: City State Country Data CRONS - To restart these just uncomment the CRONS********/
        /*$schedule->command('seed:city')->twiceMonthly(1, 16, '00:00')->timezone('Asia/Jakarta');
        $schedule->command('seed:state')->twiceMonthly(1, 16, '00:00')->timezone('Asia/Jakarta');
        $schedule->command('seed:country')->twiceMonthly(1, 16, '00:00')->timezone('Asia/Jakarta');*/
        /******end: City State Country Data CRONS - To restart these just uncomment the CRONS********/

        $schedule->command('groupExpire:cron')->dailyAt('04:00')->timezone('Asia/Jakarta');
       // $schedule->command('chatUnreadMessage:cron')->everySixHours()->timezone('Asia/Jakarta')->withoutOverlapping();
        $schedule->command('LogisticsServices:cron')->dailyAt('05:00')->timezone('Asia/Jakarta');
        //$schedule->command('chatUnreadMessage:cron')->everySixHours()->timezone('Asia/Jakarta')->withoutOverlapping();

        $schedule->command('koinworksLimitStatus:cron')->everySixHours()->timezone('Asia/Jakarta');
        $schedule->command('loanDisbursementReport:cron')->everySixHours()->timezone('Asia/Jakarta');
        $schedule->command('koinworks:latefee')->dailyAt('05:30')->timezone('Asia/Jakarta');
        $schedule->command('koinworks:creditlimit')->everyFourHours()->timezone('Asia/Jakarta');

        //$schedule->command('demo:cron')->everyFiveMinutes();

        /* Other existing commented cron are under development. */
        // daily register list of buyer and supplier
        // $schedule->command('BuyerSupplierNewRegistration:cron')->daily('9:00')->timezone('Asia/Jakarta');

        // List of RFQ, Quote, Order and Payment which are not responded since last 48hr.
        // $schedule->command('RfqQuoteOrderNotRespond:cron')->daily('9:00')->timezone('Asia/Jakarta');

        // List of users which are not loged in since last 7 days.
        // $schedule->command('BuyerNotLogin:cron')->weekly()->mondays()->at('9:00')->timezone('Asia/Jakarta');

        // List of Quote Expire within last week
        // $schedule->command('QuoteExpire:cron')->weekly()->mondays()->at('9:00')->timezone('Asia/Jakarta');

        //==== New operational mail notification cron job start ==========

            // => daily register list of buyer and supplier.
        $schedule->command('MailNotificationBackoffice:NewBuyerSupplierRegister')->dailyAt('06:00')->timezone('Asia/Jakarta');

           // => List of RFQ, Quote, Order and Payment which are not responded since last 48hr.
        $schedule->command('MailNotificationBackoffice:RfqQuoteOrderNotRespons')->dailyAt('06:30')->timezone('Asia/Jakarta');

           // => List of users which are not loged in since last 7 days.
        $schedule->command('MailNotificationBackoffice:BuyerNotLoginAndPlaceRfq')->weekly()->mondays()->at('7:00')->timezone('Asia/Jakarta');

           // => List of Quote Expire within last week.
        $schedule->command('MailNotificationBackoffice:QouteExpire')->weekly()->mondays()->at('7:00')->timezone('Asia/Jakarta');

        //==== New operational mail notification cron job end ==========

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
