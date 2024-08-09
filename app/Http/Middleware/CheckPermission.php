<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $admin = Auth::guard('admin')->user();

        // Get all permissions of the admin
        $roles = $admin->roles()->with('permissions')->get();
        $permissions = $roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');

        // Check if the admin has the required permission
        if ($permissions->contains('name', $permission)) {
            return $next($request);
        }
        
        if ($request->ajax()) {
            return response()->json(['message' => 'You are not authorized to perform this peration.'], 403);
        }

        return redirect()->back()->with('error_message', 'You are not authorized to perform this peration.');
    }
}
