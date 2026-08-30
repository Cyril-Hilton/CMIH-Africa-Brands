<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMerchandiserFieldAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if ($user->isMerchandiserAccount()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(403, 'Field agent portal access is reserved for active merchandiser field agents.');
        }

        if ($user->isMerchandiserSupervisor()) {
            return redirect()
                ->route('merchandisers.supervisor.dashboard')
                ->withErrors(['portal' => 'Supervisor accounts now use the Supervisor Portal.']);
        }

        if ($user->isMerchandiserClient()) {
            return redirect()
                ->route('merchandisers.client.dashboard')
                ->withErrors(['portal' => 'Client accounts now use the Client / TM Portal.']);
        }

        if ($user->isMerchandiserPortalAdmin()) {
            return redirect()
                ->route('merchandisers.admin.dashboard')
                ->withErrors(['portal' => 'Admin accounts now use the Merchandiser Admin Hub.']);
        }

        return redirect()->route('dashboard');
    }
}
