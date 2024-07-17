<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportOrderNotRespond implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['User Name', 'Company Name', 'Group Name', 'Quote Id', 'RFQ Id', 'Supplier Name', 'Order Number', 'Is Credit', 'Payment Amount', 'Payment Due Date', 'Payment Status', 'Payment Date', 'Min Delivery Date', 'Max Delivery Date', 'Address Name', 'Address Line 1', 'Address Line 2', 'City', 'Sub District', 'District', 'Pincode', 'State']);
        $orders = Order::with(['user:id,firstname,lastname','company:id,name','supplier:id,contact_person_name,contact_person_last_name'])
                        ->where('order_status', 1)
                        ->where('payment_status', 0)
                        ->where('is_credit', 0)
                        ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())
                        ->get();

        $excelFormateData = [];
        if (!is_null($orders)) {
            foreach ($orders as $order) {
                $excelFormateData[] = (object) array(
                    'user_id' => ($order->user->firstname ?? '-') . ' ' . ($order->user->lastname ?? '-'),
                    'company_id' => $order->company->name ?? '-',
                    'group_id' => $order->group->name ?? '-',
                    'quote_id' => $order->quote_id ?? '-',
                    'rfq_id' => $order->rfq_id ?? '-',
                    'supplier_id' => ($order->supplier->contact_person_name ?? '-') . ' ' . ($order->supplier->contact_person_last_name ?? '-'),
                    'order_number' => $order->order_number ?? '-',
                    'is_credit' => $order->is_credit ?? '-',
                    'payment_amount' => $order->payment_amount ?? '-',
                    'payment_due_date' => $order->payment_due_date ?? '-',
                    'payment_status' => $order->payment_status ?? '-',
                    'payment_date' => $order->payment_date ?? '-',
                    'min_delivery_date' => $order->min_delivery_date ?? '-',
                    'max_delivery_date' => $order->max_delivery_date ?? '-',
                    'address_name' => $order->address_name ?? '-',
                    'address_line_1' => $order->address_line_1 ?? '-',
                    'address_line_2' => $order->address_line_2 ?? '-',
                    'city' => $order->city ?? '-',
                    'sub_district' => $order->sub_district ?? '-',
                    'district' => $order->district ?? '-',
                    'pincode' => $order->pincode ?? '-',
                    'state' => $order->state ?? '-',
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
