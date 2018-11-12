<?php

namespace App\Http\Middleware;

use Closure;

class GetApiHeader
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
        $user_info = $request->header('AUTH-USER') ? json_decode($request->header('AUTH-USER')) : [];
        $request->request->add(['logged_user' => $user_info]);
        return $next($request);
    }
}
