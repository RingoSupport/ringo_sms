<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\ApiClient;
use Illuminate\Support\Facades\Hash;

class ClientAuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('client-auth.login');
    }

 public function store(Request $request): RedirectResponse
{
    $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    $username = $request->input('username');
    $password = $request->input('password');

    $client = ApiClient::query()
        ->where('username', $username)
        ->where('status', 'active')
        ->first();

    if (
        ! $client ||
        ! Hash::check($password, $client->portal_password)
    ) {
        return back()
            ->withErrors([
                'username' => 'Invalid credentials.',
            ])
            ->onlyInput('username');
    }

    Auth::guard('client')->login($client);

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
