<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Religion;
use Carbon\Carbon;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Religion::truncate();
        $religions = ['ISLAM','KATHOLIK','KRISTEN','BUDHA','HINDU','KONGHUCHU','OTHER'];
        foreach($religions as $key=>$val)
        {
            Religion::insert([
                [
                    'name'   => $val,
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            ]);
        }
    }
}
