<?php

namespace App\Exports;

use App\Models\CustomerEnquiry;
use App\Models\User;
use App\Models\UserSubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use stdClass;

class ExportCustomerSubscriptions implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithTitle
{

    protected $request;
    function __construct($request)
    {
        $this->request = $request;
    }
    public function startCell(): string
    {
        return 'A2';
    }
    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;
                $start_date = Carbon::parse(now())->format('Y-m-d');
                $end_date = Carbon::parse(now())->format('Y-m-d');
                if (isset($this->request->daterange) && !empty($this->request->daterange)) {
                    $string = explode('-', $this->request->daterange);
                    $start_date = Carbon::createFromFormat('d/m/Y', trim($string[0]))->format('d/m/Y');
                    $end_date = Carbon::createFromFormat('d/m/Y', trim($string[1]))->format('d/m/Y');
                }
                $sheet->mergeCells('A1:M1');
                $sheet->setCellValue('A1', "Customer Enquiry Based Report (" . $start_date .'-'.$end_date. ')');// . " - " . $end_date . ")");


                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];



                $headerFontArray = [
                    'name' => 'Calibri',
                    'bold' => TRUE,
                    'italic' => FALSE,
                    'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE,
                    'strikethrough' => FALSE,
                    'size' => 16,
                    'color' => [
                        'rgb' => 'FF000000'
                    ]
                ];
                $heading = 'A1:M1'; // Main Heading
                $event->sheet->getDelegate()->getStyle($heading)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($heading)->getFont()->applyFromArray($headerFontArray);


                //column style
                $columnFontArray = [
                    'name' => 'Calibri',
                    'bold' => TRUE,
                    'italic' => FALSE,
                    'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE,
                    'strikethrough' => FALSE,
                    'size' => 11,
                    'color' => [
                        'rgb' => 'FF000000'
                    ]
                ];

                $columnBorderArray =   [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [
                            'rgb' => 'FF000000'
                        ]
                    ]
                ];

                $columnColorArray = [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 0.0,
                    'startColor' => [
                        'rgb' => 'FFFFFF00'
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFF00'
                    ]
                ];

                $headerColumn = 'A2:M2'; // Columns
                $columns = range('A', 'M');
                foreach ($columns as $elements) {
                    $event->sheet->getDelegate()->getColumnDimension($elements)->setWidth(20);
                }

                $event->sheet->getDelegate()->getStyle($headerColumn)->getFill()->applyFromArray($columnColorArray);
                $event->sheet->getDelegate()->getStyle($headerColumn)->getBorders()->applyFromArray($columnBorderArray);
                $event->sheet->getDelegate()->getStyle($headerColumn)->getFont()->applyFromArray($columnFontArray);
            },
        ];
    }
    public function headings(): array
    {
        return [
            "User Email",
            "User Name",
            "User id",
            "Subscription Type",
            "Start Date",
            "End Date",
            "Payment Status",
            "Transaction Id",
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $start_date = Carbon::parse(now())->format('Y-m-d');
        $end_date = Carbon::parse(now())->format('Y-m-d');
        if (isset($this->request->daterange) && !empty($this->request->daterange)) {
            $string = explode('-', $this->request->daterange);
            $start_date = Carbon::createFromFormat('d/m/Y', trim($string[0]))->startOfDay()->toDateTimeString();
            $end_date =Carbon::createFromFormat('d/m/Y', trim($string[1]))->endOfDay()->toDateTimeString();
        }

        $query = UserSubscriptionPayment::with('user')->orderBy('updated_at','desc')->withTrashed();
        // if ($this->request->search && $this->request->search->search_user_name ['search']['search_user_name'] && ! is_null($this->request['search']['search_user_name'])) {
        //     $query->where('user_id', $this->request['search']['search_user_name']);
        // }
        $subscriptions = $query->get();

        $data = $subscriptions->map(function($subscription){

            $isSubscriptionActive = $subscription->user->subscription_id == $subscription->id;
            $_da = new stdClass;
            $_da->user_email = $subscription->user->email;
            $_da->user_name = $subscription->user->name;
            $_da->user_id = $subscription->user->id;
            $_da->type = $subscription->subscription_type;

            $createdAt = $isSubscriptionActive ? $subscription->user->subscription_start : $subscription->created_at;
            $endAt = $isSubscriptionActive ? $subscription->user->subscription_end : $subscription->updated_at;
            $_da->start_date = Carbon::parse($createdAt)->toDateTimeString();
            $_da->end_date = Carbon::parse($endAt)->toDateTimeString();
            $_da->payment_status = $subscription->payment_status;
            $_da->transaction_id = $subscription->transaction_id;
            return $_da;
        });

        return $data;
    }

    public function title(): string
    {
        $sheetName = '  Data ' . Carbon::now()->format('Y-m-d');
        return $sheetName;
    }
}
