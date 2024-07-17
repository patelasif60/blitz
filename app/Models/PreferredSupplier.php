<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreferredSupplier extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $table = 'preferred_suppliers';

    protected $fillable = [
        "user_id",
        "supplier_id",
        "is_active",
        "created_at",
        "updated_at",
        "deleted_at",
        "company_id"
    ];

}
