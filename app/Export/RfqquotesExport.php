<?php

namespace App\Export;

use App\Models\Rfq;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Quote;
use App\Models\SupplierDealWithCategory;
use App\Models\UserSupplier;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

class RfqquotesExport implements FromCollection, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        if (auth()->user()->role_id == 2){
            $user_id = Auth::user()->id;

            $columns = collect([__('admin.rfq_number'),__('admin.quote_number'),__('admin.rfq_Item_number'),__('admin.quote_item_number'),__('admin.rfq_date'),__('admin.rfq_status'),__('admin.payment_term'),__('admin.customer_company'), __('admin.customer_name'),__('admin.customer_email'),__('admin.customer_phone'),__('admin.product'),__('admin.qty'),__('admin.rfq_comments'),__('admin.rfq_exp_delivery_date'),__('admin.quote_date'),__('admin.valid_till'),__('admin.quote_status'),__('admin.supplier_company'),__('admin.supplier_name'),__('admin.note'),__('admin.quote_comment'), __('admin.final_ammount')]);


            $rfqs = Rfq::join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
                ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->join('companies', 'companies.id', '=', 'rfqs.company_id')
                ->where('rfqs.is_deleted', 0);
                /***********************begin: User RFQ by permission set******************/
                $isOwner = User::checkCompanyOwner();
                if ($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer rfqs')) {
                    $rfqs = $rfqs->where('rfqs.company_id', Auth::user()->default_company);
                }else {
                    $rfqs = $rfqs->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', Auth::user()->default_company);
                }
                /***********************end: User RFQ by permission set******************/
                    $rfqs=$rfqs->orderBy('rfqs.id', 'desc')
                    ->get(['rfqs.id as id', 'rfqs.status_id as status_id', 'rfqs.reference_number', 'rfq_status.name as status_name', DB::raw('DATE_FORMAT(rfqs.created_at, "%d-%m-%Y	 %H:%i:%s") as rfq_date'), DB::raw('IF(rfqs.is_require_credit = "1","Credit", "Advance") as is_credit'), DB::raw("CONCAT(COALESCE(rfqs.firstname,  ' '), ' ', COALESCE(rfqs.lastname,  ' ')) AS Name"), 'rfqs.email', DB::raw("CONCAT(COALESCE(rfqs.phone_code,  ' '), ' ', COALESCE(rfqs.mobile, '')) AS mobile")]);
            $execlFormateData = [];
            foreach ($rfqs as $rfq){
                $quoteDetails = Quote::join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                    ->join('quote_items', 'quotes.id', '=', 'quote_items.quote_id')
                    ->join('quote_status', 'quotes.status_id', '=', 'quote_status.id')
                    ->join('rfq_products', 'quote_items.rfq_product_id', '=', 'rfq_products.id')
                    ->join('user_rfqs', 'quotes.rfq_id', '=', 'user_rfqs.rfq_id')
                    ->join('rfqs','rfqs.id','=','quotes.rfq_id')
                    ->join('companies', 'companies.id', '=', 'rfqs.company_id')
                    ->join('units', 'rfq_products.unit_id', '=', 'units.id');
                /***********************begin: Quotes by permission set******************/
                $isOwner = User::checkCompanyOwner();
                if ($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer quotes')) {
                    $quoteDetails = $quoteDetails->where('rfqs.company_id', Auth::user()->default_company);
                }elseif(Auth::user()->hasPermissionTo('publish buyer quotes')){
                    $quoteDetails = $quoteDetails->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', Auth::user()->default_company);
                }else{
                    $quoteDetails = $quoteDetails->where('rfqs.company_id','<>', Auth::user()->default_company);
                }
                $quoteDetails = $quoteDetails->where('quote_status.id', '!=', 5)
                    ->where('quotes.rfq_id', $rfq->id)->orderBy('quotes.id', 'desc')->get(['rfqs.payment_type','rfqs.credit_days','quotes.quote_number', 'rfq_products.rfq_product_item_number', 'quote_items.quote_item_number', 'companies.name as company_name', DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"), 'rfq_products.quantity','rfq_products.comment as rfq_comment','rfq_products.expected_date',DB::raw('DATE_FORMAT(quotes.created_at, "%d-%m-%Y	 %H:%i:%s") as quote_date'), 'quotes.valid_till','quote_status.name AS quote_status','suppliers.name AS supplier_company','suppliers.contact_person_name AS contact_person_name', DB::raw('COALESCE(quotes.note) as note'), DB::raw('COALESCE(quotes.comment) as quote_comment'), DB::raw('CONCAT("Rp ", FORMAT(quotes.final_amount, 2)) as final_amount')]);

                foreach ($quoteDetails as $quoteDetail){
                    if($quoteDetail->payment_type == 0){
                        $terms = __('admin.advanced');
                    }
                    elseif($quoteDetail->payment_type == 1){
                        $terms =  __('admin.credit').'-'.$quoteDetail->credit_days;
                    }
                    elseif($quoteDetail->payment_type == 2){
                        $terms = __('admin.loan_koinworks');
                    }
                    elseif($quoteDetail->payment_type == 3){
                        $terms =  __('admin.lc');
                    }
                    else{
                        $terms = __('admin.skbdn');
                    }

                    $final_amount = '-';
                    if(!empty($quoteDetail->final_amount)){
                        $ItemValue = ["Rp ", ","];
                        $ReplaceValue = ["", "", ""];
                        
                        $final_amount = str_replace($ItemValue, $ReplaceValue, $quoteDetail->final_amount);
                    }

                    $execlFormateData[] = (object)array(
                        'rfq_number' => $rfq->reference_number,
                        'quote_number' => $quoteDetail->quote_number,
                        'quote_item_number' => $quoteDetail->quote_item_number,
                        'rfq_product_item_number' => $quoteDetail->rfq_product_item_number,
                        'rfq_created_at' => $rfq->rfq_date,
                        'rfq_status_name' => (!empty($rfq->status_name))?__('admin.' . trim($rfq->status_name)):'-',
                        'is_require_credit' => $terms,
                        'company_name' => $quoteDetail->company_name,
                        'user_name' => $rfq->Name,
                        'user_email' => $rfq->email,
                        'user_mobile' => $rfq->mobile,
                        'product' => $quoteDetail->Product,
                        'quantity' => $quoteDetail->quantity,
                        'rfq_comment' => $quoteDetail->rfq_comment,
                        'expected_date' => $quoteDetail->expected_date,
                        'quote_date' => $quoteDetail->quote_date,
                        'valid_till' => $quoteDetail->valid_till,
                        'quote_status' => $quoteDetail->quote_status,
                        'supplier_company' => $quoteDetail->supplier_company,
                        'contact_person_name' => $quoteDetail->contact_person_name,
                        'note' => $quoteDetail->note,
                        'comment' => $quoteDetail->quote_comment,
                        'final_amount' => $final_amount
                    );
                }
            }
            $raws = new Collection($execlFormateData);

        }
        else if (auth()->user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();

            $columns = collect([__('admin.rfq_number'),__('admin.quote_number'),__('admin.rfq_Item_number'),__('admin.quote_item_number'),__('admin.rfq_date'),__('admin.rfq_status'),__('admin.payment_term'),__('admin.customer_company'),__('admin.customer_name'),__('admin.product'),__('admin.qty'),__('admin.rfq_comments'),__('admin.rfq_exp_delivery_date'),__('admin.quote_date'),__('admin.valid_till'),__('admin.quote_status'),__('admin.supplier_company'),__('admin.supplier_name'),__('admin.supplier_email'),__('admin.supplier_phone'),__('admin.note'),__('admin.quote_comment'), __('admin.final_ammount')]);

            $SupplierDealingCategoty = SupplierDealWithCategory::where('supplier_id',$supplier_id)->pluck('sub_category_id')->toArray();

            $rfqs = Rfq::join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
                ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->join('rfq_products','rfq_products.rfq_id', '=', 'rfqs.id')
                ->leftJoin('quotes', 'quotes.rfq_id', '=', 'rfqs.id')
                ->join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                ->where('quotes.supplier_id', '=', $supplier_id)
                ->whereIn('rfq_products.sub_category_id', $SupplierDealingCategoty)
                ->groupBy('quotes.rfq_id')
                ->distinct('quotes.rfq_id')
                ->orderBy('rfqs.id', 'desc')
                ->get(['rfqs.id as id', 'rfqs.status_id as status_id', 'rfqs.reference_number', 'rfq_status.name as status_name', DB::raw('DATE_FORMAT(rfqs.created_at, "%d-%m-%Y	 %H:%i:%s") as rfq_date'), DB::raw('IF(rfqs.is_require_credit = "1","Credit", "Advance") as is_credit'), DB::raw("CONCAT(COALESCE(rfqs.firstname,  ' '), ' ', COALESCE(rfqs.lastname,  ' ')) AS Name"), 'rfqs.email', DB::raw("CONCAT(COALESCE(rfqs.phone_code,  ' '), ' ', COALESCE(rfqs.mobile, '')) AS mobile")]);

            $execlFormateData = [];
            foreach ($rfqs as $rfq){
                $quoteDetails = Quote::join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                    ->join('quote_items', 'quotes.id', '=', 'quote_items.quote_id')
                    ->join('quote_status', 'quotes.status_id', '=', 'quote_status.id')
                    ->join('rfq_products', 'quote_items.rfq_product_id', '=', 'rfq_products.id')
                    ->join('user_rfqs', 'quotes.rfq_id', '=', 'user_rfqs.rfq_id')
                    ->join('rfqs','rfqs.id','=','quotes.rfq_id')
                    ->join('companies', 'companies.id', '=', 'rfqs.company_id')
                    ->join('units', 'rfq_products.unit_id', '=', 'units.id')
                    ->where('quotes.supplier_id', '=', $supplier_id)
                    ->where('quotes.rfq_id', $rfq->id)->orderBy('quotes.id', 'desc')->get(['rfqs.payment_type','rfqs.credit_days','quotes.quote_number', 'rfq_products.rfq_product_item_number', 'quote_items.quote_item_number', 'companies.name as company_name', DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"), 'rfq_products.quantity','rfq_products.comment as rfq_comment','rfq_products.expected_date',DB::raw('DATE_FORMAT(quotes.created_at, "%d-%m-%Y	 %H:%i:%s") as quote_date'), 'quotes.valid_till','quote_status.name AS quote_status','suppliers.name AS supplier_company','suppliers.contact_person_name AS contact_person_name', DB::raw('COALESCE(quotes.note) as note'), DB::raw('COALESCE(quotes.comment) as quote_comment'), DB::raw('CONCAT("Rp ", FORMAT(quotes.supplier_final_amount, 2)) as final_amount'),'suppliers.contact_person_email as supplier_email', 'suppliers.contact_person_phone as supplier_phone']);

                foreach ($quoteDetails as $quoteDetail){
                    if($quoteDetail->payment_type == 0){
                        $terms = __('admin.advanced');
                    }
                    elseif($quoteDetail->payment_type == 1){
                        $terms =  __('admin.credit').'-'.$quoteDetail->credit_days;
                    }
                    elseif($quoteDetail->payment_type == 2){
                        $terms = __('admin.loan_koinworks');
                    }
                    elseif($quoteDetail->payment_type == 3){
                        $terms =  __('admin.lc');
                    }
                    else{
                        $terms = __('admin.skbdn');
                    }

                    $final_amount = '-';
                    if(!empty($quoteDetail->final_amount)){
                        $ItemValue = ["Rp ", ","];
                        $ReplaceValue = ["", ""];
                        
                        $final_amount = str_replace($ItemValue, $ReplaceValue, $quoteDetail->final_amount);
                    }

                    $execlFormateData[] = (object)array(
                        'rfq_number' => $rfq->reference_number,
                        'quote_number' => $quoteDetail->quote_number,
                        'quote_item_number' => $quoteDetail->quote_item_number,
                        'rfq_product_item_number' => $quoteDetail->rfq_product_item_number,
                        'rfq_created_at' => $rfq->rfq_date,
                        'rfq_status_name' => (!empty($rfq->status_name))?__('admin.' . trim($rfq->status_name)):'-',
                        'is_require_credit' => $terms,
                        'company_name' => $quoteDetail->company_name,
                        'user_name' => $rfq->Name,
                        'product' => $quoteDetail->Product,
                        'quantity' => $quoteDetail->quantity,
                        'rfq_comment' => $quoteDetail->rfq_comment,
                        'expected_date' => $quoteDetail->expected_date,
                        'quote_date' => $quoteDetail->quote_date,
                        'valid_till' => $quoteDetail->valid_till,
                        'quote_status' => $quoteDetail->quote_status,
                        'supplier_company' => $quoteDetail->supplier_company,
                        'contact_person_name' => $quoteDetail->contact_person_name,
                        'supplier_email' => $quoteDetail->supplier_email,
                        'supplier_phone' => $quoteDetail->supplier_phone,
                        'note' => $quoteDetail->note,
                        'comment' => $quoteDetail->quote_comment,
                        'final_amount' => $final_amount
                    );
                }
            }
            $raws = new Collection($execlFormateData);
        } else {
            $columns = collect([__('admin.rfq_number'),__('admin.quote_number'),__('admin.rfq_Item_number'),__('admin.quote_item_number'),__('admin.rfq_date'),__('admin.rfq_status'),__('admin.payment_term'),__('admin.customer_company'),__('admin.customer_name'),__('admin.customer_email'),__('admin.customer_phone'),__('admin.product'),__('admin.qty'),__('admin.rfq_comments'),__('admin.rfq_exp_delivery_date'),__('admin.quote_date'),__('admin.valid_till'),__('admin.quote_status'),__('admin.supplier_company'),__('admin.supplier_name'),__('admin.supplier_email'),__('admin.supplier_phone'),__('admin.note'),__('admin.quote_comment'), __('admin.final_ammount')]);

            $rfqs = Rfq::join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
                ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->orderBy('rfqs.id', 'desc')
                ->get(['rfqs.id as id', 'rfqs.status_id as status_id', 'rfqs.reference_number', 'rfq_status.name as status_name', DB::raw('DATE_FORMAT(rfqs.created_at, "%d-%m-%Y	 %H:%i:%s") as rfq_date'), DB::raw('IF(rfqs.is_require_credit = "1","Credit", "Advance") as is_credit'), DB::raw("CONCAT(COALESCE(rfqs.firstname,  ' '), ' ', COALESCE(rfqs.lastname,  ' ')) AS Name"), 'rfqs.email', DB::raw("CONCAT(COALESCE(rfqs.phone_code,  ' '), ' ', COALESCE(rfqs.mobile, '')) AS mobile")]);
            $execlFormateData = [];
            foreach ($rfqs as $rfq){
                $quoteDetails = Quote::join('suppliers', 'quotes.supplier_id', '=', 'suppliers.id')
                    ->join('quote_items', 'quotes.id', '=', 'quote_items.quote_id')
                    ->join('quote_status', 'quotes.status_id', '=', 'quote_status.id')
                    ->join('rfq_products', 'quote_items.rfq_product_id', '=', 'rfq_products.id')
                    ->join('user_rfqs', 'quotes.rfq_id', '=', 'user_rfqs.rfq_id')
                    ->join('rfqs','rfqs.id','=','quotes.rfq_id')
                    ->join('companies', 'companies.id', '=', 'rfqs.company_id')
                    ->join('units', 'rfq_products.unit_id', '=', 'units.id');
                    //Agent category permissions
                    if (Auth::user()->hasRole('agent')) {

                        $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                        $quoteDetails->whereIn('rfq_products.category_id', $assignedCategory);

                    }
                    $quoteDetails = $quoteDetails->where('quotes.rfq_id', $rfq->id)->orderBy('quotes.id', 'desc')->get(['rfqs.payment_type','rfqs.credit_days','quotes.quote_number', 'rfq_products.rfq_product_item_number', 'quote_items.quote_item_number', 'companies.name as company_name', DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"), 'rfq_products.quantity','rfq_products.comment as rfq_comment','rfq_products.expected_date',DB::raw('DATE_FORMAT(quotes.created_at, "%d-%m-%Y	 %H:%i:%s") as quote_date'), 'quotes.valid_till','quote_status.name AS quote_status','suppliers.name AS supplier_company','suppliers.contact_person_name AS contact_person_name', DB::raw('COALESCE(quotes.note) as note'), DB::raw('COALESCE(quotes.comment) as quote_comment'), DB::raw('CONCAT("Rp ", FORMAT(quotes.final_amount, 2)) as final_amount'),'suppliers.contact_person_email as supplier_email', 'suppliers.contact_person_phone as supplier_phone']);
                foreach ($quoteDetails as $quoteDetail){
                    if($quoteDetail->payment_type == 0){
                        $terms = __('admin.advanced');
                    }
                    elseif($quoteDetail->payment_type == 1){
                        $terms =  __('admin.credit').'-'.$quoteDetail->credit_days;
                    }
                    elseif($quoteDetail->payment_type == 2){
                        $terms = __('admin.loan_koinworks');
                    }
                    elseif($quoteDetail->payment_type == 3){
                        $terms =  __('admin.lc');
                    }
                    else{
                        $terms = __('admin.skbdn');
                    }

                    $final_amount = '-';
                    if(!empty($quoteDetail->final_amount)){
                        $ItemValue = ["Rp ",","];
                        $ReplaceValue = ["", ""];
                        $final_amount = str_replace($ItemValue, $ReplaceValue, $quoteDetail->final_amount);
                    }

                    $execlFormateData[] = (object)array(
                        'rfq_number' => $rfq->reference_number,
                        'quote_number' => $quoteDetail->quote_number,
                        'quote_item_number' => $quoteDetail->quote_item_number,
                        'rfq_product_item_number' => $quoteDetail->rfq_product_item_number,
                        'rfq_created_at' => $rfq->rfq_date,
                        'rfq_status_name' => (!empty($rfq->status_name))?__('admin.' . trim($rfq->status_name)):'-',
                        'is_require_credit' => $terms,
                        'company_name' => $quoteDetail->company_name,
                        'user_name' => $rfq->Name,
                        'user_email' => $rfq->email,
                        'user_mobile' => $rfq->mobile,
                        'product' => $quoteDetail->Product,
                        'quantity' => $quoteDetail->quantity,
                        'rfq_comment' => $quoteDetail->rfq_comment,
                        'expected_date' => $quoteDetail->expected_date,
                        'quote_date' => $quoteDetail->quote_date,
                        'valid_till' => $quoteDetail->valid_till,
                        'quote_status' => $quoteDetail->quote_status,
                        'supplier_company' => $quoteDetail->supplier_company,
                        'contact_person_name' => $quoteDetail->contact_person_name,
                        'supplier_email' => $quoteDetail->supplier_email,
                        'supplier_phone' => $quoteDetail->supplier_phone,
                        'note' => $quoteDetail->note,
                        'comment' => $quoteDetail->quote_comment,
                        'final_amount' => $final_amount
                    );
                }
            }
            $raws = new Collection($execlFormateData);
        }
        $raws->prepend($columns);
        return $raws;
    }

    public function registerEvents(): array
    {
        if (auth()->user()->role_id == 1){
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:Y1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:Y1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');

                    $event->sheet->getDelegate()->getStyle('A1:Y1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getDelegate()->getStyle("Y")->getNumberFormat()->setFormatCode('0.00');
                },
            ];
        }elseif (auth()->user()->role_id == 2){
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:W1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:X1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');

                    $event->sheet->getDelegate()->getStyle('A1:X1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getDelegate()->getStyle("W")->getNumberFormat()->setFormatCode('0.00');
                },
            ];
        }else{
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:W1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:X1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');

                    $event->sheet->getDelegate()->getStyle('A1:X1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getDelegate()->getStyle("W")->getNumberFormat()->setFormatCode('0.00');
                },
            ];

        }
    }

}
