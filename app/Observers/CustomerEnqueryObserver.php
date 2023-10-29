<?php

namespace App\Observers;

use App\Models\CustomerEnquiry;
use App\Models\User;
use App\Models\UserCreditHistory;
use Illuminate\Support\Facades\Log;

class CustomerEnqueryObserver
{
    /**
     * Handle the CustomerEnquiry "created" event.
     *
     * @param  \App\Models\CustomerEnquiry  $customerEnquiry
     * @return void
     */
    public function created(CustomerEnquiry $customerEnquiry)
    {
        try {
            $similarEnqueryCount = CustomerEnquiry::select(
                'id',
            )->where(
                'id',
                '!=',
                $customerEnquiry->id
            )->where([
                'user_id' => $customerEnquiry->user_id,
                'category_id' => $customerEnquiry->category_id,
                'sub_category_id' => $customerEnquiry->sub_category_id,
                'product_id' => $customerEnquiry->product_id,
                'product_quantity' => $customerEnquiry->product_quantity,
                'packing_type_id' => $customerEnquiry->packing_type_id,
                'packaging_material_id' => $customerEnquiry->packaging_material_id,
            ])->count();


            $creditAmountToDeduct = 0;
            if ($similarEnqueryCount == 0) {
                $creditAmountToDeduct = 1;
            }

            $userId = $customerEnquiry->user_id;

            $user = User::select('id', 'current_credit_amount')->where('id', $userId)->first();

            $currentCredit = $user->current_credit_amount;

            if ($currentCredit == 0) {
                $creditAmountToDeduct = 0;
            }

            $remaingCredit = $currentCredit - $creditAmountToDeduct;
            $user->update([
                'current_credit_amount' => $remaingCredit
            ]);
            // $user->save();
            UserCreditHistory::create(
                [
                    'user_id' => $customerEnquiry->user_id,
                    'amount' => $creditAmountToDeduct,
                    'reason' => __('my_profile.enquery_result_credit_deduct'),
                    'action' => 'deduct'
                ]
            );
        } catch (\Exception $e) {
            Log::error("Customer enquiry creation failed: " . $e->getMessage());
        }
    }

    /**
     * Handle the CustomerEnquiry "updated" event.
     *
     * @param  \App\Models\CustomerEnquiry  $customerEnquiry
     * @return void
     */
    public function updated(CustomerEnquiry $customerEnquiry)
    {
        //
    }

    /**
     * Handle the CustomerEnquiry "deleted" event.
     *
     * @param  \App\Models\CustomerEnquiry  $customerEnquiry
     * @return void
     */
    public function deleted(CustomerEnquiry $customerEnquiry)
    {
        //
    }

    /**
     * Handle the CustomerEnquiry "restored" event.
     *
     * @param  \App\Models\CustomerEnquiry  $customerEnquiry
     * @return void
     */
    public function restored(CustomerEnquiry $customerEnquiry)
    {
        //
    }

    /**
     * Handle the CustomerEnquiry "force deleted" event.
     *
     * @param  \App\Models\CustomerEnquiry  $customerEnquiry
     * @return void
     */
    public function forceDeleted(CustomerEnquiry $customerEnquiry)
    {
        //
    }
}
