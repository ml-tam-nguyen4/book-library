<?php

namespace App\Http\Middleware\Api;

use App\Model\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class LoginAPIMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request request
     * @param \Closure                 $next    next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accessToken = $request->headers->get('token');
        $user = User::where('access_token', '=', $accessToken)->firstOrFail();
        if (isset($user) && Carbon::parse($user->expired_at)->diffInSeconds(Carbon::now())) {
            return $next($request);
        }
        Auth::logout();
    }
}
