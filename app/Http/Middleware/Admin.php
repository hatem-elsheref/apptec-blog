<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->is_admin)
            return $next($request);


        return $request->expectsJson()
            ? redirect()->route('home')
            : response()->json(['message' => 'not allowed to access this resource'], Response::HTTP_FORBIDDEN);

    }
}
