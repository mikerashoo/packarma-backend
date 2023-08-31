<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorQuotation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Response;


class QuotationApiController extends Controller
{
    /**
     * Display a listing of the Quotations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];
                $page_no = 1;
                $limit = 10;
                $delivery_in_days_unit = 'Days';
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


                $data = DB::table('vendor_quotations')->select(
                    'vendor_quotations.id',
                    'vendor_quotations.vendor_price',
                    'vendor_quotations.vendor_amount',
                    'vendor_quotations.enquiry_status',
                    'vendor_quotations.vendor_warehouse_id',
                    'vendor_quotations.freight_amount',
                    'vendor_quotations.delivery_in_days',
                    'vendor_quotations.created_at',
                    'customer_enquiries.description',
                    'customer_enquiries.enquiry_type',
                    'customer_enquiries.product_weight',
                    'customer_enquiries.product_quantity',
                    'recommendation_engines.min_order_quantity',
                    'recommendation_engines.min_order_quantity_unit',
                    'customer_enquiries.shelf_life',
                    'customer_enquiries.entered_shelf_life',
                    'customer_enquiries.entered_shelf_life_unit',
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
                    'recommendation_engines.display_shelf_life',
                    'packaging_materials.packaging_material_name',
                    'products.product_name',
                    'products.product_description',
                    'categories.category_name',
                    'states.state_name',
                    // 'cities.city_name',
                    'customer_enquiries.city_name',
                    'currencies.currency_symbol',
                    'currencies.currency_code',
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
                    ->leftjoin('countries', 'customer_enquiries.country_id', '=', 'countries.id')
                    ->leftjoin('currencies', 'countries.currency_id', '=', 'currencies.id')
                    ->where([['vendor_quotations.vendor_id', $vendor_id], ['enquiry_status', '!=', 'mapped']]);


                // $data = VendorQuotation::select('vendor_price', 'id', 'product_id', 'customer_enquiry_id')->with(['product' => function ($query) {
                //     $query->select('id', 'product_name', 'product_description');
                // }])->with(['enquiry' => function ($query) {
                //     $query->select('id', 'description', 'enquiry_type', 'order_id', 'shelf_life', 'product_weight');
                // }])->where([['vendor_id', $vendor_id], ['enquiry_status', '!=', 'mapped']]);

                $quotationData = VendorQuotation::whereRaw("1 = 1");



                if ($request->enquiry_status) {


                    if ($request->enquiry_status == 'requested') {
                        $quotationData = $quotationData->whereIn($main_table . '' . '.enquiry_status', ['quoted', 'viewed', 'accept', 'requote']);
                        $data = $data->whereIn($main_table . '' . '.enquiry_status', ['quoted', 'viewed', 'accept', 'requote']);
                    } elseif ($request->enquiry_status == 'closed') {
                        $quotationData = $quotationData->whereIn($main_table . '' . '.enquiry_status', ['auto_reject', 'reject']);
                        $data = $data->whereIn($main_table . '' . '.enquiry_status', ['auto_reject', 'reject']);
                    } else {
                        $quotationData = $quotationData->whereIn($main_table . '' . '.enquiry_status', [$request->enquiry_status]);
                        $data = $data->whereIn($main_table . '' . '.enquiry_status', [$request->enquiry_status]);
                    }
                }

                if ($request->last_no_of_days && is_numeric($request->last_no_of_days)) {
                    $date_from_no_of_days = Carbon::now()->subDays($request->last_no_of_days);
                    $quotationData = $quotationData->whereDate($main_table . '' . '.created_at', '>=', $date_from_no_of_days);
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


                    // $quotationData = $quotationData->whereBetween($main_table . '' . '.created_at', [$from, $to]);
                    // $data = $data->whereBetween($main_table . '' . '.created_at', [$from, $to]);

                    $quotationData = $quotationData->whereDate($main_table . '' . '.created_at', '>=', $from)
                        ->whereDate($main_table . '' . '.created_at', '<=', $to);

                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $from)
                        ->whereDate($main_table . '' . '.created_at', '<=', $to);
                } elseif ($request->from_date && !isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $old_from_date = explode('/', $from_date);
                    $new_from_data = $old_from_date[2] . '-' . $old_from_date[1] . '-' . $old_from_date[0];
                    $from = Carbon::parse($new_from_data)->format('Y-m-d 00:00:00');

                    $quotationData = $quotationData->whereDate($main_table . '' . '.created_at', '>=', $from);
                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $from);
                } elseif ($request->to_date && !isset($request->from_date)) {
                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d 23:59:59');
                    $quotationData = $quotationData->whereDate($main_table . '' . '.created_at', '<=', $to);
                    $data = $data->whereDate($main_table . '' . '.created_at', '<=', $to);
                }


                // if (empty($quotationData->first())) {
                //     errorMessage(__('quotation.quotation_not_found'), $msg_data);
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
                    $data[$i]->quote_id = getFormatid($row->id, $main_table);
                    $data[$i]->material_unit_symbol = 'kg';
                    $data[$i]->delivery_in_days_unit = $delivery_in_days_unit;
                    if($row->product_weight == 0.00){
                        $data[$i]->product_weight = null;
                        $data[$i]->unit_name = null;
                        $data[$i]->unit_symbol = null;
                    }
                    if($row->entered_shelf_life == 0){
                        $data[$i]->entered_shelf_life = null;
                        $data[$i]->entered_shelf_life_unit = null;
                    }

                    $i++;
                }


                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;

                // if (empty($data)) {
                //     errorMessage(__('quotation.quotation_not_found'), $responseData);
                // }

                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Quotation fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
