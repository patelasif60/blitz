<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class ContactUs extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Contact";
    protected $fillable = [
        'fullname', 'company_name','mobile','email','message', 'user_type'
    ];

    public function setUserTypeAttribute($value){
        $this->attributes['user_type'] = json_encode($value);
    }

    public function getUserTypeAttribute($value){
        return $this->attributes['user_type'] = json_decode($value);
    }
}
