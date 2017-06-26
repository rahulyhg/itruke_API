<?php

namespace App\Http\Middleware;

use Closure;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $appid = $request->header('appid');
        $ruke = $request->header('ruke');
        if ($appid != getenv('APPID') || $ruke != getenv('RUKE')) {
            return error('Bye',403);
        }
        return $next($request);
    }
}
