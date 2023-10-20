<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{

    /**
     * Handle the User "retrieved" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function retrieved(User $user)
    {
        $totalCredits = $user->credit_totals;
        $currentCredit = $user->current_credit_amount;
        if ($currentCredit > $totalCredits) {
            $user->credit_totals = $currentCredit;
            $user->save();
        }
    }
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
