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
use App\Http\Controllers\api\v1\LoginController;
use App\Http\Controllers\api\v1\NoteController;
use App\Http\Controllers\api\v1\TagController;
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

Route::prefix('v1')->group(function () {
    Route::post('/login', [LoginController::class, 'store']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'delete']);

    // Games
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{game}', [GameController::class, 'show']);

    // Notations
    Route::get('/games/{game}/game-notations', [GameNotationController::class, 'index']);

    // Inputs
    Route::get('/directional-inputs', [DirectionalInputController::class, 'index']);
    Route::get('/games/{game}/attack-buttons', [AttackButtonController::class, 'index']);

    // Moves
    Route::get('/games/{game}/characters/{character}/moves', [CharacterMoveController::class, 'index']);

    // Characters
    Route::get('/games/{game}/characters', [CharacterController::class, 'index']);
    Route::get('/games/{game}/characters/{character}', [CharacterController::class, 'show']);


});


Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('v1')->group(function () {

        //Combos
        Route::get('/games/{game}/characters/{character}/character-combos', [CharacterComboController::class, 'index']);
        Route::get('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'show']);
        Route::post('/games/{game}/characters/{character}/character-combos', [CharacterComboController::class, 'store']);
        Route::put('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'update']);
        Route::delete('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'delete']);

        // Tags
        Route::get('/games/{game}/tags', [TagController::class, 'index']);
        Route::post('/games/{game}/characters/{character}/moves/{move}/tags', [CharacterMoveController::class, 'addCharacterMoveTag']);
        Route::delete('/games/{game}/characters/{character}/moves/{move}/tags/{tag}', [CharacterMoveController::class, 'removeCharacterMoveTag']);
        Route::post('/games/{game}/characters/{character}/combos/{combo}/tags', [CharacterComboController::class, 'addCharacterComboTag']);
        Route::delete('/games/{game}/characters/{character}/combos/{combo}/tags/{tag}', [CharacterComboController::class, 'removeCharacterComboTag']);

        // Game Notes
        Route::get('/games/{game}/notes', [NoteController::class, 'gameNoteIndex']);
        Route::post('/games/{game}/notes', [GameController::class, 'addNote']);
        Route::put('/games/{game}/notes/{note}', [NoteController::class, 'updateGameNote']);
        Route::delete('/games/{game}/notes/{note}', [NoteController::class, 'deleteGameNote']);

        // Character Notes
        Route::post('/games/{game}/characters/{character}/notes', [CharacterController::class, 'addNote']);
        Route::get('/games/{game}/characters/{character}/notes', [NoteController::class, 'characterNoteIndex']);
        Route::put('/games/{game}/characters/{character}/notes/{note}', [NoteController::class, 'updateCharacterNote']);
        Route::delete('/games/{game}/characters/{character}/notes/{note}', [NoteController::class, 'deleteCharacterNote']);
    });
});
