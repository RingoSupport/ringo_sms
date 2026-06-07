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
        Schema::create('client_sms_pricing', function (Blueprint $table) {

            $table->id();

            $table->foreignId('client_id')
                ->constrained('api_clients')
                ->cascadeOnDelete();

            $table->enum('network', [
                'MTN',
                'AIRTEL',
                'GLO',
                '9MOBILE',
            ]);

            $table->decimal('amount', 18, 2);

            $table->timestamps();

            $table->unique([
                'client_id',
                'network',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_sms_pricing');
    }
};
