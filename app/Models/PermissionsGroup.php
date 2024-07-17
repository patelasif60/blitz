<?php

namespace App\Models;

use App\Traits\SystemActivities;
use GPBMetadata\Google\Api\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsGroup extends Model
{
    use HasFactory, SystemActivities;

    const MAIN = 1;

    /** @var Groups constants */
    const BUYER     = 'buyer';
    const SUPPLIER  = 'supplier';
    const ADMIN     = 'admin';

    /** @var Groups Level constants */
    const LEVEL1    = 1;
    const LEVEL2    = 2;
    const LEVEL3    = 3;

    /** @var Table string[]  */
    protected $fillable = [
      'name',
      'display_name',
      'class_name',
      'is_main',
      'parent_id',
      'level',
      'sort',
      'permissions',
      'related_permissions',
      'is_active'
    ];

    /**
     * Indicates the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get permission main groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return PermissionsGroup::where('parent_id', 0);
    }

    /**
     * Get permission sub groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class , 'parent_id', 'id')->where('is_active', 1)->orderBy('sort')->get();
    }
    /**
     * Get permission expect RFN
     */
    public function childrenWithoutRfn()
    {
        return $this->hasMany(self::class , 'parent_id', 'id')->where('is_active', 1)->whereNotIn('id',[51,52])->orderBy('sort')->get();
    }

}
