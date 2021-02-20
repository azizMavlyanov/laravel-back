<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UpdatePassword;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function(Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);//->middleware('verified');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::get('/email/verify', [AuthController::class, 'notifyForVerification'])->name('verification.notice');
// Verify email
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'emailVerify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
// Resend email 
Route::post('/email/verify/resend/{email}', [VerificationController::class, 'resend'])->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');
Route::get('/success/verication', [VerificationController::class, 'getVerificationReponse'])->name('success_verification');


Route::post('/forgot-password', [UpdatePassword::class, 'forgotPassword']);
Route::post('/reset-password', [UpdatePassword::class, 'updatePassword']);

Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{id}', [ArticleController::class, 'show']);

// JWTMiddleware protects below-mentioned endpoints
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::patch('users/{id}', [UserController::class, 'update']);

    Route::post('articles', [ArticleController::class, 'store']);
    Route::put('articles/{article}', [ArticleController::class, 'update']);
    Route::delete('articles/{article}', [ArticleController::class, 'delete']);

    Route::get('photos', [PhotoController::class, 'index']);
    Route::get('photos/{photo}', [PhotoController::class, 'show']);
    Route::post('photos', [PhotoController::class, 'store']);
    Route::put('photos/{photo}', [PhotoController::class, 'update']);
    Route::delete('photos/{photo}', [PhotoController::class, 'delete']);
});