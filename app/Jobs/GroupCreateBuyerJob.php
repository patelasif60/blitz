<?php

namespace App\Jobs;
use App\Mail\GroupCreateBuyer;
use App\Models\CompanyConsumption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class GroupCreateBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        //dd($this->$data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ccUsers = \Config::get('static_arrays.bccusers');
        $users = CompanyConsumption::where('is_deleted',0)->where('product_cat_id',$this->data['group']->category_id)->get(['user_id']);
        foreach ($users as $user){
            $user_detail = $user->user()->first(['firstname','lastname','email']);
            $buyer_name = $user_detail->firstname .' '.$user_detail->lastname;
            Mail::to($user_detail->email)->bcc($ccUsers)->queue(new GroupCreateBuyer($this->data,$buyer_name));
        }
    }
}
