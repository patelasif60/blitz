<?php

namespace App\Exports;

use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportQuoteExpire implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $from = Carbon::now()->toDateString();
        $to = Carbon::now()->subDays(7)->toDateString();
        $columns = collect(['Quote Number', 'Supplier Name', 'RFQ Id', 'Valid Till', 'Final Amount', 'Tax', 'Tax Value', 'Note', 'Created At', 'Supplier Final Amount', 'Supplier Tax Value', 'Address Name', 'Address Name 1', 'Address Name 2', 'District', 'Sub District', 'City', 'State', 'Pincode']);
        $quoteExpireDatas = Quote::with(['supplier:id,name', 'state_name:id,name'])
                        ->where('valid_till', '<', $from)
                        ->where('valid_till', '>=', $to)
                        ->where('status_id', 3)
                        ->get();
        $excelFormateData = [];
        if (!is_null($quoteExpireDatas)) {
            foreach ($quoteExpireDatas as $quoteExpireData) {
                $excelFormateData[] = (object) array(
                    'quote_number' => $quoteExpireData->quote_number,
                    'name' => $quoteExpireData->supplier->name ?? '-',
                    'rfq_id' => $quoteExpireData->rfq_id ?? '-',
                    'valid_till' => $quoteExpireData->valid_till ?? '-',
                    'final_amount' => $quoteExpireData->final_amount ?? '-',
                    'tax' => $quoteExpireData->tax ?? '-',
                    'tax_value' => $quoteExpireData->tax_value ?? '-',
                    'note' => $quoteExpireData->note ?? '-',
                    'created_at' => $quoteExpireData->created_at ?? '-',
                    'supplier_final_amount' => $quoteExpireData->supplier_final_amount ?? '-',
                    'supplier_tex_value' => $quoteExpireData->supplier_tex_value ?? '-',
                    'address_name' => $quoteExpireData->address_name ?? '-',
                    'address_name_1' => $quoteExpireData->address_name_1 ?? '-',
                    'address_name_2' => $quoteExpireData->address_name_2 ?? '-',
                    'district' => $quoteExpireData->district ?? '-',
                    'sub_district' => $quoteExpireData->sub_district ?? '-',
                    'city' => $quoteExpireData->city ?? '-',
                    'state_id' => $quoteExpireData->state_name->name ?? '-',
                    'pincode' => $quoteExpireData->pincode ?? '-',
                    'country_id' => $quoteExpireData->country_id ?? '-',
                );
            }
            $raws = new Collection($excelFormateData);
        }
        return $raws->prepend($columns);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()
                                ->getStyle('A1:O1')
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB('25378b');

                $event->sheet->getDelegate()
                                ->getStyle('A1:O1')
                                ->getFont()
                                ->getColor()
                                ->setARGB('FFFFFF');

                $event->sheet->getDelegate()
                                ->getStyle('A1:O1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
