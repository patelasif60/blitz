<?php

namespace App\Console\Commands;

use App\Models\OrderActivity;
use Illuminate\Console\Command;

class ChangeOrderActivityStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ChangeOrderActivityStatus';

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

        $results = OrderActivity::where(['key_name'=>'status'])->orWhere('key_name','order_latter')->get();
        foreach ($results as $key => $value){
            if ($value->id==1665){
                break;
            }
            if ($value->key_name == 'status') {
                //if($value->order->is_credit){

                //}else {
                    $old_value = $value->old_value;
                    $isMainForOld = 0;
                    if ($value->old_value == 1) {
                        $isMainForOld = 1;
                    } elseif($value->old_value == 2) {
                        $isMainForOld = 1;
                    } elseif($value->old_value == 3) {
                        $isMainForOld = 1;
                        $old_value = null;
                    } elseif ($value->old_value == 4) {
                        $old_value = 1;
                    } else if ($value->old_value == 5) {
                        $old_value = 2;
                    } else if ($value->old_value == 6) {
                        $old_value = 3;
                    } else if ($value->old_value == 7) {
                        $old_value = 4;
                    } else if ($value->old_value == 8) {
                        $old_value = 5;
                    } else if ($value->old_value == 9) {
                        $old_value = 6;
                    } else if ($value->old_value == 10) {
                        $old_value = 7;
                    } else if ($value->old_value == 11) {
                        $old_value = 8;
                    } else if ($value->old_value == 12) {
                        $old_value = 9;
                    } else if ($value->old_value == 13) {
                        $old_value = 10;
                    } elseif ($value->old_value == 14){
                        $isMainForOld = 1;
                        $old_value = 5;
                    } elseif ($value->old_value == 15){
                        $isMainForOld = 1;
                        $old_value = 6;
                    } elseif ($value->old_value == 16){
                        $isMainForOld = 1;
                        $old_value = 7;
                    } elseif ($value->old_value == 17){
                        $isMainForOld = 1;
                        $old_value = 8;
                    } elseif ($value->old_value == 18){
                        $isMainForOld = 1;
                        $old_value = 9;
                    } elseif ($value->old_value == 19){
                        $isMainForOld = 1;
                        $old_value = 10;
                    }
                    $new_value = $value->new_value;
                    $isMainForNew = 0;
                    if ($value->new_value == 1) {
                        $isMainForNew = 1;
                    } elseif($value->new_value == 2) {
                        $isMainForNew = 1;
                    } elseif($value->new_value == 3) {
                        $isMainForNew = 1;
                        $new_value = null;
                    } elseif ($value->new_value == 4) {
                        $new_value = 1;
                    } else if ($value->new_value == 5) {
                        $new_value = 2;
                    } else if ($value->new_value == 6) {
                        $new_value = 3;
                    } else if ($value->new_value == 7) {
                        $new_value = 4;
                    } else if ($value->new_value == 8) {
                        $new_value = 5;
                    } else if ($value->new_value == 9) {
                        $new_value = 6;
                    } else if ($value->new_value == 10) {
                        $new_value = 7;
                    } else if ($value->new_value == 11) {
                        $new_value = 8;
                    } else if ($value->new_value == 12) {
                        $new_value = 9;
                    } else if ($value->new_value == 13) {
                        $new_value = 10;
                    } elseif ($value->new_value == 14){
                        $isMainForNew = 1;
                        $new_value = 5;
                    } elseif ($value->new_value == 15){
                        $isMainForNew = 1;
                        $new_value = 6;
                    } elseif ($value->new_value == 16){
                        $isMainForNew = 1;
                        $new_value = 7;
                    } elseif ($value->new_value == 17){
                        $isMainForNew = 1;
                        $new_value = 8;
                    } elseif ($value->new_value == 18){
                        $isMainForNew = 1;
                        $new_value = 9;
                    } elseif ($value->new_value == 19){
                        $isMainForNew = 1;
                        $new_value = 10;
                    }
                //}
                if ($isMainForOld===1 && $isMainForNew===1){
                    if ($value->old_value>3) {
                        $value->old_value = $old_value;
                    }else{
                        $value->old_value = $value->old_value;
                    }
                    if ($value->new_value>3) {
                        $value->new_value = $new_value;
                    }else{
                        $value->new_value = $value->new_value;
                    }
                    $value->save();
                }elseif($isMainForOld===0 && $isMainForNew===1){
                    $value->old_value = 4;
                    $value->new_value = $new_value;
                    $value->save();
                }elseif ($isMainForOld===1 && $isMainForNew===0){
                    $value->old_value = null;
                    $value->new_value = $new_value;
                    $value->key_name = 'order_item_status';
                    $value->order_item_id =$value->order_id;
                    $value->save();
                    OrderActivity::createOrUpdateOrderActivity(['order_id'=>$value->order_id, 'order_item_id'=>null, 'user_id'=>$value->user_id, 'key_name'=>'status', 'old_value'=>$old_value??3, 'new_value'=>4, 'user_type'=>$value->user_type, 'is_deleted'=>$value->user_type, 'created_at'=>date('Y-m-d H:i:s',strtotime($value->created_at)-1)]);
                }elseif($isMainForOld===0 && $isMainForNew===0){
                    $value->old_value = $old_value;
                    $value->new_value = $new_value;
                    $value->key_name = 'order_item_status';
                    $value->order_item_id =$value->order_id;
                    $value->save();
                }
            } else {
                OrderActivity::where('id', $value->id)->update(['order_item_id' => $value->order_id]);
            }
        }
        return 'done';
    }
}
