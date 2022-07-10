<?php

namespace App\Http\Middleware;

use Closure;

class RedirectToRegistration
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

        if(!$request->has('user')){
            return redirect()->to('/register/newIntakes');
        }
        return $next($request);
    }
}
