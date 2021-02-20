<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function update($user_id, Request $request) {
        $user = User::find($user_id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($request->input('name')) {
            $user->name = $request->input('name');
        }

        $photo = $request->input('photo');

        if ($photo) {
            $user->photo_id = $photo['id'];
            $user->save();
        }

        $user->save();
        
        return response()->json([
            'message' => 'User successfully updated',
            'user' => $user::with(['photo'])->get()->find($user->id)
            ]
            ,200);
    }
}
