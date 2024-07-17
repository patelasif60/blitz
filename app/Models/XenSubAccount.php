<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XenSubAccount extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['supplier_id', 'xen_platform_id', 'type', 'status', 'country', 'email', 'business_name', 'public_profile', 'created', 'updated', 'description', 'created_at', 'updated_at'];

    protected $casts = [
        'public_profile' => 'array',
    ];

    public static function createOrUpdateXenAccount($data){
        $data['xen_platform_id'] = $data['id'];
        unset($data['id']);
        if (isset($data['public_profile']['business_name'])) {
            $data['business_name'] = $data['public_profile']['business_name'];
        }
        $data['public_profile'] = json_encode($data['public_profile']);

        $result = self::where(['xen_platform_id'=>$data['xen_platform_id']])->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
