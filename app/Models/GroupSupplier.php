<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupSupplier extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;
    protected $fillable = [
        'supplier_id', 'group_id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

}
