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
    Schema::create('wallet_history', function (Blueprint $table) {

        $table->id();

        $table->foreignId('wallet_id')
            ->constrained('wallets')
            ->cascadeOnDelete();

        $table->string('reference', 100)
            ->unique();

        $table->enum('type', ['debit', 'credit']);

        $table->decimal('amount', 18, 2);

        $table->decimal('balance_before', 18, 2);

        $table->decimal('balance_after', 18, 2);

        $table->text('description')
            ->nullable();

        $table->timestamp('created_at')
            ->useCurrent();

        /*
        |--------------------------------------------------------------------------
        | Future Expansion Fields
        |--------------------------------------------------------------------------
        */

        $table->string('network')
            ->nullable();

        $table->string('vendor')
            ->nullable();

        $table->string('created_by')
            ->nullable();

        $table->json('meta')
            ->nullable();

        $table->index('wallet_id');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_history');
    }
};
