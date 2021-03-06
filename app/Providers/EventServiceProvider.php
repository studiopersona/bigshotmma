<?php

namespace Bsmma\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Bsmma\Events\UserRegistered' => [
            'Bsmma\Listeners\AddFundsToUserAccount',
            'Bsmma\Listeners\AddNewUserToMailchimp',
        ],
        'Bsmma\Events\PlayerRequestedWithdrawl' => [
            'Bsmma\Listeners\SendWithdrawlRequestNotice'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
