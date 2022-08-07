<?php

namespace App\Http\Controllers;

use App\Events\BetLists;
use App\Models\Baucua;
use App\Models\Bet;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class BaucuaController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLists()
    {
        $baucua = Baucua::orderBy('position', 'ASC')->get()->toArray();

        return response()->json($baucua);
    }

    /**
     * @return void
     */
    public function startButton()
    {
        try {
            event(new \App\Events\StartGame());

            return response()->json(['message' => 'successfully']);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * @return void
     */
    public function stopButton()
    {
        try {
            event(new \App\Events\StopGame());

            return response()->json(['message' => 'successfully']);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * @return array|null
     */
    public function resultGame()
    {
        try {
            event(new \App\Events\ResultsGame());

            return response()->json(['message' => 'successfully']);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * @return array|null
     */
    public function statusGame()
    {
        try {
            $game = Game::where('is_finish', 0)->orderBy('id', 'DESC')->first();

            return response()->json([
                'message'    => 'successfully',
                'is_playing' => $game['is_playing'] ?? 0,
            ]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * @return void
     */
    public function getbet()
    {
        $game = Game::where('is_finish', 0)->orderBy('id', 'DESC')->first();
        if (empty($game)) {
            $game = Game::create([
                'is_playing' => 0,
                'is_finish'  => 0,
            ]);
        }
        $bets         = Bet::with('user')->where('game_id', $game->id)->get();
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
                'avatar'   => $bet->user->avatar,
            ];
        }

        return $dataResponse;
    }

    public function deletebet(Request $request)
    {
        $data      = $request->all();
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $gameId      = Game::where('is_playing', 0)->where('is_finish', 0)->first()->id;
            $bet         = Bet::where('user_id', $data['user_id'])->where('baucua_id', $data['item_id'])->where('game_id', $gameId)->first();
            $moneyBet    = $bet->money_bet;
            $user        = $request->user();
            $user->price = $user->price + $moneyBet;
            $user->save();
            $bet->delete();
            event(new BetLists($gameId));

            return response()->json([
                'message' => 'successfully',
                'money'   => $user->price,
            ]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }


    }

    /**
     * @return array|null
     */
    public function addbet(Request $request)
    {
        try {
            $user      = $request->user();
            $validator = Validator::make($request->all(), [
                'item_id' => 'required',
                'money'   => 'required|numeric|max:' . $user->price,
                'x'       => 'required',
                'y'       => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $currentGame = Game::where('is_playing', 1)->where('is_finish', 0)->first();
            if ($currentGame) {
                return response()->json(['message' => 'Game is playing, please wait.'], 400);
            }
            DB::beginTransaction();

            try {
                $game   = Game::updateOrCreate([
                    'is_playing' => 0,
                    'is_finish'  => 0,
                ], [
                    'is_playing' => 0,
                    'is_finish'  => 0,
                ]);
                $gameId = $game->id;
                $userId = $user->id;
                $itemId = $request->get('item_id');
                $oldBet = Bet::where('game_id', $gameId)->where('baucua_id', $itemId)->where('user_id', $userId)->first();
                if (!empty($oldBet) && $request->get('money') > ($oldBet->money_bet + $user->price)) {
                    $totalMoney = $oldBet->money_bet + $user->price;

                    return response()->json(['message' => 'The money must not be greater than ' . $totalMoney . '.'], 400);
                }
                $bet = Bet::updateOrCreate([
                    'game_id'   => $gameId,
                    'baucua_id' => $itemId,
                    'user_id'   => $userId,
                ], [
                    'money_bet' => $request->get('money'),
                    'x'         => $request->get('x'),
                    'y'         => $request->get('y'),
                ]);

                if (!$bet->wasRecentlyCreated && $bet->wasChanged()) {
                    // updateOrCreate performed an update
                    $money = ($user->price + $oldBet->money_bet) - $request->get('money');
                } else if ($bet->wasRecentlyCreated) {
                    // updateOrCreate performed create
                    $money = $user->price - $request->get('money');
                } else {
                    $money = $bet->money_bet;
                }

                $user->price = $money;
                $user->save();

                event(new BetLists($gameId));
                DB::commit();

                return response()->json([
                    'message' => 'successfully',
                    'money'   => $money,
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'message' => $e->getMessage(),
                    'money'   => $user->price,
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * @return mixed
     */
    public function topPlayer()
    {
        $topPlayerGame = User::orderBy('price', 'DESC')->limit(10)->select('id', 'name', 'price')->get()->toArray();

        return response()->json($topPlayerGame);
    }

    /**
     * @return void
     */
    public function statistics(Request $request)
    {
        try {
            $data      = [];
            $user      = $request->user();
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $totalGame = Game::where('is_playing', 1)->where('is_finish', 1)->count();
            $items     = Baucua::addSelect([
                'id',
                'name',
                DB::raw("(select count(*) from game where is_finish = 1 and is_playing = 1 and json_contains(game.results ,json_object('id', baucua.id))) as total"),
            ])
                               ->get()->pluck('total', 'name');

            $bets = Bet::where('user_id', $request->get('user_id') ?? $user->id)
                       ->with('user')
                       ->with('baucua')
                       ->limit(10)
                       ->orderBy('id', 'DESC');

            if ($request->get('item_id')) {
                $bets = $bets->where('baucua_id', $request->get('item_id'))
                             ->get()
                             ->toArray();
            } else {
                $bets = $bets->get()
                             ->toArray();
            }

            $data['totalGame'] = $totalGame;
            $data['items']     = $items;
            $data['bets']      = $bets;

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function filters(Request $request)
    {
        $filters = [
            'items' => Baucua::all(),
            'users' => User::all(),
        ];

        return response()->json($filters);
    }
}
