<div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            {{ $title }}
        </h1>

        @if(isset($description))
            <p class="mt-1 text-sm text-slate-500">
                {{ $description }}
            </p>
        @endif
    </div>

    @if(isset($actions))
        <div>
            {{ $actions }}
        </div>
    @endif

</div>
