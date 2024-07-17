<?php

namespace App\Exports\Admin\BackOffice\MailNotification;

use App\Models\User;
use App\Models\UserRfq;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class ExportMailBuyerNotPlaceRFQ implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['Company Name', 'First Name', 'Last Name', 'Email', 'Mobile', 'Added Date','Last RFQ date','Last RFQ Day','Total RFQ']);

        $userList = UserRfq::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->pluck('user_id as id')->toArray();
        $notplaceRfqUserLists= User::where('role_id', 2)
                                    ->whereNotIn('id',$userList)
                                    ->get();

        $excelFormateData = [];
        if (!is_null($notplaceRfqUserLists)) {
            foreach ($notplaceRfqUserLists as $notplaceRfqUserList) {
                $userWise = UserRfq::where('user_id',$notplaceRfqUserList->id)->orderBy('id', 'DESC')->get()->toArray();
                $rfqCount = 0;
                $LastRfqCreateDate='';
                if(!empty($userWise)){
                   $rfqCount = count($userWise);
                   if(!empty($userWise[0]['created_at'])){
                   $LastRfqCreateDate = $userWise[0]['created_at'];
                   }                
                }
                $mobile = ($notplaceRfqUserList->mobile)?$notplaceRfqUserList->phone_code.' '.$notplaceRfqUserList->mobile:'';
                $excelFormateData[] = (object) array(
                    'company_name' => getCompanyByUserId($notplaceRfqUserList->id)??'',
                    'first_name' => $notplaceRfqUserList->firstname ?? '-',
                    'last_name' => $notplaceRfqUserList->lastname ?? '-',
                    'email' => $notplaceRfqUserList->email ?? '-',
                    'mobile' => $mobile,
                    'created_at' => date("d-m-Y H:i:s",strtotime($notplaceRfqUserList->created_at)) ?? '',
                    'Last RFQ date' => ($LastRfqCreateDate)?date("d-m-Y H:i:s",strtotime($LastRfqCreateDate)):'',
                    'Last RFQ Day' => ($LastRfqCreateDate)?date("l",strtotime($LastRfqCreateDate)):'',
                    'Total RFQ' => $rfqCount
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
                                ->getStyle('A1:I1')
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB('25378b');

                $event->sheet->getDelegate()
                                ->getStyle('A1:I1')
                                ->getFont()
                                ->getColor()
                                ->setARGB('FFFFFF');

                $event->sheet->getDelegate()
                                ->getStyle('A1:I1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
