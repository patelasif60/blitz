<?php

namespace App\Jobs;
use App\Mail\GroupExpireBuyer;
use App\Models\Groups;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class GroupExpireBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $id;
    public $url;
    public $shareLinks;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,$url,$shareLinks)
    {
        $this->id = $id;
        $this->url = $url;
        $this->shareLinks = $shareLinks;
        //dd($this->$data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $group = Groups::find($this->id);
        $ccUsers = \Config::get('static_arrays.bccusers');
        $groupBuyers = $group->groupMembersMultiple()->groupBy('user_id')->get(['user_id']);
        //dd($groupBuyers);
        foreach ($groupBuyers as $groupBuyer){
            $user = $groupBuyer->user()->first(['firstname','lastname','email']);
            $buyer_name = $user->firstname .' '.$user->lastname;
            Mail::to($user->email)->bcc($ccUsers)->queue(new GroupExpireBuyer($group,$buyer_name,$this->url,$this->shareLinks));
        }
    }
}
