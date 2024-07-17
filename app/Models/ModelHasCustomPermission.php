<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelHasCustomPermission extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $fillable = [
        'custom_permission_id',
        'model_type',
        'model_id',
        'custom_permissions'
    ];

    /**
     * Model Has Custom Permission relation Custom Permission
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function customPermission()
    {
        return $this->belongsTo(CustomPermission::class, 'custom_permission_id', 'id');
    }
}
