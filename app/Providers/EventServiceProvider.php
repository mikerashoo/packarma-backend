<?php

namespace App\Providers;

use App\Models\CustomerEnquiry;
use App\Models\User;
use App\Models\UserCreditHistory;
use App\Observers\CustomerEnqueryObserver;
use App\Observers\UserCreditHistoryObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        UserCreditHistory::observe(UserCreditHistoryObserver::class);
        CustomerEnquiry::observe(CustomerEnqueryObserver::class);
    }
}
