<?php

use App\Http\Controllers\api\v1\CharacterController;
use App\Http\Controllers\api\v1\GameController;
use App\Http\Controllers\api\v1\GameNotationController;
use App\Http\Controllers\api\v1\DirectionalInputController;
use App\Http\Controllers\api\v1\AttackButtonController;
use App\Http\Controllers\api\v1\CharacterComboController;
use App\Http\Controllers\api\v1\CharacterMoveController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'delete']);

    Route::get('/games/{game}/characters/{character}/character-combos', [CharacterComboController::class, 'index']);
    Route::get('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'show']);
    Route::post('/games/{game}/characters/{character}/character-combos', [CharacterComboController::class, 'store']);
    Route::put('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'update']);
    Route::delete('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'delete']);

    Route::get('/games/{game}/characters/{character}/moves', [CharacterMoveController::class, 'index']);

    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{game}', [GameController::class, 'show']);
    // Route::get('/games/{game}/tags', )

    Route::get('/games/{game}/characters', [CharacterController::class, 'index']);
    Route::get('/games/{game}/characters/{character}', [CharacterController::class, 'show']);

    Route::get('/games/{game}/game-notations', [GameNotationController::class, 'index']);

    Route::get('/games/{game}/tags', [TagController::class, 'index']);
    Route::post('/games/{game}/characters/{character}/moves/{move}/tags', [CharacterMoveController::class, 'addCharacterMoveTag']);
    Route::delete('/games/{game}/characters/{character}/moves/{move}/tags/{tag}', [CharacterMoveController::class, 'removeCharacterMoveTag']);
    Route::post('/games/{game}/characters/{character}/combos/{combo}/tags', [CharacterComboController::class, 'addCharacterComboTag']);
    Route::delete('/games/{game}/characters/{character}/combos/{combo}/tags/{tag}', [CharacterComboController::class, 'removeCharacterComboTag']);

    Route::get('/directional-inputs', [DirectionalInputController::class, 'index']);
    Route::get('/games/{game}/attack-buttons', [AttackButtonController::class, 'index']);


});
