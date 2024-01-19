<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    //

    public function store (Request $request)
    {
        // return $request;

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->with('roles')->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not yet verified.'], 403);
        }
        
        $token = $user->createToken('mobile app')->plainTextToken;

        $loggedInUser = [
            'token' => $token,
            'user' => $user
        ];
        
        return $loggedInUser;
    }
}
