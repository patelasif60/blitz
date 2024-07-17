<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchDisbursements extends Model
{
    use HasFactory, SystemActivities;

    public function disbursements()
    {
        return $this->hasMany(Disbursements::class);
    }
}
