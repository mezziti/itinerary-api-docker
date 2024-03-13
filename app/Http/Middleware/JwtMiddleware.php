<?php

namespace App\Http\Middleware;

use App\Http\Traits\apiResponseTrait;
use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    use apiResponseTrait;

    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->apiErrorResponse('Token is Invalid', 401, 'error');
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->apiErrorResponse('Token is Expired', 401, 'error');
            }else{
                return $this->apiErrorResponse('Authorization Token not found', 401, 'error');
            }
        }
        return $next($request);
    }
}

