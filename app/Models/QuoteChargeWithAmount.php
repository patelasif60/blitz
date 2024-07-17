<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteChargeWithAmount extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'quotes_charges_with_amounts';

    protected $fillable = [
        'quote_id', 'charge_name', 'charge_id', 'value_on', 'addition_substraction', 'type', 'charge_value', 'charge_amount', 'charge_type', 'is_deleted', 'custom_charge_name'
    ];

    public static function createOrUpdateQuoteChargeWithAmount($data){
        $result = null;
        if (isset($data['id'])) {
            $result = self::where(['id' => $data['id']])->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }
}
