<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <body class="font-sans antialiased bg-slate-100">

```
<div class="flex min-h-screen items-center justify-center px-4">

    <div class="w-full max-w-md">

        <div class="mb-6 text-center">

            <div class="text-3xl font-bold text-slate-900">
                Mobifin SMS Portal
            </div>

            <p class="mt-2 text-sm text-slate-600">
                Operations & Administration
            </p>

        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

            {{ $slot }}

        </div>

    </div>

</div>
```

</body>

    </body>
</html>
