<?php

namespace App\Exports;

use App\Models\SystemActivity;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportBuyerNotLogin implements FromCollection, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $columns = collect(['User Id', 'User Name', 'Guard Name', 'Created At']);
        $buyerNotlogins = SystemActivity::with('user:id,firstname,lastname')
                                        ->where('action', 'LOGGED IN')
                                        ->whereNotBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                                        ->get();

        $excelFormateData = [];
        if (!is_null($buyerNotlogins)) {
            foreach($buyerNotlogins as $buyerNotlogin) {
                $excelFormateData[] = (object) array(
                    'id' => $buyerNotlogin->user_id ?? '-',
                    'user_id' => ($buyerNotlogin->user->firstname ?? '-') . ' '. ($buyerNotlogin->user->lastname ?? '-'),
                    'guard_name' => $buyerNotlogin->guard_name ?? '-',
                    'created_at' => $buyerNotlogin->created_at ?? '-'
                );
            }
            $raws = new Collection($excelFormateData);
        }
        return $raws->prepend($columns);
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
