<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanEmail extends Model
{
    use HasFactory,SystemActivities;
    protected $fillable = ['id','company_id','application_id','status','type'];

    public static function createOrUpdateEmail($data){

        $result = self::where(['company_id'=>$data['company_id'],'application_id'=>$data['application_id'],'status'=>$data['status']])->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

}
