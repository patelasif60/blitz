<?php

namespace App\Exports\Admin\BackOffice\MailNotification;

use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportMailQuoteNotRespond implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['Quote Number', 'RFQ Number', 'Company Name(Buyer)', 'Buyer Name ', 'Mobile Number', 'Email', 'Company Name(Supplier)','Supplier Name','Mobile Number','Email','Category','Products','Valid Till','Payment Term','Quote Final Amount','RFQ Date','Quote Date','Age of Quote','Comment']);
        $quotes = Quote::with('userName:id,firstname,lastname')
                        ->with('supplier:id,contact_person_name,contact_person_last_name,name,c_phone_code,mobile,email')
                        ->with('rfqs','rfqProduct','rfqUser')
                        ->where('status_id', 1)
                        ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())->orderBy('created_at', 'desc')->get();
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
                $date2 = date("Y-m-d",strtotime($quote->created_at));
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
                    'valid_till' => date("d-m-Y H:i:s",strtotime($quote->valid_till)) ?? '-',
                    'payment_term' => $is_credit ?? '-',
                    'final_amount' =>($quote->final_amount)?'Rp.'.number_format($quote->final_amount):'',
                    'rfq_date' => date("d-m-Y H:i:s",strtotime($quote->rfqs->created_at)) ?? '',
                    'quote_date' => date("d-m-Y H:i:s",strtotime($quote->created_at)) ?? '',
                    'age_Of_rfq' => $days.' Days',
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
        if($days<=2){
            $message = 'Quote not responded within last 2 days';          
        }else if ($days>2 && $days<=7){
            $message = 'Quote not responded within last 7 days';
        }else if ($days>7){
            $message = 'Quote not responded within last 30 days';
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
