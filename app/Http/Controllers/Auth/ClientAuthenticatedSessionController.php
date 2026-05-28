<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientAuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('client-auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('client')->attempt($credentials)) {

            return back()->withErrors([
                'username' => 'Invalid credentials.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->route('client.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('client')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }
}
