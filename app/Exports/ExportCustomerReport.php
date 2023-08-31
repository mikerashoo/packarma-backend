<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;



class ExportCustomerReport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithTitle
{
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
                $sheet->mergeCells('A1:L1');
                $sheet->setCellValue('A1', "Customer Report (" . $start_date . " - " . $end_date . ")");


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
                $heading = 'A1:L1'; // Main Heading
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

                $headerColumn = 'A2:L2'; // Columns
                $columns = range('A', 'L');
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
            'Sr.No.',
            'Name',
            'Email',
            'Phone Code',
            'Phone',
            'GSTIN',
            'Approval Status',
            'Subscription Start',
            'Subscription End',
            'Subscription Type',
            'Visiting Card Available',
            'Registration Date',
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
        $user_data = User::with('phone_country')
                         ->select('id',
                                  'name',
                                  'email',
                                  'phone_country_id',
                                  'phone',
                                  'gstin',
                                  'approval_status',
                                  'subscription_start',
                                  'subscription_end',
                                  'type',
                                  'visiting_card_front', 
                                  \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as registration_date'));
                        
        if($this->request->report_customer){
            if($this->request->report_customer[0]!='All'){
                $user_data->whereIn('id',$this->request->report_customer);
            }else if($this->request->report_customer[0]=='All'&&isset($this->request->report_customer[1])){
                $user_ids = $this->request->report_customer;
                unset($user_ids[0]);
                $user_data->whereIn('id',$user_ids);
           }
        }else{
            $user_data->whereBetween('created_at',[$start_date,$end_date]);
        }
        $user_data = $user_data->get();
        $i = 0; 
        $j=0;
        foreach($user_data as $user){
            $user->visiting_card_front = $user->visiting_card_front!=null ? 'Yes': 'No';
            $user->id = ++$i;
            $user->subscription_start = Carbon::parse($user->subscription_start)->format('Y-m-d');
            $user->phone_country_id = $user->phone_country->phone_code;
            $user->subscription_end = Carbon::parse($user->subscription_end)->format('Y-m-d');
            $j++;
        }
        return $user_data;
    }

    public function title(): string
    {
        $sheetName = 'Customer Data ' . Carbon::now()->format('Y-m-d');
        return $sheetName;
    }
}
