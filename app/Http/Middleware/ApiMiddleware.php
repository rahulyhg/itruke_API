<?php

namespace App\Http\Middleware;

use App\Models\Domain;
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
        if ($request->isMethod('OPTIONS')) {
            return;
        }
        $appid = $request->header('appid');
        $ruke = $request->header('ruke');
        $domain = $request->header('origin');
        $ret = Domain::where('domain', $domain)->where('appid', $appid)->where('ruke', $ruke)->where('status', 2)->first();
        if (!$ret) {
            return error('Bye', 401);
        }
        return $next($request);
    }
}
