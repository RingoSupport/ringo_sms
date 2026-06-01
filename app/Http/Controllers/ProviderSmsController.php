<?php

namespace App\Http\Controllers;

use App\Models\ProviderSms;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProviderSmsController extends Controller
{
    public function index(): View
    {
        $pricing = ProviderSms::query()
            ->orderBy('provider')
            ->get();

        return view('provider-sms.index', [
            'pricing' => $pricing,
        ]);
    }

    public function edit(ProviderSms $providerSms): View
    {
        return view('provider-sms.edit', [
            'providerSms' => $providerSms,
        ]);
    }

    public function update(
        Request $request,
        ProviderSms $providerSms
    ): RedirectResponse {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $providerSms->update($validated);

        return redirect()
            ->route('provider-sms.index')
            ->with('success', 'SMS pricing updated successfully.');
    }
}
