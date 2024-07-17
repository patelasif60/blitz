<?php

namespace App\Models;

use App\Traits\FileUpload;
use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMembers extends Model
{
    use HasFactory, SystemActivities, FileUpload;

    const TEAM = 1;
    const TESTIMONIAL = 2;
    const PARTNER = 3;
    const PROFILE = 4;

    protected $fillable = [
        'model_type',
        'model_id',
        'company_id',
        'user_id',
        'company_user_type_id',
        'salutation',
        'firstname',
        'lastname',
        'email',
        'country_phone_code',
        'phone',
        'designation',
        'position',
        'company_name',
        'sector',
        'registration_NIB',
        'portfolio_type',
        'quote',
        'image',
        'description',
    ];

    /**
     * Contact person full name
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->firstname .' '. $this->lastname;
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
     * Get company user type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function companyUserType()
    {
        return $this->hasOne(CompanyUserType::class);
    }

    /**
     * Get supplier details
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function supplier()
    {
        return $this->morphTo(Supplier::class,'model_type','model_id');
    }
}
