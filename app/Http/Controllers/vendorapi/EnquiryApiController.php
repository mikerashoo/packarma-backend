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
                    $from = Carbon::parse($new_from_data)->format('Y-m-d H:i:s');


                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d H:i:s');


                    $enquiryData = $enquiryData->whereBetween($main_table . '' . '.created_at', [
                        $from, $to
                    ]);
                    $data = $data->whereBetween($main_table . '' . '.created_at', [$from, $to]);
                } elseif ($request->from_date && !isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $old_from_date = explode('/', $from_date);
                    $new_from_data = $old_from_date[2] . '-' . $old_from_date[1] . '-' . $old_from_date[0];
                    $from = Carbon::parse($new_from_data)->format('Y-m-d H:i:s');

                    $enquiryData = $enquiryData->whereDate($main_table . '' . '.created_at', '>=', $from);
                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $from);
                } elseif ($request->to_date && !isset($request->from_date)) {
                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d H:i:s');
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
                $id = $request->id;
                $staus = $request->vendor_price;


                $checkQuotation = VendorQuotation::where([['id', $id], ['vendor_id', $vendor_id], ['enquiry_status', 'mapped']])->first();
                if (empty($checkQuotation)) {
                    errorMessage(__('quotation.enquiry_not_found_to_quote'), $msg_data);
                }
                $quotation_data = $request->all();
                $quotation_data['vendor_id'] = $vendor_id;
                $quotation_data['enquiry_status'] = 'quoted';
                unset($quotation_data['id']);
                // print_r($quotation_data);
                // die;
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
}
