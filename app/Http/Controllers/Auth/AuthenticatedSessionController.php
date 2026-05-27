<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\OtpService;


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
  public function store(
    LoginRequest $request,
    OtpService $otpService
): RedirectResponse
{
    $request->authenticate();

    $user = Auth::user();

    if ($user->status !== 'active') {

        Auth::logout();

        return back()->withErrors([
            'email' => 'Your account has been deactivated.',
        ]);
    }

    Auth::logout();

    $otpService->generate(
        $user,
        $request->ip()
    );

    $request->session()->put(
        'pending_otp_user_id',
        $user->id
    );

    return redirect()->route('otp.verify');
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
