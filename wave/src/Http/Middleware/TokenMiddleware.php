<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Wave\ApiKey;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the token from Authorization header (Bearer token)
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['error' => true, 'message' => 'Missing API token'], 401);
        }

        // Look up the API token
        $apiToken = ApiKey::where('key', $bearerToken)->first();

        if (!$apiToken || !$apiToken->user) {
            return response()->json(['error' => true, 'message' => 'Invalid API token'], 401);
        }

        // Set the authenticated user in the request
        Auth::setUser($apiToken->user);

        return $next($request);
    }
}
