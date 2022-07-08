<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @return void
     */
    public function update(Request $request)
    {
        $data      = $request->all();
        $validator = Validator::make($data, [
            'avatar' => 'file|mimes:jpg,bmp,png|max:2048',
            'email'  => 'email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $user = $request->user();

        if ($request->file('avatar')) {
            $file     = $request->file('avatar');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $user->avatar = $filename;
        }

        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }

        if (!empty($data['name'])) {
            $user->name = $data['name'];
        }

        $user->save();

        return $user;
    }
}
