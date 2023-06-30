<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
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

        // * Check if the User's `roles` includes `admin`
        // if(! $user || $user->roles)
        if(! $user->roles->contains('name', 'admin')) {
            abort(401);
        }
    
    
        $token = $user->createToken('mobile app')->plainTextToken;

        $loggedInUser = [
            'token' => $token,
            'user' => $user
        ];
        
        return $loggedInUser;
    }
}
