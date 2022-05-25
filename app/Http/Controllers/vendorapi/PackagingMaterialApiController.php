<?php

/**
 * Created By :Maaz Ansari
 * Created On : 11 May 2022
 * Uses : This controller will be used for Packaging Materials related APIs.
 */

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingMaterial;
use App\Models\VendorMaterialMapping;
use Illuminate\Support\Facades\DB;
use Response;

class PackagingMaterialApiController extends Controller
{
    /**
     * Display a listing of the packaging materials.
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

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                $main_table = 'vendor_material_mappings';


                $data = DB::table('vendor_material_mappings')->select(
                    'vendor_material_mappings.id',
                    'vendor_material_mappings.vendor_price',
                    'packaging_materials.packaging_material_name',
                    'packaging_materials.material_description',
                    'packaging_materials.shelf_life',
                    'packaging_materials.wvtr',
                    'packaging_materials.otr',
                    'packaging_materials.cof',
                    'packaging_materials.sit',
                    'packaging_materials.gsm',
                    'products.product_name',
                    'products.product_description',
                )
                    ->leftjoin('products', 'vendor_material_mappings.product_id', '=', 'products.id')
                    ->leftjoin('packaging_materials', 'vendor_material_mappings.packaging_material_id', '=', 'packaging_materials.id')
                    ->where([[$main_table . '' . '.status', '1'], [$main_table . '' . '.deleted_at', NULL]])->where('vendor_id', $vendor_id);



                // $data = VendorMaterialMapping::with('packaging_material')->where('status', '1')->where('vendor_id', $vendor_id);
                // $data = VendorMaterialMapping::select('id', 'vendor_price', 'packaging_material_id')->with(['packaging_material' => function ($query) {
                //     $query->select('id', 'packaging_material_name', 'shelf_life', 'wvtr', 'otr', 'cof', 'sit', 'gsm', 'special_feature');
                // }])->where('status', '1')->where('vendor_id', $vendor_id);

                $materialData = VendorMaterialMapping::whereRaw("1 = 1");
                // $materialData = PackagingMaterial::whereRaw("1 = 1");

                if ($request->packaging_material_id) {
                    $materialData = $materialData->where($main_table . '' . '.packaging_material_id', $request->packaging_material_id);
                    $data = $data->where($main_table . '' . '.packaging_material_id', $request->packaging_material_id);
                }
                if (empty($materialData->first())) {
                    errorMessage(__('packagingmaterial.material_not_found'), $msg_data);
                }

                if ($request->id) {
                    $data = $data->where($main_table . '' . '.id', $request->id);
                }

                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'vendor_price|packaging_material_name');
                }

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();

                $i = 0;
                // foreach ($data as $row) {
                //     $data[$i]['product_image'] = getFile($row['product_image'], 'product');
                //     $data[$i]['product_thumb_image'] = getFile($row['product_thumb_image'], 'product', false, 'thumb');
                //     $i++;
                // }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                // print_r($data);
                // die();
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Material fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
