<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PincodeDetailApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 01/06/2022
     * Uses : Get Pincode details 
     * Use to show Vendor My profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        \Log::info("Fetch pincode data, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                $pincodeValidationErrors = $this->validatePincode($request);
                if (count($pincodeValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $pincodeValidationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $pincodeValidationErrors->all());
                }
                $pincode = "1";
                if ($request->pin_code) {
                    $pincode = $request->pin_code;
                }
                \Log::info("Pincode details api starts here");
                $getPincodeDetails = getPincodeDetails($pincode);
                if (!$getPincodeDetails) {
                    \Log::info("There is problem with api");
                    errorMessage(__('pin_code.api_error'), $msg_data);
                }

                successMessage(__('pin_code.details_found'), $getPincodeDetails);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Fetching Info failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    private function validatePincode(Request $request)
    {
        return \Validator::make($request->all(), [
            'pin_code' => 'required|numeric|min:6',

        ])->errors();
    }
}
