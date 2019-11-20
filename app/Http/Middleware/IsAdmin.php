<?php

namespace App\Http\Middleware;

use Closure;
// eh IsLoggedIn sek
class IsAdmin
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
        if ($request->loggedin_role === null) {
            return abort(401, 'Unauthorized');
        } else if ($request->loggedin_role != 1) {
            return abort(403, 'Forbidden');
        }
        
        return $next($request);
    }
}
