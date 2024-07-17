<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqAttachment extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'rfq_attachments';
    protected $fillable = [
        'rfq_id', 'attached_document'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at','deleted_at'];
}
