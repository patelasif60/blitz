<?php

namespace App\Exports\Admin\BackOffice\MailNotification;

use App\Models\SystemActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class ExportMailBuyerNotLogin implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['Company Name', 'First Name', 'Last Name', 'Email', 'Mobile', 'Added Date','Last Login date','Last Login Day']);
        $Day = Carbon::now()->subDays(7)->toDateString();
        $userListsUserList = user::select(['users.id as id',
        'users.firstname as firstname','users.lastname as lastname',
        'users.email as email','users.mobile as mobile','users.phone_code as phone_code','users.created_at as ucreate_at','system_activities.created_at as saCreateDate','system_activities.module_name as module_name'
        ])->join('system_activities','system_activities.user_id','=','users.Id')
        ->where('users.role_id',2)
        ->where('system_activities.action','LOGGED IN')
        ->where('system_activities.module_name','Buyer - SignIn')
        ->where('system_activities.created_at', '<=', $Day)
        ->groupBy('users.id')
        ->orderBy('system_activities.created_at','DESC')
        ->get();
    
        $excelFormateData = []; 
        if (!empty($userListsUserList)) {    
            $date1=date('Y-m-d');
            foreach($userListsUserList as $buyerNotlogin) {
                $mobile = ($buyerNotlogin->mobile)?$buyerNotlogin->phone_code.' '.$buyerNotlogin->mobile:'';
                $date2 = date("Y-m-d",strtotime($buyerNotlogin->saCreateDate));
                $days = $this->dateDiffInDays($date1,$date2);
                $excelFormateData[] = (object) array(
                    'company_name' => getCompanyByUserId($buyerNotlogin->id) ?? '-',
                    'first_name' => $buyerNotlogin->firstname ?? '-',
                    'last_name' => $buyerNotlogin->lastname ?? '-',
                    'email' => $buyerNotlogin->email ?? '-',
                    'mobile' => $mobile,
                    'created_at' => date("d-m-Y H:i:s",strtotime($buyerNotlogin->ucreate_at)) ?? '',
                    'last_login_date' => date("d-m-Y H:i:s",strtotime($buyerNotlogin->saCreateDate)) ?? '',
                    'last_login_day' => date("l",strtotime($buyerNotlogin->saCreateDate)) ?? '',
                );
            }
            $raws = new Collection($excelFormateData);
        }
        return $raws->prepend($columns);
    }

    public function dateDiffInDays($date1, $date2) { 
        $diff = strtotime($date2) - strtotime($date1); 
        return abs(round($diff / 86400));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()
                                ->getStyle('A1:H1')
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB('25378b');

                $event->sheet->getDelegate()
                                ->getStyle('A1:H1')
                                ->getFont()
                                ->getColor()
                                ->setARGB('FFFFFF');

                $event->sheet->getDelegate()
                                ->getStyle('A1:H1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
