<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    //

    public function store (Request $request)
    {
        $request->validate([
            // 'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
        ]);
    
        $user = User::where('username', $request->username)->with('roles')->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not yet verified.'], 403);
        }



        
        // $token = $user->createToken('Personal Access Token')->plainTextToken->update([
        //     'expires_at' => now()->addHours(24)
        // ]);


        $newAccessToken = $user->createToken('Personal Access Token');
        $plainTextToken = $newAccessToken->plainTextToken;
        [$tokenId, ] = explode('|', $plainTextToken, 2);
        $token = PersonalAccessToken::find($tokenId);
        $token->expires_at = now()->addHours(24);
        $token->save();




        $loggedInUser = [
            'token' => $plainTextToken,
            'user' => $user
        ];
        
        return $loggedInUser;
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out.'], 200);
    }
}
