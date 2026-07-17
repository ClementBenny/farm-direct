<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FarmAuthenticatedSessionController extends Controller
{
    /**
     * Display the farm (admin/staff) login view.
     */
    public function create(): View
    {
        return view('auth.farm-login');
    }

    /**
     * Handle an incoming farm-side authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Reject anyone whose role isn't admin/staff — even though the
        // credentials were valid, this login is reserved for farm accounts.
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This login is reserved for farm staff and administrators.',
            ]);
        }

        $request->session()->regenerate();

        $destination = match (auth()->user()->role) {
            'admin' => route('admin.dashboard'),
            'staff' => route('staff.dashboard'),
        };

        return redirect()->intended($destination);
    }
}