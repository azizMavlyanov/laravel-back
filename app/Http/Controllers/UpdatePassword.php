<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class UpdatePassword extends Controller
{
    public function forgotPassword(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response()->json([
                        'message' => 'Password reset instruction successfully sent',
                        'status' => __($status)
                    ], 200)
                    : response()->json([
                        'message' => 'Error occured',
                        'email' => __($status)
                    ], 400);
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
    
                $user->setRememberToken(Str::random(60));
    
                event(new PasswordReset($user));
            }
        );
    
        return $status == Password::PASSWORD_RESET
                    ? response()->json([
                        'message' => 'Password reset operation successfully completed',
                        'status' => __($status)
                    ], 200)
                    : response()->json([
                        'message' => 'Error occured',
                        'email' => __($status)
                    ], 400);
    }
}
