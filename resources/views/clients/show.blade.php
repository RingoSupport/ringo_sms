<x-app-layout>

    <div x-data="{ openFundWallet: false, openWebhook: false, openPricing: false }">

        <div class="space-y-6">

            <x-ui.page-header
                :title="$client->client_name"
                description="Client account overview and wallet activity."
            >

                <x-slot:actions>

                    <div class="flex items-center gap-3">

                        <div>
                            <div class="mb-1 text-sm font-medium text-slate-500">
                                Webhook URL
                            </div>
                            <div class="text-sm text-slate-900 break-all">
                                {{ $client->webhook_url ?: 'Not Configured' }}
                            </div>
                        </div>

                        <div>
                            <div class="mb-1 text-sm font-medium text-slate-500">
                                Webhook Status
                            </div>
                            <x-ui.badge :color="$client->webhook_enabled ? 'green' : 'gray'">
                                {{ $client->webhook_enabled ? 'Enabled' : 'Disabled' }}
                            </x-ui.badge>
                        </div>

                        <button
                            type="button"
                            @click="openWebhook = true"
                            class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                            Configure Webhook
                        </button>

                        @can('disable clients')
                            <form method="POST" action="{{ route('clients.update-status', $client) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white
                                    {{ $client->status === 'active'
                                        ? 'bg-rose-600 hover:bg-rose-700'
                                        : 'bg-emerald-600 hover:bg-emerald-700' }}"
                                >
                                    {{ $client->status === 'active' ? 'Deactivate Client' : 'Activate Client' }}
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

                {{-- Left column: Client Info --}}
                <x-ui.card>
                    <div class="space-y-6">

                        <div>
                            <div class="mb-1 text-sm font-medium text-slate-500">Username</div>
                            <div class="text-sm text-slate-900">{{ $client->username }}</div>
                        </div>

                        <div>
                            <div class="mb-1 text-sm font-medium text-slate-500">Email</div>
                            <div class="text-sm text-slate-900">{{ $client->email ?: '—' }}</div>
                        </div>

                        <div>
                            <div class="mb-1 text-sm font-medium text-slate-500">Phone</div>
                            <div class="text-sm text-slate-900">{{ $client->phone ?: '—' }}</div>
                        </div>

                        <div>
                            <div class="mb-1 text-sm font-medium text-slate-500">Company</div>
                            <div class="text-sm text-slate-900">{{ $client->company_name ?: '—' }}</div>
                        </div>

                        <div>
                            <div class="mb-1 text-sm font-medium text-slate-500">Client Status</div>
                            <x-ui.badge :color="$client->status === 'active' ? 'green' : 'red'">
                                {{ ucfirst($client->status) }}
                            </x-ui.badge>
                        </div>

                    </div>
                </x-ui.card>

                {{-- Right columns: Wallet + Activity --}}
                <div class="space-y-6 lg:col-span-2">

                    {{-- Wallet Balance Card --}}
                    <x-ui.card>
                        <div class="mb-6 flex items-center justify-between">

                            <div>
                                <div class="text-sm font-medium text-slate-500">Wallet Balance</div>
                                <div class="mt-1 text-3xl font-bold text-slate-900">
                                    @if ($client->wallet)
                                        ₦{{ number_format($client->wallet->balance, 2) }}
                                    @else
                                        ₦0.00
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3">

                                @if ($client->wallet)
                                    <x-ui.badge :color="$client->wallet->status === 'active' ? 'green' : 'red'">
                                        {{ ucfirst($client->wallet->status) }}
                                    </x-ui.badge>
                                @else
                                    <x-ui.badge color="gray">No Wallet</x-ui.badge>
                                @endif

                                <button
                                    type="button"
                                    @click="openFundWallet = true"
                                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                    + Fund Wallet
                                </button>

                            </div>

                        </div>
                    </x-ui.card>

                    {{-- Fund Wallet Modal --}}
                    <div
                        x-show="openFundWallet"
                        x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

                        <div
                            @click.away="openFundWallet = false"
                            class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">

                            <div class="mb-6 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-slate-900">Fund Wallet</h2>
                                <button type="button" @click="openFundWallet = false" class="text-slate-500 hover:text-slate-700">✕</button>
                            </div>

                            <form method="POST" action="{{ route('clients.fund-wallet', $client) }}" class="space-y-4">
                                @csrf

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Amount</label>
                                    <input
                                        type="number"
                                        name="amount"
                                        min="1"
                                        step="0.01"
                                        required
                                        onkeydown="return !['e', 'E', '+', '-'].includes(event.key)"
                                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-slate-500 focus:outline-none">
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                                    <input
                                        type="text"
                                        name="description"
                                        placeholder="Optional note"
                                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-slate-500 focus:outline-none">
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" @click="openFundWallet = false"
                                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                        Fund Wallet
                                    </button>
                                </div>

                            </form>

                        </div>

                    </div>

                    {{-- Configure Webhook Modal --}}
                    <div
                        x-show="openWebhook"
                        x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

                        <div
                            @click.away="openWebhook = false"
                            class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">

                            <div class="mb-6 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-slate-900">Configure Webhook</h2>
                                <button type="button" @click="openWebhook = false" class="text-slate-500 hover:text-slate-700">✕</button>
                            </div>

                            <form method="POST" action="{{ route('clients.webhook', $client) }}" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Webhook URL</label>
                                    <input
                                        type="url"
                                        name="webhook_url"
                                        value="{{ old('webhook_url', $client->webhook_url) }}"
                                        placeholder="https://client.com/api/sms/dlr"
                                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-slate-500 focus:outline-none">
                                </div>

                                <div>
                                    <label class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            name="webhook_enabled"
                                            value="1"
                                            {{ $client->webhook_enabled ? 'checked' : '' }}>
                                        <span class="text-sm text-slate-700">Enable Webhook</span>
                                    </label>
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" @click="openWebhook = false"
                                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                        Save
                                    </button>
                                </div>

                            </form>

                        </div>

                    </div>
                    {{-- Sms Pricing Per Client Modal--}}
                    <div
    x-show="openPricing"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

    <div
        @click.away="openPricing = false"
        class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">

        <div class="mb-6 flex items-center justify-between">

            <h2 class="text-lg font-semibold text-slate-900">
                Configure SMS Pricing
            </h2>

            <button
                type="button"
                @click="openPricing = false"
                class="text-slate-500 hover:text-slate-700">

                ✕

            </button>

        </div>

        <form
            method="POST"
            action="{{ route('clients.pricing', $client) }}"
            class="space-y-4">

            @csrf
            @method('PATCH')

            @php
                $pricing = $client->smsPricing->keyBy('network');
            @endphp

            @foreach (['MTN', 'AIRTEL', 'GLO', '9MOBILE'] as $network)

                <div>

                    <label class="mb-1 block text-sm font-medium text-slate-700">

                        {{ $network }}

                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="pricing[{{ $network }}]"
                        value="{{ $pricing[$network]->amount ?? '' }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">

                </div>

            @endforeach

            <div class="flex justify-end gap-3 pt-4">

                <button
                    type="button"
                    @click="openPricing = false"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">

                    Save Pricing

                </button>

            </div>

        </form>

    </div>

</div>
                    <x-ui.card>

    <div class="mb-4 flex items-center justify-between">

        <div>

            <h3 class="text-base font-semibold text-slate-900">
                Client SMS Pricing
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Network pricing configuration for this client.
            </p>

        </div>

        <button
            type="button"
            @click="openPricing = true"
            class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">

            Configure Pricing

        </button>

    </div>

    <div class="grid grid-cols-2 gap-4">

        @foreach (['MTN', 'AIRTEL', 'GLO', '9MOBILE'] as $network)

            @php
                $price = $client->smsPricing
                    ->firstWhere('network', $network);
            @endphp

            <div class="rounded-lg border border-slate-200 p-4">

                <div class="text-sm text-slate-500">
                    {{ $network }}
                </div>

                <div class="mt-1 text-lg font-semibold text-slate-900">

                    ₦{{ number_format($price?->amount ?? 0, 2) }}

                </div>

            </div>

        @endforeach

    </div>

</x-ui.card>
                    {{-- Recent Wallet Activity --}}
                    <x-ui.card>

                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Recent Wallet Activity</h3>
                                <p class="mt-1 text-sm text-slate-500">Latest debit and credit transactions.</p>
                            </div>
                        </div>

                        <x-ui.table>

                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Balance After</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200 bg-white">

                                @forelse ($client->wallet?->histories ?? [] as $history)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-slate-700">{{ $history->reference }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <x-ui.badge :color="$history->type === 'credit' ? 'green' : 'rose'">
                                                {{ ucfirst($history->type) }}
                                            </x-ui.badge>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-700">₦{{ number_format($history->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-700">₦{{ number_format($history->balance_after, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-700">{{ $history->created_at?->format('M d, Y h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                            No wallet activity found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </x-ui.table>

                    </x-ui.card>

                </div>{{-- end lg:col-span-2 --}}

            </div>{{-- end grid --}}

        </div>{{-- end space-y-6 --}}

    </div>{{-- end x-data --}}

</x-app-layout>