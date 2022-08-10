<?php

namespace App\Http\Controllers;

use App\Models\Baucua;
use App\Models\Request as ModelRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class RequestController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequests(Request $request)
    {
        $limit    = 2;
        $user     = $request->user();
        $requests = new ModelRequest;

        if ($user->email !== 'quang.chau@monimedia.com') {
            $requests = $requests->where('user_id', $user->id);
        }

        $totalRequest = clone $requests;
        $totalRequest = $totalRequest->count();

        if ($request->get('currentPage')) {
            $requests = $requests->skip(($request->get('currentPage') - 1) * $limit)->take($limit);
        }

        $requests = $requests->with('user')->orderBy('id', 'DESC')->get()->toArray();

        return response()->json([
            'requests'      => $requests,
            'total_request' => $totalRequest,
            'is_load_more'  => $totalRequest > $request->get('currentPage') * $limit ? TRUE : FALSE,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRequest(Request $request)
    {
        $user      = $request->user();
        $validator = Validator::make($request->all(), [
            'money' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $request = ModelRequest::create([
            'money'   => $request->get('money'),
            'status'  => 0,
            'user_id' => $user->id,
        ]);

        return response()->json($request);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatusRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|numeric',
            'id'     => 'required|exists:requests,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $dataRequest = ModelRequest::find($request->get('id'));
        if ($dataRequest->status != 0) {
            return response()->json(['message' => 'This request already apply before'], 400);
        }

        $dataRequest->status = $request->get('status');
        $dataRequest->save();

        if ($request->get('status') == 1) {
            $userUpdate        = User::find($dataRequest->user_id);
            $userUpdate->price = $userUpdate->price + $dataRequest->money;
            $userUpdate->save();

            event(new \App\Events\ApplyRequest($userUpdate));
        }

        return response()->json($dataRequest);
    }
}
