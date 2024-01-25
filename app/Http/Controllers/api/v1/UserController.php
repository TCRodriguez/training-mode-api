<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
      public function index()
      {
         $users = User::all();

         return $users;
      }

      public function store(Request $request)
      {
         $request->validate([
            'username' => 'required|min:3|max:15',
            'email' => 'required|email:dns',
            'password' => [
               'required',
               'min:8',
               'max:32',
               'regex:/^(?=.*[A-Z])(?=.*[!@#]).+$/',
            ],
         ], [
            'password.regex' => 'Your password must include at least one uppercase letter and one of the following special characters: !, @, #.',
         ]);

         // $user = User::create([
         //    'username' => $request->username,
         //    'email' => $request->email,
         //    'password' => Hash::make($request->password),
         // ]);
         $user = User::make([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
         ]);
         
         return $user;
      }

      public function registerUser(Request $request)
      {
         $userNameExistenceCheck = User::where('username', $request->username)->doesntExist();
         if(!$userNameExistenceCheck) {
            return response()->json(['message' => 'Username is already taken.'], 409);
         }
         $userEmailExistenceCheck = User::where('email', $request->email)->doesntExist();
         if(!$userEmailExistenceCheck) {
            return response()->json(['message' => 'Email is already taken.'], 409);
         }

         $request->validate([
            'username' => 'required|min:3|max:15',
            'email' => 'required|email:dns',
            'password' => [
               'required',
               'min:8',
               'max:32',
               'regex:/^(?=.*[A-Z])(?=.*[!@#]).+$/',
            ],
         ], [
            'password.regex' => 'Your password must include at least one uppercase letter and one of the following special characters: !, @, #.',
         ]);

         $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
         ]);

         $userId = $user->id;
         Redis::set("user:{$userId}:return_url", $request->input('returnURL'), 'EX', 3600); // Expires in 1 hour

         Mail::to($user->email)->send(new VerifyEmail($user));
         
         return response()->json(['message' => 'Check your email for a verification link.']);
         
      }

      public function verifyEmail(Request $request)
      {
         try {
            $user = User::findOrFail($request->route('id'));

            if ($user->hasVerifiedEmail()) {
               return response()->json(['message' => 'Email is already verified.'], 200);
            }

            if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
               return response()->json(['message' => 'Invalid verification link.'], 401);
               throw new AuthorizationException();
            }
      
            if ($user->markEmailAsVerified()) {
                  event(new Verified($user));
            }

            $userId = $user->id;
            $returnURLRedisKey = "user:{$userId}:return_url";
            $returnURL = Redis::get($returnURLRedisKey);
            if (!$returnURL) {
               $returnURL = 'http://127.0.0.1:5173/'; // Fallback URL
            }

            $queryParams = http_build_query([
                  'status' => $user->hasVerifiedEmail() ? 'success' : 'failed',
                  'message' => $user->hasVerifiedEmail() ? 'Email successfully verified.' : 'Invalid or expired link.'
            ]);

            Redis::del($returnURLRedisKey);
         
            return redirect($returnURL . '?' . $queryParams);

         } catch (InvalidSignatureException $e) {
            return response()->json(['message' => 'Verification link is invalid or has expired.'], 401);
         }
      }

      public function resendVerificationEmail(Request $request)
      {
         $user = User::where('email', $request->email)->firstOrFail();

         if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email is already verified.'], 400);
         }

         Mail::to($user->email)->send(new VerifyEmail($user));

         return response()->json(['message' => 'Verification email resent.']);
      }

      public function validateToken(Request $request)
      {
         $bearerToken = $request->bearerToken();
         if (!$bearerToken) {
            return response()->json(['message' => 'No token provided'], 401);
         }

         [$tokenId, ] = explode('|', $bearerToken, 2);
         $token = PersonalAccessToken::find($tokenId);

         if (!$token || !$token->tokenable) {
            return response()->json(['message' => 'Invalid token'], 401);
         }
   
         if ($token->expires_at && now()->greaterThan($token->expires_at)) {
            return response()->json(['message' => 'Token expired'], 401);
         }
   
         // Token is valid
         return response()->json(['message' => 'Token is valid']);
      }

      public function show(Request $request)
      {
         return $request->user();
      }

      public function update(Request $request, $id)
      {
         return 'PUT Users endpoint HIT'; 
      }

      public function delete($id)
      {
         return 'DELETE User endpoint HIT'; 
      }
}
