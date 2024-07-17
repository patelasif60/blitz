<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemActivity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    const CREATED       = 'CREATED';
    const UPDATED       = 'UPDATED';
    const DELETED       = 'DELETED';
    const DELETING      = 'SOFT DELETE';
    const VIEW          = 'VIEW LIST';
    const EDITVIEW      = 'VIEW EDIT';
    const ADDVIEW       = 'VIEW CREATE';
    const RECORDVIEW    = 'VIEW RECORD';
    const DOWNLOAD      = 'DOWNLOAD';
    const LOGIN         = 'LOGGED IN';



    /**
     * @var string[]
     */
    protected $fillable = [
        'system_logable_id',
        'system_logable_type',
        'user_id',
        'guard_name',
        'module_name',
        'action',
        'old_value',
        'new_value',
        'ip_address'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function system_logable()
    {
        return $this->morphTo();
    }

    /**
     * Get the causer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function causer()
    {
        return $this->hasMany(User::class, 'id', 'user_id');

    }

    /**
     * Get activity of a single user
     *
     * @param $user
     * @param $activityModel
     * @return mixed
     */
    public function getActivityAttribute($user, $activityModel, $action)
    {
        return SystemActivity::where('user_id', $user->id)->where('system_logable_type', $activityModel)->where('system_logable_id', '!=', 0)->where('action', $action)->groupBy('system_logable_id')->pluck('system_logable_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
