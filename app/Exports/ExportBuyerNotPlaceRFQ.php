<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserRfq;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportBuyerNotPlaceRFQ implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['Name', 'Email', 'Phone Code', 'Mobile', 'Assigned Company', 'Default Company']);
        $userLists = UserRfq::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->pluck('user_id')->toArray();
        $notplaceRfqUserLists= User::with(['defaultCompany:id,name'])
                                    ->where('role_id', 2)
                                    ->whereNotIn('id', $userLists)
                                    ->get(['firstname', 'lastname', 'email', 'phone_code', 'mobile', 'assigned_companies', 'default_company']);

        $excelFormateData = [];
        if (!is_null($notplaceRfqUserLists)) {
            foreach ($notplaceRfqUserLists as $notplaceRfqUserList) {
                $excelFormateData[] = (object) array(
                    'name' => ($notplaceRfqUserList->firstname ?? '-') . ' ' . ($notplaceRfqUserList->lastname ?? '-'),
                    'email' => $notplaceRfqUserList->email ?? '-',
                    'phone_code' => $notplaceRfqUserList->phone_code ?? '-',
                    'mobile' => $notplaceRfqUserList->mobile ?? '-',
                    'assigned_companies' => $notplaceRfqUserList->assigned_companies ?? '-',
                    'default_company' => $notplaceRfqUserList->defaultCompany->name ?? '-',
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
                                ->getStyle('A1:O1')
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB('25378b');

                $event->sheet->getDelegate()
                                ->getStyle('A1:O1')
                                ->getFont()
                                ->getColor()
                                ->setARGB('FFFFFF');

                $event->sheet->getDelegate()
                                ->getStyle('A1:O1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
