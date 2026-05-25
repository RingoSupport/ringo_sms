<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="Users"
           :description="'Manage portal users and access control. Total users: ' . $users->total()"
        >
            <x-slot:actions>
                @can('create users')
                <a href="{{ route('users.create') }}">
                    <x-ui.button>
                        Add User
                    </x-ui.button>
                </a>
                @endcan
            </x-slot:actions>
        </x-ui.page-header>

        <x-ui.card>
        <form method="GET" action="{{ route('users.index') }}">

    <div class="flex items-center gap-4">

        <div class="w-full max-w-sm">
            <x-ui.input
                type="text"
                name="search"
                :value="request('search')"
                placeholder="Search users..."
            />
        </div>

        <x-ui.button type="submit">
            Search
        </x-ui.button>

    </div>

</form>

        <x-ui.table>

            <thead class="bg-slate-50">
                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Name
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Email
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Role
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Created
                    </th>

                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Actions
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">

    @forelse ($users as $user)

        <tr>

            <td class="px-6 py-4 text-sm text-slate-700">
                {{ $user->name }}
            </td>

            <td class="px-6 py-4 text-sm text-slate-700">
                {{ $user->email }}
            </td>

            <td class="px-6 py-4 text-sm text-slate-700">
                @forelse ($user->getRoleNames() as $role)

                    <x-ui.badge color="blue">
                        {{ $role }}
                    </x-ui.badge>

                @empty

                    <x-ui.badge>
                        No Role
                    </x-ui.badge>

                @endforelse
            </td>

            <td class="px-6 py-4 text-sm text-slate-700">

                <x-ui.badge :color="$user->status === 'active' ? 'green' : 'red'">
                    {{ ucfirst($user->status) }}
                </x-ui.badge>

            </td>

            <td class="px-6 py-4 text-sm text-slate-700">
                {{ $user->created_at->format('d M Y') }}
            </td>

            <td class="px-6 py-4 text-right text-sm">
                <div class="flex items-center justify-end gap-2">

                    <button class="text-sm font-medium text-blue-600 hover:text-blue-800">
                        View
                    </button>
                    @can('edit users')
                    <a href="{{ route('users.edit', $user) }}"
                        class="text-sm font-medium text-slate-600 hover:text-slate-900">
                            Edit
                        </a>
                                            @endcan
                </div>
            </td>

        </tr>

    @empty

        <tr>
            <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">
                No users found.
            </td>
        </tr>

    @endforelse

</tbody>

        </x-ui.table>
    </x-ui.card>

        <div>
    {{ $users->links() }}
</div>

    </div>

</x-app-layout>
