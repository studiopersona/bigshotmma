<?php

namespace Bsmma\Listeners;

use Bsmma\Events\PlayerRequestedWithdrawl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWithdrawlRequestNotice
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PlayerRequestedWithdrawl  $event
     * @return void
     */
    public function handle(PlayerRequestedWithdrawl $event)
    {
        Mail::send('emails.withdrawl-request', ['user' => $event->user, 'amount' => $event->amount, 'fundsMethod' => $event->fundsMethod], function ($m) {
            $m->from('admin@bsmma.com', 'Big Shot MMA');

            $m->to('jim@divstong.com', 'James Doyle')->subject('Withdrawl Request from Big Shot MMA');
        });

        $user = $event->user;

        Mail::send('emails.confirm-withdrawl-request', ['user' => $event->user, 'amount' => $event->amount], function ($m) use ($user) {
            $m->from('admin@bigshotmma.com', 'Big Shot MMA');

            $m->to($user->email, $user->firstname.' '.$user->lastname)->subject('Withdrawl Request Received');
        });
    }
}
