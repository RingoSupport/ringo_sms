<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use App\Models\Wallet;
use App\Support\Audit;

class ApiClientController extends Controller
{
    //
    public function index(Request $request): View
{
    $clients = ApiClient::query()
        ->with('wallet')
        ->when($request->search, function ($query, $search) {

            $query->where(function ($q) use ($search) {

                $q->where('client_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");

            });

        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('clients.index', [
        'clients' => $clients,
    ]);
}

public function show(ApiClient $client): View
{
    $client->load([
        'wallet.histories' => function ($query) {

            $query->latest('created_at')
                ->limit(10);

        },
    ]);

    return view('clients.show', [
        'client' => $client,
    ]);
}

public function create(): View
{
    return view('clients.create');
}


public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'client_name' => ['required', 'string', 'max:150'],
        //'company_name' => ['nullable', 'string', 'max:255'],
        //'email' => ['nullable', 'email', 'max:255'],
        //'phone' => ['nullable', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:100', 'unique:api_clients,username'],
        'status' => ['required', 'in:active,inactive'],
    ]);

    $plainPassword = Str::password(12);

    DB::transaction(function () use ($validated, $plainPassword, &$client) {

        $client = ApiClient::create([

            'client_name' => $validated['client_name'],
            //'company_name' => $validated['company_name'] ?? null,
           // 'email' => $validated['email'] ?? null,
            //'phone' => $validated['phone'] ?? null,
            'username' => $validated['username'],
            'status' => $validated['status'],

            'password' => Hash::make($plainPassword),

        ]);



        Wallet::create([

            'client_id' => $client->id,
            'balance' => 0,
            'status' => 'active',

        ]);

        Audit::log(
            action: 'client_created',
            description: 'Created API client: ' . $client->client_name,
            target: $client
        );

    });

    return redirect()
        ->route('clients.show', $client)
        ->with('generated_password', $plainPassword)
        ->with('success', 'Client created successfully.');
}

public function updateStatus(ApiClient $client): RedirectResponse
{
    $client->update([
        'status' => $client->status === 'active'
            ? 'inactive'
            : 'active',
    ]);

    Audit::log(
    action: $client->status === 'active'
        ? 'client_activated'
        : 'client_disabled',

    description: ($client->status === 'active'
        ? 'Activated client: '
        : 'Disabled client: ')
        . $client->client_name,

    target: $client
);

    return back()->with(
        'success',
        'Client status updated successfully.'
    );
}

}
