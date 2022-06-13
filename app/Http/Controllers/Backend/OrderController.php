<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\StorageCondition;
use App\Models\PackagingMachine;
use App\Models\ProductForm;
use App\Models\PackingType;
use App\Models\MeasurementUnit;
use App\Models\PackagingTreatment;
use App\Models\PackagingMaterial;
use App\Models\Country;
use App\Models\Currency;
use App\Models\OrderPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;
use NumberToWords\NumberToWords;

class OrderController extends Controller
{
    /**
     *  created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To show order  listing page
     */
    public function index()
    {
        $data['user'] = User::all();
        $data['vendor'] = Vendor::all();
        $data['paymentStatus'] = paymentStatus();
        $data['deliveryStatus'] = deliveryStatus();
        $data['order_view'] = checkPermission('order_view');
        $data['order_delivery_update'] = checkPermission('order_delivery_update');
        $data['order_payment_update'] = checkPermission('order_payment_update');
        $data['vendor_payment_update'] = checkPermission('vendor_payment_update');
        return view('backend/order/order_list/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  display dynamic data in datatable for Contactus  page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Order::with('user', 'vendor', 'currency')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_user_id']) && !is_null($request['search']['search_user_id'])) {
                            $query->where('user_id', $request['search']['search_user_id']);
                        }
                        if (isset($request['search']['search_vendor_id']) && !is_null($request['search']['search_vendor_id'])) {
                            $query->where('vendor_id', $request['search']['search_vendor_id']);
                        }
                        if (isset($request['search']['search_delivery_status']) && !is_null($request['search']['search_delivery_status'])) {
                            $query->where('order_delivery_status', 'like', "%" . $request['search']['search_delivery_status'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('user_name', function ($event) {
                        return $event->user->name;
                    })
                    ->editColumn('vendor_name', function ($event) {
                        return $event->vendor->vendor_name;
                    })
                    ->editColumn('grand_total', function ($event) {
                        return $event->currency->currency_symbol . $event->grand_total;
                    })
                    ->editColumn('product_quantity', function ($event) {
                        return $event->product_quantity;
                    })
                    ->editColumn('order_delivery_status', function ($event) {
                        return deliveryStatus($event->order_delivery_status);
                    })
                    ->editColumn('payment_status', function ($event) {
                        return paymentStatus($event->customer_payment_status);
                    })
                    ->editColumn('packaging_material', function ($event) {
                        return $event->packaging_material->packaging_material_name;
                    })
                    ->editColumn('action', function ($event) {
                        $order_view = checkPermission('order_view');
                        $order_pdf = checkPermission('order_pdf');
                        $order_delivery_update = checkPermission('order_delivery_update');
                        $order_payment_update = checkPermission('order_payment_update');
                        $vendor_payment_update = checkPermission('vendor_payment_update');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($order_view) {
                            $actions .= '<a href="order_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }

                        if ($order_pdf) {
                            $actions .= '  <a href="order_pdf/' . $event->id . '" class="btn btn-success btn-sm" title="Pdf" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
                        }

                        if ($event->order_delivery_status != "delivered") {
                            if ($order_delivery_update) {
                                $actions .= '  <a href="order_delivery_update/' . $event->id . '" class="btn btn-info btn-sm src_data" title="Update Delivery"><i class="fa fa-truck"></i></a>';
                            }
                        }
                        if ($event->customer_pending_payment != 0) {
                            if ($order_payment_update) {
                                $actions .= '  <a href="orderPaymentUpdate/' . $event->id . '" class="btn btn-secondary btn-sm src_data" title="Customer Payment"><i class="fa fa-money"></i></a>';
                            }
                        }
                        if ($event->vendor_pending_payment != 0) {
                            if ($vendor_payment_update) {
                                $actions .= ' <a href="vendor_payment_list?id=' . Crypt::encrypt($event->id) . '" class="btn btn-warning btn-sm " title="Vendor Payment"><i class="fa fa-money"></i></a>';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['user_name', '	vendor_name', 'grand_total', 'order_delivery_status', 'payment_status', 'updated_at', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());
                return response([
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Something went wrong',
                ]);
            }
        }
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To load update order delivery status page
     *   @param int $id
     *   @return Response
     */
    public function updateOrderDelivery($id)
    {
        $data['data'] = Order::with('user', 'product', 'vendor', 'packaging_material')->find($id);
        $data['deliveryStatus'] = deliveryStatus();
        $data['paymentStatus'] = paymentStatus();
        return view('backend/order/order_list/order_delivery_status_update', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-Mar-2022
     *   Uses :  To store order delivery status in table
     *   @param Request request
     *   @return Response
     */
    public function updateDeliveryStatusData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Order Delivery Status Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $deliveryData = Order::find($_GET['id']);
        if (isset($_GET['id'])) {
            $getKeys = true;
            $deliveryStatus = deliveryStatus('', $getKeys);
            if (in_array($request->order_delivery_status, $deliveryStatus)) {
                if ($request->order_delivery_status == $deliveryData->order_delivery_status) {
                    errorMessage('Order is Already in ' . deliveryStatus($request->order_delivery_status) . ' Status.', $msg_data);
                }
                $tableObject = Order::find($_GET['id']);
                $msg = "Delivery Status Updated Successfully";
            } else {
                errorMessage('Delivery Status Does not Exists.', $msg_data);
            }
        }
        $tableObject->order_delivery_status = $request->order_delivery_status;
        if ($request->order_delivery_status ==  'processing') {
            $tableObject->processing_datetime = date('Y-m-d H:i:s');
        }
        if ($request->order_delivery_status ==  'out_for_delivery') {
            $tableObject->out_for_delivery_datetime = date('Y-m-d H:i:s');
        }
        if ($request->order_delivery_status ==  'delivered') {
            $tableObject->delivery_datetime = date('Y-m-d H:i:s');
        }
        $tableObject->updated_at = date('Y-m-d H:i:s');
        $tableObject->updated_by =  session('data')['id'];
        $tableObject->save();
        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To load update order payment status page
     *   @param int $id
     *   @return Response
     */
    public function updateOrderPayment($id)
    {
        $data['data'] = Order::with('user', 'product', 'vendor', 'currency')->find($id);
        $data['deliveryStatus'] = deliveryStatus();
        $data['customerPaymentStatus'] = customerPaymentStatus();
        $data['onlinePaymentMode'] = onlinePaymentMode();
        return view('backend/order/order_list/order_payment_status_update', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To store order payment status in table
     *   @param Request request
     *   @return Response
     */
    public function updatePaymentStatusData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validatePaymentRequest($request);
        if (count($validationErrors)) {
            \Log::error("Order Payment Status Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $orderData = Order::find($_GET['id']);
        if (isset($_GET['id'])) {
            $getKeys = true;
            $customerPaymentStatus = customerPaymentStatus('', $getKeys);
            $onlinePaymentMode = onlinePaymentMode('', $getKeys);
            if (in_array($request->payment_status, $customerPaymentStatus) && in_array($request->payment_mode, $onlinePaymentMode)) {
                // $tableObject = OrderPayment::find($_GET['id']);
                if ($request->payment_status == 'pending') {
                    errorMessage('Order is Already in Pending Status, Please Select Another Status', $msg_data);
                } elseif ($request->amount == 0) {
                    errorMessage('Entered Amount Should be Greater Than Zero', $msg_data);
                } elseif (($request->payment_status == 'fully_paid') && ($request->amount != $orderData->customer_pending_payment)) {
                    errorMessage('Please Enter Proper Amount for Selected Status.', $msg_data);
                } elseif (($request->payment_status == 'semi_paid') && ($request->amount == $orderData->customer_pending_payment)) {
                    errorMessage('Please Select Proper Status for Entered Amount.', $msg_data);
                } elseif ($orderData->customer_pending_payment >= $request->amount) {
                    $msg = "Payment Status Updated Successfully";
                } else {
                    errorMessage('Amount Should be Less Than or Equal To Pending Payment', $msg_data);
                }
            } else {
                errorMessage('Payment Status Does not Exists.', $msg_data);
            }
        }
        $tableObject  = new OrderPayment;
        $tableObject->user_id = $request->user_id;
        $tableObject->order_id = $_GET['id'];
        $tableObject->product_id = $request->product_id;
        $tableObject->vendor_id = $request->vendor_id;
        $tableObject->payment_mode = $request->payment_mode;
        $tableObject->payment_status = $request->payment_status;
        $tableObject->amount = $request->amount;
        $tableObject->transaction_date = $request->transaction_date;
        if ($request->remark != '') {
            $tableObject->remark = $request->remark;
        } else {
            $tableObject->remark = '';
        }
        if ($request->hasFile('order_image')) {
            $fixedSize = config('global.SIZE.ORDER_PAYMENT');
            $size = $fixedSize / 1000;
            $fileSize = $request->file('order_image')->getSize();  //check file size
            if ($fileSize >= $fixedSize) {
                errorMessage('Image file size should be less than ' . $size . 'KB', $msg_data);
            };
        }
        $tableObject->created_at = date('Y-m-d H:i:s');
        $tableObject->created_by =  session('data')['id'];
        $tableObject->save();
        $last_inserted_id = $tableObject->id;
        if ($request->hasFile('order_image')) {
            $image = $request->file('order_image');
            $actualImage = saveSingleImage($image, 'order_payment', $last_inserted_id);
            $thumbImage = createThumbnail($image, 'order_payment', $last_inserted_id, 'order_payment');
            $bannerObj = OrderPayment::find($last_inserted_id);
            $bannerObj->order_payment_image = $actualImage;
            $bannerObj->order_payment_thumb_image = $thumbImage;
            $bannerObj->save();
        }
        //decreasing customer pending_payment by amount in order table
        Order::where('id',  $_GET['id'])->decrement('customer_pending_payment', $request->amount);
        if (($request->payment_status == 'fully_paid') && ($request->amount == $orderData->customer_pending_payment)) {
            Order::where("id", '=',  $_GET['id'])->update(['customer_payment_status' => 'fully_paid']);
            successMessage($msg, $msg_data);
        }
        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To view order details
     *   @param int $id
     *   @return Response
     */
    // 'storage_condition', table pending
    public function viewOrder($id)
    {
        $data['data'] = Order::with('user', 'vendor', 'category', 'sub_category', 'product', 'packaging_machine', 'product_form', 'packing_type', 'packaging_treatment', 'country', 'currency', 'measurement_unit')->find($id);
        return view('backend/order/order_list/order_view', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  delivery status Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'order_delivery_status' => 'string|required',
        ])->errors();
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-Mar-2022
     *   Uses :  payment status Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validatePaymentRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'payment_status' => 'required|string',
            'payment_mode' => 'required|string',
            'amount' => 'required|numeric',
        ])->errors();
    }

    /**
     *   created by : Maaz Ansari
     *   Created On : 10-Jun-2022
     *   Uses :  Print Order pdf
     *   @param Request request
     *   @return Response
     */
    public function orderPdf($id)
    {

        $main_table = 'orders';

        $data = DB::table('orders')->select(
            'orders.id',
            'orders.product_weight',
            'orders.vendor_amount',
            'orders.vendor_pending_payment',
            'orders.vendor_payment_status',
            'orders.order_delivery_status',
            'orders.product_quantity',
            'orders.mrp',
            'orders.gst_type',
            'orders.gst_percentage',
            'orders.freight_amount',
            'orders.gst_amount',
            'orders.grand_total',
            'orders.shipping_details',
            'orders.billing_details',
            'orders.shelf_life',
            'orders.delivery_datetime',
            'orders.created_at',
            'vendors.vendor_name',
            'vendors.vendor_company_name',
            'vendors.vendor_email',
            'vendors.gstin',
            'vendors.vendor_address',
            'vendors.phone',
            'categories.category_name',
            'sub_categories.sub_category_name',
            'products.product_name',
            'products.product_description',
            'measurement_units.unit_name',
            'measurement_units.unit_symbol',
            'countries.country_name',
            'countries.phone_code',
            'currencies.currency_name',
            'currencies.currency_symbol',
            'storage_conditions.storage_condition_title',
            'packaging_machines.packaging_machine_name',
            'product_forms.product_form_name',
            'packing_types.packing_name',
            'packaging_treatments.packaging_treatment_name',
            'recommendation_engines.engine_name',
            'recommendation_engines.structure_type',
            'recommendation_engines.min_shelf_life',
            'recommendation_engines.max_shelf_life',
            'packaging_materials.packaging_material_name',
            'packaging_materials.material_description',
            'customer_enquiries.address',
            'states.state_name',
            'cities.city_name',
        )
            ->leftjoin('categories', 'orders.category_id', '=', 'categories.id')
            ->leftjoin('vendors', 'orders.vendor_id', '=', 'vendors.id')
            ->leftjoin('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
            ->leftjoin('products', 'orders.product_id', '=', 'products.id')
            ->leftjoin('measurement_units', 'orders.measurement_unit_id', '=', 'measurement_units.id')
            ->leftjoin('countries', 'orders.country_id', '=', 'countries.id')
            ->leftjoin('currencies', 'orders.currency_id', '=', 'currencies.id')
            ->leftjoin('storage_conditions', 'orders.storage_condition_id', '=', 'storage_conditions.id')
            ->leftjoin('packaging_machines', 'orders.packaging_machine_id', '=', 'packaging_machines.id')
            ->leftjoin('product_forms', 'orders.product_form_id', '=', 'product_forms.id')
            ->leftjoin('packing_types', 'orders.packing_type_id', '=', 'packing_types.id')
            ->leftjoin('packaging_treatments', 'orders.packaging_treatment_id', '=', 'packaging_treatments.id')
            ->leftjoin('recommendation_engines', 'orders.recommendation_engine_id', '=', 'recommendation_engines.id')
            ->leftjoin('packaging_materials', 'orders.packaging_material_id', '=', 'packaging_materials.id')
            ->leftjoin('customer_enquiries', 'orders.customer_enquiry_id', '=', 'customer_enquiries.id')
            ->leftjoin('states', 'customer_enquiries.state_id', '=', 'states.id')
            ->leftjoin('cities', 'customer_enquiries.city_id', '=', 'cities.id')
            ->where([[$main_table . '' . '.id', $id], [$main_table . '' . '.deleted_at', NULL]])->first();


        $invoice_date = Carbon::now()->format('d/m/Y');
        !empty($data->created_at) ? $order_date = Carbon::parse($data->created_at)->format('d/m/Y') : $order_date = 'Order Date Not Found';
        !empty($data->delivery_datetime) ?  $delivery_date = Carbon::parse($data->delivery_datetime)->format('d/m/Y') : $delivery_date = 'Delivery Date Not Found';

        $shipping_data = json_decode($data->shipping_details);
        $billing_data = json_decode($data->billing_details);

        if (!empty($data->gst_type) &&  $data->gst_type == 'Igst') {
            $cgst = $cgst_amount =  $sgst = $sgst_amount = $dc_cgst = $dc_cgst_amount = $dc_sgst = $dc_sgst_amount =  0;
            $igst = $dc_igst = $data->gst_percentage ?? 0;
            $igst_amount = $data->gst_amount ?? 0;

            //reverse calculation
            $dc_amount = $data->freight_amount ?? 0;
            $dc_tax_val = round($dc_amount / (1 + ($dc_igst / 100)), 2);
            $dc_igst_amount = $dc_amount - $dc_tax_val;
        } else {

            $cgst = $sgst = $dc_cgst = $dc_sgst = $data->gst_percentage ? ($data->gst_percentage / 2) : 0;
            $cgst_amount = $sgst_amount = $data->gst_amount ? ($data->gst_amount / 2) : 0;

            $dc_amount = $data->freight_amount ?? 0;
            $dc_tax_val = round($dc_amount / (1 + ((2 * $dc_cgst) / 100)), 2);
            $dc_tax_amount = $dc_amount - $dc_tax_val;
            $dc_cgst_amount = $dc_sgst_amount  = $dc_tax_amount / 2;

            $igst =  $igst_amount = $dc_igst = $dc_igst_amount = 0;
        }

        $numberToWords = new NumberToWords();
        $grand_total = ($data->grand_total ?? 0) + ($dc_amount ?? 0);
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $in_words = $numberTransformer->toWords($grand_total);


        $result = [
            'data' => $data,
            'invoice_date' => $invoice_date,
            'order_date' => $order_date,
            'delivery_date' => $delivery_date,
            'shipping_data' => $shipping_data,
            'billing_data' => $billing_data,
            'cgst' => $cgst,
            'dc_cgst' => $dc_cgst,
            'cgst_amount' => $cgst_amount,
            'dc_cgst_amount' => $dc_cgst_amount,
            'sgst' => $sgst,
            'dc_sgst' => $dc_sgst,
            'sgst_amount' => $sgst_amount,
            'dc_sgst_amount' => $dc_sgst_amount,
            'igst' => $igst,
            'dc_igst' => $dc_igst,
            'igst_amount' => $igst_amount,
            'dc_amount' => $dc_amount,
            'dc_tax_val' => $dc_tax_val,
            'dc_igst_amount' => $dc_igst_amount,
            'in_words' => $in_words,
            'no_image' => getFile('mypcot.jpg', 'default'),
        ];
        // $result['data'] = $data;
        // echo '<pre>';
        // print_r($billing_data);
        // echo '</pre>';
        // die;
        $html =  view('backend/order/order_list/order_pdf', $result);


        PDF::SetTitle('Order Invoice');
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');

        PDF::Output('Order Invoice.pdf', 'I');
    }
}
