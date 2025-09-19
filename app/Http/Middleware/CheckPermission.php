<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = auth()->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        // Super Admin has access to everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has permission for this module and action
        if (!$this->hasPermission($user, $module, $action)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak mempunyai kebenaran untuk mengakses sumber ini.',
                    'error' => 'Forbidden'
                ], 403);
            }
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }

        return $next($request);
    }

    /**
     * Check if user has permission for specific module and action
     */
    private function hasPermission($user, string $module, string $action): bool
    {
        // Get user's role
        $role = $user->role;
        
        if (!$role || !$role->permissions) {
            return false;
        }

        // Check if role has permission for this module and action
        // Permission values can be boolean true, string "1", or integer 1
        return isset($role->permissions[$module][$action]) &&
               in_array($role->permissions[$module][$action], [true, "1", 1], true);
    }
}
