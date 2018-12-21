<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Setting;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $module = null, $action='')
    {
        $action = $action ? array($action) : array();
        if( has_access($module, $action) ) {            
            return $next($request);
        }

        return redirect()->route('backend.general.dashboard');

    }
}
