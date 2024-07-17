<?php

namespace Database\Seeders;

use App\Models\CompanyAddress;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class CompanyAddressAdded extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $supplier  = Supplier::where('address','!=', "")->get();
            $supplier->each(function($supplierAddress){
                if (!empty($supplierAddress->address)) {
                    $companyAddress  = CompanyAddress::where('model_id','=', $supplierAddress->id)->where('model_type',Supplier::class)->count();
                    if($companyAddress == 0) {
                        $supplierCompany = new CompanyAddress;
                        $supplierCompany->model_type = Supplier::class;
                        $supplierCompany->model_id = $supplierAddress->id;
                        $supplierCompany->user_id =  isset($supplierAddress->user->id) ? $supplierAddress->user->id : null; // get user id by supplier id
                        $supplierCompany->address = $supplierAddress->address;
                        $supplierCompany->company_id = null;
                        $supplierCompany->is_deleted = 0;
                        $supplierCompany->save();
                    }

                }
            });
            dd('Address set in Company address');
        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }
    }
}
