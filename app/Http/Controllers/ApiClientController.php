<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use App\Models\Wallet;
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
    ]);

  


    $plainPassword = Str::password(12);

    DB::beginTransaction();

    try {

        $client = ApiClient::create([
            'client_name' => $validated['client_name'],
            'username'    => $validated['username'],
            'status'      => $validated['status'],
            'password'    => Hash::make($plainPassword),
        ]);



        $client->wallet()->create([
            'balance' => 0,
            'status'  => 'active',
        ]);



        DB::commit();

    } catch (\Throwable $e) {

        DB::rollBack();
        throw $e;

    }

    return redirect()
        ->route('clients.show', $client)
        ->with('success', 'Client created successfully.')
        ->with('generated_password', $plainPassword);
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

}
