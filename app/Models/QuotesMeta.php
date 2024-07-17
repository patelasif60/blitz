<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotesMeta extends Model
{
    use HasFactory, SystemActivities;

    protected $table = 'quotes_meta';

    protected $fillable = [
        'quote_id',
        'user_type',
        'user_id',
        'approval_process',
        'approval_process_complete',
    ];

    protected $dates = ['created_at','updated_at'];

    /**
     * Get quotes relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotes()
    {
        return $this->hasOne(Quote::class,'id','quote_id');
    }
}
