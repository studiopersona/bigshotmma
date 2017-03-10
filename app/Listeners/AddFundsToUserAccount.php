<?php

namespace Bsmma\Listeners;

use Bsmma\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Bsmma\UserBalance;

class AddFundsToUserAccount
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserBalance $userBalance)
    {
        $this->userBalance = $userBalance;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        // $this->userBalance->insert(['user_id'=>$event->user->id, 'amount'=>100, 'transaction_type_id'=>4]);
    }
}
