<?php

namespace App\Http\Middleware;

use Closure;
use App\Branch;
use Illuminate\Support\Facades\Log;

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
        $user_info = $request->header('AUTH-USER') ? json_decode($request->header('AUTH-USER')) : new stdClass();
        $user_info->name = base64_decode($user_info->name);
        $branch = Branch::findOrfail($user_info->branch);
        $user_info->crm_owner = $branch->crm_owner;
        $request->request->add(['logged_user' => $user_info]);
        return $next($request);
    }
}
