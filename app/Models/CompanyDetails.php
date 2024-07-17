<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetails extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = 'Business Details';

    protected $fillable = [
        'model_type',
        'model_id',
        'user_id',
        'company_id',
        'founders',
        'name',
        'headquarters',
        'sector',
        'sector_type',
        'product_services',
        'number_of_employee',
        'net_income',
        'net_income_currency',
        'annual_sales',
        'annual_sales_currency',
        'financial_target',
        'financial_target_currency',
        'company_description',
        'business_description',
        'mission',
        'vision',
        'history_growth',
        'industry_information',
        'policies',
        'policies_image',
        'public_relations',
        'advertising',
    ];

    /**
     * Get users
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function users()
    {
        return $this->morphTo(User::class, 'model_type','model_id');
    }

    /**
     * Get supplier
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function supplier()
    {
        return $this->morphTo(Supplier::class, 'model_type','model_id');

    }
}
