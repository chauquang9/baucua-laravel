<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    /**
     * @return void
     */
    public function changePassword(Request $request)
    {
        $data      = $request->all();
        $user      = $request->user();
        $validator = Validator::make($data, [
            'old_password'              => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'new_password'              => 'required|same:confirmation_new_password',
            'confirmation_new_password' => 'required',
        ], [
            'new_password.same' => 'New password and confirm new password must match.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return $user;
    }
}
