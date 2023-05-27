<?php

namespace App\Support\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class Start
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): RedirectResponse|Response
    {
        if (auth()->user()?->hasRole('admin')) {
            return Redirect::route('dashboard.index')->with('user_role', 'admin');
        }
        if (auth()->user()?->hasRole('client')) {
            return Redirect::route('showcase.index')->with('user_role', 'client');
        }
        return $next($request);
    }
}
