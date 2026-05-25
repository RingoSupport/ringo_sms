<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="Clients"
            :description="'Manage API clients and monitor wallet activity. Total clients: ' . $clients->total()"
        >

            <form method="GET" action="{{ route('clients.index') }}">

                <div class="flex items-center gap-4">

                    <div class="w-full max-w-sm">

                        <x-ui.input
                            type="text"
                            name="search"
                            :value="request('search')"
                            placeholder="Search clients..."
                        />

                    </div>

                    <x-ui.button type="submit">
                        Search
                    </x-ui.button>

                </div>

            </form>

            <x-slot:actions>

            @can('create client')

               <a href="{{ route('clients.create') }}">

                    <x-ui.button>
                        Add Client
                    </x-ui.button>

                </a>
            @endcan

            </x-slot:actions>

        </x-ui.page-header>

        <x-ui.card>

            <x-ui.table>

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Client
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Username
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Wallet Balance
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Wallet Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Client Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse ($clients as $client)

                        <tr>

                            <td class="px-6 py-4">

                                <div class="text-sm font-medium text-slate-900">
                                    {{ $client->client_name }}
                                </div>

                                @if ($client->company_name)

                                    <div class="text-sm text-slate-500">
                                        {{ $client->company_name }}
                                    </div>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $client->username }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">

                                    @if ($client->wallet)

                                        ₦{{ number_format($client->wallet->balance, 2) }}

                                    @else

                                        —

                                    @endif

                                </td>

                            <td class="px-6 py-4 text-sm">

                                @if ($client->wallet)

                                    <x-ui.badge :color="$client->wallet->status === 'active' ? 'green' : 'red'">

                                        {{ ucfirst($client->wallet->status) }}

                                    </x-ui.badge>

                                @else

                                    <x-ui.badge color="gray">
                                        No Wallet
                                    </x-ui.badge>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-sm">

                                <x-ui.badge :color="$client->status === 'active' ? 'green' : 'red'">

                                    {{ ucfirst($client->status) }}

                                </x-ui.badge>

                            </td>

                            <td class="px-6 py-4 text-right text-sm">

                                <a href="{{ route('clients.show', $client) }}"
                                class="text-slate-600 hover:text-slate-900">
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="px-6 py-10 text-center text-sm text-slate-500">

                                No clients found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </x-ui.table>

        </x-ui.card>

        <div>
            {{ $clients->links() }}
        </div>

    </div>

</x-app-layout>
