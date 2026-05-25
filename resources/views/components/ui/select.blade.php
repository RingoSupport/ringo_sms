<select
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-800 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200'
    ]) }}
>
    {{ $slot }}
</select>
