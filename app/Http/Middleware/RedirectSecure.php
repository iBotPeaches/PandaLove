<?php

namespace PandaLove\Http\Middleware;

use Closure;

class RedirectSecure
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
        if(! $request->secure() && ! config('app.debug')) {
            return redirect()->secure($request->path());
        }
        return $next($request);
    }
}
