<?php

namespace App\Console\Commands;

use App\Models\OrderItemTracks;
use App\Models\OrderTrack;
use Illuminate\Console\Command;

class OrderTrackDBChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orderTrackDBChange:cron';

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
        $all_order = OrderTrack::where('status_id', '>=', 4)->where('status_id', '<=', 13)->get();
        foreach ($all_order as $key => $value){

            $ois = json_decode($value->toJson(),true);

            unset($ois['id']);
            unset($ois['user_type']);
            unset($ois['is_deleted']);
            $ois['order_item_id'] = $ois['order_id'];
            $ois['status_id'] = 1;
            if ($value->status_id == 5){
                $ois['status_id'] = 2;
            } else if ($value->status_id == 6){
                $ois['status_id'] = 3;
            } else if ($value->status_id == 7){
                $ois['status_id'] = 4;
            } else if ($value->status_id == 8){
                $ois['status_id'] = 5;
            } else if ($value->status_id == 9){
                $ois['status_id'] = 6;
            } else if ($value->status_id == 10){
                $ois['status_id'] = 7;
            } else if ($value->status_id == 11){
                $ois['status_id'] = 8;
            } else if ($value->status_id == 12){
                $ois['status_id'] = 9;
            } else if ($value->status_id == 13){
                $ois['status_id'] = 10;
            }

            OrderItemTracks::insert($ois);

            OrderTrack::where('id', $value->id)->update(['status_id' => 4]);
        }

        OrderTrack::where('status_id', 19)->update(['status_id' => 10]);
        OrderTrack::where('status_id', 18)->update(['status_id' => 9]);
        OrderTrack::where('status_id', 17)->update(['status_id' => 8]);
        OrderTrack::where('status_id', 16)->update(['status_id' => 7]);
        OrderTrack::where('status_id', 15)->update(['status_id' => 6]);
        OrderTrack::where('status_id', 14)->update(['status_id' => 5]);

        $get_multiple_record = OrderTrack::where('status_id', 4)->select('order_id', 'id')->groupBy('order_id')->get();
        foreach ($get_multiple_record as $key => $value){
            $get_all_record_same_satatus = OrderTrack::where('order_id', $value->order_id)->where('status_id', 4)->where('id', '<>', $value->id)->get(['id']);
            foreach ($get_all_record_same_satatus as $rec_del){
                $rec_del->delete();
            }
        }
        return 'Done';
    }
}
