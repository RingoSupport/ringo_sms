<x-ui.card>

    <div class="flex items-start justify-between">

        <div>
            <p class="text-sm font-medium text-slate-500">
                {{ $title }}
            </p>

            <h3 class="mt-2 text-3xl font-bold text-slate-800">
                {{ $value }}
            </h3>
        </div>

        @if(isset($icon))
            <div>
                {{ $icon }}
            </div>
        @endif

    </div>

</x-ui.card>
