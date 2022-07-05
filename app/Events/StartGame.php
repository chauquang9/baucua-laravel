<?php

namespace App\Events;

use App\Models\Baucua;
use App\Models\Game;
use App\Models\Order;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

/**
 *
 */
class StartGame implements ShouldBroadcastNow
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
        return 'start-game';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $game = Game::where('is_finish', 0)->where('is_playing', 0)->first();
        $game->is_playing = 1;
        $game->save();

        return [];
    }
}
