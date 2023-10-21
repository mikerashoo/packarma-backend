<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserCreditHistory;

class UserCreditHistoryObserver
{
    /**
     * Handle the UserCreditHistory "retrieved" event.
     *
     * @param  \App\Models\UserCreditHistory  $userCreditHistory
     * @return void
     */
    public function retrieved(UserCreditHistory $userCreditHistory)
    {
        $expireDate = $userCreditHistory->expire_date;
        if ($expireDate == null) {
            $user = User::select('subscription_end')->where('id', $userCreditHistory->user_id)->first();
            if ($user) {
                $userSubscriptionEnd = $user->subscription_end;
                $userCreditHistory->update([
                    'expire_date' => $userSubscriptionEnd
                ]);
            }
        }
    }

    /**
     * Handle the UserCreditHistory "created" event.
     *
     * @param  \App\Models\UserCreditHistory  $userCreditHistory
     * @return void
     */
    public function created(UserCreditHistory $userCreditHistory)
    {
        //
    }

    /**
     * Handle the UserCreditHistory "updated" event.
     *
     * @param  \App\Models\UserCreditHistory  $userCreditHistory
     * @return void
     */
    public function updated(UserCreditHistory $userCreditHistory)
    {
        //
    }

    /**
     * Handle the UserCreditHistory "deleted" event.
     *
     * @param  \App\Models\UserCreditHistory  $userCreditHistory
     * @return void
     */
    public function deleted(UserCreditHistory $userCreditHistory)
    {
        //
    }

    /**
     * Handle the UserCreditHistory "restored" event.
     *
     * @param  \App\Models\UserCreditHistory  $userCreditHistory
     * @return void
     */
    public function restored(UserCreditHistory $userCreditHistory)
    {
        //
    }

    /**
     * Handle the UserCreditHistory "force deleted" event.
     *
     * @param  \App\Models\UserCreditHistory  $userCreditHistory
     * @return void
     */
    public function forceDeleted(UserCreditHistory $userCreditHistory)
    {
        //
    }
}
