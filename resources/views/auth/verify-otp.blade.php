<x-guest-layout>

    <div class="mb-6 text-center">

        <h2 class="text-2xl font-bold text-slate-900">
            Verify OTP
        </h2>

        <p class="mt-2 text-sm text-slate-600">
            Enter the 6-digit OTP sent to your email.
        </p>

    </div>

    @if (session('status'))

        <div class="mb-4 rounded-lg bg-emerald-100 px-4 py-3 text-sm text-emerald-700">

            {{ session('status') }}

        </div>

    @endif

    <form method="POST" action="{{ route('otp.verify.store') }}">

        @csrf

        <div>

            <x-input-label for="otp" :value="'OTP Code'" />

            <x-text-input
                id="otp"
                class="mt-1 block w-full text-center text-2xl tracking-[0.4em]"
                type="text"
                name="otp"
                maxlength="6"
                required
                autofocus
            />

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />

        </div>

        <div class="mt-6">

            <x-primary-button class="w-full justify-center">
                Verify OTP
            </x-primary-button>

        </div>

    </form>

    <div class="mt-4 text-center">

        <form
            method="POST"
            action="{{ route('otp.resend') }}"
        >

            @csrf

            <button
                type="submit"
                class="text-sm font-medium text-slate-600 hover:text-slate-900"
            >
                Resend OTP
            </button>

        </form>

    </div>

</x-guest-layout>
