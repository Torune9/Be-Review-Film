<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = auth()->user();

        $admin = Roles::where('id',$user->role_id)->first();

        if ($admin->name !== 'admin') {
            return response()->json(['message' => 'Unauthorized untuk mengakses/manipulasi data role'], 403);
        }
        return $next($request);
    }
}
