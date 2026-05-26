<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="Message Details"
            description="Inspect SMS delivery information and operational metadata."
        >

            <x-slot:actions>

                <a href="{{ route('messages.index', request()->only([
                    'page',
                    'search',
                    'status',
                    'network',
                    'start_date',
                    'end_date',
                ])) }}"
                   class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                    Back
                </a>

            </x-slot:actions>

        </x-ui.page-header>

        <x-ui.card>

    <div class="flex items-center justify-between">
<div>

    <div class="text-sm font-medium text-slate-500">
        Delivery Status
    </div>

    <div class="mt-2">

        <x-ui.badge :color="$message->delivery_color">

            {{ $message->delivery_state }}

        </x-ui.badge>

    </div>

</div>

        <div class="text-right">

            <div class="text-sm text-slate-500">
                Network
            </div>

            <div class="mt-1 text-lg font-semibold text-slate-900">
                {{ $message->network ?: '—' }}
            </div>

        </div>

    </div>

</x-ui.card>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <x-ui.card>

                <div class="space-y-5">

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Message ID
                        </div>

                        <div class="mt-1 text-sm text-slate-900">
                            {{ $message->id }}
                        </div>

                    </div>

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            MSISDN
                        </div>

                        <div class="mt-1 text-sm text-slate-900">
                            {{ $message->msisdn }}
                        </div>

                    </div>

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Sender ID
                        </div>

                        <div class="mt-1 text-sm text-slate-900">
                            {{ $message->senderid ?: '—' }}
                        </div>

                    </div>

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Network
                        </div>

                        <div class="mt-1 text-sm text-slate-900">
                            {{ $message->network ?: '—' }}
                        </div>

                    </div>




                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Created At
                        </div>

                        <div class="mt-1 text-sm text-slate-900">
                            {{ $message->created_at?->format('M d, Y h:i:s A') }}
                        </div>

                    </div>

                </div>

            </x-ui.card>

            <x-ui.card>

                <div class="space-y-5">

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Message Text
                        </div>

                        <div class="mt-2 rounded-lg bg-slate-50 p-4 text-sm text-slate-700">
                            <pre class="whitespace-pre-wrap text-sm text-slate-700">
                            {{ $message->text }}
                            </pre>
                        </div>

                    </div>

                    <div>

                        <div class="text-xs   font-semibold uppercase tracking-wide text-slate-500">
                           Gateway Reference
                        </div>

                        <div class="mt-2 overflow-x-auto font-mono rounded-lg bg-slate-900 p-4 text-xs text-slate-100 ">
                            <pre class="whitespace-pre-wrap break-words">
                            {{ $message->response ?: 'No response available.' }}
                            </pre>
                        </div>

                    </div>

                  <div>

    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
        Carrier Code
    </div>

    <div class="mt-1 text-sm text-slate-900">
        {{ $message->dlr_request ?: '—' }}
    </div>

</div>



                </div>

            </x-ui.card>

        </div>

    </div>

</x-app-layout>
