<?php

namespace App\Exports\Admin\Finance;

use App\Models\Order;
use App\Models\UserSupplier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Alignment;

class AdminFinanceExport implements FromCollection, ShouldAutoSize, WithEvents
{
   /**
     *
     * Export Finance Cash Tab
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $authUser       = \Auth::user();
        $supplier_id    = UserSupplier::where('user_id', $authUser->id)->pluck('supplier_id')->first();
        $query = Order::with('orderItems','orderStatus','orderTransactions', 'orderCreditDay','companyDetails','quote')->where('is_deleted', 0)
            ->distinct('id');

        //Records by role
        if ($authUser->hasRole('supplier')) {
            $query->where('supplier_id', $supplier_id);
        }

        $orders = $query->get();

        $exportData = collect();

        $orders->map(function ($order) use($authUser, $exportData){

            $orderNumber    = $order->order_number;

            if ($order->is_credit == 1){
                $creditDays     = !empty($order->orderCreditDay) ? $order->orderCreditDay->approved_days : '0';
                $payment_terms  = __('admin.credit')." - ".$creditDays;
                $source         = __('admin.credit');

            } else {
                $payment_terms  = __('admin.advanced');
                $source         = __('admin.cash');

            }

            //Order Status
            $order_status = '-';
            if ($order->order_status == 10) {
                $order_status = __('admin.cancelled');

            } else if (!empty($order->payment_due_date)&& $order->payment_status == 0) {
                if (Carbon::parse($order->payment_due_date)->format('d-m-Y')<Carbon::now()->format('d-m-Y')) {
                    $order_status = __('admin.overdue');

                } else {
                    $order_status = __('admin.payment_pending');

                }
            } else if ($order->payment_status == 0) {
                $order_status = __('admin.payment_pending');

            } else if ($order->payment_status == 1) {
                if(!empty($order->disbursements->first())){
                    $disbursedOrder = $order->disbursements->where('status', 'COMPLETED')->pluck('id')->first();
                }
                if (!empty($disbursedOrder)) {
                    $order_status = __('admin.paid_to_supplier');
                } else {
                    $order_status = __('admin.disbursement_pending');
                }

            } else if ($order->payment_status == 2) {
                $order_status = __('admin.offline_paid');

            } else {
                $order_status = '-';
            }

            //Price calculation
            if ($authUser->hasRole('supplier')) {
                $orderAmount        = $order->quote->supplier_final_amount;

            } else {
                $orderBulkDiscount  = getBulkOrderDiscount($order->id);
                $orderAmount        = $order->quote->final_amount - ($orderBulkDiscount > 0 ? $orderBulkDiscount : 0);

            }

            //Product
            if ($order->orderItems->count() == 0 && $order->orderItems->count() > 1 || empty($order->orderItems->first())){
                $product            = $order->orderItems->count().' '.__('admin.products');

            } else {
                $product            = get_product_name_by_id($order->orderItems->first()->rfq_product_id,1);

            }

            //Category
            $category           = "-";
            if ($order->orderItems->count() != 0) {
                if (!empty($order->orderItems->first()->rfqProduct)) {
                    $category   = $order->orderItems->first()->rfqProduct->category;
                }
            }

            $exportData[] = (object)array(
                'order_number'      => $orderNumber,
                'company_name'      => !empty($order->companyDetails) ? $order->companyDetails->name : '-',
                'date'              => Carbon::parse($order->created_at)->format('d-m-Y'),
                'product'           => $product,
                'category'          => $category,
                'price'             => str_replace(",","",number_format($orderAmount, 2)),
                'source'            => $source,
                'payment_terms'     => $payment_terms,
                'payment_status'    => $order_status,
                'overdue_date'      => !empty($order->payment_due_date) ? Carbon::parse($order->payment_due_date)->format('d-m-Y') : "-",

            );
        });

        $columns = collect([__('admin.order_number'), __('admin.customer_company'), __('admin.date'), __('admin.product'), __('admin.category'), __('admin.price'),__('admin.source'), __('admin.payment_terms'), __('admin.payment_status'), __('admin.overdue_date')]);

        $raws = new Collection($exportData);

        $raws->prepend($columns);

        return $raws;

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:J1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('25378b');

                $event->sheet->getDelegate()->getStyle('A1:J1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');

                $event->sheet->getDelegate()->getStyle('A1:J1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getDelegate()->getStyle("F")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle("F")->getNumberFormat()->setFormatCode('0.00');
            },
        ];
    }
}
