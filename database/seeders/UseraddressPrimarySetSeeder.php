<?php

namespace Database\Seeders;

use App\Models\UserAddresse;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Seeder;

class UseraddressPrimarySetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder will set is_user_primary column  in user address.This will used to set primary address in user address
     * @return void
     */
    public function run()
    {
        try {
            $userAddress = UserAddresse::where('is_deleted', 0)->get(['id', 'user_id', 'company_id', 'default_address', 'is_user_primary', 'is_deleted']);
            foreach ($userAddress as $address) {
                $is_primary = [];
                if ($address->default_address == 1) {
                    $primarySetArr = !empty($address->is_user_primary) ? json_decode($address->is_user_primary) : [];
                    if(!in_array($address->user_id, $primarySetArr)) {
                        array_push($primarySetArr, $address->user_id);
                    }
                    $addressUpdate = UserAddresse::where('id',$address->id)->where('is_deleted', 0)->update([
                        'is_user_primary' => $primarySetArr
                    ]);
                } else {
                    $addressUpdate = UserAddresse::where('id',$address->id)->where('is_deleted', 0)->update([
                        'is_user_primary' => $is_primary
                    ]);
                }
            }
            dd('Primary User id set in user address table.');
        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }
    }
}
