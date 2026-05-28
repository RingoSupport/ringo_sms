<x-guest-layout>

    <div class="mb-6 text-center">

        <h2 class="text-2xl font-bold text-slate-900">
            Client Portal
        </h2>

        <p class="mt-2 text-sm text-slate-600">
            Sign in to access your SMS dashboard and wallet.
        </p>

    </div>

    <form method="POST" action="{{ route('client.login') }}">

        @csrf

        <div>

            <x-input-label for="username" :value="'Username'" />

            <x-text-input
                id="username"
                class="mt-1 block w-full"
                type="text"
                name="username"
                :value="old('username')"
                required
                autofocus
            />

            <x-input-error :messages="$errors->get('username')" class="mt-2" />

        </div>

        <div class="mt-4">

            <x-input-label for="password" :value="'Password'" />

            <x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                required
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>

        <div class="mt-6">

            <x-primary-button class="w-full justify-center">
                Sign In
            </x-primary-button>

        </div>

    </form>

</x-guest-layout>
