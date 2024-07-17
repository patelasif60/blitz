<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemStatus extends Model
{
    use HasFactory, SystemActivities;

    protected $table = 'order_item_status';
}
