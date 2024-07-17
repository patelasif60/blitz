<?php

namespace App\Export;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\UserSupplier;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Alignment;


class OrderExport implements FromCollection, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        if (auth()->user()->role_id == 2){
            $user_id = Auth::user()->id;
            $columns = collect(['Order Number','Quote Number','RFQ Number',
                'Order Item Number',
                'Quote Item Number',
                'RFQ Item Number','Order Date',
                'Order Status','Customer Reference Id','PO Number','Customer Company Name','Customer Name','Customer Email','Customer Phone', 'Supplier Company Name','Supplier Name','Product Name','Payment Term','Payment Due Date','Address','Sub District', 'District', 'City','Pincode','State','Price Per Unit', 'Final Amount']);

            $orderDetails = DB::table('orders')
                ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
                ->leftJoin('order_pos', 'orders.id', '=', 'order_pos.order_id')
                ->join('order_status', 'orders.order_status', '=', 'order_status.id')
                ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
                ->join('companies', 'orders.company_id', '=', 'companies.id')
                // ->join('quote_items', 'quotes.id', '=', 'quote_items.quote_id')
                // ->join('products', 'quote_items.product_id', '=', 'products.id')
                // ->join('products', 'quotes.product_id', '=', 'products.id')
                ->join('order_items', 'rfq_products.id', '=', 'order_items.rfq_product_id') //rightJoin if want all order record // added by ronak
                ->join('quote_items', 'rfq_products.id', '=', 'quote_items.rfq_product_id'); // added by ronak
                /*********begin: set permissions based on custom role.**************/
                $isOwner = User::checkCompanyOwner();
                if (Auth::user()->hasPermissionTo('list-all buyer orders') || $isOwner == true) {
                    $orderDetails = $orderDetails->where('rfqs.company_id', Auth::user()->default_company);
                }else {
                    $orderDetails = $orderDetails->where('orders.user_id', Auth::user()->id)->where('rfqs.company_id', Auth::user()->default_company);
                }
                /*********end: set permissions based on custom role.**************/

            $orderDetails = $orderDetails->where('orders.is_deleted', 0)
                ->orderBy('orders.id', 'desc')
                // ->groupBy('orders.id')
                ->groupBy('order_items.order_item_number') // added by ronak bhabhor
                ->get(['orders.payment_type','orders.credit_days','orders.id','orders.order_number','quotes.quote_number','rfqs.reference_number as rfq_reference_number',
                    'order_items.order_item_number',
                    'quote_items.quote_item_number',
                    'rfq_products.rfq_product_item_number',
                    DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y	 %H:%i:%s") as order_date'),
                    'order_status.name','orders.order_status as order_status_id', 'orders.customer_reference_id', 'order_pos.po_number', 'companies.name as company_name', DB::raw("CONCAT(COALESCE(users.firstname,  ' '), ' ', COALESCE(users.lastname,  ' ')) AS Name"),'users.email',DB::raw("CONCAT(COALESCE(users.phone_code,  ' '), ' ', COALESCE(users.mobile, '')) AS mobile"), 'suppliers.name as supplier_company_name', 'suppliers.contact_person_name as supplier_name',DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"),DB::raw('IF(orders.is_credit = "1","Credit", "Advance") as is_credit'),'orders.payment_due_date',DB::raw("CONCAT(COALESCE(orders.address_line_1,  ' '), ' ', COALESCE(orders.address_line_2,  ' ')) AS address"), 'orders.sub_district', 'orders.district', 'orders.city','orders.pincode','orders.state','orders.city_id', 'orders.state_id','quote_items.product_price_per_unit',  'quotes.final_amount']);
            $execlFormateData = [];
            foreach ($orderDetails as $orderDetail){
                if($orderDetail->payment_type == 0){
                        $terms = 'Advance';
                    }
                    elseif($orderDetail->payment_type == 1){
                        $terms = 'Credit -'.$orderDetail->credit_days;
                    }
                    elseif($orderDetail->payment_type == 2){
                        $terms = 'Loan Koinworks';
                    }
                    elseif($orderDetail->payment_type == 3){
                        $terms = 'LC';
                    }
                    else{
                        $terms = 'SKBDN';
                    }
                $execlFormateData[] = (object)array(

                    'id' =>$orderDetail->id,
                    'order_number' => $orderDetail->order_number,
                    'quote_number' => $orderDetail->quote_number,
                    'rfq_reference_number' => $orderDetail->rfq_reference_number,
                    'order_item_number' => $orderDetail->order_item_number,
                    'quote_item_number' => $orderDetail->quote_item_number,
                    'rfq_product_item_number' => $orderDetail->rfq_product_item_number,
                    'order_date' => $orderDetail->order_date,
                    'order_status' => ($orderDetail->order_status_id == 8) ? sprintf(trim($orderDetail->name),changeDateFormat($orderDetail->payment_due_date,'d/m/Y')) : trim($orderDetail->name),
                    'customer_reference_id' => $orderDetail->customer_reference_id,
                    'po_number' => $orderDetail->po_number,
                    'company_name' => $orderDetail->company_name,
                    'user_name' => $orderDetail->Name,
                    'user_email' => $orderDetail->email,
                    'user_mobile' => $orderDetail->mobile,
                    'supplier_company_name' => $orderDetail->supplier_company_name,
                    'supplier_name' => $orderDetail->supplier_name,
                    'Product' => $orderDetail->Product,
                    'is_credit' => $terms,
                    'payment_due_date' => $orderDetail->payment_due_date,
                    'address' => $orderDetail->address,
                    'sub_district' => $orderDetail->sub_district,
                    'district' => $orderDetail->district,
                    'city' => $orderDetail->city_id > 0 ? getCityName($orderDetail->city_id) : ($orderDetail->city ? $orderDetail->city : '-'),
                    'pincode' => $orderDetail->pincode,
                    'state' => $orderDetail->state_id > 0 ? getStateName($orderDetail->state_id): ($orderDetail->state ? $orderDetail->state : '-'),
                    'product_price_per_unit' => $orderDetail->product_price_per_unit,
                    'final_amount' => $orderDetail->final_amount
                );

            }
            $raws = new Collection($execlFormateData);

        }else if (auth()->user()->role_id == 3){
            $columns = collect(['Order Number','Quote Number','RFQ Number',
                'Order Item Number',
                'Quote Item Number',
                'RFQ Item Number','Order Date',
                'Order Status','Customer Reference Id','PO Number','Customer Company Name','Customer Name', 'Supplier Company Name','Supplier Name','Supplier Email','Supplier Phone','Product Name','Payment Term','Payment Due Date','Address','Sub District', 'District', 'City','Pincode','State','Price Per Unit', 'Final Amount']);

            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $orderDetails = DB::table('orders')
                ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
                ->leftJoin('order_pos', 'orders.id', '=', 'order_pos.order_id')
                ->join('order_status', 'orders.order_status', '=', 'order_status.id')
                ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
                ->join('companies', 'orders.company_id', '=', 'companies.id')
                // ->join('quote_items', 'quotes.id', '=', 'quote_items.quote_id')
                // ->join('products', 'quote_items.product_id', '=', 'products.id')
                // ->join('products', 'quotes.product_id', '=', 'products.id')
                ->join('order_items', 'rfq_products.id', '=', 'order_items.rfq_product_id') //rightJoin if want all order record // added by ronak
                ->join('quote_items', 'rfq_products.id', '=', 'quote_items.rfq_product_id') // added by ronak
                ->where('suppliers.id', $supplier_id)
                ->orderBy('orders.id', 'desc')
                // ->groupBy('orders.id')
                ->groupBy('order_items.order_item_number') // added by ronak bhabhor
                ->get(['orders.payment_type','orders.credit_days','orders.id','orders.order_number','quotes.quote_number','rfqs.reference_number as rfq_reference_number',
                    'order_items.order_item_number',
                    'quote_items.quote_item_number',
                    'rfq_products.rfq_product_item_number',
                    DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y	 %H:%i:%s") as order_date'),
                    'order_status.name','orders.order_status as order_status_id','orders.order_status as order_status_id', 'orders.customer_reference_id', 'order_pos.po_number', 'companies.name as company_name', DB::raw("CONCAT(COALESCE(users.firstname,  ' '), ' ', COALESCE(users.lastname,  ' ')) AS Name"), 'suppliers.name as supplier_company_name', 'suppliers.contact_person_name as supplier_name','suppliers.contact_person_email as email_suppliers',DB::raw("CONCAT(COALESCE(suppliers.`cp_phone_code`,  ' '), ' ', COALESCE(suppliers.contact_person_phone, '')) as mobile_suppliers"),DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"),DB::raw('IF(orders.is_credit = "1","Credit", "Advance") as is_credit'),'orders.payment_due_date',DB::raw("CONCAT(COALESCE(orders.address_line_1,  ' '), ' ', COALESCE(orders.address_line_2,  ' ')) AS address"), 'orders.sub_district', 'orders.district', 'orders.city','orders.pincode','orders.state','orders.city_id', 'orders.state_id','quote_items.product_price_per_unit',  DB::raw('quotes.supplier_final_amount as final_amount')]);
            $execlFormateData = [];
            foreach ($orderDetails as $orderDetail){
                if($orderDetail->payment_type == 0){
                    $terms = 'Advance';
                }
                elseif($orderDetail->payment_type == 1){
                    $terms = 'Credit -'.$orderDetail->credit_days;
                }
                elseif($orderDetail->payment_type == 2){
                    $terms = 'Loan Koinworks';
                }
                elseif($orderDetail->payment_type == 3){
                    $terms = 'LC';
                }
                else{
                    $terms = 'SKBDN';
                }
                $execlFormateData[] = (object)array(
                    'id' =>$orderDetail->id,
                    'order_number' => $orderDetail->order_number,
                    'quote_number' => $orderDetail->quote_number,
                    'rfq_reference_number' => $orderDetail->rfq_reference_number,
                    'order_item_number' => $orderDetail->order_item_number,
                    'quote_item_number' => $orderDetail->quote_item_number,
                    'rfq_product_item_number' => $orderDetail->rfq_product_item_number,
                    'order_date' => $orderDetail->order_date,
                    'order_status' => ($orderDetail->order_status_id == 8) ? sprintf(trim($orderDetail->name),changeDateFormat($orderDetail->payment_due_date,'d/m/Y')) : trim($orderDetail->name),
                    'customer_reference_id' => $orderDetail->customer_reference_id,
                    'po_number' => $orderDetail->po_number,
                    'company_name' => $orderDetail->company_name,
                    'user_name' => $orderDetail->Name,
                    'supplier_company_name' => $orderDetail->supplier_company_name,
                    'supplier_name' => $orderDetail->supplier_name,
                    'supplier_email' => $orderDetail->email_suppliers,
                    'supplier_mobile' => $orderDetail->mobile_suppliers,
                    'Product' => $orderDetail->Product,
                    'is_credit' => $terms,
                    'payment_due_date' => $orderDetail->payment_due_date,
                    'address' => $orderDetail->address,
                    'sub_district' => $orderDetail->sub_district,
                    'district' => $orderDetail->district,
                    'city' => $orderDetail->city_id > 0 ? getCityName($orderDetail->city_id) : ($orderDetail->city ? $orderDetail->city : '-'),
                    'pincode' => $orderDetail->pincode,
                    'state' => $orderDetail->state_id > 0 ? getStateName($orderDetail->state_id): ($orderDetail->state ? $orderDetail->state : '-'),
                    'product_price_per_unit' => $orderDetail->product_price_per_unit,
                    'final_amount' => $orderDetail->final_amount
                );
            }
            $raws = new Collection($execlFormateData);
        } else {
            $columns = collect(['Order Number','Quote Number','RFQ Number',
            'Order Item Number',
            'Quote Item Number',
            'RFQ Item Number','Order Date',
            'Order Status','Customer Reference Id','PO Number','Customer Company Name','Customer Name','Customer Email','Customer Phone', 'Supplier Company Name','Supplier Name','Supplier Email','Supplier Phone','Product Name','Payment Term','Payment Due Date','Address','Sub District', 'District', 'City','Pincode','State','Price Per Unit', 'Final Amount']);
            $orderDetails = DB::table('orders')
                ->leftJoin('order_credit_days as ocd', 'orders.id', '=', 'ocd.order_id')
                ->leftJoin('order_pos', 'orders.id', '=', 'order_pos.order_id')
                ->join('order_status', 'orders.order_status', '=', 'order_status.id')
                // ->join('order_items', 'orders.id', '=', 'order_items.order_id') // added by ronak
                ->join('quotes', 'orders.quote_id', '=', 'quotes.id')
                ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                ->join('rfqs', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
                ->join('companies', 'orders.company_id', '=', 'companies.id')
                // ->join('quote_items', 'quotes.id', '=', 'quote_items.quote_id') // added by ronak
                // ->join('products', 'quote_items.product_id', '=', 'products.id') // added by ronak
                // ->join('products', 'quotes.product_id', '=', 'products.id') // commented by ronak
                ->join('order_items', 'rfq_products.id', '=', 'order_items.rfq_product_id') //rightJoin if want all order record // added by ronak
                ->join('quote_items', 'rfq_products.id', '=', 'quote_items.rfq_product_id'); // added by ronak
                //Agent category permissions
                if (Auth::user()->hasRole('agent')) {

                    $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                    $orderDetails->whereIn('rfq_products.category_id', $assignedCategory);

                }
            $orderDetails = $orderDetails->orderBy('orders.id', 'desc')
                // ->groupBy('orders.id')
                ->groupBy('order_items.order_item_number') // added by ronak bhabhor
                ->get(['orders.payment_type','orders.credit_days','orders.id','orders.order_number','quotes.quote_number','rfqs.reference_number as rfq_reference_number',
                'order_items.order_item_number',
                'quote_items.quote_item_number',
                'rfq_products.rfq_product_item_number',
                DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y	 %H:%i:%s") as order_date'),
                'order_status.name','orders.order_status as order_status_id', 'orders.customer_reference_id', 'order_pos.po_number', 'companies.name as company_name', DB::raw("CONCAT(COALESCE(users.firstname,  ' '), ' ', COALESCE(users.lastname,  ' ')) AS Name"),'users.email',DB::raw("CONCAT(COALESCE(users.phone_code,  ' '), ' ', COALESCE(users.mobile, '')) AS mobile"), 'suppliers.name as supplier_company_name', 'suppliers.contact_person_name as supplier_name','suppliers.contact_person_email as email_suppliers',DB::raw("CONCAT(COALESCE(suppliers.cp_phone_code,  ' '), ' ', COALESCE(suppliers.contact_person_phone, '')) as mobile_suppliers"),DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"),DB::raw('IF(orders.is_credit = "1","Credit", "Advance") as is_credit'),'orders.payment_due_date',DB::raw("CONCAT(COALESCE(orders.address_line_1,  ' '), ' ', COALESCE(orders.address_line_2,  ' ')) AS address"), 'orders.sub_district', 'orders.district', 'orders.city','orders.pincode','orders.state','orders.city_id', 'orders.state_id','quote_items.product_price_per_unit','quotes.final_amount']);
            $execlFormateData = [];
            foreach ($orderDetails as $orderDetail){
                    if($orderDetail->payment_type == 0){
                        $terms = 'Advance';
                    }
                    elseif($orderDetail->payment_type == 1){
                        $terms = 'Credit -'.$orderDetail->credit_days;
                    }
                    elseif($orderDetail->payment_type == 2){
                        $terms = 'Loan Koinworks';
                    }
                    elseif($orderDetail->payment_type == 3){
                        $terms = 'LC';
                    }
                    else{
                        $terms = 'SKBDN';
                    }
                $execlFormateData[] = (object)array(
                    'id' =>$orderDetail->id,
                    'order_number' => $orderDetail->order_number,
                    'quote_number' => $orderDetail->quote_number,
                    'rfq_reference_number' => $orderDetail->rfq_reference_number,
                    'order_item_number' => $orderDetail->order_item_number,
                    'quote_item_number' => $orderDetail->quote_item_number,
                    'rfq_product_item_number' => $orderDetail->rfq_product_item_number,
                    'order_date' => $orderDetail->order_date,
                    'order_status' => ($orderDetail->order_status_id == 8) ? sprintf(trim($orderDetail->name),changeDateFormat($orderDetail->payment_due_date,'d/m/Y')) : trim($orderDetail->name),
                    'customer_reference_id' => $orderDetail->customer_reference_id,
                    'po_number' => $orderDetail->po_number,
                    'company_name' => $orderDetail->company_name,
                    'user_name' => $orderDetail->Name,
                    'user_email' => $orderDetail->email,
                    'user_mobile' => $orderDetail->mobile,
                    'supplier_company_name' => $orderDetail->supplier_company_name,
                    'supplier_name' => $orderDetail->supplier_name,
                    'supplier_email' => $orderDetail->email_suppliers,
                    'supplier_mobile' => $orderDetail->mobile_suppliers,
                    'Product' => $orderDetail->Product,
                    'is_credit' => $terms,
                    'payment_due_date' => $orderDetail->payment_due_date,
                    'address' => $orderDetail->address,
                    'sub_district' => $orderDetail->sub_district,
                    'district' => $orderDetail->district,
                    'city' => $orderDetail->city_id > 0 ? getCityName($orderDetail->city_id) : ($orderDetail->city ? $orderDetail->city : '-'),
                    'pincode' => $orderDetail->pincode,
                    'state' => $orderDetail->state_id > 0 ? getStateName($orderDetail->state_id): ($orderDetail->state ? $orderDetail->state : '-'),
                    'product_price_per_unit' => $orderDetail->product_price_per_unit,
                    'final_amount' => $orderDetail->final_amount
                );
            }
            $raws = new Collection($execlFormateData);
        }
    //    dd($raws);
        foreach ($raws as $i=>$raw){
            $finalAmount = (float)$raw->final_amount;
            $pricePerUnit = (float)$raw->product_price_per_unit;
            if (auth()->user()->role_id != 3) {
                $finalAmount = $finalAmount - (float)(getBulkOrderDiscount($raw->id) ?? 0);
            }
            $raws[$i]->final_amount = str_replace(",","",number_format($finalAmount,2));
            $raws[$i]->product_price_per_unit = str_replace(",","",number_format($pricePerUnit,2));
            unset($raws[$i]->id);
        }
        $raws->prepend($columns);
        return $raws;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        if (auth()->user()->role_id == 1){
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:AC1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:AC1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');


                    $event->sheet->getDelegate()->getStyle('A1:AC1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getDelegate()->getStyle("AB:AC")->getNumberFormat()->setFormatCode('0.00');
                },
            ];
        }if (auth()->user()->role_id == 2){
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:AA1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:AA1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');


                    $event->sheet->getDelegate()->getStyle('A1:AA1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getDelegate()->getStyle("Z:AA")->getNumberFormat()->setFormatCode('0.00');
                },
            ];
        }else{
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:AA1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:AA1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');


                    $event->sheet->getDelegate()->getStyle('A1:AA1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getDelegate()->getStyle("Z:AA")->getNumberFormat()->setFormatCode('0.00');

       },
            ];
        }
    }
}
