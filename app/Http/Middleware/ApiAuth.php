<?php

namespace App\Http\Middleware;

use App\Support\Response\Response;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Closure;
use Illuminate\Http\Request;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = new Response();

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $exception) {
            return $response->setCode(401)->setMessage('Token is Invalid')->respond();
        } catch (TokenExpiredException $exception) {
            return $response->setCode(401)->setMessage('Token is Expired')->respond();
        } catch (\Throwable $exception) {
            return $response->setCode(401)->setMessage('Token not found')->respond();
        }

        return $next($request);
    }
}
