<?php

namespace App\Events;

use App\Models\Baucua;
use App\Models\Bet;
use App\Models\Game;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class ResultsGame implements ShouldBroadcastNow
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
        return 'results-game';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $items     = Baucua::get()->toArray();
        $count     = count($items) - 1;
        $results[]    = $items[rand(0, $count)];
        $results[]    = $items[rand(0, $count)];
        $results[]    = $items[rand(0, $count)];
        $itemIds      = array_column($results, 'id');
        $game         = Game::where('is_finish', 0)->first();
        $dataMoneyWon = [];
        if ($game) {
            $bets      = Bet::with('user')->where('game_id', $game->id)->get()->toArray();
            $dataMoney = [];
            foreach ($bets as $bet) {
                $userId             = $bet['user']['id'];
                $itemId             = $bet['baucua_id'];
                $dataMoney[$userId] = $dataMoney[$userId] ?? $bet['user']['price'];
                $isBetCorrect       = 0;
                $isBetNotCorrect    = 0;
                foreach ($itemIds as $id) {
                    if ($id == $itemId) {
                        $isBetCorrect++;
                    } else {
                        $isBetNotCorrect++;
                    }
                }

                if ($isBetCorrect > 0) {
                    $dataMoney[$userId]    = $dataMoney[$userId] + ($bet['money_bet'] * $isBetCorrect) + $bet['money_bet'];
                    $dataMoneyWon[$userId] = ($dataMoneyWon[$userId] ?? 0) + ($bet['money_bet'] * $isBetCorrect);
                    Log::info('won'.$dataMoneyWon[$userId]);
                } else if ($isBetNotCorrect > 0 && $isBetCorrect == 0) {
                    $dataMoneyWon[$userId] = ($dataMoneyWon[$userId] ?? 0) - $bet['money_bet'];
                    Log::info('fail'.$dataMoneyWon[$userId]);
                } else if ($dataMoney[$userId] === $bet['user']['price']) {
                    unset($dataMoney[$userId]);
                }
            }

            $dataUpsert = [];
            foreach ($dataMoney as $userId => $moneyBet) {
                $dataUpsert[] = [
                    'id'    => $userId,
                    'price' => $moneyBet,
                ];
            }

            if (!empty($dataUpsert)) {
                User::upsert($dataUpsert, ['id'], ['price']);
            }

            $game->results = json_encode($results);
            $game->is_finish = 1;
            $game->save();

            Game::create([
                'is_playing' => 0,
                'is_finish'  => 0,
            ]);
        }

        $topPlayerGame = User::orderBy('price', 'DESC')->limit(10)->select('id', 'name', 'price')->get()->toArray();

        $dataResponse = [
            'results'       => $results,
            'dataMoney'     => $dataMoney ?? [],
            'topPlayerGame' => $topPlayerGame,
        ];

        if (!empty($dataMoneyWon)) {
            $dataMoneyWon                          = array_filter($dataMoneyWon, function ($v, $k) {
                return $v > 0;
            }, ARRAY_FILTER_USE_BOTH);
            $dataResponse['congratulationUserIds'] = $dataMoneyWon;
        }

        return $dataResponse;
    }
}
