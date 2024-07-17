<?php

namespace App\Models;

use App\Models\MongoDB\ChatGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Rfq extends Model
{
    use HasFactory, SystemActivities, HybridRelations;

    protected $connection = 'mysql';

    protected $tagname = "Rfq";

    protected $fillable = [
        'firstname', 'is_deleted', 'group_id',
        'lastname','phone_code','mobile','email','billing_tax_option',
        'address_id','address_name','status_id','address_line_1','address_line_2','city','sub_district','district','state','pincode',
        'reference_number','rental_forklift','unloading_services','is_require_credit','prod_weight','prod_length','prod_width','prod_height', 'attached_document',
        'termsconditions_file','city_id',
        'state_id',
        'country_one_id',
        'is_preferred_supplier',
        'country_one_id',
        'company_id',
        'payment_type',
        'credit_days',
        'is_favourite',
        'no_of_repeat'
    ];

    /**
     * Hidden attributes for Activity
     *
     * @var array
     */
    protected $hiddenActivity = [
        'deleted_at',
        'updated_at',
        'created_at',
        'company_id',
        'is_deleted',
        'group_id',
        'status_id',

    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    /*public function getMobileAttribute($value)
    {
        return $this->phone_code.$value;
    }*/

    public function rfqStatus()
    {
        return $this->hasOne(RfqStatus::class,'id','status_id');
    }

    public function rfqProduct()
    {
        return $this->hasOne(RfqProduct::class);
    }

    public function rfqProducts()
    {
        return $this->hasMany(RfqProduct::class);
    }

    public function rfqUser()
    {
        return $this->hasOneThrough(User::class,UserRfq::class,'rfq_id','id','','user_id');
    }

    public function rfqUsers()
    {
        return $this->hasManyThrough(User::class,UserRfq::class,'rfq_id','id','','user_id');
    }
    public function userRfqs()
    {
        return $this->hasMany(UserRfq::class,'rfq_id','id')->where('is_deleted',0);
    }
    public function rfqSuppliers()
    {
        return $this->hasManyThrough(SupplierProduct::class,RfqProduct::class,'rfq_id','product_id','','product_id');
    }

    public function rfqCalls()
    {
        return $this->hasMany(rfqCall::class);
    }

    public function rfqQuotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function chatGroupRfq()
    {
        return $this->hasOne(ChatGroup::class,'chat_id','id')->where('chat_type','Rfq');
    }

    /**
     * Get the city associated with the user RFQ.
     */
    public function getCity()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->first();
    }

    /**
     * Get the state associated with the user RFQ.
     */
    public function getState()
    {
        return $this->belongsTo(State::class, 'state_id', 'id')->first();

    }

    /**
     * Get the country associated with the user RFQ.
     */
    public function getCountryOne()
    {
        return $this->belongsTo(CountryOne::class, 'country_one_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class);
    }
    /**
     * Get order company details.
     */
    public function companyDetails()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }
    /*public function userRfqMongo()
    {
        return $this->hasOne(ChatGroup::class, 'rfq_id', 'chat_id');
    }*/
    public function quote()
    {
     return $this->hasMany(Quote::class);
    }

    /**
     * get quote_metas
     */
    public function getQuoteMetaData() {
        return $this->hasManyThrough(
            QuotesMeta::class,
            Quote::class,
            'rfq_id', // Foreign key on quotes table...
            'quote_id', // Foreign key on quote_metas table...
            '',
            'id' // Local key on rfqs table...
        );
    }

    /** get RFQ Attachemnt */
    public function rfqAttachment(){
        return $this->hasMany(RfqAttachment::class);
    }
    /** get user quote feedback */
    public function feedbackRfq() {
        return $this->hasMany(UserQuoteFeedback::class,'rfq_id','id');
    }
    /** multiple order relation */
    public function order()
    {
     return $this->hasMany(Order::class);
    }
    public function City(){
        return $this->belongsTo(City::class);
    }
    public function State(){
        return $this->belongsTo(State::class);
    }
}
