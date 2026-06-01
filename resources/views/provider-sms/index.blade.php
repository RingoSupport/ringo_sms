<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="SMS Pricing"
            :description="'Manage SMS pricing configuration. Total providers: ' . $pricing->count()"
        />

        <x-ui.card>

            <x-ui.table>

                <thead class="bg-slate-50">
                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Provider
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Amount
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Updated
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse ($pricing as $item)

                        <tr>

                            <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                {{ $item->provider }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                ₦{{ number_format($item->amount, 2) }}
                            </td>

                            <td class="px-6 py-4 text-sm">

                                <x-ui.badge :color="$item->status === 'active' ? 'green' : 'red'">
                                    {{ ucfirst($item->status) }}
                                </x-ui.badge>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $item->updated_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-right text-sm">

                                @can('edit sms pricing')
                                    <a href="{{ route('provider-sms.edit', $item) }}"
                                       class="text-sm font-medium text-slate-600 hover:text-slate-900">
                                        Edit
                                    </a>
                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5"
                                class="px-6 py-10 text-center text-sm text-slate-500">
                                No pricing configuration found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </x-ui.table>

        </x-ui.card>

    </div>

</x-app-layout>
