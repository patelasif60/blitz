<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'order_status';
    protected $fillable = [
        'name', 'description','status','parent_id','show_order_id','is_deleted'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    public function OrderTrack(){
        return $this->hasMany(OrderTrack::class,'status_id','id');
    }

}
