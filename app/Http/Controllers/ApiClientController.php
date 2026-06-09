<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
//use Illuminate\Validation\Rule;
use App\Models\Wallet;
use App\Models\ClientSmsPricing;
//use App\Support\Audit;

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

        'smsPricing',
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
       'username' => ['required', 'string', 'max:100'],
        'status'      => ['required', 'in:active,inactive'],
          'webhook_url'      => ['nullable', 'url', 'max:500'],
    'webhook_enabled'  => ['nullable', 'boolean'],
    ]);




  $apiPassword = Str::password(12);
$portalPassword = Str::password(12);

    DB::beginTransaction();

    try {

        $client = ApiClient::create([
            'client_name' => $validated['client_name'],
            'username'    => $validated['username'],
            'status'      => $validated['status'],
            'password'    => Hash::make($apiPassword),
            'portal_password' => Hash::make($portalPassword),
            'webhook_url' => $validated['webhook_url'] ?? null,
            'webhook_enabled' => $validated['webhook_enabled'] ?? false,
        ]);



        $client->wallet()->create([
            'balance' => 0,
            'status'  => 'active',
        ]);



        DB::commit();

    } catch (\Throwable $e) {

        DB::rollBack();
         dd($e->getMessage());

    }

 return redirect()
    ->route('clients.show', $client)
    ->with('success', 'Client created successfully.')
    ->with('credentials', [
        'username' => $client->username,
        'api_password' => $apiPassword,
        'portal_password' => $portalPassword,
    ]);
}

public function updateStatus(ApiClient $client): RedirectResponse
{
    $client->update([
        'status' => $client->status === 'active'
            ? 'inactive'
            : 'active',
    ]);



    return back()->with(
        'success',
        'Client status updated successfully.'
    );
}

public function fundWallet(Request $request, ApiClient $client): RedirectResponse
{
    $validated = $request->validate([
        'amount' => ['required', 'numeric', 'min:1'],
        'description' => ['nullable', 'string', 'max:255'],
    ]);

    $wallet = $client->wallet;

    abort_if(! $wallet, 404);

    DB::transaction(function () use ($wallet, $validated) {

        $amount = (float) $validated['amount'];

        $before = $wallet->balance;

        $after = $before + $amount;

        $wallet->increment('balance', $amount);

        DB::table('wallet_history')->insert([

            'wallet_id' => $wallet->id,

            'reference' => 'CR-' . strtoupper(Str::random(10)),

            'type' => 'credit',

            'amount' => $amount,

            'balance_before' => $before,

            'balance_after' => $after,

            'description' => $validated['description'] ?? 'Wallet funding',

            'created_at' => now(),

        ]);

    });

    return back()->with(
        'success',
        'Wallet funded successfully.'
    );
}


public function updateWebhook(
    Request $request,
    ApiClient $client
): RedirectResponse
{
    $validated = $request->validate([
        'webhook_url' => [
            'nullable',
            'url',
            'max:500',
        ],
    ]);

    $client->update([
        'webhook_url' => $validated['webhook_url'] ?? null,
        'webhook_enabled' => $request->boolean('webhook_enabled'),
    ]);

    return back()->with(
        'success',
        'Webhook configuration updated successfully.'
    );
}

public function updatePricing(
    Request $request,
    ApiClient $client
): RedirectResponse
{
    $validated = $request->validate([
        'pricing' => ['required', 'array'],
    ]);

    foreach ($validated['pricing'] as $network => $amount) {

        if ($amount === null || $amount === '') {
            continue;
        }

        ClientSmsPricing::updateOrCreate(
            [
                'client_id' => $client->id,
                'network' => $network,
            ],
            [
                'amount' => $amount,
            ]
        );
    }

    return back()->with(
        'success',
        'SMS pricing updated successfully.'
    );
}
}
