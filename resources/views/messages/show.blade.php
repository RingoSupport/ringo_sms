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
                Submission Status
            </div>

            <div class="mt-1 text-2xl font-bold
                {{ $message->status === '1'
                    ? 'text-emerald-600'
                    : 'text-amber-600' }}">

                {{ $message->status === '1'
                    ? 'Submitted'
                    : 'Pending' }}

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
                            Status
                        </div>

                        <div class="mt-1">

                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                {{ $message->status === '1'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-amber-100 text-amber-700' }}">

                                {{ $message->status === '1'
                                    ? 'Successful'
                                    : 'Pending / Failed' }}

                            </span>

                        </div>

                    </div>

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            DLR Status
                        </div>

                        <div class="mt-1 text-sm text-slate-900">
                            {{ $message->dlr_status ?: '—' }}
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
                            {{ $message->text ?: '—' }}
                        </div>

                    </div>

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Vendor Response
                        </div>

                        <div class="mt-2 overflow-x-auto rounded-lg bg-slate-900 p-4 text-xs text-slate-100">
                            <pre class="whitespace-pre-wrap break-words">
                            {{ $message->response ?: 'No response available.' }}
                            </pre>
                        </div>

                    </div>

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            DLR Request
                        </div>

                        <div class="mt-2 overflow-x-auto rounded-lg bg-slate-900 p-4 text-xs text-slate-100">
                            <pre class="whitespace-pre-wrap break-words">{{ $message->dlr_request ?: 'No DLR request available.' }}</pre>
                        </div>

                    </div>

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            DLR Results
                        </div>

                        <div class="mt-2 overflow-x-auto rounded-lg bg-slate-900 p-4 text-xs text-slate-100">
                            <pre class="whitespace-pre-wrap break-words">{{ $message->dlr_results ?: 'No DLR results available.' }}</pre>
                        </div>

                    </div>

                </div>

            </x-ui.card>

        </div>

    </div>

</x-app-layout>
