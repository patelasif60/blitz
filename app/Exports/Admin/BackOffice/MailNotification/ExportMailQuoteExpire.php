<?php

namespace App\Exports\Admin\BackOffice\MailNotification;

use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportMailQuoteExpire implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $from = Carbon::now()->toDateString();
        $to = Carbon::now()->addDays(7)->toDateString();
        $columns = collect(['Quote Number', 'RFQ Number', 'Company Name(Buyer)', 'Buyer Name ', 'Mobile Number', 'Email', 'Company Name(Supplier)','Supplier Name','Mobile Number','Email','Category','Products','Payment Term','Quote Final Amount','RFQ Date','Quote Date','Quote Status','Valid Till','Comment']);
        $quotes = Quote::with('userName','supplier','rfqs','rfqProduct','rfqUser','quoteStatus')
        ->where('valid_till', '>=', $from)
        ->where('valid_till', '<=', $to)
        ->where('status_id', 1)
        ->get();

        $excelFormateData = [];
        if (!is_null($quotes)) {
            $date1=date('Y-m-d');
            foreach ($quotes as $key => $quote) {  

                //get rfq discription for rfqProduct table. 
                $productDis[] = $quote->rfqProduct->map(function ($product){
                    return $product->product_name_desc;
                });
                
                //Get rfq category;
                $getCategory=$quote->rfqProduct->map(function ($product){
                    return $product->category;
                });
                
                //Get rfq sub category;
                $getSubCategory=$quote->rfqProduct->map(function ($product){
                    return $product->sub_category;
                });

                $Category=(count($getCategory)>0)? $getCategory[0]:'';
                $Sub_Category=(count($getSubCategory)>0)? $getSubCategory[0]:'';

                //Expload product name;
                $productName='';
                if(count($productDis)>0){
                $productName = implode(',',$productDis);
                $ReplaceItem=array('[',']','"');
                $ReplaceValue=array(' ',' ',' ');
                $productName = str_replace($ReplaceItem,$ReplaceValue,$productName);
                $productDis = [];
                }
                
                $BuyerName=$quote->rfqs->firstname.' '.$quote->rfqs->lastname;
                $BuyerMobile = ($quote->rfqs->mobile)?$quote->rfqs->phone_code.' '.$quote->rfqs->mobile:'';
                $SupplireMobile = ($quote->supplier->mobile)?$quote->supplier->c_phone_code.' '.$quote->supplier->mobile:'';
                $date2 = date("Y-m-d",strtotime($quote->valid_till));
                $days = $this->dateDiffInDays($date1,$date2);
                $commentMessage = $this->DaysComment($days);
                $is_credit=($quote->rfqs->is_require_credit==1)? "Credit" : "Advance";
                $BuyerCompany=($quote->rfqUser->user_id > 0)?getCompanyByUserId($quote->rfqUser->user_id):'';

                
                $excelFormateData[] = (object) array(
                    'quoteNumner' => $quote->quote_number ?? '-',
                    'rfq_no'=> $quote->rfqs->reference_number ?? '-',
                    'buyer_comapany_name'=> $BuyerCompany ??'',
                    'buyer_name'=> $BuyerName ?? '-',
                    'buyer_mobile'=>$BuyerMobile ?? '-',
                    'buyer_email'=>$quote->rfqs->email ?? '-',
                    'supplier_company_name'=>$quote->supplier->name ?? '-',
                    'supplier_name' => ($quote->supplier->contact_person_name??'-').' '. ($quote->supplier->contact_person_last_name??'-'),
                    'supplier_mobile'=>$SupplireMobile ?? '-',
                    'supplier_email'=>$quote->supplier->email ?? '-',
                    'category' => $Category ?? '',
                    'product'=>$productName ?? '',
                    'payment_term' => $is_credit ?? '-',
                    'final_amount' =>($quote->final_amount)?'Rp.'.number_format($quote->final_amount):'',
                    'rfq_date' => date("d-m-Y H:i:s",strtotime($quote->rfqs->created_at)) ?? '',
                    'quote_duote' => date("d-m-Y H:i:s",strtotime($quote->created_at)) ?? '',
                    'state_name' =>$quote->quoteStatus->name,
                    'valid_till' => date("d-m-Y H:i:s",strtotime($quote->valid_till))?? '-',
                    'comment' => $commentMessage
                );
            }
            $raws = new Collection($excelFormateData);
        }
        $data = $raws->prepend($columns);
        return $data;
    }

    //Calculate days for form to todate.
    public function dateDiffInDays($date1, $date2) { 
    $diff = strtotime($date2) - strtotime($date1); 
    return abs(round($diff / 86400));
    }
        
    //Comment return in days based.
    public function DaysComment($days){
        $message ='Quote expire within within next '.$days.' day';
        if($days>1){ 
            $message ='Quote expire within next '.$days.' days';
        }if($days==0){
            $message ='Quote will be expire Today';
        }
        
        return $message;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()
                                ->getStyle('A1:S1')
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB('25378b');

                $event->sheet->getDelegate()
                                ->getStyle('A1:S1')
                                ->getFont()
                                ->getColor()
                                ->setARGB('FFFFFF');

                $event->sheet->getDelegate()
                                ->getStyle('A1:S1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
