<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\UserAddress;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportOrderReport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents, WithTitle
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
                $sheet->mergeCells('A1:AL1');
                $sheet->setCellValue('A1', "Order Based Report (" . $start_date .'-'.$end_date. ')');// . " - " . $end_date . ")");


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
                $heading = 'A1:AL1'; // Main Heading
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

                $headerColumn = 'A2:AL2'; // Columns
                for($i='A';$i!='AM';$i++) {
                    $elements = $i;
                    $event->sheet->getDelegate()->getColumnDimension($elements)->setWidth(30);
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
            'Order No.',
            'Customer Enquiry No',
            'Customer',
            'Vendor Quotation No',
            'Vendor',
            'Category',
            'Sub-Category',
            'Product',
            'Shelf Life',
            'Product Weight',
            'Measurement Unit',
            'Product Quantity',
            'Storage Condition',
            'packaging Machine',
            'Product Form',
            'Packing Type',
            'Packaging Treatment',
            'Recommendation Engine',
            'Packaging Material',
            'Currency',
            'MRP',
            'SubTotal',
            'GST Amount',
            'GST Type',
            'GST %',
            'Freight Amount',
            'Delivery In Days',
            'Grand Total',
            'Vendor Amount',
            'Customer Payment Status',
            'Customer Pending Payment',
            'Vendor Pending Payment',
            'Vendor Payment Status',
            'Date Of Order',
            'Processing Datetime',
            'Out For Delivery Datetime',
            'Delivery Datetime',
            'Order Delivery Status',
            'Shipping Details',
            'Billing Details',
            
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
        $user_orders = Order::with(        'user',
                                           'vendor',
                                           'product',
                                           'category',
                                           'sub_category',
                                           'product_form',
                                           'packing_type',
                                           'packaging_machine',
                                           'packaging_material',
                                           'storage_condition',
                                           'packaging_treatment',
                                           'recommendation_engine',
                                           'user_address',
                                           'measurement_unit',
                                           'storage_condition',
                                           'currency',
                                           'state',
                                           'country')
                                  ->select('id',
                                  'customer_enquiry_id',
                                  'user_id',
                                  'vendor_quotation_id',
                                  'vendor_id',
                                  'category_id',
                                  'sub_category_id',
                                  'product_id',
                                  'shelf_life',
                                  'product_weight',
                                  'measurement_unit_id',
                                  'product_quantity',
                                  'storage_condition_id',
                                  'packaging_machine_id',
                                  'product_form_id',
                                  'packing_type_id',
                                  'packaging_treatment_id',
                                  'recommendation_engine_id',
                                  'packaging_material_id',
                                  'currency_id',
                                  'mrp',
                                  'sub_total',
                                  'gst_amount',
                                  'gst_type',
                                  'gst_percentage',
                                  'freight_amount',
                                  'delivery_in_days',
                                  'grand_total',
                                  'vendor_amount',
                                  'customer_payment_status',
                                  'customer_pending_payment',
                                  'vendor_pending_payment',
                                  'vendor_payment_status',
                                  \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%m:%s") as date_of_order'),
                                  "processing_datetime",
                                  'out_for_delivery_datetime',
                                  'delivery_datetime',
                                  'order_delivery_status',
                                  'shipping_details',
                                  'billing_details',
                                 )
                                ->whereBetween('created_at',[$start_date,$end_date]);
                                 
        if($this->request->order_vendor){
            if($this->request->order_vendor[0]!='All'){
                $vendor_ids = $this->request->order_vendor;
                $user_orders->whereIn('vendor_id',$vendor_ids);
             }
        }
        if($this->request->order_customer){
            if($this->request->order_customer[0]!='All'){
                $user_ids = $this->request->order_customer;
                $user_orders->whereIn('user_id',$user_ids);
            }
        }
        if($this->request->order_product){
            if($this->request->order_product[0]!='All'){
                $product_ids = $this->request->order_product;
                $user_orders->whereIn('product_id',$product_ids);
            }
        }
        if($this->request->order_recommendation_engine){
            if($this->request->order_recommendation_engine[0]!='All'){
                $recommendation_engine_ids = $this->request->order_recommendation_engine;
               $user_orders->whereIn('recommendation_engine_id',$recommendation_engine_ids);
            }
        }
        $user_orders = $user_orders->orWhereBetween('updated_at',[$start_date,$end_date])->get();
        foreach($user_orders as $order){
            $order->user_id =$order->user->name;
            $order->vendor_id =$order->vendor->vendor_name;
            $order->category_id =  $order->category->category_name;
            $order->product_id =  $order->product->product_name;
            $order->sub_category_id =  $order->sub_category->sub_category_name;
            $order->storage_condition_id =  $order->storage_condition->storage_condition_name;
            $order->packaging_machine_id =  $order->packaging_machine->packaging_machine_name;
            $order->packaging_treatment_id =  $order->packaging_treatment->packaging_treatment_name;
            $order->product_form_id =  $order->product_form->product_form_name;
            $order->recommendation_engine_id =  $order->recommendation_engine->engine_name;
            $order->packaging_material_id =  $order->packaging_material->packaging_material_name;
            $order->packing_type_id =  $order->packing_type->packing_name;
            $order->measurement_unit_id =  $order->measurement_unit->unit_name;
            $order->storage_condition_id =  $order->storage_condition->storage_condition_title;
            $order->currency_id =  $order->currency->currency_name;
            $order->processing_datetime = $order->processing_datetime!=null? Carbon::parse($order->processing_datetime)->format('Y-m-d'):'-';
            $order->out_for_delivery_datetime = $order->out_for_delivery_datetime!=null? Carbon::parse($order->out_for_delivery_datetime)->format('Y-m-d'):'-';
            $order->delivery_datetime = $order->delivery_datetime!=null? Carbon::parse($order->delivery_datetime)->format('Y-m-d H:m:s'):'-';
            $order->customer_payment_status = paymentStatus($order->customer_payment_status);
            $order->vendor_payment_status = paymentStatus($order->vendor_payment_status);
            $user_address = UserAddress::find(json_decode($order->shipping_details)->user_address_id);
            $order->shipping_details = $user_address!=null?$user_address->address_name .' '. $user_address->flat .','. $user_address->area .','. $user_address->landmark .','. $user_address->city_name .','. $user_address->state->state_name .' '. $user_address->country->country_name .' '. $user_address->pincode:'-';
            $user_address = UserAddress::find(json_decode($order->billing_details)->user_address_id);
            $order->billing_details =  $user_address!=null?$user_address->address_name .' '. $user_address->flat .','. $user_address->area .','. $user_address->landmark .','. $user_address->city_name .','. $user_address->state->state_name .' '. $user_address->country->country_name .' '. $user_address->pincode:'-';
           $order->order_delivery_status = deliveryStatus($order->order_delivery_status);
       }
       return $user_orders;
    }

    public function title(): string
    {
        $sheetName = 'Order Data ' . Carbon::now()->format('Y-m-d');
        return $sheetName;
    }
}
