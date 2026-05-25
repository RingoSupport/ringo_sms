<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 font-sans antialiased">

    <div class="flex min-h-screen">

        @include('layouts.partials.sidebar')

        <div class="flex flex-1 flex-col">

            @include('layouts.partials.topbar')

            <main class="flex-1 p-6">
                @if (session('success'))

                    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>

                @endif

                @if (session('generated_password'))

                    <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-4">

                        <div class="text-sm font-semibold text-amber-800">
                            Generated API Credentials
                        </div>

                        <div class="mt-2 text-sm text-amber-700">
                            Password:
                            <span class="font-mono font-semibold">
                                {{ session('generated_password') }}
                            </span>
                        </div>

                        <p class="mt-2 text-xs text-amber-600">
                            This password will only be displayed once. Share it securely with the client.
                        </p>

                    </div>

                @endif

                {{ $slot }}
            </main>

        </div>

    </div>

</body>
</html>
