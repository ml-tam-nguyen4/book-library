<?php

namespace App\Http\Middleware\Api;

use App\Model\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Response;
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
        if ($accessToken != null) {
            $user = User::where('access_token', '=', $accessToken)
                        ->firstOrFail();
            if (Carbon::parse($user->expired_at) > Carbon::now()) {
                return $next($request);
            } else {
                return response()->json([
                    'meta' => [
                        'code' => 440,
                        'message' => config('define.messages.440_login_timeout')
                    ]
                ]);
            }
        }
        return response()->json([
            'meta' => [
                'code' => Response::HTTP_NOT_FOUND,
                'message' => config('define.messages.token_not_found')
            ]
        ]);
    }
}