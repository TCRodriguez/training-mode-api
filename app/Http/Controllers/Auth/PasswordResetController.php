<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redis;

class PasswordResetController extends Controller
{
    
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
        ]);

        $email = $request->email;
        $returnURL = $request->returnURL;
        Redis::setex("password_reset_return_url:$email", 3600, $returnURL);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response()->json(['message' => 'Password reset link sent to email.'], 200)
                    : response()->json(['message' => 'Unable to send password reset link.'], 500);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => [
                'required',
                'min:8',
                'max:32',
                'regex:/^(?=.*[A-Z])(?=.*[!@#]).+$/',
            ],
        ], [
            'password.regex' => 'Your password must include at least one uppercase letter and one of the following special characters: !, @, #.',
        ]);


        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();

                $user->tokens()->delete(); 
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been successfully reset.']);
        } else {
            return response()->json(['error' => 'Failed to reset password, invalid token or email.'], 500);
        }




    }
}
