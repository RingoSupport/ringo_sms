<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAccountController extends Controller
{
    public function editPassword(): View
    {
        return view('client.account.change-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);

        $client = Auth::guard('client')->user();

        if (! Hash::check(
            $request->current_password,
            $client->portal_password
        )) {
            return back()
                ->withErrors([
                    'current_password' => 'Current password is incorrect.',
                ])
                ->withInput();
        }

        $client->update([
            'portal_password' => $request->password,
        ]);

        return back()->with(
            'success',
            'Password changed successfully.'
        );
    }
}
