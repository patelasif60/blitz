<?php

namespace Database\Seeders;

use App\Models\CompanyUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class BuyerDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // fetch buyers with default company id, department and designation
        $users = User::where('role_id', Role::BUYER)
            ->whereNotNull(['default_company'])
            ->where(function($query){
                $query->whereNotNull('department')->orWhereNotNull('designation');
            })
            ->get(['id','default_company','department','designation']);

        // create array for add data to company users table
        $companyUsers = [];
        foreach ($users as $value) {
            $companyUsers[] = [
                'company_id' => $value->default_company,
                'designation' => $value->designation,
                'department' => $value->department,
                'users_type' => User::class,
                'users_id' => $value->id
            ];
        }

        if(CompanyUser::insert($companyUsers)){
            dd('success');
        }
        dd('data not found');

    }
}
