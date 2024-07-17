<?php

namespace Database\Seeders;

use App\Models\PreferredSupplier;
use App\Models\User;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Seeder;

class AddCompanyIdInPreferSupplierSeeder extends Seeder
{
    /**
     * This seeder is used to update company id for Prefer supplier
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        try {
            $preferSupplier  = PreferredSupplier::get(['id', 'user_id', 'company_id']);
            $preferSupplier->each(function($supplier){
                if (!empty($supplier->user_id)) {
                    $supplier->company_id = User::find($supplier->user_id)->default_company??null; // Only set company id for user
                    $supplier->save();
                }
            });
            dd('Company id set in Preferred suppliers table.');
        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }
    }
}
