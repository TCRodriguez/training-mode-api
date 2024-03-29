<?php

use App\Http\Controllers\AdminLoginController;
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
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\DeviceController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
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
    Route::post('/admin/login', [AdminLoginController::class, 'store']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'delete']);

    Route::middleware('throttle:5,1')->group(function (){
        Route::post('/login', [LoginController::class, 'store']);

        Route::post('/password/email-reset-link', [PasswordResetController::class, 'sendPasswordResetLink']);
        Route::post('password/reset', [PasswordResetController::class, 'resetPassword']);

        Route::post('/users/register', [UserController::class, 'registerUser']);
        Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])->name('verification.verify');
        Route::post('/email/resend-verification-email', [UserController::class, 'resendVerificationEmail'])->name('verification.resend');
    });

    // Games
    Route::get('/games/guest', [GameController::class, 'guestGameIndex']);
    // Route::get('/games/{game}', [GameController::class, 'show']);

    // Notations
    Route::get('/games/{game}/game-notations', [GameNotationController::class, 'index']);

    // Inputs
    Route::get('/directional-inputs', [DirectionalInputController::class, 'index']);
    Route::get('/directional-inputs/mappings/{device}', [DirectionalInputController::class, 'indexWithMappings']);
    Route::get('/games/{game}/attack-buttons', [AttackButtonController::class, 'index']);
    Route::get('games/{game}/device-input-mappings/{device}', [DeviceController::class, 'getGameDeviceInputMappings']);
    Route::get('games/{game}/attack-buttons/device-mappings/{device}', [AttackButtonController::class, 'indexWithDeviceMappings']);

    // Characters
    Route::get('/games/{game}/characters/guest', [CharacterController::class, 'characterIndexGuest']);
    Route::get('/games/{game}/characters/{character}', [CharacterController::class, 'show']);
    
    // Moves
    Route::get('/games/{game}/characters/{character}/moves/guest', [CharacterMoveController::class, 'guestCharacterMoveIndex']);

    // Devices and mappings
    Route::get('/devices', [DeviceController::class, 'index']);
    Route::get('/devices/{device}', [DeviceController::class, 'show']);
    Route::get('/devices/{device}/mappings/{game}/attack-buttons', [DeviceController::class, 'showWithAttackButtonMappings']);

    // Move data routes
    
    Route::put('/games/{game}/characters/{character}', [CharacterController::class, 'updateMoveData']);
    Route::put('/games/{game}/characters/{character}/moves/{move}', [CharacterMoveController::class, 'update']);
    
});

Route::get('/validate-token', [UserController::class, 'validateToken'])->prefix('v1');

Route::middleware(['auth:sanctum', 'check.token.expiry'])->group(function (){
    Route::prefix('v1')->group(function () {

        Route::get('/user', [UserController::class, 'show']);
        Route::post('/logout', [LoginController::class, 'destroy']);        

        // Games
        Route::get('/games', [GameController::class, 'index']);
        
        // Characters
        Route::get('/games/{game}/characters', [CharacterController::class, 'index']);

        // Moves
        Route::get('/games/{game}/characters/{character}/moves', [CharacterMoveController::class, 'index']);

        //Combos
        Route::get('/games/{game}/characters/{character}/character-combos', [CharacterComboController::class, 'index']);
        Route::get('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'show']);
        Route::post('/games/{game}/characters/{character}/character-combos', [CharacterComboController::class, 'store']);
        Route::put('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'update']);
        Route::delete('/games/{game}/characters/{character}/character-combos/{character_combo}', [CharacterComboController::class, 'delete']);

        // Tags
        Route::get('/games/{game}/tags', [TagController::class, 'index']);

        Route::get('/games/{game}/characters/{character}/tags', [TagController::class, 'characterMoveTagIndex']);

        // Move Tags
        Route::post('/games/{game}/characters/{character}/moves/{move}/tags', [CharacterMoveController::class, 'addCharacterMoveTag']);
        Route::delete('/games/{game}/characters/{character}/moves/{move}/tags/{tag}', [CharacterMoveController::class, 'removeCharacterMoveTag']);

        // Combo tags
        Route::post('/games/{game}/characters/{character}/combos/{combo}/tags', [CharacterComboController::class, 'addCharacterComboTag']);
        Route::delete('/games/{game}/characters/{character}/combos/{combo}/tags/{tag}', [CharacterComboController::class, 'removeCharacterComboTag']);

        // Note tags
        Route::post('/games/{game}/notes/{note}/tags', [NoteController::class, 'addNoteTag']);
        Route::delete('/games/{game}/notes/{note}/tags/{tag}', [NoteController::class, 'removeNoteTag']);
        // Route::post('/games/{game}/characters/{character}/notes/{note}/tags', [NoteController::class, 'addNoteTag']);
        // Route::post('/games/{game}/characters/{character}/moves/{move}/notes/{note}/tags', [NoteController::class, 'addNoteTag']);
        // Route::post('/games/{game}/characters/{character}/combos/{combo}/notes/{note}/tags', [NoteController::class, 'addNoteTag']);
        // Route::delete('/games/{game}/characters/{character}/combos/{combo}/notes/{note}/tags/{tag}', [NoteController::class, 'removeCharacterComboTag']);

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

        // Character Move Notes
        Route::post('/games/{game}/characters/{character}/moves/{move}/notes', [CharacterMoveController::class, 'addCharacterMoveNote']);
        Route::get('/games/{game}/characters/{character}/moves/{move}/notes', [NoteController::class, 'characterMoveNoteIndex']);
        Route::put('/games/{game}/characters/{character}/moves/{move}/notes/{note}', [NoteController::class, 'updateCharacterMoveNote']);
        Route::delete('/games/{game}/characters/{character}/moves/{move}/notes/{note}', [NoteController::class, 'deleteCharacterMoveNote']);

        // Character Combo Notes
        Route::post('/games/{game}/characters/{character}/combos/{combo}/notes', [CharacterComboController::class, 'addCharacterComboNote']);
        Route::get('/games/{game}/characters/{character}/combos/{combo}/notes', [NoteController::class, 'characterComboNoteIndex']);
        Route::put('/games/{game}/characters/{character}/combos/{combo}/notes/{note}', [NoteController::class, 'updateCharacterComboNote']);
        Route::delete('/games/{game}/characters/{character}/combos/{combo}/notes/{note}', [NoteController::class, 'deleteCharacterComboNote']);
    });
});
