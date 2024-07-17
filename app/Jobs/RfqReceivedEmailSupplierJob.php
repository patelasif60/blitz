<?php

namespace App\Jobs;

use App\Mail\RfqReceivedEmailToSupplier;
use App\Models\Notification;
use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\Supplier;
use App\Models\SupplierDealWithCategory;
use App\Models\SupplierProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RfqReceivedEmailSupplierJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $id;
     protected $verify;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->verify = app('App\Twilloverify\TwilloService');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rfq = Rfq::find($this->id);
        $ccUsers = \Config::get('static_arrays.bccusers');

        if($rfq->is_preferred_supplier == 1){
            $rfqSuppliers = Rfq::join('preferred_suppliers_rfqs','rfqs.id','=','preferred_suppliers_rfqs.rfq_id')->where('rfqs.id',$this->id)->groupBy('supplier_id')->get(['supplier_id']);
            foreach ($rfqSuppliers as $rfqSupplier){
                $supplier = Supplier::where('id',$rfqSupplier->supplier_id)->first(['id','name','contact_person_email','alternate_email']);
                Mail::to($supplier->contact_person_email)->cc($supplier->alternate_email)->bcc($ccUsers)->send(new RfqReceivedEmailToSupplier($rfq,$supplier->name));
            }
        } else {
            $rfqProducts = RfqProduct::where('rfq_id',$this->id)->pluck('sub_category_id')->toArray();
            $rfqCategories = array_unique($rfqProducts);
            $supplierProducts = SupplierProduct::with('products')->whereHas('products', function($q) use($rfqCategories){
                $q->whereIn('subcategory_id',$rfqCategories);
            })->get();
            $getSupplierProductsSupplierIds = $supplierProducts->pluck('supplier_id')->toArray();
            $supplierDealWithCategory = SupplierDealWithCategory::where('sub_category_id',$rfqCategories)->pluck('supplier_id')->toArray();
            $suppliersAll = array_merge($getSupplierProductsSupplierIds,$supplierDealWithCategory);
            $rfqSuppliers = array_unique($suppliersAll);
            foreach ($rfqSuppliers as $rfqSupplier){
                $supplier = Supplier::with(['user'])->where('id',$rfqSupplier)->first();
                //only goes to that supplier who r in use_supplier table
                if($supplier->user){
                    $sendMsg = $this->verify->sendMsg($supplier->user->firstname,$supplier->user->lastname,'rfq_received',$supplier->user->phone_code, $supplier->user->mobile);
                }
                Mail::to($supplier->contact_person_email)->cc($supplier->alternate_email)->bcc($ccUsers)->send(new RfqReceivedEmailToSupplier($rfq,$supplier->name));
            }
        }

    }
}

