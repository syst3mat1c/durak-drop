<?php

namespace App\Services\Billing\Middleware;

use Illuminate\Http\Request;
use Closure;

class BillingAccessMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
