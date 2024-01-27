<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class CheckTokenExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $bearerToken = $request->bearerToken();
        $tokenId = Str::before($bearerToken, '|');
    
        $token = PersonalAccessToken::find($tokenId);
        
        if ($token && $token->expires_at && $token->expires_at->isPast()) {
            try {
                $token->delete();
            } catch (Exception $e) {
                Log::error('Failed to delete expired token: ' . $e->getMessage());
    
                return response()->json(['message' => 'Error processing token'], 500);
            }
            return response()->json(['message' => 'Token expired'], 401);
        }

        return $next($request);
    }
}
