<?php

namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Support\Facades\Cache;

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
        $token = $request->header('token');
        $path = $request->path();
//        return error(Cache::get('token'));
        if (explode('/', $path)[0] == 'admin' && explode('/', $path)[1] != 'login') {
            if (empty($token)) {
                return error('Bye', 401);
            }
            if (Cache::get('token') != $token) {
                return error('Bye', 401);
            }
        }
        $ret = Domain::where('domain', $domain)->where('appid', $appid)->where('ruke', $ruke)->where('status', 2)->first();
        if (!$ret) {
//            return error('Bye', 401);
        }
        return $next($request);
    }
}
