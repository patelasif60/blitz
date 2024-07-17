<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class Order extends Model
{
    use HasFactory, SystemActivities;

    const PAYMENTPENDING = 1;
    const PAYMENTOVERDUE = 2;
    const OFFLINEPAID = 3;
    const DISBURSMENTPENDING = 4;
    const DISBURSMENTCOMPLETED = 5;
    const ORDERCANCELLED = 6;

    const ORDER_UNPAID = 0;
    const ORDER_ONLINE_PAID = 1;
    const ORDER_OFFLINE_PAID = 2;
    const ORDER_LOAN_PAID = 3;

    const ADVANCED = 0;
    const SUPPLIER_CREDIT = 1;
    const LENDER_CREDIT = 2;

    protected $tagname = "Order";

    protected $fillable = [
        'user_id',
        'company_id',
        'group_id',
        'quote_id',
        'rfq_id',
        'supplier_id',
        'order_number',
        'is_credit',
        'payment_amount',
        'payment_due_date',
        'payment_status',
        'payment_date',
        'min_delivery_date',
        'max_delivery_date',
        'address_name',
        'address_line_1',
        'address_line_2',
        'city',
        'city_id',
        'sub_district',
        'district',
        'pincode',
        'country_one_id',
        'state',
        'state_id',
        'country_one_id',
        'order_status',
        'items_manage_separately',
        'otp_supplier',
        'tax_receipt',
        'invoice',
        'pickup_date',
        'pickup_time',
        'customer_reference_id',
        'is_deleted',
        'order_latter',
        'adjustment_amount',
        'payment_type',
        'credit_days'
    ];

    public function userrfqName()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public static function getAllOrderDetails($inArray=1,$where=[],$select=''){
        if (empty($select)){
            $select = 'orders.*,ocd.request_days,ocd.approved_days,ocd.status as request_days_status,order_status.name as order_status_name,quotes.quote_number,quotes.final_amount,
            rfqs.reference_number as rfq_reference_number,users.firstname,users.lastname,companies.name as company_name,products.name as product_name,
            rfq_products.product_description as product_description,sub_categories.name as sub_category_name,categories.name as category_name,
            suppliers.contact_person_name as supplier_name,suppliers.name as supplier_company_name';
        }
        $orders = DB::table('orders')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->join('products', 'quotes.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where($where)
            ->orderBy('orders.id', 'desc')
            ->selectRaw($select)
            ->get();
        if ($inArray){//return in array
            return json_decode(json_encode($orders),true);
        }
        return $orders;
    }

    public static function getOrderDetails($inArray=1,$where=[],$select=''){
        if (empty($select)){
            $select = 'orders.*,ocd.request_days,ocd.approved_days,ocd.status as request_days_status,order_status.name as order_status_name,quotes.quote_number,quotes.final_amount,
            rfqs.reference_number as rfq_reference_number,users.firstname,users.lastname,companies.name as company_name,products.name as product_name,
            rfq_products.product_description as product_description,sub_categories.name as sub_category_name,categories.name as category_name,
            suppliers.contact_person_name as supplier_name,suppliers.name as supplier_company_name';
        }
        $orders = DB::table('orders')
            ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
            ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
            ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            ->join('companies', 'user_companies.company_id', '=', 'companies.id')
            ->join('products', 'quotes.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where($where)
            ->orderBy('orders.id', 'desc')
            ->selectRaw($select)
            ->first();
        if ($inArray){//return in array
            return json_decode(json_encode($orders),true);
        }
        return $orders;
    }

    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class,'order_status');
    }

    public function orderStatusTrack(){
        return $this->hasOne(OrderTrack::class);
    }

    public function group(){
        return $this->belongsTo(Groups::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function quote(){
        return $this->belongsTo(Quote::class);
    }

    public function rfq(){
        return $this->belongsTo(Rfq::class);
    }

    public function orderCreditDay(){
        return $this->hasOne(OrderCreditDays::class);
    }

    public function orderTransaction(){
        return $this->hasOne(OrderTransactions::class);
    }

    public function orderTransactions(){
        return $this->hasMany(OrderTransactions::class);
    }

    public function groupTransaction(){
        return $this->hasOne(GroupTransactions::class);
    }

    public function groupTransactions(){
        return $this->hasMany(GroupTransactions::class);
    }

    public function bulkOrderPayment(){
        return $this->hasOne(BulkOrderPayments::class);
    }

    /**
     * Bulk order payments many
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bulkOrderPayments(){
        return $this->hasMany(BulkOrderPayments::class);
    }

    public function disbursement(){
        return $this->hasOne(Disbursements::class);
    }

    public function disbursements(){
        return $this->hasMany(Disbursements::class);
    }

    public function orderPo(){
        return $this->hasOne(OrderPo::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class,'order_id','id');
    }

    public function orderActivities(){
        return $this->hasMany(OrderActivity::class);
    }
    public function chattypes(){
        return $this->morphMany(GroupChat::class, 'chat');
    }

    /**
     * Get order company details.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function companyDetails()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship b/n Loan Apply and Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanApply()
    {
        return $this->hasOne(LoanApply::class,'order_id','id');
    }
    /**
     * get payment transaction data
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function paymentProviderTransaction(){
        return $this->morphMany(PaymentProviderTransaction::class,'related');
    }

    /**
     * Relationship B/n Loan Transaction and Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanTransactions()
    {
        return $this->hasMany(LoanTransaction::class,'order_id','id');
    }
    public function rfqProduct()
    {
        return $this->hasMany(RfqProduct::class,'rfq_id','rfq_id');
    }
    public function unit()
    {
        return $this->hasMany(Unit::class,'rfq_id','rfq_id');
    }
    public function OrderTrack()
    {
        return $this->hasMany(OrderTrack::class);
    }
    public function orderItemTracks()
    {
        return $this->hasMany(OrderItemTracks::class);
    }
    public function City(){
        return $this->belongsTo(City::class);
    }
    public function State(){
        return $this->belongsTo(State::class);
    }
    public function quoteItem()
    {
        return $this->hasMany(QuoteItem::class,'quote_id','quote_id')->whereNull('deleted_at');
    }
}
