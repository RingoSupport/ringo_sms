<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            :title="$client->client_name"
            description="Client account overview and wallet activity."
        >

            <x-slot:actions>

    <div class="flex items-center gap-3">

        @can('disable clients')

            <form method="POST"
                action="{{ route('clients.update-status', $client) }}">

                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white
                    {{ $client->status === 'active'
                        ? 'bg-rose-600 hover:bg-rose-700'
                        : 'bg-emerald-600 hover:bg-emerald-700' }}"
                >

                    {{ $client->status === 'active'
                        ? 'Deactivate Client'
                        : 'Activate Client' }}

                </button>


            </form>

        @endcan

        <a href="{{ route('clients.index') }}"
        class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Back
        </a>

    </div>

</x-slot:actions>
        </x-ui.page-header>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <x-ui.card>

                <div class="space-y-6">

                    <div>

                        <div class="mb-1 text-sm font-medium text-slate-500">
                            Username
                        </div>

                        <div class="text-sm text-slate-900">
                            {{ $client->username }}
                        </div>

                    </div>

                    <div>

                        <div class="mb-1 text-sm font-medium text-slate-500">
                            Email
                        </div>

                        <div class="text-sm text-slate-900">
                            {{ $client->email ?: '—' }}
                        </div>

                    </div>

                    <div>

                        <div class="mb-1 text-sm font-medium text-slate-500">
                            Phone
                        </div>

                        <div class="text-sm text-slate-900">
                            {{ $client->phone ?: '—' }}
                        </div>

                    </div>

                    <div>

                        <div class="mb-1 text-sm font-medium text-slate-500">
                            Company
                        </div>

                        <div class="text-sm text-slate-900">
                            {{ $client->company_name ?: '—' }}
                        </div>

                    </div>

                    <div>

                        <div class="mb-1 text-sm font-medium text-slate-500">
                            Client Status
                        </div>

                        <x-ui.badge :color="$client->status === 'active' ? 'green' : 'red'">

                            {{ ucfirst($client->status) }}

                        </x-ui.badge>

                    </div>

                </div>

            </x-ui.card>

            <div class="space-y-6 lg:col-span-2">

                <x-ui.card>

                    <div class="mb-6 flex items-center justify-between">

                        <div>

                            <div class="text-sm font-medium text-slate-500">
                                Wallet Balance
                            </div>

                            <div class="mt-1 text-3xl font-bold text-slate-900">

                                @if ($client->wallet)

                                    ₦{{ number_format($client->wallet->balance, 2) }}

                                @else

                                    ₦0.00

                                @endif

                            </div>

                        </div>

                        <div>

                            @if ($client->wallet)

                                <x-ui.badge :color="$client->wallet->status === 'active' ? 'green' : 'red'">

                                    {{ ucfirst($client->wallet->status) }}

                                </x-ui.badge>

                            @else

                                <x-ui.badge color="gray">
                                    No Wallet
                                </x-ui.badge>

                            @endif

                        </div>

                    </div>

                </x-ui.card>

                <x-ui.card>

                    <div class="mb-4 flex items-center justify-between">

                        <div>

                            <h3 class="text-base font-semibold text-slate-900">
                                Recent Wallet Activity
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Latest debit and credit transactions.
                            </p>

                        </div>

                    </div>

                    <x-ui.table>

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Reference
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Type
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Amount
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Balance After
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Date
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-200 bg-white">

                            @forelse ($client->wallet?->histories ?? [] as $history)

                                <tr>

                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        {{ $history->reference }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">

                                        <x-ui.badge :color="$history->type === 'credit' ? 'green' : 'rose'">

                                            {{ ucfirst($history->type) }}

                                        </x-ui.badge>

                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        ₦{{ number_format($history->amount, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        ₦{{ number_format($history->balance_after, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        {{ $history->created_at?->format('M d, Y h:i A') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="px-6 py-10 text-center text-sm text-slate-500">

                                        No wallet activity found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </x-ui.table>

                </x-ui.card>

            </div>

        </div>

    </div>

</x-app-layout>
