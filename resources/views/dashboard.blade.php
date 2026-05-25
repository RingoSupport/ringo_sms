<x-app-layout>

    <div class="space-y-6">

      <x-ui.page-header
    title="Dashboard"
    description="Welcome to the RingoSMS Operations Portal."
/>

       <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 2xl:grid-cols-4">

                <x-ui.stat-card
                    title="Total Messages"
                    value="0"
                />

                <x-ui.stat-card
                    title="Delivered"
                    value="0"
                />

                <x-ui.stat-card
                    title="Failed"
                    value="0"
                />

                <x-ui.stat-card
                    title="Wallet Balance"
                    value="₦0.00"
                />

</div>

    </div>

</x-app-layout>
