<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Alignment;


class SupplierExport implements FromCollection, ShouldAutoSize, WithEvents
{
    use Exportable;
    protected $suppliers;

    /**
     * @param $suppliers
     */
    public function __construct($suppliers)
    {
        $this->suppliers = $suppliers;
    }

    public function collection()
    {
        if (auth()->user()->role_id == 1 ) {
            $columns = collect(['Company Name','Company Email', "Company Mobile", 'Contact Person Name', 'Contact Person Email', 'Contact Person Phone Number', 'Bank Name', 'Bank code',  'Bank Account Name', 'Bank Account Number', 'IsActive']);
            $raws = $this->suppliers;
            $raws->prepend($columns);
            return $raws;
        }
    }

    public function registerEvents(): array
    {
        if (auth()->user()->role_id == 1){
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:K1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:K1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');


                    $event->sheet->getDelegate()->getStyle('A1:K1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                },
            ];
        }else{
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:K1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:K1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');


                    $event->sheet->getDelegate()->getStyle('A1:K1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

       },
            ];
        }
    }
}
