<?php

namespace App\Exports;

use App\Models\Rfq;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

class ExportRfqNotRespond implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['First Name', 'Last Name', 'phone code', 'mobile', 'email', 'Billing Tex options', 'Address Name', 'Address Line 1', 'Address Line 2', 'City', 'Sub District', 'District', 'State', 'pincode', 'reference number', 'Is Preferred Supplier']);
        $rfqs = Rfq::where('status_id',1)
            ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())
            ->get(['firstname', 'lastname', 'phone_code', 'mobile', 'email', 'billing_tax_option', 'address_name', 'address_line_1', 'address_line_2', 'city', 'sub_district', 'district', 'state', 'pincode', 'reference_number', 'is_preferred_supplier']);
        $excelFormateData = [];
        if (!empty($rfqs)) {
            foreach ($rfqs as $rfq) {
                $excelFormateData[] = (object) array(
                    'firstname' => $rfq->firstname ?? '-',
                    'lastname' => $rfq->lastname ?? '-',
                    'phone_code' => $rfq->phone_code ?? '-',
                    'mobile' => $rfq->mobile ?? '-',
                    'email' => $rfq->email ?? '-',
                    'billing_tax_option' => $rfq->billing_tax_option ?? '-',
                    'address_name' => $rfq->address_name ?? '-',
                    'address_line_1' => $rfq->address_line_1 ?? '-',
                    'address_line_2' => $rfq->address_line_2 ?? '-',
                    'city' => $rfq->city ?? '-',
                    'sub_district' => $rfq->sub_district ?? '-',
                    'district' => $rfq->district ?? '-',
                    'state' => $rfq->state ?? '-',
                    'pincode' => $rfq->pincode ?? '-',
                    'reference_number' => $rfq->reference_number ?? '-',
                    'is_preferred_supplier' => $rfq->is_preferred_supplier ?? '-',
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
