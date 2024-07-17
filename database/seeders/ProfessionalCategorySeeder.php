<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfessionalCategory;
use Carbon\Carbon;
class ProfessionalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //DB::table('users')->delete();
        $categories = ['Pertanian, perikanan & peternakan','Kecantikan, farmasi','Bahan bangunan','Konstruksi & desain interior','Jasa pengiriman, kurir','Dropshipper','Elektronik, komputer','Fashion, aksesoris','Makanan, minuman','Furnitur','Oleh-Oleh','Salon, spa, pusat kebugaran','Kerajinan tangan','Hotel dan penginapan','Keperluan rumah tangga','Jasa laundry','alat medis, sport, musik','Jasa fotografi','Tanaman, hewan peliharaan','Percetakan, ATK','Restoran, cafe','Pariwisata dan travel','Mainan','Bengkel, sparepart','Lainnya'];
        foreach($categories as $key=>$val)
        {
            ProfessionalCategory::insert([
                [
                    'name'   => $val,
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            ]);
        }
    
    }
}
