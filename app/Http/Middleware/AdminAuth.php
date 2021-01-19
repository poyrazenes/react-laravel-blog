<?php

namespace App\Http\Middleware;

use App\Support\Response\Response;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = new Response();

        if (!auth()->user()->is_admin) {
            return $response->setCode(401)
                ->setMessage('Yetkisiz deneme!')->respond();
        }

        return $next($request);
    }
}
