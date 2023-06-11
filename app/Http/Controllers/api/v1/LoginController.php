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
        // return 'LOGIN';
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'device_name' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        $token = $user->createToken('mobile app')->plainTextToken;

        // $loggedInUser = [
        //     'token' => new TokenResource($token),
        //     'trainer' => new TrainerResource($trainer)
        // ];
        $loggedInUser = [
            'token' => $token,
            'user' => $user
        ];
        // return new TokenResource($token);
        return $loggedInUser;
    }
}
