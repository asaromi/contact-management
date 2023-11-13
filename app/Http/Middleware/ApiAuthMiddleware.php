<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        $authenticate = true;
        $user = null;

        if (!$token) $authenticate = false;
        else {
            $user = User::where('token', $token)->first();

            if (!$user) $authenticate = false;
        }

        if (!$authenticate) {
            return response()->json([
                'errors' => [
                    'message' => ['Unauthorized']
                ]
            ])->setStatusCode('401');
        }

        Auth::login($user->makeHidden('token'));
        return $next($request);
    }
}
