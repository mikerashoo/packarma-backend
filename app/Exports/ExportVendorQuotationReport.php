<?php

namespace App\Exports;

use App\Models\VendorQuotation;
use App\Models\VendorWarehouse;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ExportVendorQuotationReport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithTitle, WithStyles
{
    function __construct($request)
    {
        $this->request = $request;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            'A1'    => ['alignment' => ['wrapText' => true]],
        ];
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
                $sheet->mergeCells('A1:T1');
                $sheet->setCellValue('A1', "Vendor Quotation Based Report (" . $start_date .'-'.$end_date. ')');// . " - " . $end_date . ")");


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
                $heading = 'A1:T1'; // Main Heading
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

                $headerColumn = 'A2:T2'; // Columns
                $columns = range('A', 'T');
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
            'Quotation No.',
            'Customer Enquiry No',	
            'Vendor Name',	
            'Vendor Price', 
            'Product Name', 	
            'Product Quantity',
            'MRP',
            'Sub-Total',
            'GST Amount',
            'GST Type',
            'GST %',
            'Freight Amount',
            'Delivery In Days',
            'Grand Total',
            'Vendor Amount',
            'Commission',
            'Enquiry Status',
            'Quotation enquiry Datetime',
            'Lead Time',
            'Vendor Warehouse Address'
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
        $vendor_quotation = VendorQuotation::with('vendor',
                                           'vendor_warehouse',
                                           'product',
                                           'customer_enquiry')
                                  ->select('id',
                                           'customer_enquiry_id',
                                           'vendor_id',
                                           'vendor_price',
                                           'product_id',
                                           'product_quantity',
                                           'mrp',
                                           'sub_total',
                                           'gst_amount',
                                           'gst_type',
                                           'gst_percentage',
                                           'freight_amount',
                                           'delivery_in_days',
                                           'total_amount',
                                           'vendor_amount',
                                           'commission',
                                           'enquiry_status',
                                           'quotation_expiry_datetime',
                                           'lead_time',
                                           'vendor_warehouse_id');
        if($this->request->vendor_quotation_vendor){
            if($this->request->vendor_quotation_vendor[0]!='All'){
                $vendor_ids = $this->request->vendor_quotation_vendor;
                $vendor_quotation->whereIn('vendor_id',$vendor_ids);
            }
            
        }
        if($this->request->vendor_quotation_packaging_material){
            if($this->request->vendor_quotation_packaging_material[0]!='All'){
                $packaging_material_ids = $this->request->vendor_quotation_packaging_material;
                $vendor_quotation->whereIn('customer_enquiry.packaging_material_id',$packaging_material_ids);
            }
        }
        $vendor_quotation = $vendor_quotation->whereBetween('created_at',[$start_date,$end_date])
                                             ->orWhereBetween('updated_at',[$start_date,$end_date])
                                             ->get();
        foreach($vendor_quotation as $enquiry){
            $enquiry->vendor_id =$enquiry->vendor->vendor_name;
            $enquiry->product_id =  $enquiry->product->product_name;
            $vendor_warehouse = VendorWarehouse::with('city','state','country')->find($enquiry->vendor_warehouse_id);
            $enquiry->vendor_warehouse_id =  $vendor_warehouse!=null?$vendor_warehouse->warehouse_name .' '. $vendor_warehouse->flat .','. $vendor_warehouse->area .','. $vendor_warehouse->landmark .','. $vendor_warehouse->city_name .','. $vendor_warehouse->state->state_name .' '. $vendor_warehouse->country->country_name .' '. $vendor_warehouse->pincode:'-';
            $enquiry->enquiry_status = vendorEnquiryStatus($enquiry->enquiry_status);
        }
        return $vendor_quotation;
    }

    public function title(): string
    {
        $sheetName = 'Vendor Quotation Data ' . Carbon::now()->format('Y-m-d');
        return $sheetName;
    }
}
