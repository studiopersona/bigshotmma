<?php

namespace Bsmma\Listeners;

use Bsmma\Events\UserRegistered;
use Bsmma\divStrong\Services\Mailchimp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AddNewUserToMailchimp
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mailchimp = new Mailchimp();
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
        $response = $this->mailchimp->addUserToList($event->user);

        $r = json_decode($response);

        if ( !$r->id ) Log::error('Mailchimp Add Result Error', ['response' => $response ]);
    }
}
