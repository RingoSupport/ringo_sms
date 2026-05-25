<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="Create Client"
            description="Onboard a new API client and initialize account access."
        >

            <x-slot:actions>

                <a href="{{ route('clients.index') }}"
                   class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                    Back
                </a>

            </x-slot:actions>

        </x-ui.page-header>

        <x-ui.card>

            <form method="POST" action="{{ route('clients.store') }}">

                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-4">

    <div class="text-sm font-medium text-amber-800">
        Credentials will be generated automatically.
    </div>

    <p class="mt-1 text-sm text-amber-700">
        A secure API password will be generated during client creation and displayed once after onboarding.
    </p>

</div>

                    <div>


                         <label class="mb-2 block text-sm font-medium text-slate-700">
            Client Name
        </label>

                        <x-ui.input
                            id="client_name"
                            type="text"
                            name="client_name"
                            :value="old('client_name')"
                            required
                        />



                    </div>



                    <div>



                                    <label class="mb-2 block text-sm font-medium text-slate-700">
            Username
        </label>

                        <x-ui.input
                            id="username"
                            type="email"
                            name="username"
                            :value="old('username')"
                            required
                        />



                    </div>

                    <div>


                                              <label class="mb-2 block text-sm font-medium text-slate-700">
           Status
        </label>

                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                        >

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>


                    </div>

                </div>

                <div class="mt-8 flex items-center justify-end gap-4">

                    <a href="{{ route('clients.index') }}"
                       class="text-sm font-medium text-slate-600 hover:text-slate-900">
                        Cancel
                    </a>

                    <x-ui.button type="submit">
                        Create Client
                    </x-ui.button>

                </div>

            </form>

        </x-ui.card>

    </div>

</x-app-layout>
