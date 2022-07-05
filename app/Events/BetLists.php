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
class BetLists implements ShouldBroadcastNow
{
    protected $gameId;

    /**
     *
     */
    public function __construct($gameId)
    {
        $this->gameId = $gameId;
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
        return 'bet-lists';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $bets         = Bet::with('user')->where('game_id', $this->gameId)->get();
        $dataResponse = [];
        foreach ($bets as $bet) {
            $dataResponse[] = [
                'itemId'   => $bet->baucua_id,
                'x'        => $bet->x,
                'y'        => $bet->y,
                'userId'   => $bet->user->id,
                'username' => $bet->user->name,
                'priceBet' => $bet->money_bet,
                'colorHex' => $bet->user->colorHex,
            ];
        }

        return [
            'betLists'  => $dataResponse,
            'topPlayer' => User::orderBy('price', 'DESC')->limit(10)->select('id', 'name', 'price')->get()->toArray(),
        ];
    }
}
