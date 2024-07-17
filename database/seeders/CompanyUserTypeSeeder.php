<?php

namespace Database\Seeders;

use App\Models\CompanyUserType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CompanyUserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        CompanyUserType::truncate();
        $userTypes = ['Core Team','Testimonial','Company Partner','Portfolio'];
        foreach($userTypes as $key=>$val)
        {
            CompanyUserType::insert([
                [
                    'user_type'   => $val,
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            ]);
        }
    }
}
