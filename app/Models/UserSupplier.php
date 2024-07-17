<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSupplier extends Model
{
    use HasFactory, SystemActivities;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
