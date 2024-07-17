<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MultipleProductOldDataToNewTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:multipleProductOldDataToNewTable';

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
        Artisan::call('migrate',array('--path' => '/database/migrations/2022_03_01_190857_add_field_ids_for_rfq_multiple_rfq_products_table.php'));
        Artisan::call('rfqProductItemNumber:cron');
        Artisan::call('migrate',array('--path' => '/database/migrations/2022_03_16_173715_create_quote_items_table.php'));
        Artisan::call('quoteDataToItem:cron');
        Artisan::call('migrate',array('--path' => '/database/migrations/2022_03_17_163543_add_fields_to_orders_table2.php'));
        Artisan::call('migrate',array('--path' => '/database/migrations/2022_03_22_184958_remove_coulumns_quotes_table.php'));
        Artisan::call('migrate',array('--path' => '/database/migrations/2022_03_31_170159_create_order_item_statuses_table.php'));
        Artisan::call('migrate',array('--path' => '/database/migrations/2022_03_31_170160_create_order_items_table.php'));
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement("
        INSERT INTO `order_item_status` (`id`, `name`, `description`, `status`, `sort`, `created_at`) VALUES
(1, 'Order Received', 'Order Received', 1, 1, '2021-11-01 11:30:00'),
(2, 'Order Confirmed & Payment Pending ', 'Order Confirmed & Payment Pending ', 1, 2, '2021-11-01 11:30:00'),
(3, 'Payment Done', 'Payment Done', 1, 4, '2021-11-01 11:30:00'),
(4, 'Under Preparation', 'Under Preparation', 1, 5, '2021-11-01 11:30:00'),
(5, 'Ready to Dispatch', 'Ready to Dispatch', 1, 6, '2021-11-01 11:30:00'),
(6, 'Order Picked-up', 'Order Picked-up', 1, 7, '2021-11-01 11:30:00'),
(7, 'In Transit', 'In Transit', 1, 8, '2021-11-01 11:30:00'),
(8, 'Out for Delivery', 'Out for Delivery', 1, 9, '2021-11-01 11:30:00'),
(9, 'Under QC', 'Under QC', 1, 10, '2021-11-01 11:30:00'),
(10, 'QC Failed', 'QC Failed', 1, 11, '2021-11-01 11:30:00'),
(11, 'QC Passed', 'QC Passed', 1, 12, '2021-11-01 11:30:00'),
(12, 'Order Troubleshooting', 'Order Troubleshooting', 1, 13, '2021-11-01 11:30:00'),
(13, 'Delivered', 'Delivered', 1, 14, '2021-11-01 11:30:00'),
(14, 'Order Completed', 'Order Completed', 1, 15, '2021-11-01 11:30:00'),
(15, 'Order Returned', 'Order Returned', 1, 16, '2021-11-01 11:30:00'),
(16, 'Order Cancelled', 'Order Cancelled', 1, 17, '2021-11-01 11:30:00'),
(17, 'Payment Due on %s', 'Payment Due DD/MM/YYYY', 1, 18, '2021-11-01 11:30:00'),
(18, 'Credit Approved', 'Credit Approved', 1, 19, '2021-11-01 11:30:00'),
(19, 'Credit Rejected', 'Credit Rejected', 1, 3, '2021-11-01 11:30:00');");
        Artisan::call('orderDataToItem:cron');
        DB::statement("TRUNCATE TABLE order_item_status;");
        DB::statement("
        INSERT INTO `order_item_status` (`id`, `name`, `description`, `status`, `sort`, `created_at`, `deleted_at`) VALUES
(1, 'Under Preparation', 'Under Preparation', 1, 1, '2022-03-31 05:24:11', NULL),
(2, 'Ready to Dispatch', 'Ready to Dispatch', 1, 2, '2022-03-31 05:24:11', NULL),
(3, 'Order Picked-up', 'Order Picked-up', 1, 3, '2022-03-31 05:24:11', NULL),
(4, 'In Transit', 'In Transit', 1, 4, '2022-03-31 05:24:11', NULL),
(5, 'Out for Delivery', 'Out for Delivery', 1, 5, '2022-03-31 05:24:11', NULL),
(6, 'Under QC', 'Under QC', 1, 6, '2022-03-31 05:24:11', NULL),
(7, 'QC Failed', 'QC Failed', 1, 7, '2022-03-31 05:24:11', NULL),
(8, 'QC Passed', 'QC Passed', 1, 8, '2022-03-31 05:24:11', NULL),
(9, 'Order Troubleshooting', 'Order Troubleshooting', 1, 9, '2022-03-31 05:24:11', NULL),
(10, 'Delivered', 'Delivered', 1, 10, '2022-03-31 05:24:11', NULL);");
        Artisan::call('changeOrderStatus:cron');
        DB::statement("TRUNCATE TABLE order_status;");
        DB::statement("
        INSERT INTO `order_status` (`id`, `name`, `description`, `status`, `parent_id`, `show_order_id`, `credit_sorting`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 'Order Received', 'Order Received', 1, NULL, 1, 1, '2021-11-01 06:00:00', '2021-11-01 06:00:00', 0),
(2, 'Order Confirmed & Payment Pending ', 'Order Confirmed & Payment Pending ', 1, NULL, 2, 2, '2021-11-01 06:00:00', '2021-11-01 06:00:00', 0),
(3, 'Payment Done', 'Payment Done', 1, NULL, 4, 7, '2021-11-01 06:00:00', '2022-03-31 05:25:29', 0),
(4, 'Order in Progress', 'Order in Progress', 1, NULL, 5, 5, '2021-11-01 00:30:00', '2022-03-31 00:08:56', 0),
(5, 'Order Completed', 'Order Completed', 1, NULL, 6, 8, '2021-11-01 00:30:00', '2022-03-31 00:10:11', 0),
(6, 'Order Returned', 'Order Returned', 1, NULL, 7, 9, '2021-11-01 06:00:00', '2022-03-31 05:40:17', 0),
(7, 'Order Cancelled', 'Order Cancelled', 1, NULL, 8, 10, '2021-11-01 06:00:00', '2022-03-31 05:40:21', 0),
(8, 'Payment Due on %s', 'Payment Due DD/MM/YYYY', 1, NULL, 9, 6, '2021-11-01 06:00:00', '2022-03-31 05:39:09', 0),
(9, 'Credit Approved', 'Credit Approved', 1, NULL, 10, 3, '2021-11-01 06:00:00', '2022-03-31 05:39:14', 0),
(10, 'Credit Rejected', 'Credit Rejected', 1, NULL, 3, 4, '2021-11-01 06:00:00', '2021-11-01 06:00:00', 0);");
        Artisan::call('migrate');
        DB::statement('UPDATE `rfq_products` ft JOIN categories st ON st.name = ft.category SET ft.category_id = st.id WHERE st.name = ft.category;');
        DB::statement('UPDATE `rfq_products` ft JOIN products st ON st.name = ft.product SET ft.product_id = st.id WHERE st.name = ft.product;');
        DB::statement('UPDATE `rfq_products` ft JOIN sub_categories st ON st.name = ft.sub_category SET ft.sub_category_id = st.id WHERE st.name = ft.sub_category;');
        Artisan::call('orderTrackDBChange:cron');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Artisan::call('command:ChangeOrderActivityStatus');
        Artisan::call('command:ChangeOrderPaymentStatus');
        Artisan::call('optimize:clear');
        return 'Done';

        // check in live scenario
        /*
         * check all category with proper value not 0 or null
         *
         * */
    }
}
