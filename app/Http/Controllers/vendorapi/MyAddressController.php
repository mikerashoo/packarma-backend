<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use App\Models\VendorWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyAddressController extends Controller
{
    /**
     * Display a listing of the Address.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];
                // echo $vendor_id;
                $page_no = 1;
                $limit = 10;

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                $main_table = 'vendor_warehouses';


                $data = DB::table('vendor_warehouses')->select(
                    'vendor_warehouses.id',
                    'vendor_warehouses.warehouse_name',
                    'vendor_warehouses.gstin',
                    'vendor_warehouses.mobile_no',
                    'vendor_warehouses.flat',
                    'vendor_warehouses.area',
                    'vendor_warehouses.land_mark',
                    'vendor_warehouses.city_name',
                    'states.state_name',
                    'countries.country_name',
                    'countries.phone_code',
                )
                    ->leftjoin('countries', 'vendor_warehouses.country_id', '=', 'countries.id')
                    ->leftjoin('states', 'vendor_warehouses.state_id', '=', 'states.id')
                    ->where([[$main_table . '' . '.status', '1'], [$main_table . '' . '.deleted_at', NULL]])->where('vendor_id', $vendor_id);


                $materialData = VendorWarehouse::whereRaw("1 = 1");

                if (empty($materialData->first())) {
                    errorMessage(__('vendor_address.address_not_found'), $msg_data);
                }

                if ($request->id) {
                    $data = $data->where($main_table . '' . '.id', $request->id);
                }

                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'warehouse_name|gstin');
                }

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();

                $i = 0;

                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                // print_r($data);
                // die();
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Address Fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];

                // Request Validation
                $addressValidationErrors = $this->validateAdressRegister($request);
                if (count($addressValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $addressValidationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $addressValidationErrors->all());
                }
                \Log::info("My address creation started!");
                $vendor_address_data = array();
                $vendor_address_data = $request->all();
                $vendor_address_data['vendor_id'] = $vendor_id;

                // Store a new vendor address
                $vendorAdressData = VendorWarehouse::create($vendor_address_data);
                \Log::info("My adress created successfully!");

                $vendorAddress = $vendorAdressData->toArray();
                $vendorAdressData->created_at->toDateTimeString();
                $vendorAdressData->updated_at->toDateTimeString();


                successMessage(__('vendor_address.created'), $vendorAddress);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Address Creation failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];

                // Request Validation
                $addressValidationErrors = $this->validateAdressRegister($request);
                if (count($addressValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $addressValidationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $addressValidationErrors->all());
                }
                \Log::info("My address creation started!");
                $vendor_address_data = array();
                if (!$request->id) {
                    errorMessage(__('vendor_address.id_require'), $msg_data);
                }
                $id = $request->id;

                // Store a new vendor address

                $checkVendorAdress = VendorWarehouse::where([['id', $id], ['vendor_id', $vendor_id]])->first();
                if (empty($checkVendorAdress)) {
                    errorMessage(__('vendor_address.address_not_found'), $msg_data);
                }
                $vendor_address_data = $request->all();
                $vendor_address_data['vendor_id'] = $vendor_id;
                unset($vendor_address_data['id']);
                $checkVendorAdress->update($vendor_address_data);
                $vendorAdressData = $checkVendorAdress;

                $vendorAddress = $vendorAdressData->toArray();
                $vendorAdressData->created_at->toDateTimeString();
                $vendorAdressData->updated_at->toDateTimeString();

                \Log::info("My adress Updated successfully!");

                successMessage(__('vendor_address.updated'), $vendorAddress);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Address Updation failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];

                \Log::info("My address deletion started!");
                if (!$request->id) {
                    errorMessage(__('vendor_address.id_require'), $msg_data);
                }
                $id = $request->id;

                // Store a new vendor address

                $checkVendorAdress = VendorWarehouse::where([['id', $id], ['vendor_id', $vendor_id]])->first();
                if (empty($checkVendorAdress)) {
                    errorMessage(__('vendor_address.address_not_found'), $msg_data);
                }

                $checkVendorAdress->delete();


                \Log::info("My adress deleted successfully!");

                successMessage(__('vendor_address.deleted'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Address deletion failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }


    /**
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateAdressRegister(Request $request)
    {
        return \Validator::make($request->all(), [
            'warehouse_name' => 'required|string',
            'city_name' => 'required|string',
            'gstin' => 'required|string',
            'mobile_no' => 'required|digits:10',
            'state_id' => 'required|numeric',
            'country_id' => 'required|numeric',
            'pincode' => 'required|digits:6'

        ])->errors();
    }
}
