<?php

namespace App\Export;

use App\Models\Category;
use App\Models\Rfq;
use App\Models\SupplierDealWithCategory;
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



class RfqExport implements FromCollection, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        if (auth()->user()->role_id == 2){
            //user
            $user_id = Auth::user()->id;
            $columns = collect([__('admin.rfq_number'),__('admin.rfq_product_Number'),__('admin.Date'),__('admin.status'),__('admin.payment_term'),__('admin.company_name'),__('admin.customer_name'),__('admin.customer_email'),__('admin.customer_phone'),__('admin.product'),__('admin.quantity'),__('admin.comments'), __('admin.expected_date'),__('admin.Address'),__('admin.sub_district'),__('admin.district'),__('admin.city'),__('admin.provinces'),__('admin.pincode'),__('admin.need_uploding_services'),__('admin.need_rental_forklift')]);



            $rfqDetails = Rfq::join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
                    ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                    ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                    ->leftJoin('groups','rfqs.group_id','=','groups.id')
                    ->join('companies', 'companies.id', '=', 'rfqs.company_id')
                     ->where('rfqs.is_deleted', 0);
                    /*********begin: set permissions based on custom role.**************/
                    $isOwner = User::checkCompanyOwner();
                    if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer rfqs')){
                        $rfqDetails = $rfqDetails->where('rfqs.company_id', Auth::user()->default_company);
                    }else {
                        $rfqDetails = $rfqDetails->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', Auth::user()->default_company);
                    }
                    /*********begin: set permissions based on custom role.**************/
                    $rfqDetails = $rfqDetails->orderBy('rfqs.id', 'desc')
                    ->get(['rfqs.payment_type','rfqs.credit_days','rfqs.reference_number as rfq_number','rfq_products.rfq_product_item_number', DB::raw('DATE_FORMAT(rfqs.created_at, "%d-%m-%Y	 %H:%i:%s") as rfq_date'), 'rfq_status.name as status_name', DB::raw('IF(rfqs.is_require_credit = "1","Credit", "Advance") as is_credit'), 'companies.name as company_name', DB::raw("CONCAT(COALESCE(rfqs.firstname,  ' '), ' ', COALESCE(rfqs.lastname,  ' ')) AS Name"),'rfqs.email', DB::raw("CONCAT(COALESCE(rfqs.phone_code,  ' '), ' ', COALESCE(rfqs.mobile, '')) AS mobile"), DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"), 'rfq_products.quantity','rfq_products.comment','rfq_products.expected_date', DB::raw("CONCAT(COALESCE(rfqs.address_line_1,  ' '), ' ', COALESCE(rfqs.address_line_2,  ' ')) AS address"), 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state','rfqs.city_id', 'rfqs.state_id', 'rfqs.pincode', DB::raw('IF(rfqs.unloading_services = "1","Yes", "No") as unloading_services'), DB::raw('IF(rfqs.rental_forklift = "1","Yes", "No") as rental_forklift')]);
            $execlFormateData = [];
            foreach ($rfqDetails as $rfqDetail){
                if($rfqDetail->payment_type == 0){
                    $terms = __('admin.advanced');
                }
                elseif($rfqDetail->payment_type == 1){
                    $terms =  __('admin.credit').'-'.$rfqDetail->credit_days;
                }
                elseif($rfqDetail->payment_type == 2){
                    $terms = __('admin.loan_koinworks');
                }
                elseif($rfqDetail->payment_type == 3){
                    $terms =  __('admin.lc');
                }
                else{
                    $terms = __('admin.skbdn');
                }
                $execlFormateData[] = (object)array(
                    'rfq_number' => $rfqDetail->rfq_number,
                    'rfq_product_item_number' => $rfqDetail->rfq_product_item_number,
                    'rfq_created_at' => $rfqDetail->rfq_date,
                    'rfq_status_name' => (!empty($rfqDetail->status_name))?__('admin.' . trim($rfqDetail->status_name)):'-',
                    'is_require_credit' => $terms,
                    'company_name' => $rfqDetail->company_name,
                    'user_name' => $rfqDetail->Name,
                    'user_email' => $rfqDetail->email,
                    'user_mobile' => $rfqDetail->mobile,
                    'product' => $rfqDetail->Product,
                    'quantity' => $rfqDetail->quantity,
                    'rfq_comment' => $rfqDetail->comment,
                    'expected_date' => $rfqDetail->expected_date,
                    'user_address' => $rfqDetail->address,
                    'user_sub_district' => $rfqDetail->sub_district,
                    'user_district' => $rfqDetail->district,
                    'city' => $rfqDetail->city_id > 0 ? getCityName($rfqDetail->city_id) : ($rfqDetail->city ? $rfqDetail->city : '-'),
                    'state' => $rfqDetail->state_id > 0 ? getStateName($rfqDetail->state_id): ($rfqDetail->state ? $rfqDetail->state : '-'),
                    'pincode' => $rfqDetail->pincode,
                    'unloading_services' => $rfqDetail->unloading_services,
                    'rental_forklift' => $rfqDetail->rental_forklift
                );
            }
            $raws = new Collection($execlFormateData);
        } else if (auth()->user()->role_id == 3){
            //supplier
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $SupplierDealingCategoty = SupplierDealWithCategory::where('supplier_id',$supplier_id)->pluck('sub_category_id')->toArray();

            $columns = collect([__('admin.rfq_number'),__('admin.rfq_product_Number'),__('admin.Date'),__('admin.status'),__('admin.payment_term'),__('admin.company_name'),__('admin.customer_name'),__('admin.product'),__('admin.quantity'),__('admin.comments'), __('admin.expected_date'),__('admin.Address'),__('admin.sub_district'),__('admin.district'),__('admin.city'),__('admin.provinces'),__('admin.pincode'), __('admin.need_uploding_services'), __('admin.need_rental_forklift')]);

            $rfqDetails = Rfq::join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id')
                ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->join('products', 'rfq_products.product_id', '=', 'products.id')
                ->join('supplier_products', 'products.id', '=', 'supplier_products.product_id')
                ->join('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
                ->join('companies', 'companies.id', '=', 'rfqs.company_id')
                ->where('suppliers.status', 1)
                ->where('suppliers.is_deleted', 0)
                ->whereIn('sub_category_id',$SupplierDealingCategoty)
                ->where('supplier_products.is_deleted', 0)
                ->orderBy('rfqs.id', 'desc')
                ->groupBy('rfq_products.rfq_product_item_number')
                ->get(['rfqs.payment_type','rfqs.reference_number as rfq_number', 'rfq_products.rfq_product_item_number', DB::raw('DATE_FORMAT(rfqs.created_at, "%d-%m-%Y	 %H:%i:%s") as rfq_date'), 'rfq_status.backofflice_name as status_name', DB::raw('IF(rfqs.is_require_credit = "1","Credit", "Advance") as is_credit'), 'companies.name as company_name',DB::raw("CONCAT(COALESCE(rfqs.firstname,  ' '), ' ', COALESCE(rfqs.lastname,  ' ')) AS Name"), DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"),'rfq_products.quantity','rfq_products.comment','rfq_products.expected_date', DB::raw("CONCAT(COALESCE(rfqs.address_line_1,  ' '), ' ', COALESCE(rfqs.address_line_2,  ' ')) AS address"), 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state','rfqs.city_id', 'rfqs.state_id', 'rfqs.pincode', DB::raw('IF(rfqs.unloading_services = "1","Yes", "No") as unloading_services'), DB::raw('IF(rfqs.rental_forklift = "1","Yes", "No") as rental_forklift')]);
            $execlFormateData = [];
            foreach ($rfqDetails as $rfqDetail){
                if($rfqDetail->payment_type == 0){
                    $terms = __('admin.advanced');
                }
                elseif($rfqDetail->payment_type == 1){
                    $terms =  __('admin.credit').'-'.$rfqDetail->credit_days;
                }
                elseif($rfqDetail->payment_type == 2){
                    $terms = __('admin.loan_koinworks');
                }
                elseif($rfqDetail->payment_type == 3){
                    $terms =  __('admin.lc');
                }
                else{
                    $terms = __('admin.skbdn');
                }
                $execlFormateData[] = (object)array(
                    'rfq_number' => $rfqDetail->rfq_number,
                    'rfq_product_item_number' => $rfqDetail->rfq_product_item_number,
                    'rfq_created_at' => $rfqDetail->rfq_date,
                    'rfq_status_name' => (!empty($rfqDetail->status_name))?__('admin.' . trim($rfqDetail->status_name)):'-',
                    'is_require_credit' => $terms,
                    'company_name' => $rfqDetail->company_name,
                    'user_name' => $rfqDetail->Name,
                    'product' => $rfqDetail->Product,
                    'quantity' => $rfqDetail->quantity,
                    'rfq_comment' => $rfqDetail->comment,
                    'expected_date' => $rfqDetail->expected_date,
                    'user_address' => $rfqDetail->address,
                    'user_sub_district' => $rfqDetail->sub_district,
                    'user_district' => $rfqDetail->district,
                    'city' => $rfqDetail->city_id > 0 ? getCityName($rfqDetail->city_id) : ($rfqDetail->city ? $rfqDetail->city : '-'),
                    'state' => $rfqDetail->state_id > 0 ? getStateName($rfqDetail->state_id): ($rfqDetail->state ? $rfqDetail->state : '-'),
                    'pincode' => $rfqDetail->pincode,
                    'unloading_services' => $rfqDetail->unloading_services,
                    'rental_forklift' => $rfqDetail->rental_forklift
                );
            }
            $raws = new Collection($execlFormateData);
        } else {
            //admin
            $columns = collect([__('admin.rfq_number'),__('admin.rfq_product_Number'),__('admin.Date'),__('admin.status'),__('admin.payment_term'),__('admin.company_name'),__('admin.customer_name'),__('admin.customer_email'),__('admin.customer_phone'),__('admin.product'),__('admin.quantity'),__('admin.comments'), __('admin.expected_date'),__('admin.Address'),__('admin.sub_district'),__('admin.district'),__('admin.city'),__('admin.provinces'),__('admin.pincode'),__('admin.need_uploding_services'),__('admin.need_rental_forklift')]);

            $rfqDetails = Rfq::join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
                ->join('rfq_products', 'rfq_products.rfq_id', '=', 'rfqs.id');

            //Agent category permissions
            if (Auth::user()->hasRole('agent')) {

                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                $rfqDetails->whereIn('rfq_products.category_id', $assignedCategory);

            }

            $rfqDetails = $rfqDetails->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->join('user_companies', 'user_companies.user_id', '=', 'user_rfqs.user_id')
                ->join('companies', 'companies.id', '=', 'rfqs.company_id')
                ->orderBy('rfqs.id', 'desc')
                ->groupBy('rfq_products.rfq_product_item_number')
                ->get(['rfqs.payment_type','rfqs.reference_number as rfq_number', 'rfq_products.rfq_product_item_number', DB::raw('DATE_FORMAT(rfqs.created_at, "%d-%m-%Y	 %H:%i:%s") as rfq_date'), 'rfq_status.backofflice_name as status_name', DB::raw('IF(rfqs.is_require_credit = "1","Credit", "Advance") as is_credit'), 'companies.name as company_name',DB::raw("CONCAT(COALESCE(rfqs.firstname,  ' '), ' ', COALESCE(rfqs.lastname,  ' ')) AS Name"),'rfqs.email',DB::raw("CONCAT(COALESCE(rfqs.phone_code,  ' '), ' ', COALESCE(rfqs.mobile, '')) AS mobile"), DB::raw("TRIM(CONCAT(COALESCE(rfq_products.category), ' - ', COALESCE(rfq_products.sub_category),  ' - ', COALESCE(rfq_products.product), ' - ', COALESCE(rfq_products.product_description))) AS Product"),'rfq_products.quantity','rfq_products.comment','rfq_products.expected_date', DB::raw("CONCAT(COALESCE(rfqs.address_line_1,  ' '), ' ', COALESCE(rfqs.address_line_2,  ' ')) AS address"), 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state','rfqs.city_id', 'rfqs.state_id', 'rfqs.pincode', DB::raw('IF(rfqs.unloading_services = "1","Yes", "No") as unloading_services'), DB::raw('IF(rfqs.rental_forklift = "1","Yes", "No") as rental_forklift')]);
            $execlFormateData = [];
            foreach ($rfqDetails as $rfqDetail){
                if($rfqDetail->payment_type == 0){
                    $terms = __('admin.advanced');
                }
                elseif($rfqDetail->payment_type == 1){
                    $terms =  __('admin.credit').'-'.$rfqDetail->credit_days;
                }
                elseif($rfqDetail->payment_type == 2){
                    $terms = __('admin.loan_koinworks');
                }
                elseif($rfqDetail->payment_type == 3){
                    $terms =  __('admin.lc');
                }
                else{
                    $terms = __('admin.skbdn');
                }
                $execlFormateData[] = (object)array(
                    'rfq_number' => $rfqDetail->rfq_number,
                    'rfq_product_item_number' => $rfqDetail->rfq_product_item_number,
                    'rfq_created_at' => $rfqDetail->rfq_date,
                    'rfq_status_name' => (!empty($rfqDetail->status_name))?__('admin.' . trim($rfqDetail->status_name)):'-',
                    'is_require_credit' => $terms,
                    'company_name' => $rfqDetail->company_name,
                    'user_name' => $rfqDetail->Name,
                    'user_email' => $rfqDetail->email,
                    'user_mobile' => $rfqDetail->mobile,
                    'product' => $rfqDetail->Product,
                    'quantity' => $rfqDetail->quantity,
                    'rfq_comment' => $rfqDetail->comment,
                    'expected_date' => $rfqDetail->expected_date,
                    'user_address' => $rfqDetail->address,
                    'user_sub_district' => $rfqDetail->sub_district,
                    'user_district' => $rfqDetail->district,
                    'city' => $rfqDetail->city_id > 0 ? getCityName($rfqDetail->city_id) : ($rfqDetail->city ? $rfqDetail->city : '-'),
                    'state' => $rfqDetail->state_id > 0 ? getStateName($rfqDetail->state_id): ($rfqDetail->state ? $rfqDetail->state : '-'),
                    'pincode' => $rfqDetail->pincode,
                    'unloading_services' => $rfqDetail->unloading_services,
                    'rental_forklift' => $rfqDetail->rental_forklift
                );
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
        }elseif (auth()->user()->role_id == 2){
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
        }else{
            return [
                AfterSheet::class    => function(AfterSheet $event) {

                    $event->sheet->getDelegate()->getStyle('A1:S1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('25378b');

                    $event->sheet->getDelegate()->getStyle('A1:S1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');

                    $event->sheet->getDelegate()->getStyle('A1:S1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                },
            ];

        }
    }

}
