<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPlaceOrderLog extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['group_id', 'quote_id', 'request_data', 'created_at', 'updated_at'];

    public static function createOrUpdateGroupPlaceOrderLog($data){

        $result = self::where(['group_id' => $data['group_id'],'quote_id' => $data['quote_id']])->first();

        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public function group()
    {
        return $this->belongsTo(Groups::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

}
