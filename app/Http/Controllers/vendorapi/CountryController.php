<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Country;

class CountryController extends Controller
{
    /**
     * Created By : Maaz Ansari
     * Created at : 27-05-2022
     * Uses : Display a listing of the country.
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
                $page_no = 1;
                $limit = 10;

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                $data = DB::table('countries')->select(
                    'countries.id',
                    'countries.country_name',
                    'countries.phone_code',
                    'countries.phone_length',
                    'currencies.currency_name',
                    'currencies.currency_symbol',
                    'currencies.currency_code',
                )
                    ->leftjoin('currencies', 'currencies.id', '=', 'countries.currency_id')
                    ->where('countries.status', 1);

                $countryData = Country::whereRaw("1 = 1");
                if ($request->country_id) {
                    $countryData = $countryData->where('countries.id', $request->country_id);
                    $data = $data->where('countries.id', $request->country_id);
                }
                if ($request->country_name) {
                    $countryData = $countryData->where('countries.country_name', $request->country_name);
                    $data = $data->where('countries.country_name', $request->country_name);
                }
                if ($request->currency_id) {
                    $countryData = $countryData->where('countries.currency_id', $request->currency_id);
                    $data = $data->where('countries.currency_id', $request->currency_id);
                }
                if ($request->phone_code) {
                    $countryData = $countryData->where('countries.phone_code', $request->phone_code);
                    $data = $data->where('countries.phone_code', $request->phone_code);
                }
                // if (empty($countryData->first())) {
                //     errorMessage(__('country.country_not_found'), $msg_data);
                // }
                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'country_name');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();




                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;

                // if (empty($data)) {
                //     errorMessage(__('country.country_not_found'), $responseData);
                // }

                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Country fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
