<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierGallery extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'supplier_gallery';
    protected $fillable = [
        'supplier_id', 'image', 'added_by', 'updated_by', 'deleted_by', 'created_at', 'updad_at'
    ];
}
