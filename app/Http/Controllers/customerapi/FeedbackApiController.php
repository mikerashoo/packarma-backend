<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Carbon\Carbon;
use Response;

class FeedbackApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created At : 27/05/2022
     * Uses : To store feedback in table
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msg_data = array();
        \Log::info("Initiating Customer Enquiry process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                
                // Request Validation
                $validationErrors = $this->validateFeedbackStore($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                $feedbackData = Review::create($request->all());
                \Log::info("Feedback Submitted Successfully");
                successMessage(__('feedback.feedback_send_successfully'), $feedbackData->toArray());
             }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
            \Log::error("Feedback submission failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 27/05/2022
     * Uses : to validate feedback storing request
     * Validate request for Customer Enquiry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    private function validateFeedbackStore(Request $request)
    {
        return \Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'order_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'rating' => 'required|numeric',
            'review' => 'required|string'
        ])->errors();
    }
}
