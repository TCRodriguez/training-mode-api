<?php

use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('v1')->group(function () {
    Route::get('oauth/login/discord', [SocialAuthController::class, 'redirectToDiscord']);
    Route::get('oauth/discord/callback', [SocialAuthController::class, 'handleDiscordCallback']);
});

Route::get('/', function () {
    // return view('welcome');
});
