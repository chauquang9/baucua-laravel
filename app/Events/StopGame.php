<?php

namespace App\Events;

use App\Models\Baucua;
use App\Models\Game;
use App\Models\Order;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

/**
 *
 */
class StopGame implements ShouldBroadcastNow
{

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
        return 'stop-game';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [];
    }
}
