<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="Edit User"
            description="Update internal staff user information."
        />

        <div class="max-w-5xl">

            <x-ui.card>

                @if ($errors->any())

                    <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 p-4">

                        <div class="mb-2 text-sm font-semibold text-rose-700">
                            Please fix the following errors:
                        </div>

                        <ul class="list-inside list-disc space-y-1 text-sm text-rose-600">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif

                <form method="POST"
                    action="{{ route('users.update', $user) }}"
                    class="space-y-6">

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Full Name
                            </label>

                            <x-ui.input
                                type="text"
                                name="name"
                                :value="old('name', $user->name)"
                                placeholder="Enter full name"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Email Address
                            </label>

                            <x-ui.input
                                type="email"
                                name="email"
                                :value="old('email', $user->email)"
                                placeholder="Enter email address"
                            />
                        </div>

                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Role
                            </label>

                            <x-ui.select name="role">

                                <option value="">
                                    Select role
                                </option>

                                @foreach ($roles as $role)

                                    <option
                                        value="{{ $role->name }}"
                                        @selected(old('role', $user->roles->first()?->name) === $role->name)
                                    >
                                        {{ ucfirst($role->name) }}
                                    </option>

                                @endforeach

                            </x-ui.select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Status
                            </label>

                            <x-ui.select name="status">

                                <option value="active"
                                    @selected(old('status', $user->status) === 'active')>
                                    Active
                                </option>

                                <option value="inactive"
                                    @selected(old('status', $user->status) === 'inactive')>
                                    Inactive
                                </option>

                            </x-ui.select>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3">

                        <a href="{{ route('users.index') }}"
                           class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                            Cancel
                        </a>

                        <x-ui.button type="submit">
                            Update User
                        </x-ui.button>

                    </div>

                </form>

            </x-ui.card>

        </div>

    </div>

</x-app-layout>
