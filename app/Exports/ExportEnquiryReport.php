<?php

namespace App\Exports;

use App\Models\CustomerEnquiry;
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



class ExportEnquiryReport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithTitle
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
                if (isset($this->request->daterange) && !empty($this->request->daterange)) {
                    $string = explode('-', $this->request->daterange);
                    $start_date = Carbon::createFromFormat('d/m/Y', trim($string[0]))->format('d/m/Y');
                    $end_date = Carbon::createFromFormat('d/m/Y', trim($string[1]))->format('d/m/Y');
                }
                $sheet->mergeCells('A1:R1');
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
                $heading = 'A1:R1'; // Main Heading
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

                $headerColumn = 'A2:R2'; // Columns
                $columns = range('A', 'R');
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
            'Enquiry No.',
            'User',
            'Country',
            'Category Name',
            'Sub-Category Name',
            'Product Name',
            'Product Quantity',
            'Shelf Life',
            'Storage Condition',
            'Packaging Machine',
            'Product Form',
            'Packing Type',
            'Packaging Treatment',
            'Recommendation Engine',
            'Packaging Material',
            'Enquiry Status',
            'Enquired On',
            
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
        $user_enquiries = CustomerEnquiry::with('country',
                                           'user',
                                           'product',
                                           'category',
                                           'sub_category',
                                           'product_form',
                                           'packing_type',
                                           'packaging_machine',
                                           'packaging_material',
                                           'storage_condition',
                                           'packaging_treatment',
                                           'recommendation_engine')
                                  ->select('id',
                                           'user_id',
                                           'country_id',
                                           'category_id',
                                           'sub_category_id',
                                           'product_id',
                                           'product_quantity',
                                           'shelf_life',
                                           'storage_condition_id',
                                           'packaging_machine_id',
                                           'product_form_id',
                                           'packing_type_id',
                                           'packaging_treatment_id',
                                           'recommendation_engine_id',
                                           'packaging_material_id',
                                           'quote_type',
                                           \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as enquired_on'));
        if($this->request->enquiry_customer){
            if($this->request->enquiry_customer[0]!='All'){
                $user_ids = $this->request->enquiry_customer;
                $user_enquiries->whereIn('user_id',$user_ids);
            }
            
        }
        if($this->request->enquiry_product){
            if($this->request->enquiry_product[0]!='All'){
                $product_ids = $this->request->enquiry_product;
                $user_enquiries->whereIn('product_id',$product_ids);
            }
        }
        if($this->request->enquiry_recommendation_engine){
            if($this->request->enquiry_recommendation_engine[0]!='All'){
                $recommendation_engine_ids = $this->request->enquiry_recommendation_engine;
                $user_enquiries->whereIn('recommendation_engine_id',$recommendation_engine_ids);
            }
            
        }
        if($this->request->enquiry_packaging_material){
            if($this->request->enquiry_packaging_material[0]!='All'){
                $packaging_material_ids = $this->request->enquiry_packaging_material;
                $user_enquiries->whereIn('packaging_material_id',$packaging_material_ids);
            }
            
        }
        $user_enquiries = $user_enquiries->whereBetween('created_at',[$start_date,$end_date])
                                         ->orWhereBetween('updated_at',[$start_date,$end_date])
                                         ->get();
        foreach($user_enquiries as $enquiry){
            $enquiry->user_id =$enquiry->user->name;
            $enquiry->category_id =  $enquiry->category->category_name;
            $enquiry->product_id =  $enquiry->product->product_name;
            $enquiry->sub_category_id =  $enquiry->sub_category->sub_category_name;
            $enquiry->storage_condition_id =  $enquiry->storage_condition->storage_condition_title;
            $enquiry->packaging_machine_id =  $enquiry->packaging_machine->packaging_machine_name;
            $enquiry->packaging_treatment_id =  $enquiry->packaging_treatment->packaging_treatment_name;
            $enquiry->product_form_id =  $enquiry->product_form->product_form_name;
            $enquiry->recommendation_engine_id =  $enquiry->recommendation_engine->engine_name;
            $enquiry->packaging_material_id =  $enquiry->packaging_material->packaging_material_name;
            $enquiry->packing_type_id =  $enquiry->packing_type->packing_name;
            $enquiry->country_id =  $enquiry->country->country_name;
            $enquiry->quote_type = customerEnquiryQuoteType($enquiry->quote_type);
            $enquiry->enquiry_type = customerEnquiryType($enquiry->enquiry_type);
        }
        return $user_enquiries;
    }

    public function title(): string
    {
        $sheetName = 'Enquiry Data ' . Carbon::now()->format('Y-m-d');
        return $sheetName;
    }
}
