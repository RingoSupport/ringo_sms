<?php

namespace App\Services;

use App\Models\LoginOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\LoginOtpMail;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generate(User $user, ?string $ipAddress = null): string
    {
        LoginOtp::query()
            ->where('user_id', $user->id)
            ->whereNull('used_at')
            ->delete();

        $otp = (string) random_int(100000, 999999);

        LoginOtp::create([
            'user_id' => $user->id,

            'otp' => Hash::make($otp),

            'expires_at' => now()->addMinutes(5),

            'ip_address' => $ipAddress,
        ]);

        Mail::to($user->email)
    ->send(new LoginOtpMail($otp));
        return $otp;
    }
}
