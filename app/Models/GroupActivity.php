<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupActivity extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = ['user_id', 'group_id','key_name ','old_value','new_value','user_type'];

    protected $dates = ['created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
//    public function groupSupplierName()
//    {
//        return $this->hasOne(Supplier::class,'id','user_id');
//    }
}
