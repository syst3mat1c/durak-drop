<?php

namespace Modules\Users\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Modules\Users\Entities\User;

class SocialiteExistsMiddleware
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return app('user-socialite')->existsPath($request->route('provider'))
            ? $next($request) : abort(404, 'Wrong Provider');
    }
}
