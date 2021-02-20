<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function emailVerify(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('success_verification');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('success_verification');
    }

    public function resend($email, Request $request) {
        // $request->user()->sendEmailVerificationNotification();
        // return back()->with('message', 'Verification link sent!');
        $user_with_email = User::where('email', $email)->first();

        if (!$user_with_email) {
            return response()->json([
                'message' => 'Email not found'
            ], 404);
        }

        if ($user_with_email->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 400);
        }

        $user_with_email->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email sent to your email address'
        ], 200);
    }

    public function getVerificationReponse(Request $request) {
        return response()->json(["message" => "Email successfully verified"], 200);
    }
}
