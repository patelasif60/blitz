<?php

namespace App\Jobs;

use App\Mail\OrderPlacedConfirmationToSupplier;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Supplier;

class SendOrderConfirmMailToSupplierJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $orderData;
    protected $verify;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $orderData)
    {
        $this->orderData = $orderData;
        $this->verify = app('App\Twilloverify\TwilloService');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->orderData->otp_supplier = rand(100000, 999999);
        $this->orderData->save();
        $bccUsers = \Config::get('static_arrays.bccusers');
        $supplier = $this->orderData->supplier()->first(['id','contact_person_email', 'alternate_email']);

        $smsData['order_number'] = $this->orderData->order_number;

        $supplierUser = Supplier::with(['user'])->where('id',$supplier->id)->first();
        $sendMsg = $this->verify->sendMsg($supplierUser->user->firstname,$supplierUser->user->lastname,'order_received',$supplierUser->user->phone_code,$supplierUser->user->mobile,$smsData); 
        Mail::to($supplier->contact_person_email)->cc($supplier->alternate_email)->bcc($bccUsers)->queue(new OrderPlacedConfirmationToSupplier($this->orderData));
    }
}
