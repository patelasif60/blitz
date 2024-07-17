<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

class ExportDailyByrList implements FromCollection, ShouldAutoSize, WithEvents
{

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['First Name', 'Last Name', 'Email', 'phone code', 'Mobile No']);

        if ($this->type == 'buyer') {
            $lists = DB::table("users")->where('created_at', '>=', Carbon::yesterday())->where('role_id',2)->get();
        } else if($this->type == 'suppliers'){
            $lists = DB::table('suppliers')->select(['contact_person_name as firstname', 'contact_person_last_name as lastname', 'contact_person_email as email', 'cp_phone_code as phone_code', 'contact_person_phone as mobile'])->where('created_at', '>=', Carbon::yesterday())->get();
        }
        $excelFormateData = [];
        if (!empty($lists)) {
            foreach ($lists as $list) {
                $excelFormateData[] = (object) array(
                    'firstname' => $list->firstname ?? '-',
                    'lastname' => $list->lastname ?? '-',
                    'email' => $list->email ?? '-',
                    'phone_code' => $list->phone_code ?? '-',
                    'mobile' => $list->mobile ?? '-',
                );
            }
        }
        $raws = new Collection($excelFormateData);
        $data = $raws->prepend($columns);
        return $data;
    }

   public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:U1')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('25378b');

                $event->sheet->getDelegate()->getStyle('A1:U1')
                        ->getFont()
                        ->getColor()
                        ->setARGB('FFFFFF');

                $event->sheet->getDelegate()->getStyle('A1:U1')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            },
        ];
    }
}
