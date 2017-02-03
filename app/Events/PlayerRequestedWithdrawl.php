<?php

namespace Bsmma\Events;

use Bsmma\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PlayerRequestedWithdrawl extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $amount, $fundsMethod)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->fundsMethod = $fundsMethod;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
