<x-app-layout>

    <div class="space-y-6 overflow-x-hidden">

        <x-ui.page-header
            title="Messages"
            description="Monitor SMS delivery activity and investigate message operations."
        />
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

    <x-ui.card>

        <div class="space-y-2">

            <div class="text-sm font-medium text-slate-500">
                Total Messages Today
            </div>

            <div class="text-3xl font-bold text-slate-900">
                {{ number_format($totalMessagesToday) }}
            </div>

        </div>

    </x-ui.card>

    <x-ui.card>

        <div class="space-y-2">

            <div class="text-sm font-medium text-slate-500">
                Derlivered Today
            </div>

            <div class="text-3xl font-bold text-emerald-600">
                {{ number_format($deliveredToday) }}
            </div>

        </div>

    </x-ui.card>

    <x-ui.card>

        <div class="space-y-2">

            <div class="text-sm font-medium text-slate-500">
                Pending Today
            </div>

            <div class="text-3xl font-bold text-amber-600">
                {{ number_format($pendingToday) }}
            </div>

        </div>

    </x-ui.card>

    <x-ui.card>

        <div class="space-y-2">

            <div class="text-sm font-medium text-slate-500">
                Failed Today
            </div>

            <div class="text-3xl font-bold text-rose-600">
                {{ number_format($failedToday) }}
            </div>

        </div>

    </x-ui.card>

</div>

        <x-ui.card>

            <form method="GET"
                  action="{{ route('messages.index') }}"
                 class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                <div>

                    <label class="mb-1 block text-sm font-medium text-slate-700">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="MSISDN, Sender ID, Message ID"
                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >

                </div>

                <div>

                    <label class="mb-1 block text-sm font-medium text-slate-700">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >

                       <option value="">
                            All Statuses
                        </option>

                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>
                            Delivered
                        </option>

                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>
                            Failed
                        </option>

                    </select>

                </div>

                <div>

                    <label class="mb-1 block text-sm font-medium text-slate-700">
                        Network
                    </label>

                    <select
                        name="network"
                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >

                        <option value="">
                            All Networks
                        </option>

                        <option value="MTN" {{ request('network') === 'MTN' ? 'selected' : '' }}>
                            MTN
                        </option>

                        <option value="AIRTEL" {{ request('network') === 'AIRTEL' ? 'selected' : '' }}>
                            Airtel
                        </option>

                        <option value="GLO" {{ request('network') === 'GLO' ? 'selected' : '' }}>
                            Glo
                        </option>

                        <option value="9MOBILE" {{ request('network') === '9MOBILE' ? 'selected' : '' }}>
                            9mobile
                        </option>

                    </select>



                </div>

                <div>

    <label class="mb-1 block text-sm font-medium text-slate-700">
        Sender ID
    </label>

    <select
    name="senderid"
    class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
>

    <option value="">
        All Sender IDs
    </option>

    @foreach ($senderIds as $senderId)

        <option
            value="{{ $senderId }}"
            {{ request('senderid') === $senderId ? 'selected' : '' }}
        >
            {{ $senderId }}
        </option>

    @endforeach

</select>

</div>

                    <div>

                        <label class="mb-1 block text-sm font-medium text-slate-700">
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            value="{{ request('start_date') }}"
                            class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                    </div>

                    <div>

                        <label class="mb-1 block text-sm font-medium text-slate-700">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            value="{{ request('end_date') }}"
                            class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                    </div>

                    <div>

    <label class="mb-1 block text-sm font-medium text-slate-700">
        Per Page
    </label>

    <select
        name="per_page"
        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
    >

        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>
            10
        </option>

        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>
            25
        </option>

        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>
            50
        </option>

        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>
            100
        </option>

    </select>

</div>

                <div class="flex items-end">

                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
                            >
                                Filter
                            </button>

                </div>

            </form>

        </x-ui.card>

        <x-ui.card>

            <div class="overflow-x-auto">

                <table class="w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                       <tr class="hover:bg-slate-50 transition-colors">

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Message ID
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                MSISDN
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Message
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Sender ID
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Network
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Date
                            </th>


                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse ($messages as $message)

                           <tr
    onclick="window.location='{{ route('messages.show', [
    'message' => $message,
    'page' => request('page'),
    'search' => request('search'),
    'status' => request('status'),
    'network' => request('network'),
    'start_date' => request('start_date'),
    'end_date' => request('end_date'),
]) }}'"
    class="cursor-pointer hover:bg-slate-50 transition-colors"
>

                                <td class="px-6 py-3 text-sm text-slate-700">
                                    {{ $message->id }}
                                </td>

                                <td class="px-6 py-3 text-sm text-slate-700">
                                    {{ $message->msisdn }}
                                </td>

                                <td class="px-6 py-3 text-sm text-slate-700">

                                <div class="max-w-[220px] truncate">
                                    {{ $message->text }}
                                </div>

                            </td>

                                <td class="px-6 py-3 text-sm text-slate-700">
                                    {{ $message->senderid }}
                                </td>

                                <td class="px-6 py-3 text-sm text-slate-700">
                                    {{ $message->network ?: '—' }}
                                </td>


<td class="px-6 py-3 text-sm">

    <x-ui.badge :color="$message->delivery_color">

        {{ $message->delivery_state }}

    </x-ui.badge>

</td>


                                <td class="px-6 py-3 text-sm text-slate-700">
                                   <div class="space-y-1">

                                        <div>
                                            {{ $message->created_at?->format('M d, Y h:i A') }}
                                        </div>

                                        <div class="text-xs text-slate-400">
                                            {{ $message->created_at?->diffForHumans() }}
                                        </div>

                                    </div>
                                </td>



                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="px-6 py-10 text-center text-sm text-slate-500">

                                    <div class="space-y-2">

    <div class="font-medium text-slate-700">
        No messages found
    </div>

    <div class="text-xs text-slate-500">
        SMS activity matching the selected filters will appear here.
    </div>

</div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </x-ui.card>

        <div>
            {{ $messages->links() }}
        </div>

    </div>

</x-app-layout>
