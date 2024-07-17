<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * LoanProvider Table
 */
class LoanProvider extends Model
{
    use HasFactory, SystemActivities;

    const KOINWORKS = 1;
    const KOINWORKSTEXT = 'Koinworks';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'production_base_path',
        'staging_base_path',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function loanProviderApiList()
    {
        return $this->hasOne(LoanProviderApiList::class);
    }

    public function loanProviderApiLists()
    {
        return $this->hasMany(LoanProviderApiList::class);
    }
}
