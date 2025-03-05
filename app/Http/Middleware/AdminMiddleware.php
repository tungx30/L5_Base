<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponseTrait;

class AdminMiddleware
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user || !$user instanceof \App\Models\Admin) {
            return $this->errorResponse('Unauthorized: Admin access only', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
