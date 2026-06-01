@php
    $isClientPortal = Auth::guard('client')->check();
@endphp
<aside class="flex h-screen w-72 flex-shrink-0 flex-col border-r border-slate-200 bg-white">

    <div class="flex h-16 items-center border-b border-slate-200 px-6">
        <h1 class="text-lg font-semibold text-slate-800">
            Mobifin SMS
        </h1>
    </div>

    <nav class="flex-1 space-y-1 p-4">

     <a href="{{ $isClientPortal
    ? route('client.dashboard')
    : route('dashboard') }}"
        class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
        {{ request()->routeIs('dashboard', 'client.dashboard')
                ? 'bg-slate-100 text-slate-900'
                : 'text-slate-700 hover:bg-slate-100' }}">
            Dashboard
        </a>


           @if ($isClientPortal || auth()->user()?->can('view messages'))

            <a href="{{ $isClientPortal
        ? route('client.messages.index')
        : route('messages.index')}}"
            class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
            {{ request()->routeIs('messages.*', 'client.messages.*')
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-700 hover:bg-slate-100' }}">
                Messages
            </a>

    @endif

 @if ($isClientPortal)

    <a href="{{ route('client.wallet.my') }}"
       class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">

        Wallet

    </a>

@endif
        {{-- <a href="#"
           class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Reports
        </a> --}}

       @if (! Auth::guard('client')->check())

       @can('view users')

            <a href="{{ route('users.index') }}"
            class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
            {{ request()->routeIs('users.*')
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-700 hover:bg-slate-100' }}">
                Users
            </a>

        @endcan

@endif

@if (! Auth::guard('client')->check())
            @can('view clients')
            <a href="{{ route('clients.index') }}"
            class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
            {{ request()->routeIs('clients.*')
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-700 hover:bg-slate-100' }}">
                Clients
            </a>
            @endcan
@endif

@if (! Auth::guard('client')->check())
    @can('view sms pricing')
        <a href="{{ route('provider-sms.index') }}"
            class="flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-colors
            {{ request()->routeIs('provider-sms.*')
                    ? 'bg-slate-100 text-slate-900'
                    : 'text-slate-700 hover:bg-slate-100' }}">
            SMS Pricing
        </a>
    @endcan
@endif



    </nav>

</aside>
