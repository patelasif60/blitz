<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class ChatQuickMessage extends Model
{
    use HasFactory,HybridRelations, SystemActivities;
    protected $connection = 'mysql';
    protected $table = 'chat_quick_message';
    protected $fillable = [
        'message',
        'role_id',
        'status',
        'created_at',
        'updated_at'
    ];
}
