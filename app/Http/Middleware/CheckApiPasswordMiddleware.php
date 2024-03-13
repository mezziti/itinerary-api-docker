<?php

namespace App\Http\Middleware;

use App\Http\Traits\apiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiPasswordMiddleware
{
    use ApiResponseTrait;
    public function handle(Request $request, Closure $next): Response
    {
      if($request->apiPassword !== env('API_PASSWORD')) {
        return $this->apiErrorResponse('Unauthorize to use this Api', 401, 'error');
      }
      return $next($request);
    }
}
