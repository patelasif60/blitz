<?php

namespace App\Exports\Admin\BackOffice\MailNotification;

use App\Models\Rfq;
use Carbon\Carbon;
use App\Models\RfqProduct;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

class ExportMailRfqNotRespond implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['RFQ Number',
        'Company Name','Name','Mobile', 'Email', 'Category', 'Products', 'Payment Term','City', 'Country', 'Added Date','Age Of RFQ','Comment']);
        $Rfqlist = Rfq::with('getCountryOne','rfqProducts','companyDetails')
                    ->where('status_id',1)
                    ->where('created_at', '<=', Carbon::now()->subHours(48)->toDateTimeString())
                    ->orderBy('created_at', 'desc')
                    ->get();
        $excelFormateData = [];
        if (!empty($Rfqlist)) {
            $date1=date('y-m-d');
            foreach ($Rfqlist as $rfq) {
                //get rfq discription for rfqProduct table. 
                $productDis[] = $rfq->rfqProducts->map(function ($product){
                    return $product->product_name_desc;
                });
                
                //Get rfq category;
                $getCategory=$rfq->rfqProducts->map(function ($product){
                    return $product->category;
                });
                
                //Get rfq sub category;
                $getSubCategory=$rfq->rfqProducts->map(function ($product){
                    return $product->sub_category;
                });

                $Category=(count($getCategory)>0)? $getCategory[0]:'';
                $Sub_Category=(count($getSubCategory)>0)? $getSubCategory[0]:'';

                //Expload product name;
                $productName='';
                if(count($productDis)>0){
                $productName = implode(",",$productDis);
                $ReplaceItem=array('[',']','"');
                $ReplaceValue=array(' ',' ',' ');
                $productName = str_replace($ReplaceItem,$ReplaceValue,$productName);
                $productDis = [];
                }

                $code=($rfq->phone_code)?$rfq->phone_code.' ':'';
                $mobile = ($rfq->mobile)?$code.$rfq->mobile:'-';
                $days = $this->dateDiffInDays($date1,$rfq->created_at);
                $commentMessage = $this->DaysComment($days);
                $is_credit=($rfq->is_require_credit==1)? "Credit" : "Advance";
                  
                $CityName=($rfq->city_id > 0)?getCityName($rfq->city_id):$rfq->city;

                $Country = 'Indonesia';
                if(isset($rfq->getCountryOne)){
                $Country= ($rfq->getCountryOne->name)?$rfq->getCountryOne->name:'Indonesia';
                }

                $excelFormateData[] = (object) array(
                    'rfq' => $rfq->reference_number ?? '-', 
                    'comapny_name' => $rfq->companyDetails->name ?? '-',
                    'buyer_name' => $rfq->firstname.' '.$rfq->lastname ?? '-',
                    'mobile' => $mobile ?? '-',
                    'email' => $rfq->email ?? '-',
                    'category' => $Category ??'-',
                    'product' =>  $productName,
                    'payment_term' => $is_credit,
                    'city' =>$CityName ?? '-',
                    'Country' => $Country ?? '',
                    'added_date' => date("d-m-Y H:i:s",strtotime($rfq->created_at)) ?? '',
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
            $message = 'RFQ not responded within last 2 days';          
        }else if ($days>2 && $days<=7){
            $message = 'RFQ not responded within last 7 days';
        }else if ($days>7){
            $message = 'RFQ not responded within last 30 days';
        }
        return $message;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()
                                ->getStyle('A1:M1')
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB('25378b');

                $event->sheet->getDelegate()
                                ->getStyle('A1:M1')
                                ->getFont()
                                ->getColor()
                                ->setARGB('FFFFFF');

                $event->sheet->getDelegate()
                                ->getStyle('A1:M1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}