<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupSupplierDiscountOption extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = [
        'group_supplier_id', 'min_quantity', 'max_quantity', 'unit_id', 'discount', 'added_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'
    ];
}
