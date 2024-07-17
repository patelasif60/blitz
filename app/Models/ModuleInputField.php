<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleInputField extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $fillable = [
        'field_name',
        'field_ids',
        'columns_name',
        'table_name',
        'getby_columnname',
        'priority',
        'percentage',
        'display_name',
        'module_name',
        'system_role_id'
    ];
}
