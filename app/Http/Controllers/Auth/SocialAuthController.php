<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToDiscord(Request $request)
    {
        // $state = $request->state;
        // Cache::put("{$state}:redirect_url", $request->redirectURL, now()->addMinutes(5));
        // dd($request->query('redirectURL'));
        Session::put('redirectURL', $request->query('redirectURL'));
        Log::info('Redirecting to Discord');
        return Socialite::driver('discord')->redirect();
    }

    public function handleDiscordCallback()
    {
        Log::info('Handling Discord callback');
        $discordUser = Socialite::driver('discord')->user();
        
        $userSocialAccount = UserSocialAccount::where('provider', 'discord')
                                            ->where('provider_id', $discordUser->getId())
                                            ->first();

        if ($userSocialAccount) {
            // User exists, log them in
            $user = $userSocialAccount->user;
        } else {
            if (User::where('username', $discordUser->getName())->exists()) {
                // $redirectURL = Session::get('redirectURL') ?? env('FRONT_END_URL');
                // return response()->json([], 409);
                $user = User::create([
                    // Set user fields as needed, e.g., name, email
                    'username' => $discordUser->getName() . "#" . $this->generateRandomNumber(4),
                    'email' => $discordUser->getEmail(),
                    'password' => Hash::make($this->generateRandomPassword())
                    // other fields...
                ]);
                
                // $queryParams = http_build_query([
                //     'message' => 'We\'ve added a unique identifier to your username to avoid conflicts.',
                //     'error' => 'Discord username already in use.'
                // ]);
                // return redirect()->to($redirectURL . '?' . $queryParams);

            } else {
                $user = User::create([
                    'username' => $discordUser->getName(),
                    'email' => $discordUser->getEmail(),
                    'password' => Hash::make($this->generateRandomPassword())
                ]);
            }
    
            // Create social account link
            $user->socialAccounts()->create([
                'provider' => 'discord',
                'provider_id' => $discordUser->getId(),
                'provider_username' => $discordUser->getName()
            ]);
        }

        $newAccessToken = $user->createToken('Personal Access Token');
        $plainTextToken = $newAccessToken->plainTextToken;
        [$tokenId, ] = explode('|', $plainTextToken, 2);
        $token = PersonalAccessToken::find($tokenId);
        $token->expires_at = now()->addHours(24); // Set token expiration
        $token->save();

        // $redirectUrl = 'https://app.trainingmode.gg';
        // $redirectUrl = env('FRONT_END_URL');
        // $redirectURL = Cache::get('user:{$request->state}:redirect_url');
        $redirectURL = Session::get('redirectURL');
        $queryParams = http_build_query([
            'access_token' => $plainTextToken,
            'token_type' => 'Bearer',
            'user_id' => $user->id, 
            'category' => 'oauth_callback'
        ]);
        return redirect()->to($redirectURL . '?' . $queryParams);
        
        // return response()->json([
        //     'access_token' => $plainTextToken,
        //     'token_type' => 'Bearer',
        //     'user' => $user
        // ]);
    }

    public function generateRandomPassword($length = 12) {
        if ($length < 8 || $length > 32) {
            throw new \InvalidArgumentException('Password length must be between 8 and 32 characters.');
        }
    
        $upperLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerLetters = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#'; // Only the special characters specified in your rule
    
        // Ensure the password contains at least one character from each required set
        $password = $upperLetters[rand(0, 25)] 
                    . $specialChars[rand(0, strlen($specialChars) - 1)]
                    . $numbers[rand(0, 9)];
    
        // Fill the rest of the password length with a mix of all characters
        $allChars = $upperLetters . $lowerLetters . $numbers . $specialChars;
        $remainingLength = $length - 3; // Adjust for the already added characters
    
        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }
    
        // Shuffle the password to mix the characters
        $password = str_shuffle($password);
    
        return $password;
    }

    public function generateRandomNumber($length) {
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        return random_int($min, $max);
    }
}
