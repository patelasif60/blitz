<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, SystemActivities;

    const ADMIN = 1;
    const BUYER = 2;
    const SUPPLIER = 3;
    const SUPER_ADMIN = 4;
    const AGENT = 5;
    const JNE = 7;
    const FINANCE = 8;

    protected $fillable = [
        'name', 'is_deleted'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

}
