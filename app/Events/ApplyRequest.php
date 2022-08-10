<?php

namespace App\Events;

use App\Models\Baucua;
use App\Models\Bet;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

/**
 *
 */
class ApplyRequest implements ShouldBroadcastNow
{
    /**
     * @var
     */
    protected $user;

    /**
     *
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PrivateChannel
     */
    public function broadcastOn()
    {
        return 'baucua-channel';
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'apply-request';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => 'admin accepted request',
            'userId'  => $this->user->id,
        ];
    }
}
