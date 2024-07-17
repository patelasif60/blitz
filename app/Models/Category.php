<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class Category extends Model
{
    use HasFactory, SystemActivities;

    const WOOD = 6;

    const SERVICES_CATEGORY_IDS = [21,56];

    protected $table = 'categories';

    protected $tagname = "Category";

    protected $fillable = [
        'name', 'is_deleted',
        'description', 'status','added_by','updated_by','deleted_by'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];
    public function trackAddData()
    {
        return $this->hasOne(User::class,'id','added_by');
    }
    public function trackUpdateData()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
}
