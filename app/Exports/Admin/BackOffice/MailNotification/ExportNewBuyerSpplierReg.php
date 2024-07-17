<?php

namespace App\Exports\Admin\BackOffice\MailNotification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

class ExportNewBuyerSpplierReg implements FromCollection, ShouldAutoSize, WithEvents
{
    public function __construct($type)
    {
        $this->type = $type;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['Company Name','First Name', 'Last Name', 'Email', 'Mobile No', 'Added Date']);
        if ($this->type == 'buyer') {
            $lists = User::select([
            'companies.name as company_name', 
            'users.firstname as firstname', 
            'users.lastname as lastname', 
            'users.email as email',
            'users.phone_code as phone_code',
            'users.mobile as mobile',
            'users.created_at as added_date'
            ])->leftjoin('companies','companies.owner_user','=', 'users.id')->where('users.created_at', '>=', Carbon::yesterday())->where('role_id',2)->get();
        }

        if($this->type == 'supplier'){
            $lists = Supplier::select([
             'name as company_name',
             'contact_person_name as firstname',
             'contact_person_last_name as lastname',
             'contact_person_email as email', 
             'cp_phone_code as phone_code', 
             'contact_person_phone as mobile',
             'created_at as added_date'
             ])->where('created_at', '>=', Carbon::yesterday())->get();
        }

        $excelFormateData = [];
        if (!empty($lists)) {
            foreach ($lists as $list) {
                $code=($list->phone_code)?$list->phone_code.' ':'';
                $mobile = ($list->mobile)?$code.$list->mobile:'-';
                $excelFormateData[] = (object) array(
                    'company_name' => $list->company_name,
                    'firstname' => $list->firstname ?? '-',
                    'lastname' => $list->lastname ?? '-',
                    'email' => $list->email ?? '-',
                    'mobile' => $mobile,
                    'added_date' => date("d-m-Y H:i:s",strtotime($list->added_date)) ?? '',
                );
            }
        }
        $raws = new Collection($excelFormateData);
        $data = $raws->prepend($columns);
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:F1')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('25378b');

                $event->sheet->getDelegate()->getStyle('A1:F1')
                        ->getFont()
                        ->getColor()
                        ->setARGB('FFFFFF');

                $event->sheet->getDelegate()->getStyle('A1:F1')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            },
        ];
    }
}
