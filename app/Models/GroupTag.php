<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupTag extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;
    protected $fillable = [
        'group_id', 'tag', 'created_at', 'updated_at', 'deleted_at'
    ];
}
