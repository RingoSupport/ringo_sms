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
       Schema::create('provider_sms', function (Blueprint $table) {

    $table->id();

    $table->string('provider', 100)
        ->unique();

    $table->decimal('amount', 18, 2);

    $table->enum('status', ['active', 'inactive'])
        ->default('active');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_sms');
    }
};
