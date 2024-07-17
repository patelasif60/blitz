<?php

namespace Database\Seeders\Loan;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class KoinworksPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /****************begin: Create new Permissions for Logistic Charges - Backend Side (Super-Admin / Admin / Supplier / Agent / Jne )*******************/
        DB::transaction(function () {
            Permission::UpdateOrCreate(['name' => 'list-all buyer company credits']);

        });
        /****************begin: Create new Permissions for Logistic Charges - Backend Side (Super-Admin / Admin / Supplier / Agent / Jne )*******************/

    }
}
