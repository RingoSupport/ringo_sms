<aside class="flex h-screen w-72 flex-shrink-0 flex-col border-r border-slate-200 bg-white">

    <div class="flex h-16 items-center border-b border-slate-200 px-6">
        <h1 class="text-lg font-semibold text-slate-800">
            Mobifin SMS 
        </h1>
    </div>

    <nav class="flex-1 space-y-1 p-4">

     <a href="{{ route('dashboard') }}"
        class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
        {{ request()->routeIs('dashboard')
                ? 'bg-slate-100 text-slate-900'
                : 'text-slate-700 hover:bg-slate-100' }}">
            Dashboard
        </a>


            @can('view messages')

            <a href="{{ route('messages.index') }}"
            class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
            {{ request()->routeIs('messages.*')
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-700 hover:bg-slate-100' }}">
                Messages
            </a>

    @endcan

    @can('view wallets')
        <a href="#"
           class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Wallets
        </a>
    @endcan

        <a href="#"
           class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Reports
        </a>

       @can('view users')

            <a href="{{ route('users.index') }}"
            class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
            {{ request()->routeIs('users.*')
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-700 hover:bg-slate-100' }}">
                Users
            </a>

        @endcan

            @can('view clients')
            <a href="{{ route('clients.index') }}"
            class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
            {{ request()->routeIs('clients.*')
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-700 hover:bg-slate-100' }}">
                Clients
            </a>
            @endcan


    </nav>

</aside>
