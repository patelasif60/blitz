<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XenditRequestResponse extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['type', 'xendit_id', 'data', 'created_at'];

    const UPDATED_AT = null;

    public static function createXenditRequestResponse($data){
        return self::create($data);
    }
}
