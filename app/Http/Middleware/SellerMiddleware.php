<?php

namespace App\Http\Middleware;

use App\Http\Traits\apiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    use apiResponseTrait;

    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role !== 'seller') {
            return $this->apiErrorResponse('unauthorized', 401, 'Error');
        }
        return $next($request);
    }
}
