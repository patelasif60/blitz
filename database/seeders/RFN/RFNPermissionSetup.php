<?php

namespace Database\Seeders\RFN;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RFNPermissionSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**begin: New RFN Permission**/
        DB::transaction(function () {
            Permission::UpdateOrCreate(['name' => 'buyer rfn create']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn update']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn cancel']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn publish']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn convert global rfn']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn to rfq']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn list']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn list-all']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn multi convert to rfq']);
            Permission::UpdateOrCreate(['name' => 'buyer rfn reject']);

            Permission::UpdateOrCreate(['name' => 'buyer global rfn create']);
            Permission::UpdateOrCreate(['name' => 'buyer global rfn update']);
            Permission::UpdateOrCreate(['name' => 'buyer global rfn cancel']);
            Permission::UpdateOrCreate(['name' => 'buyer global rfn publish']);
            Permission::UpdateOrCreate(['name' => 'buyer request global rfn']);
            Permission::UpdateOrCreate(['name' => 'buyer global rfn list']);
            Permission::UpdateOrCreate(['name' => 'buyer global rfn list-all']);
            Permission::UpdateOrCreate(['name' => 'buyer global rfn to rfq']);
            Permission::UpdateOrCreate(['name' => 'buyer global rfn multi convert to rfq']);
            Permission::UpdateOrCreate(['name' => 'buyer edit request global rfn']);
            Permission::UpdateOrCreate(['name' => 'buyer delete request global rfn']);
            Permission::UpdateOrCreate(['name' => 'buyer List RFR Request']);
            Permission::UpdateOrCreate(['name' => 'buyer List All RFR Request']);
        });
        /**end: New RFN Permission**/
    }
}
