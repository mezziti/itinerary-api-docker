<?php

namespace App\Http\Middleware;

use App\Http\Traits\apiResponseTrait;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminMiddleware
{
  use apiResponseTrait;

    public function handle(Request $request, Closure $next): Response
    {
      $user = null;
      try {
        $user = JWTAuth::parseToken()->authenticate();

      } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->apiResponse(NULL, 400, ['error' => 'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->apiResponse(NULL, 400, ['error' => 'Token is Expired']);
            }else{
                return $this->apiResponse(NULL, 400, ['error' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}
