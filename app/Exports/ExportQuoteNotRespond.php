<?php

namespace App\Exports;

use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportQuoteNotRespond implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['Quote Number', 'Supplier Name', 'RFQ Id', 'User name', 'valid till', 'Final Amount', 'Tax', 'Tax Value', 'Note', 'Comment', 'Supplier Final Amount', 'Supplier Tex Value', 'Address', 'Address Line 1', 'Address Line 2', 'District', 'Sub District', 'City', 'Pincode']);
        $quotes = Quote::with('userName:id,firstname,lastname')
                        ->with('supplier:id,contact_person_name,contact_person_last_name')
                        ->where('status_id', 2)
                        ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())->get();
        $excelFormateData = [];
        if (!is_null($quotes)) {
            foreach ($quotes as $quote) {
                $excelFormateData[] = (object) array(
                    'quoteNumner' => $quote->quote_number ?? '-',
                    'supplier_id' => ($quote->supplier->contact_person_name??'-').' '. ($quote->supplier->contact_person_last_name??'-'),
                    'rfq_id' => $quote->rfq_id ?? '-',
                    'user_id' => ($quote->user->firstname??'-') .' '. ($quote->user->lastname??'-'),
                    'valid_till' => $quote->valid_till ?? '-',
                    'final_amount' => $quote->final_amount ?? '-',
                    'tax' => $quote->tax ?? '-',
                    'tax_value' => $quote->tax_value ?? '-',
                    'note' => $quote->note ?? '-',
                    'comment' => $quote->comment ?? '-',
                    'supplier_final_amount' => $quote->supplier_final_amount ?? '-',
                    'supplier_tex_value' => $quote->supplier_tex_value ?? '-',
                    'address_name' => $quote->address_name ?? '-',
                    'address_line_1' => $quote->address_line_1 ?? '-',
                    'address_line_2' => $quote->address_line_2 ?? '-',
                    'district' => $quote->district ?? '-',
                    'sub_district' => $quote->sub_district ?? '-',
                    'city' => $quote->city ?? '-',
                    'pincode' => $quote->pincode ?? '-',
                );
            }
            $raws = new Collection($excelFormateData);
        }
        $data = $raws->prepend($columns);
        return $data;
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
