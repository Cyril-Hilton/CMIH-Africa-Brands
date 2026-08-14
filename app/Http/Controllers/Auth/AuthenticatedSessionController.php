<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if (! $user->isActive()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Your account is pending approval or has been suspended.',
            ]);
        }


        $previousLoginAt = $user->last_login_at;

        $user->forceFill([
            'previous_login_at' => $previousLoginAt,
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'last_login_user_agent' => substr((string) $request->userAgent(), 0, 255),
        ])->save();

        $welcomeMsg = 'Welcome back to the portal, ' . ($user->name ?: 'User') . '!';

        if ($request->filled('redirect_to')) {
            return redirect()->to($request->input('redirect_to'))->with('status', $welcomeMsg);
        }

        if ($request->session()->has('url.intended')) {
            return redirect()->intended()->with('status', $welcomeMsg);
        }

        if ($user->isMerchandiserSupervisor()) {
            return redirect()->route('merchandisers.admin.dashboard');
        }

        if ($user->isMerchandiserAccount()) {
            return redirect()->route('merchandisers.dashboard');
        }

        $assignment = \App\Models\BrandStaffAssignment::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($assignment) {
            $brand = $assignment->brand;
            $brandKey = $brand->slug ?: $brand->id;
            
            if (
                $assignment->role === \App\Models\BrandStaffAssignment::ROLE_RETAIL
                || $assignment->enrollment_type === \App\Models\BrandStaffAssignment::TYPE_RETAIL_TERMINAL
            ) {
                return redirect()->route('brands-platform.support', $brandKey);
            }

            if (
                $assignment->role === \App\Models\BrandStaffAssignment::ROLE_AGENCY
                || $assignment->role === \App\Models\BrandStaffAssignment::ROLE_ADMIN
                || $assignment->role === \App\Models\BrandStaffAssignment::ROLE_SUPERVISOR
                || $assignment->enrollment_type === \App\Models\BrandStaffAssignment::TYPE_AGENCY_STAFF
            ) {
                return redirect()->route('brands-platform.agency', $brandKey);
            }
            
            return redirect()->route('brands-platform.support', $brandKey);
        }

        if ($user->hasRole(['agency']) || $user->isCvoOrSuperAdmin() || $user->isLineManager()) {
            return redirect()->route('brands-platform.index');
        }

        $redirectTo = $user->hasRole(['admin', 'super_admin'])
            ? route('admin.dashboard', absolute: false)
            : route('dashboard', absolute: false);

        return redirect()->intended($redirectTo);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
