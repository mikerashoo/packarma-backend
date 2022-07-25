<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorQuotation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Response;

class EnquiryApiController extends Controller
{
    /**
     * Display a listing of the Enquiry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try {
            // vendor token
            // vendor token new
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                // print_r($vendor_token);
                // die();
                $vendor_id = $vendor_token['sub'];
                $page_no = 1;
                $limit = 10;
                $orderByArray = ['vendor_quotations.updated_at' => 'DESC',];
                $defaultSortByName = false;

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                $main_table = 'vendor_quotations';

                $data = DB::table($main_table)->select(
                    'vendor_quotations.id',
                    'vendor_quotations.vendor_price',
                    'vendor_quotations.enquiry_status',
                    'vendor_quotations.vendor_warehouse_id',
                    'vendor_quotations.created_at',
                    'customer_enquiries.description',
                    'customer_enquiries.enquiry_type',
                    'customer_enquiries.product_weight',
                    'customer_enquiries.product_quantity',
                    'customer_enquiries.shelf_life',
                    'customer_enquiries.address',
                    'measurement_units.unit_name',
                    'measurement_units.unit_symbol',
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
                    'products.product_name',
                    'products.product_description',
                    'categories.category_name',
                    'states.state_name',
                    // 'cities.city_name',
                    'customer_enquiries.city_name',
                )
                    ->leftjoin('products', 'vendor_quotations.product_id', '=', 'products.id')
                    ->leftjoin('customer_enquiries', 'vendor_quotations.customer_enquiry_id', '=', 'customer_enquiries.id')
                    ->leftjoin('categories', 'customer_enquiries.category_id', '=', 'categories.id')
                    ->leftjoin('measurement_units', 'customer_enquiries.measurement_unit_id', '=', 'measurement_units.id')
                    ->leftjoin('storage_conditions', 'customer_enquiries.storage_condition_id', '=', 'storage_conditions.id')
                    ->leftjoin('packaging_machines', 'customer_enquiries.packaging_machine_id', '=', 'packaging_machines.id')
                    ->leftjoin('product_forms', 'customer_enquiries.product_form_id', '=', 'product_forms.id')
                    ->leftjoin('packing_types', 'customer_enquiries.packing_type_id', '=', 'packing_types.id')
                    ->leftjoin('packaging_treatments', 'customer_enquiries.packaging_treatment_id', '=', 'packaging_treatments.id')
                    ->leftjoin('recommendation_engines', 'customer_enquiries.recommendation_engine_id', '=', 'recommendation_engines.id')
                    ->leftjoin('packaging_materials', 'customer_enquiries.packaging_material_id', '=', 'packaging_materials.id')
                    ->leftjoin('states', 'customer_enquiries.state_id', '=', 'states.id')
                    ->leftjoin('cities', 'customer_enquiries.city_id', '=', 'cities.id')
                    ->where([['vendor_quotations.vendor_id', $vendor_id], ['enquiry_status', 'mapped']]);

                // $data = VendorQuotation::with('product', 'vendor', 'Enquiry')->where([['vendor_id', $vendor_id], ['enquiry_status', 'mapped']]);

                $enquiryData = VendorQuotation::whereRaw("1 = 1");

                if ($request->product_id) {
                    $enquiryData = $enquiryData->where($main_table . '' . '.product_id', $request->product_id);
                    $data = $data->where($main_table . '' . '.product_id', $request->product_id);
                }


                if ($request->last_no_of_days && is_numeric($request->last_no_of_days)) {
                    $date_from_no_of_days = Carbon::now()->subDays($request->last_no_of_days);
                    $enquiryData = $enquiryData->whereDate($main_table . '' . '.created_at', '>=', $date_from_no_of_days);
                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $date_from_no_of_days);
                }

                if ($request->from_date && $request->to_date) {
                    $from_date = $request->from_date;
                    $old_from_date = explode('/', $from_date);
                    $new_from_data = $old_from_date[2] . '-' . $old_from_date[1] . '-' . $old_from_date[0];
                    $from = Carbon::parse($new_from_data)->format('Y-m-d 00:00:00');


                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d 23:59:59');


                    // $enquiryData = $enquiryData->whereBetween($main_table . '' . '.created_at', [
                    //     $from, $to
                    // ]);
                    // $data = $data->whereBetween($main_table . '' . '.created_at', [$from, $to]);

                    $enquiryData = $enquiryData->whereDate($main_table . '' . '.created_at', '>=', $from)
                        ->whereDate($main_table . '' . '.created_at', '<=', $to);

                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $from)
                        ->whereDate($main_table . '' . '.created_at', '<=', $to);
                } elseif ($request->from_date && !isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $old_from_date = explode('/', $from_date);
                    $new_from_data = $old_from_date[2] . '-' . $old_from_date[1] . '-' . $old_from_date[0];
                    $from = Carbon::parse($new_from_data)->format('Y-m-d 00:00:00');

                    $enquiryData = $enquiryData->whereDate($main_table . '' . '.created_at', '>=', $from);
                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $from);
                } elseif ($request->to_date && !isset($request->from_date)) {
                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d 23:59:59');
                    $enquiryData = $enquiryData->whereDate($main_table . '' . '.created_at', '<=', $to);
                    $data = $data->whereDate($main_table . '' . '.created_at', '<=', $to);
                }


                // if (empty($enquiryData->first())) {
                //     errorMessage(__('enquiry.enquiry_not_found'), $msg_data);
                // }

                if ($request->id) {
                    $data = $data->where($main_table . '' . '.id', $request->id);
                }

                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'vendor_price|product_name');
                }

                if ($defaultSortByName) {
                    $orderByArray = ['products.product_name' => 'ASC'];
                }

                $data = allOrderBy($data, $orderByArray);

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();

                $i = 0;
                foreach ($data as $row) {
                    $data[$i]->enq_id = getFormatid($row->id, $main_table);
                    $data[$i]->material_unit_symbol = 'kg';
                    $i++;
                }


                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;

                // if (empty($data)) {
                //     errorMessage(__('enquiry.enquiry_not_found'), $responseData);
                // }

                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Enquiry fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }


    public function sendQuotation(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];

                \Log::info("Sending Quotation Started!");
                $quotation_data = array();
                if (!$request->id) {
                    errorMessage(__('quotation.id_require'), $msg_data);
                }

                if (!$request->vendor_price) {
                    errorMessage(__('quotation.vendor_price_require'), $msg_data);
                }

                $validationErrors = $this->validateSendQuotation($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }

                if ($request->vendor_warehouse_id) {
                    // if (!is_int($request->vendor_warehouse_id)) {
                    //     errorMessage(__('quotation.wrong_vendor_warehouse'), $msg_data);
                    // }
                    $quotation_data['vendor_warehouse_id'] = $request->vendor_warehouse_id;
                }

                $id = $request->id;
                $checkQuotation = VendorQuotation::where([['id', $id], ['vendor_id', $vendor_id], ['enquiry_status', 'mapped']])->first();
                if (empty($checkQuotation)) {
                    errorMessage(__('quotation.enquiry_not_found_to_quote'), $msg_data);
                }


                $existing_vendor_price = $checkQuotation->vendor_price;
                $new_vendor_price = number_format((float)$request->vendor_price, 2, '.', '');

                if ($new_vendor_price != $existing_vendor_price) {

                    $commission_amt = $checkQuotation->commission_amt;
                    $product_quantity = $checkQuotation->product_quantity;
                    $gst_percentage = $checkQuotation->gst_percentage;
                    $freight_amount = $checkQuotation->freight_amount;

                    $mrp = $new_vendor_price + $commission_amt;
                    $sub_total_amount = $product_quantity * $mrp;
                    $gst_amount = $mrp * $gst_percentage / 100;
                    $total_amount = $sub_total_amount + $gst_amount + $freight_amount;
                    $quotation_data['mrp'] = $mrp;
                    $quotation_data['sub_total'] = $sub_total_amount;
                    $quotation_data['gst_amount'] = $gst_amount;
                    $quotation_data['total_amount'] = $total_amount;
                }

                $quotation_data['vendor_price'] = $new_vendor_price;
                $quotation_data['vendor_id'] = $vendor_id;
                $quotation_data['enquiry_status'] = 'quoted';
                $quotation_data['quotation_expiry_datetime'] = Carbon::now()->addDays(30)->format('Y-m-d H:i:s');

                $checkQuotation->update($quotation_data);
                $quotationData = $checkQuotation;

                $quotation = $quotationData->toArray();
                $quotationData->created_at->toDateTimeString();
                $quotationData->updated_at->toDateTimeString();

                \Log::info("Quotation sent successfully!");

                successMessage(__('quotation.sent'), $quotation);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Quotation sending failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for forgot password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateSendQuotation(Request $request)
    {
        return \Validator::make(
            $request->all(),
            [
                'vendor_price' => 'required|numeric|between:1,99999.999',
                'vendor_warehouse_id' => 'required|integer|gt:0',

            ],
            [
                'vendor_price.between' => 'The vendor price must not be greater than 99999.99',
                'vendor_warehouse_id.required' => 'Warehouse is require',
                'vendor_warehouse_id.gt' => 'Warehouse not found',
            ]

        )->errors();
    }
}
