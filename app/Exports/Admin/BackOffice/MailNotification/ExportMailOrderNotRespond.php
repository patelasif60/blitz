<?php

namespace App\Exports\Admin\BackOffice\MailNotification;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportMailOrderNotRespond implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['Order Number', 'Quote Number', 'RFQ Number', 'Company Name(Buyer)', 'Buyer Name', 'Buyer Mobile','Buyer email','Company Name(Supplier)', 'Supplier Name', 'Supplier Mobile', 'Supplier email','Category','Products','Order Status','Payment term','Due Date','Payment Status','Quote Final Amount','Order Created Date','Order Update Date','Quote Date','Age of Order','Comment']);
     
        $date = Carbon::now()->subHours(48)->toDateTimeString();
        $status = array(1,2,8);
        $is_credit_status = array(0,1);
        $orders = Order::with(['user:id,firstname,lastname','company:id,name','supplier:id,name,email,contact_person_name,contact_person_last_name,mobile,c_phone_code'])
                        ->with(['quote:id,quote_number,supplier_final_amount,created_at'])
                        ->with(['rfq:id,reference_number,firstname,lastname,email,mobile,phone_code'])
                        ->With(['orderStatus:id,name'])
                        ->With(['rfqProduct'])
                        ->wherein('is_credit', $is_credit_status)
                        ->wherein('order_status', $status)
                        ->where('updated_at', '<', Carbon::now()->subHours(48)->toDateTimeString())
                        ->groupBy('order_number')
                        ->orderBy('updated_at', 'desc')
                        ->get();
       
        $excelFormateData = [];
        if (!is_null($orders)) {
            $date1=date('Y-m-d');
            foreach ($orders as $order) {
                //get rfq discription for rfqProduct table. 
                $productDis[] = $order->rfqProduct->map(function ($product){
                    return $product->product_name_desc;
                });
                
                //Get rfq category;
                $getCategory=$order->rfqProduct->map(function ($product){
                    return $product->category;
                });
                
                //Get rfq sub category;
                $getSubCategory=$order->rfqProduct->map(function ($product){
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
                
                $BuyerName = '-';
                $BuyerMobile = '-';
                $BuyerEmail = '-';
                $Rfqreference_number = '-';
                if(!empty($order->rfq)){
                  $BuyerName = $order->rfq->firstname.' '.$order->rfq->lastname;
                  $BuyerMobile = ($order->rfq->mobile)?$order->rfq->phone_code.' '.$order->rfq->mobile:'';
                  $BuyerEmail = ($order->rfq->email)? $order->rfq->email:'-';
                  $Rfqreference_number=($order->rfq->reference_number)?$order->rfq->reference_number:'-';
                }

                
                $payment_status='';
                if($order->payment_status==0){
                    $payment_status='Unpaid';
                }else if($order->payment_status==1){
                    $payment_status='Online';
                }else if($order->payment_status==2){
                    $payment_status='Offline Paid';
                }else if($order->payment_status==3){
                    $payment_status='Loan Paid';
                }

                $date2 = date("Y-m-d",strtotime($order->updated_at));
                $days = $this->dateDiffInDays($date1,$date2);
                $commentMessage = $this->DaysComment($days);
                
                $OrderStatus=$order->orderStatus->name;
                if($order->orderStatus->name=='Payment Due on %s'){
                   $OrderStatus = 'Payment Due';
                }

                $payment_term = '';
                if($order->is_credit == 0){
                   $payment_term = 'Advance';
                }else if($order->is_credit == 1){
                   $payment_term = 'Credit';
                }else if($order->is_credit == 2){
                   $payment_term = 'Loan';
                }

                $quote_final_amount = '-';
                $quote_number = '-';
                $quote_created_date = '-';
                if(!empty($order->quote)){
                    $quote_number = $order->quote->quote_number;
                    $quote_final_amount = ($order->quote->supplier_final_amount)?'Rp.'.number_format($order->quote->supplier_final_amount):'-';
                    $quote_created_date = date("d-m-Y H:i:s",strtotime($order->quote->created_at)) ?? '-';
                }

                $supplier_email='-';
                $supplier_name = '-';
                $SupplireMobile = '-';
                if($order->supplier){
                    $supplier_email =  ($order->supplier->email)? $order->supplier->email:'-';
                    $supplier_name = ($order->supplier->contact_person_name)? $order->supplier->contact_person_name:'-';
                    $SupplireMobile = ($order->supplier->mobile)?$order->supplier->c_phone_code.' '.$order->supplier->mobile:'-';
                }
                


                $excelFormateData[] = (object) array(
                    'order_no' => $order->order_number,
                    'quote_number' => $quote_number,
                    'rfq_number' => $Rfqreference_number,
                    'buyer_company' => $order->company->name??'-',
                    'buyer_name' => $BuyerName ?? '-',
                    'buyer_mobile' => $BuyerMobile ?? '-',
                    'email'=>$BuyerEmail,
                    'supplier_company' => ($order->company)?$order->company->name:'-',
                    'supplier_name' => $supplier_name,
                    'supplier_mobile' => $SupplireMobile ?? '-',
                    'supplier_email' => $supplier_email,
                    'category'=>$Category ?? '',
                    'product'=> $productName ?? '',
                    'order_status'=> $OrderStatus ?? '',
                    'Payment_term'=> $payment_term ?? '',
                    'due_date'=>($payment_term=='Credit')?date("d-m-Y H:i:s",strtotime($order->created_at)):'-',
                    'payment_status' => $payment_status ?? '-',
                    'quote_final_amount' =>$quote_final_amount,
                    'order_created_date' => date("d-m-Y H:i:s",strtotime($order->created_at)) ?? '',
                    'order_update_date' => date("d-m-Y H:i:s",strtotime($order->updated_at)) ?? '',
                    'quote_created_date' => $quote_created_date,
                    'Age of Order' => $days.' Days',
                    'comment' => $commentMessage
                );
            }
            $raws = new Collection($excelFormateData);
        }
        return $raws->prepend($columns);
    }

    //Calculate days for form to todate.
    public function dateDiffInDays($date1, $date2) { 
    $diff = strtotime($date2) - strtotime($date1); 
    return abs(round($diff / 86400));
    }
        
    //Comment return in days based.
    public function DaysComment($days){
        if($days<=2){
            $message = 'Order not responded within last 2 days';          
        }else if ($days>2 && $days<=7){
            $message = 'Order not responded within last 7 days';
        }else if ($days>7){
            $message = 'Order not responded within last 30 days';
        }
        return $message;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()
                                ->getStyle('A1:W1')
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB('25378b');

                $event->sheet->getDelegate()
                                ->getStyle('A1:W1')
                                ->getFont()
                                ->getColor()
                                ->setARGB('FFFFFF');

                $event->sheet->getDelegate()
                                ->getStyle('A1:W1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

}
