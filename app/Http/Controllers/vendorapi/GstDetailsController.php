<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GstDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];

                // Request Validation
                $gstDetailsValidationErrors = $this->validateGstDetailsRegister($request, $vendor_id);
                if (count($gstDetailsValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $gstDetailsValidationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $gstDetailsValidationErrors->all());
                }
                \Log::info("Store Gst Details Starts");

                $vendorGstDetails = Vendor::where([['id', $vendor_id]])->first();

                $vendor_gst_details = array();
                $vendor_gst_details = $request->all();
                unset($vendor_gst_details['country_id']);
                if ($request->hasFile('gst_certificate')) {
                    \Log::info("Storing Gst Certificate image.");
                    $gst_certificate = $request->file('gst_certificate');
                    $extension = $gst_certificate->extension();
                    $certificate_imgname = $vendor_id . '_certificate_' . Carbon::now()->format('dmYHis') . '.' . $extension;
                    $gst_certificate->storeAs('uploads/vendor_gst_certificate', $certificate_imgname, 'public');
                    $vendor_gst_details['gst_certificate'] = $certificate_imgname;
                }
                if (!empty($vendorGstDetails->gst_certificate)) {

                    $file_to_unlink =  getFile($vendorGstDetails->gst_certificate, 'vendor_gst_certificate', FALSE, 'unlink');
                    if ($file_to_unlink != 'file_not_found') {
                        unlink($file_to_unlink);
                    }
                }

                // Store Gst Details

                $vendorGstDetails->update($vendor_gst_details);
                \Log::info("Gst Details Stored Successfully");
                $vendorGstDetailsData = $vendorGstDetails;

                $GstDetails = $vendorGstDetailsData->toArray();
                $vendorGstDetailsData->created_at->toDateTimeString();
                $vendorGstDetailsData->updated_at->toDateTimeString();


                successMessage(__('vendor.gst_details_stored'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Gst Details Store Failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $msg_data = array();
        \Log::info("Show GST Details, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $vendor_token = readVendorHeaderToken();

            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];

                $vendorGstData = Vendor::select('vendor_name', 'vendor_company_name', 'gstin', 'gst_certificate')->where('id', $vendor_id)->get()->toArray();
                // if (empty($vendorGstData->gst_certificate)) {
                //     errorMessage(__('vendor.gst_certificate_not_found'), $msg_data);
                // }

                // print_r($vendorGstData);
                // die;
                $i = 0;
                foreach ($vendorGstData as $row) {

                    $vendorGstData[$i]['file_type'] = explode('.', $row['gst_certificate'])['1'] ?? '';
                    $vendorGstData[$i]['gst_certificate'] = getFile($row['gst_certificate'], 'vendor_gst_certificate', false);
                    $i++;
                }

                successMessage(__('vendor.gst_details_fetched'), $vendorGstData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Show Gst Details Failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateGstDetailsRegister(Request $request, $id)
    {
        return \Validator::make($request->all(), [
            'gstin' => 'required|string|regex:' . config('global.GST_NO_VALIDATION') . '|unique:vendors,gstin,' . $id . ',id,deleted_at,NULL',
            'gst_certificate' => 'sometimes|required|mimes:jpeg,png,jpg,pdf|max:' . config('global.MAX_IMAGE_SIZE'),

        ])->errors();
    }
}
