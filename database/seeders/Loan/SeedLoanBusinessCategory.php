<?php

namespace Database\Seeders\Loan;

use App\Models\LoanBusinessCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeedLoanBusinessCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        LoanBusinessCategory::truncate();
        LoanBusinessCategory::insert([
            [
                'name'=>'Pertanian, perikanan & peternakan',
            ],
            [
                'name'=>'Kecantikan, farmasi',
            ],
            [
                'name'=>'Bahan bangunan',
            ],
            [
                'name'=>'Konstruksi & desain interior',
            ],
            [
                'name'=>'Jasa pengiriman, kurir',
            ],
            [
                'name'=>'Dropshipper',
            ],
            [
                'name'=>'Elektronik, komputer',
            ],
            [
                'name'=>'Fashion, aksesoris',
            ],
            [
                'name'=>'Makanan, minuman',
            ],
            [
                'name'=>'Furnitur',
            ],
            [
                'name'=>'Oleh-Oleh',
            ],
            [
                'name'=>'Salon, spa, pusat kebugaran',
            ],
            [
                'name'=>'Kerajinan tangan',
            ],
            [
                'name'=>'Hotel dan penginapan',
            ],
            [
                'name'=>'Keperluan rumah tangga',
            ],
            [
                'name'=>'Jasa laundry',
            ],
            [
                'name'=>'alat medis, sport, musik',
            ],
            [
                'name'=>'Jasa fotografi',
            ],
            [
                'name'=>'Tanaman, hewan peliharaan',
            ],
            [
                'name'=>'Percetakan, ATK',
            ],
            [
                'name'=>'Restoran, cafe',
            ],
            [
                'name'=>'Pariwisata dan travel',
            ],
            [
                'name'=>'Mainan',
            ],
            [
                'name'=>'Bengkel, sparepart',
            ],
            [
                'name'=>'Lainnya',
            ]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
