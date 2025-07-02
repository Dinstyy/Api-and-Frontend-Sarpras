<?php

namespace App\Http\Middleware;

use App\Custom\Formatter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RolePermission
{
public function handle(Request $request, Closure $next, ...$roles)
{
    // Fix: handle "siswa,guru" as single arg
    if (count($roles) === 1 && str_contains($roles[0], ',')) {
        $roles = array_map('trim', explode(',', $roles[0]));
    }

    $currentUser = Auth::guard("sanctum")->user();

    if (!$currentUser) {
        return Formatter::apiResponse(401, "Unauthorized: Please login first");
    }

    $userRole = $currentUser->role;

    if (is_null($userRole) || !in_array($userRole, $roles)) {
        return Formatter::apiResponse(403, "Access denied. Only users with roles: " . implode(', ', $roles) . " are allowed.");
    }

    $request->merge(["user" => $currentUser]);

    return $next($request);
}
}
