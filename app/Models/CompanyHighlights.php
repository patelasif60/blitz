<?php

namespace App\Models;

use App\Traits\FileUpload;
use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class CompanyHighlights extends Model
{
    use HasFactory, SystemActivities, FileUpload;

    const AWARD = 1;
    const CERTIFICATIONS = 2;
    const MEDIARECOGNITION = 3;

    protected $fillable = [
        'model_type',
        'model_id',
        'company_id',
        'category',
        'name',
        'number',
        'image',
    ];

    public function users()
    {
        return $this->morphTo(User::class, 'model_type','model_id');
    }
}
