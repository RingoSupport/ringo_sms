<x-app-layout>

    <div class="space-y-6">

        <x-ui.page-header
            title="My Wallet"
            description="View your current wallet balance and wallet activity."
        />

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            <x-ui.stat-card
                title="Current Balance"
                value="₦{{ number_format($wallet?->balance ?? 0, 2) }}"
            />

        </div>

    </div>

</x-app-layout>
