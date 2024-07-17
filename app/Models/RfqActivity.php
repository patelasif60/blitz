<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqActivity extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'rfq_activity';
    protected $fillable = [
        'rfq_id', 'key_name','old_value','new_value','user_id','is_deleted',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];
    public function rfquserName()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
