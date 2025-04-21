<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class CheckRole
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse('Unauthorized', 401);
        }

        if (!$user->role) {
            return $this->errorResponse('User has no role assigned', 403);
        }

        if (!in_array(strtolower($user->role->name), $roles)) {
            return $this->errorResponse('Forbidden', 403);
        }

        return $next($request);
    }
}
