<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolverPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->user()->hasPermissionTo('view_resolver_panel')){
            return redirect()->route('home');
        }
        return $next($request);
    }
}
