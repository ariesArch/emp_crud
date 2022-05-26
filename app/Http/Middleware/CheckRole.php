<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
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
        $employee = auth()->guard('employee-api')->user();

        if ($request->user() === null) {
            return response(['status' => 2, 'message' => 'User have not permission for this page access.'], Response::HTTP_OK);
        }
        $actions = $request->route()->getAction();
        $roles = isset($actions['roles']) ? $actions['roles'] : null;
        if (in_array($employee->role->name, $roles)) {
            return $next($request);
        }
        // if ($employee->hasAnyRole($roles) || !$roles) {
        //     return $next($request);
        // }
        return response(['status' => 2, 'message' => 'User have not permission for this page access.'], Response::HTTP_OK);
    }
}
