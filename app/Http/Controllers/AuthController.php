<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $email = $request->email;
        $user_with_email = User::where('email', $email)->first();

        if (!$user_with_email) {
            return response()->json([
                'message' => 'Email not found'
            ], 404);
        }

        if (!$user_with_email->hasVerifiedEmail()) {
            return response()->json(['message' => 'Please verify email address'], 403);
        }


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = Auth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $emailValidator = Validator::make($request->all(), [
            'email' => 'unique:users',
        ],
        $messages = [
            'unique' => 'The :attribute already exists',
        ]);

        if($emailValidator->fails()){
            return response()->json($emailValidator->errors()->toJson(), 409);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        event(new Registered($user));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        Auth::logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }

   

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(Request $request) {
        $authenticated_user = auth()->user();
        $authenticated_user_with_photo = User::with('photo')->get()->find($authenticated_user->id);

        return response()->json($authenticated_user_with_photo);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $authenticated_user = auth()->user();
        $authenticated_user_with_photo = User::with('photo')->get()->find($authenticated_user->id);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60 * 24 * 365,
            'user' => $authenticated_user_with_photo
        ]);
    }

    public function notifyForVerification(Request $request) {
        
    }

    public function verify(EmailVerificationRequest $request) {
        $request->fulfill();

        return response()->json(['message' => 'Email successfully verified'], 200);
    }
    
    public function resend($user_id, Request $request) {
        $authenticated_user = auth()->user();
        $authenticated_user_with_photo = User::findOrFail($authenticated_user->id);
        if ($authenticated_user_with_photo->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 400);
        }

        $authenticated_user_with_photo->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email sent to your email address'
        ], 200);
    }

    
}
