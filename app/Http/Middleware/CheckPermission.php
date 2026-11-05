<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(protected PermissionService $permissions)
    {
    }

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        [$module, $action] = array_pad(explode('.', $permission, 2), 2, null);

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($module !== '*') {
            $has = $action !== null
                ? $this->permissions->can($user, $module, $action)
                : $this->permissions->canAccessAdminPanel($user);

            if (!$has) {
                return response()->json(['message' => "Missing permission: {$module}.{$action}."], 403);
            }
        }

        return $next($request);
    }
}