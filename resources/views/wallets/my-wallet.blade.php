<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="My Wallet"
            description="View your current wallet balance and wallet activity."
        />


        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

    <x-ui.stat-card
        title="Current Balance"
        value="₦{{ number_format($wallet?->balance ?? 0, 2) }}"
    />

    <x-ui.stat-card
        title="Total Credits"
        value="₦{{ number_format($totalCredits, 2) }}"
    />

    <x-ui.stat-card
        title="Total Debits"
        value="₦{{ number_format($totalDebits, 2) }}"
    />

</div>

        <x-ui.card>

            <div class="mb-4">

                <h3 class="text-base font-semibold text-slate-900">
                    Recent Wallet Activity
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Credits and SMS charges affecting your wallet.
                </p>

            </div>

            <x-ui.table>

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Reference
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Description
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

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse($walletHistory as $history)

                        <tr>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $history->created_at?->format('M d, Y h:i A') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $history->reference }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $history->description }}
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

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                No wallet activity found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </x-ui.table>

          @if(method_exists($walletHistory, 'links'))

                <div class="mt-4">
                    {{ $walletHistory->links() }}
                </div>

            @endif

        </x-ui.card>

    </div>

</x-app-layout>
