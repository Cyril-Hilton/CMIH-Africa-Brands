<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $rolesList = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $sub) {
                if (trim($sub) !== '') {
                    $rolesList[] = trim($sub);
                }
            }
        }

        $routeName = $request->route() ? (string) $request->route()->getName() : '';
        $isUserRoute = str_starts_with($routeName, 'admin.users');
        $isMerchandiserAdminRoute = $request->is('merchandisers/admin*')
            || str_starts_with($routeName, 'merchandisers.admin');
        $isDeveloperBypass = in_array(strtolower(trim($user->name)), ['cyril hilton', 'cyril hilton wemegah', 'curtis barnor', 'curtis banor'], true)
            && ! $user->hasRole(['admin', 'super_admin']);

        if (
            $user->hasRole($rolesList)
            || ($user->hasFullHrAccess() && $isUserRoute && ! $isDeveloperBypass)
            || ($isMerchandiserAdminRoute && $user->isMerchandiserPortalAdmin())
        ) {
            return $next($request);
        }

        abort(403);
    }
}
