<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use illuminate\Auth\Middleware\Authenticate as middleware;


class AdminAuthenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     */
    protected function redirectTo(Request $request):   ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }
}
   