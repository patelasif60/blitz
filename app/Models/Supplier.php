<?php

namespace App\Models;

use App\Traits\FileUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;
use App\Models\SupplierDealWithCategory;


class Supplier extends Model
{
    use HasFactory, SystemActivities, FileUpload;

    protected $tagname = "Supplier";

    protected $table = "suppliers";

    protected $fillable = [
        'name', 'description','status','email','c_phone_code','mobile','is_deleted','website','logo','nib','nib_file','npwp','npwp_file','address','salutation','contact_person_name','contact_person_last_name','contact_person_email','cp_phone_code','contact_person_phone','alternate_email','catalog','pricing','product','commercialCondition','accepted_terms','licence','company_alternative_phone_code','company_alternative_phone','facebook','twitter','linkedin','youtube','instagram','established_date','profile_username','added_by','updated_by','deleted_by','interested_in'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    public function getProfileLinkAttribute()
    {
        return getSettingValueByKey('slug_prefix').$this->profile_username;
    }

    /**
     * Contact person full name
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->contact_person_name .' '. $this->contact_person_last_name;
    }

    /**
     * Salutation in string
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getSalutationNameAttribute()
    {
        return ($this->salutation == 1 ? __('admin.salutation_mr') : ($this->salutation == 2 ? __('admin.salutation_ms') : ($this->salutation == 3 ? __('admin.salutation_mrs') : '')));
    }

    /**
     * Get mobile number and code
     * @return string
     */
    public function getMobileNumberAttribute()
    {
        return $this->cp_phone_code.' '.$this->contact_person_phone;
    }

    /**
     * User relationship of supplier
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function user()
    {
        return $this->hasOneThrough(User::class,UserSupplier::class,'supplier_id','id','','user_id');
    }

    public function trackAddData()
    {
        return $this->hasOne(User::class,'id','added_by');
    }
    public function trackUpdateData()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
    public function supplierBank()
    {
        return $this->hasOne(SuppliersBank::class);
    }

    public function order()
    {
        return $this->morphMany(Supplier::class, 'user');
    }

    public function xenAccount()
    {
        return $this->hasOne(XenSubAccount::class);
    }

    public function supplierTransactionCharge()
    {
        return $this->hasOne(SupplierTransactionCharge::class);
    }

    public function supplierTransactionCharges()
    {
        return $this->hasMany(SupplierTransactionCharge::class);
    }

    public function supplierUser()
    {
        return $this->hasOne(UserSupplier::class, 'supplier_id', 'id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class,SupplierProduct::class,'supplier_id','id','','product_id');
    }

    /**
     * Get Company Details
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function companyDetails()
    {
        return $this->morphOne(CompanyDetails::class, 'model');
    }

    /**
     * Get Company Highlights
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function companyHighlights()
    {
        return $this->morphOne(CompanyHighlights::class, 'model');
    }

    /**
     * Get Company Partners / Core Team / Client & Supplier Portfolio / Company Testimonial by company_user_type
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function companyMembers()
    {
        return $this->morphMany(CompanyMembers::class, 'model');
    }

    /**
     * Supplier Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class,'supplier_id','id');
    }

    /**
     * Get the gallery images
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function supplierGallery()
    {
        return $this->hasMany(SupplierGallery::class,'supplier_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyAddress()
    {
        return $this->morphMany(CompanyAddress::class, 'model');
    }
    /*
     * common function for admin and supplier
     * Supplier Dealing With category
     * */
    public function dealWithSubCategoriesTag($supplierId){
        $allSubCategories = SubCategory::with('category:id,name')->where('is_deleted',0)->get();
        $SupplierDealingCategoty = SupplierDealWithCategory::where('supplier_id',$supplierId)->pluck('sub_category_id')->toArray();
        $getSupplierProductsCatIds = SupplierProduct::with(['product'=>function($q){
            $q->select(['id','subcategory_id']);
        }])->where('supplier_id',$supplierId)->where('is_deleted',0)->select('product_id')->distinct()->get();
        $NotDeleteSubCategory = array_unique($getSupplierProductsCatIds->pluck('product.subcategory_id')->toArray());
        $dealing_category = [];
        foreach($allSubCategories as $svalue){
            $selected='';
            if(!empty($SupplierDealingCategoty)){
                $selected = (in_array($svalue->id, $SupplierDealingCategoty)) ? 'true' : '';
            }
            $NoteDeleteSelected='';
            if(!empty($NotDeleteSubCategory)){
                $NoteDeleteSelected = (in_array($svalue->id,array_unique($NotDeleteSubCategory))) ? 'False' : '';
            }
            $dealing_category[] = array($svalue->category->name.'-'.$svalue->name,$svalue->id,$selected,$NoteDeleteSelected);
        }
        return $dealing_category;
        /** Supplier Dealing With category */
    }

    public function scopeSearch($query, $searchValue)
    {
        return $query->where('name', 'like', '%'.$searchValue.'%')
            ->orwhere('email', 'like', '%'.$searchValue.'%')
            ->orwhere('mobile', 'like', '%'.$searchValue.'%')
            ->orwhere('contact_person_email', 'like', '%'.$searchValue.'%')
            ->orWhereDate('created_at', '=', date('Y-m-d',strtotime($searchValue)));
    }

    /**
     * Supplier Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function SupplierDealWithCategories()
    {
        return $this->hasMany(SupplierDealWithCategory::class,'supplier_id','id');
    }

}
