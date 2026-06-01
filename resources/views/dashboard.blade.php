<x-app-layout>

    <div class="space-y-6">

      <x-ui.page-header
    title="Dashboard"
    description="Welcome to the MobifinSms Operations Portal."
/>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5">


    <x-ui.stat-card
        title="Total Messages"
        value="{{ number_format($totalMessages) }}"
    />

                <x-ui.stat-card
        title="Delivered"
        value="{{ number_format($deliveredMessages) }}"
    />

    <x-ui.stat-card
    title="Pending"
    value="{{ number_format($pendingMessages) }}"
/>
                 <x-ui.stat-card
        title="Failed"
        value="{{ number_format($failedMessages) }}"
    />

     <x-ui.stat-card
        title="Delivery Rate"
        value="{{ $deliveryRate }}%"
    />
</div>


<div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
    <div class="rounded-xl border border-slate-200 bg-white">

    <div class="border-b border-slate-200 px-6 py-4">

        <h3 class="text-lg font-semibold text-slate-900">
            Hourly Traffic Health
        </h3>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Hour
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Traffic Volume
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @forelse ($hourlyTraffic as $traffic)

                    <tr>

                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                            {{ str_pad($traffic->hour, 2, '0', STR_PAD_LEFT) }}:00
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                            {{ number_format($traffic->total) }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="2"
                            class="px-6 py-6 text-center text-sm text-slate-500"
                        >
                            No traffic data available today.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>



<div class="rounded-xl border border-slate-200 bg-white">

    <div class="border-b border-slate-200 px-6 py-4">

        <h3 class="text-lg font-semibold text-slate-900">
            Network Health
        </h3>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Network
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Total
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Delivered
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Pending
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Failed
                    </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Rate
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @forelse ($networkHealth as $network)

                    <tr>

                      <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">

                            @php
                                $networkName = strtoupper(trim($network->network ?? 'UNKNOWN'));
                            @endphp

                            @if($networkName === 'MTN')
                                <span class="font-semibold text-yellow-600">
                                    MTN
                                </span>

                            @elseif($networkName === 'AIRTEL')
                                <span class="font-semibold text-red-600">
                                    Airtel
                                </span>

                            @elseif($networkName === 'GLO')
                                <span class="font-semibold text-green-600">
                                    Glo
                                </span>

                            @elseif(
                                $networkName === '9MOBILE' ||
                                $networkName === 'ETISALAT'
                            )
                                <span class="font-semibold text-emerald-600">
                                    9mobile
                                </span>

                            @else
                                <span class="font-semibold text-slate-600">
                                    {{ $network->network ?? 'Unknown' }}
                                </span>
                            @endif

                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                            {{ number_format($network->total) }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-emerald-600">
                            {{ number_format($network->delivered) }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-amber-600">
                            {{ number_format($network->pending) }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-rose-600">
                            {{ number_format($network->failed) }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold">
                                @if($network->delivery_rate >= 95)
                                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">
                                        {{ $network->delivery_rate }}%
                                    </span>

                                @elseif($network->delivery_rate >= 85)
                                    <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">
                                        {{ $network->delivery_rate }}%
                                    </span>

                                @else
                                    <span class="rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700">
                                        {{ $network->delivery_rate }}%
                                    </span>
                                @endif

                        </td>
                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-6 text-center text-sm text-slate-500"
                        >
                            No network traffic available today.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

@if (! Auth::guard('client')->check())
<div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white">

    <div class="border-b border-slate-200 px-6 py-4">

        <h3 class="text-lg font-semibold text-slate-900">
            Top Sender IDs
        </h3>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Sender ID
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Traffic Volume
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @forelse ($topSenderIds as $sender)

                    <tr>

                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                            {{ $sender->senderid ?? 'Unknown' }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                            {{ number_format($sender->total) }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="2"
                            class="px-6 py-6 text-center text-sm text-slate-500"
                        >
                            No sender activity available today.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

 <div class="rounded-xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-6 py-4">

            <h3 class="text-lg font-semibold text-slate-900">
                Active Clients
            </h3>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Client
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Traffic Volume
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">

                    @forelse ($activeClients as $client)

                        <tr>

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                                {{ $client->client?->name ?? 'Unknown Client' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ number_format($client->total) }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="2"
                                class="px-6 py-6 text-center text-sm text-slate-500"
                            >
                                No active client traffic today.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="rounded-xl border border-slate-200 bg-white">

    <div class="border-b border-slate-200 px-6 py-4">

        <h3 class="text-lg font-semibold text-slate-900">
            Low Wallet Clients
        </h3>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Client
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Balance
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @forelse ($lowWalletClients as $wallet)

                    <tr>

                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                            {{ $wallet->client?->client_name ?? 'Unknown Client' }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-rose-600">
                            ₦{{ number_format($wallet->balance, 2) }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="2"
                            class="px-6 py-6 text-center text-sm text-slate-500"
                        >
                            No low wallet balances detected.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
</div>
@endif
<div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

    <div class="rounded-xl border border-slate-200 bg-white">

    <div class="border-b border-slate-200 px-6 py-4">

        <h3 class="text-lg font-semibold text-slate-900">
            Recent Pending Messages
        </h3>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        MSISDN
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Network
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Sender ID
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Time
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @forelse ($recentPendingMessages as $message)

                    <tr>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                            {{ $message->msisdn }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                            {{ $message->network ?? 'Unknown' }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                            {{ $message->senderid }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-amber-600">
                            {{ $message->dlr_status ?? 'PENDING' }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                            {{ $message->created_at?->format('H:i:s') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-6 text-center text-sm text-slate-500"
                        >
                            No pending messages currently.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

    <div class="rounded-xl border border-slate-200 bg-white">

    <div class="border-b border-slate-200 px-6 py-4">

        <h3 class="text-lg font-semibold text-slate-900">
            Recent Failed Messages
        </h3>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        MSISDN
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Network
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Sender ID
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Time
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @forelse ($recentFailedMessages as $message)

                    <tr>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                            {{ $message->msisdn }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                            {{ $message->network ?? 'Unknown' }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                            {{ $message->senderid }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-rose-600">
                            {{ $message->dlr_status }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                            {{ $message->created_at?->format('H:i:s') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-6 text-center text-sm text-slate-500"
                        >
                            No failed messages today.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>



</div>




    </div>

</x-app-layout>
