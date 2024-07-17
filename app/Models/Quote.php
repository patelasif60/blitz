<?php

namespace App\Models;

use App\Jobs\SetExpireOnInvoice;
use App\Models\MongoDB\ChatGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\SystemActivities;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Quote extends Model
{
    use HasFactory, SystemActivities, HybridRelations;

    const PARTIAL_QUOTE = 5;

    protected $connection = 'mysql';
    protected $tagname = "Quotes";
    protected $table = 'quotes';
    protected $guarded = [];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    /*
     * Munir
     * date:-30/5/2022
     * quote update on group invoice payment
     * */
    public static function groupQuoteUpdateOnPayment($quoteId,$quoteData){
        $quote = self::where(['id' => $quoteId])->first();
        if ($quote->final_amount != $quoteData->final_amount) {
            $quote->final_amount = $quoteData->final_amount;
            $quote->tax_value = $quoteData->tax_amount;
            $quote->supplier_final_amount = $quoteData->supplier_final_amount;
            $quote->supplier_tex_value = $quoteData->supplier_tax_amount;
            $quote->save();

            $quoteChargesWithAmount = $quote->quoteChargesWithAmount()->where('charge_name','Group Discount')->first();
            if (!empty($quoteChargesWithAmount)) {
                $quoteChargesWithAmount->charge_value = $quoteData->discount;
                $quoteChargesWithAmount->charge_amount = $quoteData->group_discount;
                $quoteChargesWithAmount->save();
            }else{
                $chargeDetail = OtherCharge::where('name','Group Discount')->first();
                $quoteChargesWithAmount = new QuoteChargeWithAmount;
                $quoteChargesWithAmount->quote_id = $quoteId;
                $quoteChargesWithAmount->charge_id = $chargeDetail->id;
                $quoteChargesWithAmount->charge_name = $chargeDetail->name;
                $quoteChargesWithAmount->value_on = $chargeDetail->value_on;
                $quoteChargesWithAmount->addition_substraction = $chargeDetail->addition_substraction;
                $quoteChargesWithAmount->type = $chargeDetail->type;
                $quoteChargesWithAmount->charge_value = $quoteData->discount;
                $quoteChargesWithAmount->charge_amount = $quoteData->group_discount;
                $quoteChargesWithAmount->charge_type = $chargeDetail->charges_type;
                $quoteChargesWithAmount->save();
            }

            //cancel old active invoice
            $activeInvoice = $quote->groupTransaction()->where('status','PENDING')->first(['id','user_id','invoice_id']);
            if(!empty($activeInvoice)) {
                dispatch(new SetExpireOnInvoice($activeInvoice));
            }
        }
    }

    /*
     * Munir
     * date:-31/5/2022
     * quote final amount calculate
     * $isRealTime : 1 = realtime final amount or 0 = current final amount
     * */
    public static function calculateGroupFinalAmount($quoteId,$isRealTime=1){
        $quote = self::where(['id' => $quoteId])->first();
        $group = $quote->rfq->group()->first();
        $quoteItem = $quote->quoteItems()->first(['product_quantity','product_price_per_unit']);
        $orderQty = $quoteItem->product_quantity;
        $productRealPrice = $quoteItem->product_price_per_unit;
        $productTotalAmount = self::getProductTotalAmount($group,$orderQty,$productRealPrice,$isRealTime);
        $subTotalAmount = $productTotalAmount;
        $quoteChargesWithAmounts = $quote->quoteChargesWithAmounts()->get();
        $supplierAmount = self::calculateSupplierAmount($quote,$quoteChargesWithAmounts,$subTotalAmount);

        foreach ($quoteChargesWithAmounts->where('charge_type','!=',2) as $quoteCharge) {
            if (empty($quoteCharge->charge_amount) || $quoteCharge->charge_name=="Group Discount"){
                continue;
            }
            $isPlus = $quoteCharge->addition_substraction;
            if ($isPlus){
                $subTotalAmount = $subTotalAmount+$quoteCharge->charge_amount;
            }else{
                $subTotalAmount = $subTotalAmount-$quoteCharge->charge_amount;
            }
        }
        $taxAmount = self::getTaxAmount($quote,$subTotalAmount);
        $transactionCharge = $quoteChargesWithAmounts->where('charge_type',2)->sum('charge_amount');
        $finalAmount = $subTotalAmount+$taxAmount+$transactionCharge;
        return array_merge(['final_amount'=>round($finalAmount),'tax_amount'=>$taxAmount],$supplierAmount);
    }

    /*
     * Munir
     * date:-31/5/2022
     * quote supplier final amount calculate
     * */
    public static function calculateSupplierAmount($quote,$quoteChargesWithAmounts,$subTotalAmount){
        foreach ($quoteChargesWithAmounts->where('charge_type',0) as $quoteCharge) {
            if (empty($quoteCharge->charge_amount) || $quoteCharge->charge_name=="Group Discount"){
                continue;
            }
            $isPlus = $quoteCharge->addition_substraction;
            if ($isPlus){
                $subTotalAmount = $subTotalAmount+$quoteCharge->charge_amount;
            }else{
                $subTotalAmount = $subTotalAmount-$quoteCharge->charge_amount;
            }
        }
        $taxAmount = self::getTaxAmount($quote,$subTotalAmount);
        return ['supplier_final_amount' => round($subTotalAmount+$taxAmount), 'supplier_tax_amount' => $taxAmount];
    }

    /*
     * Munir
     * date:-31/5/2022
     * get group discount amount on order qty
     * $isRealTime : 1 = realtime final amount or 0 = current final amount
     * */
    public static function getGroupDiscountAmount($group,$orderQty,$productRealPrice,$isRealTime=1){
        $achievedQty = $group->achieved_quantity;
        $totalQty = $achievedQty;
        if ($isRealTime) {
            $totalQty = $totalQty + $orderQty;
        }
        $groupDiscountOptions = $group->productDetailsMultiple()->where('min_quantity','<=',$totalQty)->where('max_quantity','>=',$totalQty)->first(['discount','discount_price']);
        $discountProductPrice =  $groupDiscountOptions->discount_price??0;
        if (empty($discountProductPrice)){//if not in range then return 0 discount
            return (object)['discount'=>0,'group_discount'=>0];
        }
        return (object)['discount'=>$groupDiscountOptions->discount,'group_discount'=>round($orderQty*($productRealPrice-$discountProductPrice))];//order qty * (Product real price - discounted product price)
    }
    /*
     * Munir
     * date:-31/5/2022
     * get group product discount amount
     * $isRealTime : 1 = realtime final amount or 0 = current final amount
     * */
    public static function getProductTotalAmount($group,$orderQty,$productRealPrice,$isRealTime=1){
        $groupDiscount = self::getGroupDiscountAmount($group,$orderQty,$productRealPrice,$isRealTime);
        return round(($productRealPrice*$orderQty)-$groupDiscount->group_discount);//(Product real price * order qty) - group discount amount
    }

    /*
     * Munir
     * date:-31/5/2022
     * get calculated tax amount
     * */
    public static function getTaxAmount($quote,$subTotalAmount){
            return round(($subTotalAmount*$quote->tax)/100);
    }

    public function rfq()
    {
        return $this->belongsTo(Rfq::class,'rfq_id', 'id');
    }

    public function quoteStatus()
    {
        return $this->hasOne(QuoteStatus::class,'id','status_id');
    }

    public function quoteItem()
    {
        return $this->hasOne(QuoteItem::class);
    }

    public function quoteItems()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function productUnit(){
        return $this->belongsTo(Unit::class,'price_unit');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function quoteChargesWithAmounts(){
        return $this->hasMany(QuoteChargeWithAmount::class);
    }

    public function quoteChargesWithAmount(){
        return $this->hasOne(QuoteChargeWithAmount::class);
    }

    public function quotePlatformCharges(){
        return $this->hasMany(QuoteChargeWithAmount::class, 'quote_id')->where('charge_type', 0);
    }

    public function quoteLogisticCharges(){
        return $this->hasMany(QuoteChargeWithAmount::class, 'quote_id')->where('charge_type', 1);
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','supplier_id');
    }

    public function quotePaymentFees(){
        return $this->hasMany(QuoteChargeWithAmount::class, 'quote_id')->where('charge_type', 2);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function group(){
        return $this->belongsTo(Groups::class);
    }

    public function groupTransaction(){
        return $this->hasOne(GroupTransactions::class);
    }

    public function groupTransactions(){
        return $this->hasMany(GroupTransactions::class);
    }

    public function quoteGroupDiscountCharges(){
        return $this->hasMany(QuoteChargeWithAmount::class, 'quote_id')->where('charge_type', 0)->where('charge_name','Group Discount');
    }
    public function chatGroupQuote()
    {
        return $this->hasOne(ChatGroup::class,'chat_id','id')->where('chat_type','Quote');
    }

    /**
     *  Get User quote feedback based on quote_id
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|\Jenssegers\Mongodb\Relations\HasOne
     */
    public function getUserQuoteFeedback()
    {
        return $this->hasMany(UserQuoteFeedback::class,'quote_id','id');
    }

    //Only quote id
    public function getQuoteForApproval()
    {
        return $this->hasOne(UserQuoteFeedback::class,'quote_id', 'id');
    }
    public function rfqProduct()
    {
        return $this->hasMany(RfqProduct::class,'rfq_id','rfq_id');
    }

    public function rfqs()
    {
        return $this->hasOne(Rfq::class,'id','rfq_id');
    }

    public function userName()
    {
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public function state_name()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function rfqUser()
    {
        return $this->hasOne(UserRfq::class, 'rfq_id', 'rfq_id');
    }
    public function getUser()
    {
        return $this->belongsTo(User::class,'full_quote_by','id');
    }
    public function approvalRejectReason() {
        return $this->hasMany(ApprovalRejectReason::class);
    }

    /**
    * Get quote approval reason (Show reason to approver)
    */
    public function getQuoteApprovalReason() {
        return $this->hasMany(GetApprovalReason::class);
    }


}
