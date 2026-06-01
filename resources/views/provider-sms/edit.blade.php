<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="Edit SMS Pricing"
            :description="'Update pricing configuration for ' . $providerSms->provider"
        />

        <x-ui.card>

            <form
                method="POST"
                action="{{ route('provider-sms.update', $providerSms) }}"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Provider
                    </label>

                    <x-ui.input
                        type="text"
                        :value="$providerSms->provider"
                        disabled
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Price Per SMS (₦)
                    </label>

                    <x-ui.input
                        type="number"
                        name="amount"
                        step="0.01"
                        min="0"
                        :value="old('amount', $providerSms->amount)"
                    />

                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                        <option
                            value="active"
                            @selected(old('status', $providerSms->status) === 'active')
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            @selected(old('status', $providerSms->status) === 'inactive')
                        >
                            Inactive
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">

                    <a href="{{ route('provider-sms.index') }}">
                        <x-ui.button type="button" variant="secondary">
                            Cancel
                        </x-ui.button>
                    </a>

                    <x-ui.button type="submit">
                        Save Changes
                    </x-ui.button>

                </div>

            </form>

        </x-ui.card>

    </div>

</x-app-layout>
