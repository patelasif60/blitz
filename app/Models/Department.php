<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class Department extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Departments";
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'status',
        'is_deleted',
        'added_by',
        'updated_by',
        'deleted_by',
    ];
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
