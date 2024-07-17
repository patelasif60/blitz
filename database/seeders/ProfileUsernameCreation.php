<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProfileUsernameCreation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = Supplier::whereNull('profile_username')->get();
        foreach ($suppliers as $supplier) {
            $this->updateUsername($supplier);
        }
    }

    /**
     * Update username
     *
     * @param $supplier
     */
    public function updateUsername($supplier)
    {
        $username = Str::slug($supplier->name,'-');
        $username = $username.Str::lower(Str::random(4));
        $temp = Supplier::where('profile_username',$username)->first();

        if (empty($temp)) {
            $supplier->profile_username = $username;
            $supplier->save();
        }
    }
}
