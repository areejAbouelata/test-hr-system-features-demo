<?php

namespace Modules\Hr\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CheckPermission
{
	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next, $permission)
	{
	/* 	if (!Auth::check()) {
			return redirect()->route('login'); // Redirect to login page
		}
		$user_role = auth()->user()->roles;
		if (!Auth::user()->HasRole('admin') || !$role->hasPermissionTo($permission)) {
			abort(403, 'Unauthorized action.'); // Return 403 Forbidden error
		} */

		return $next($request);
	}
}
