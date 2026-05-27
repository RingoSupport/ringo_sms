<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\OtpService;

class OtpController extends Controller
{
    public function create(Request $request): View|RedirectResponse
{
    if (Auth::check()) {

        return redirect()->route('dashboard');
    }

    return view('auth.verify-otp');
}

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

       $userId = (int) $request->session()->get('pending_otp_user_id');

       $user = User::find($userId);

        if (! $user) {

            return redirect()->route('login');
        }

        $loginOtp = LoginOtp::query()
            ->where('user_id', $user->id)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (! $loginOtp) {

            return back()->withErrors([
                'otp' => 'OTP not found.',
            ]);
        }

        if ($loginOtp?->used_at) {

    return back()->withErrors([
        'otp' => 'OTP already used.',
    ]);
}

        if ($loginOtp->expires_at->isPast()) {

            return back()->withErrors([
                'otp' => 'OTP has expired.',
            ]);
        }

        if ($loginOtp->attempts >= 5) {

    return back()->withErrors([
        'otp' => 'Too many invalid attempts.',
    ]);
}

        if (! Hash::check($request->otp, $loginOtp->otp)) {

            $loginOtp->increment('attempts');

            return back()->withErrors([
                'otp' => 'Invalid OTP.',
            ]);
        }

        $loginOtp->update([
            'used_at' => now(),
        ]);

        Auth::login($user);
      

        $request->session()->forget('pending_otp_user_id');

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }

    public function resend(
    Request $request,
    OtpService $otpService
): RedirectResponse
{
    $userId = (int) $request->session()->get('pending_otp_user_id');

    if (! $userId) {

        return redirect()->route('login');
    }

    $user = User::find($userId);

    if (! $user) {

        return redirect()->route('login');
    }

    if (
        $user->last_otp_sent_at &&
        $user->last_otp_sent_at->diffInSeconds(now()) < 60
    ) {

       return back()->with(
    'status',
    'Please wait before requesting another OTP.'
);
    }

    $otpService->generate(
        $user,
        $request->ip()
    );

    $user->update([
        'last_otp_sent_at' => now(),
    ]);

    return back()->with('status', 'OTP resent successfully.');
}
}
