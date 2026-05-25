<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('wallets', function (Blueprint $table) {

        $table->id();

        $table->foreignId('client_id')
            ->constrained('api_clients')
            ->cascadeOnDelete();

        $table->decimal('balance', 18, 2)
            ->default(0);

        $table->enum('status', ['active', 'inactive'])
            ->default('active');

        /*
        |--------------------------------------------------------------------------
        | Future Expansion Fields
        |--------------------------------------------------------------------------
        */

        $table->decimal('alert_threshold', 18, 2)
            ->nullable();

        $table->timestamp('last_funded_at')
            ->nullable();

        $table->text('notes')
            ->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
