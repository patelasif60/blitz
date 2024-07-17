<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory, SystemActivities;

    protected $table = 'company_users';

    protected $fillable = [
        'company_id', 'designation', 'department', 'users_type', 'users_id','branches', 'created_at', 'updated_at'
    ];

    public function departmentDetails()
    {
        return $this->belongsTo(Department::class, 'department');
    }

    public function designationDetails()
    {
        return $this->belongsTo(Designation::class, 'designation');
    }
}
