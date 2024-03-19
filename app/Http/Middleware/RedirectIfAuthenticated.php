<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (Auth::user()->role == USER_ROLE_SUPER_ADMIN) {
                    return redirect(route('super-admin.dashboard'));
                }else if (Auth::user()->role == USER_ROLE_ADMIN || Auth::user()->role == USER_ROLE_TEAM_MEMBER) {
                    return redirect(route('admin.dashboard'));
                }else if (Auth::user()->role == USER_ROLE_CLIENT) {
                    return redirect(route('user.dashboard'));
                } else {
                    Auth::logout();
                    return redirect("login")->with('error', __('Invalid user'));
                }
            }
        }
        return $next($request);
    }
}
