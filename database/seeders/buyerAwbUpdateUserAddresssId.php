<?php

namespace Database\Seeders;

use App\Models\Rfq;
use App\Models\UserAddresse;
use App\Models\UserRfq;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Seeder;
use mysql_xdevapi\Exception;

class buyerAwbUpdateUserAddresssId extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $rfqData = Rfq::join('rfq_products','rfq_products.rfq_id','=','rfqs.id')
            ->join('user_rfqs','user_rfqs.rfq_id','=','rfqs.id')
            ->join('user_addresses','user_addresses.user_id','=','user_rfqs.user_id')
            ->where('rfq_products.category_id',21)->where('is_deleted',0)->whereNotIn(3,4)->whereNotNull(['id','address_name','address_line_1','address_line_2','city_id','state_id'])->get(['id','address_name','address_line_1','address_line_2','city_id','state_id']);

            dd($rfqData,"123");

            $rfqData->each(function($rfq) {
                $userRfqData = UserRfq::join('rfqs','rfqs.id','=','user_rfqs.rfq_id')->where('rfq_id',$rfq->id)->get(['user_id']);

                $userRfqData->each(function($user) {
                    UserAddresse::join('user_rfqs','user_rfqs.user_id','=','user_addresses.user_id')->where($user->user_id)->get(['id','address_name','address_line_1','address_line_2','city_id','state_id']);
                });
            });
        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }
    }
}
