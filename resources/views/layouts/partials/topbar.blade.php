<header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-6">

    <div>
        <h2 class="text-lg font-semibold text-slate-800">
            Dashboard
        </h2>
    </div>

    <div class="flex items-center gap-4">

        <div class="text-sm text-slate-600">
            {{ auth()->user()->name }}
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                Logout
            </button>
        </form>

    </div>

</header>
