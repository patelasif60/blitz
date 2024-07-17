<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreferredSuppliersRfq extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $table = 'preferred_suppliers_rfqs';

    protected $fillable = [
        'user_id',
        'supplier_id',
        'rfq_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
