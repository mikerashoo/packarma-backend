<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class GstDetailsApiController extends Controller
{
    /**Created by : Pradyumn Dwivedi
     * Created at : 27/06/2022
     * uses : To store gst details of customer
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                // Request Validation
                $gstDetailsValidationErrors = $this->validateGstDetailsRegister($request, $user_id);
                if (count($gstDetailsValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $gstDetailsValidationErrors->all()));
                    errorMessage($gstDetailsValidationErrors->all(), $gstDetailsValidationErrors->all());
                }
                \Log::info("Store Customer Gst Details Starts");

                $UserGstDetails = User::where([['id', $user_id]])->first();

                $user_gst_details = array();
                $user_gst_details = $request->all();
                unset($user_gst_details['country_id']);
                if ($request->hasFile('gst_certificate')) {
                    \Log::info("Storing Gst Certificate image.");
                    $gst_certificate = $request->file('gst_certificate');
                    $extension = $gst_certificate->extension();
                    $certificate_imgname = $user_id . '_certificate_' . Carbon::now()->format('dmYHis') . '.' . $extension;
                    $user_gst_details['gst_certificate'] = saveImageGstVisitingCard($gst_certificate,'gst_certificate', $certificate_imgname);
                }
                if (!empty($UserGstDetails->gst_certificate)) {

                    $file_to_unlink =  getFile($UserGstDetails->gst_certificate, 'gst_certificate', FALSE, 'unlink');
                    if ($file_to_unlink != 'file_not_found') {
                        unlink($file_to_unlink);
                    }
                }

                // Store Gst Details

                $UserGstDetails->update($user_gst_details);
                \Log::info("User Gst Details Stored Successfully");
                $userGstDetailsData = $UserGstDetails;

                $GstDetails = $userGstDetailsData->toArray();
                $userGstDetailsData->created_at->toDateTimeString();
                $userGstDetailsData->updated_at->toDateTimeString();


                successMessage(__('user.gst_details_stored'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("User Gst Details Store Failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 27/05/2022
     * Uses : Display User gst details
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $msg_data = array();
        \Log::info("Show User GST Details, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $token = readHeaderToken();

            if ($token) {
                $user_id = $token['sub'];

                $userGstData = User::select('name', 'gstin', 'gst_certificate')->where('id', $user_id)->get()->toArray();

                $i = 0;
                foreach ($userGstData as $row) {

                    $userGstData[$i]['file_type'] = explode('.', $row['gst_certificate'])['1'] ?? '';

                    $userGstData[$i]['gst_certificate'] = getFile($row['gst_certificate'], 'gst_certificate', false, 'gst_certificate');
                    $i++;
                }

                successMessage(__('user.gst_details_fetched'), $userGstData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Show User Gst Details Failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
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
            'gstin' => 'required|string|regex:' . config('global.GST_NO_VALIDATION') . '|unique:users,gstin,' . $id . ',id,deleted_at,NULL',
            'gst_certificate' => 'sometimes|required|mimes:jpeg,png,jpg,pdf|max:' . config('global.MAX_IMAGE_SIZE'),

        ])->errors();
    }
}
