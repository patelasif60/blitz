<?php

namespace App\Export;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Alignment;

class BuyerExport implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 5) {
            $columns = collect(['Company Name','Company Email','Alternate Company Email', "Company Website", 'Company Phone', 'Alternative Company Phone', 'NIB', 'NPWP', 'First Name',  'Last Name', 'Email', 'Mobile Number', 'Designation', 'Department','IsActive']);
            $raws = User::leftjoin('user_companies', 'user_companies.user_id', '=', 'users.id')
            ->leftjoin('companies', 'companies.id', '=', 'user_companies.company_id')
            ->leftjoin('designations','users.designation','=','designations.id')
            ->leftjoin('departments','users.department','=','departments.id');
                //Agent category permission
                if (Auth::user()->hasRole('agent')) {

                    $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                    $raws = $raws->leftjoin('company_consumptions', 'company_consumptions.user_id', '=', 'users.id')
                    ->whereIn('company_consumptions.product_cat_id', $assignedCategory)
                    ->orWhere('users.added_by', Auth::user()->id);

                }
            $raws = $raws->where('users.is_delete',0)
            ->where('users.role_id',2)
            ->where('users.approval_invite',0)
            ->orderBy('users.id', 'desc')
            ->get(['companies.name as company_name','companies.company_email','companies.alternative_email as company_alternative_email','companies.web_site',DB::raw('CONCAT(companies.c_phone_code," ",companies.company_phone) as cmp_phone'),DB::raw('CONCAT(companies.a_phone_code," ",companies.alternative_phone) as cmp_altphone'),'companies.registrantion_NIB as nib','companies.npwp','users.firstname','users.lastname','users.email as user_email',DB::raw('CONCAT(users.phone_code," ", users.mobile) as phone_number'),'designations.name as designation',
                        'departments.name as department',DB::raw('IF(users.is_active = "1","Yes", "No") as is_active')]);
            $raws->prepend($columns);
            return $raws;
        }
    }

    public function registerEvents(): array
    {
        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 5){
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:O1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:O1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');


                    $event->sheet->getDelegate()->getStyle('A1:O1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                },
            ];
        }else{
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:O1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:O1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');


                    $event->sheet->getDelegate()->getStyle('A1:O1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

       },
            ];
        }
    }
}
