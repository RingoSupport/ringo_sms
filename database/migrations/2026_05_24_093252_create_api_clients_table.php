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
    Schema::create('api_clients', function (Blueprint $table) {

        $table->id();

        $table->string('client_name', 150); // Company or individual name

        $table->string('username', 100) // Unique identifier for API access mostly used for authentication - email or custom username
            ->unique();

        $table->string('password');

        $table->enum('status', ['active', 'inactive'])
            ->default('active');

        /*
        |--------------------------------------------------------------------------
        | Future Expansion Fields
        |--------------------------------------------------------------------------
        */

        $table->string('phone')
            ->nullable();


        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
};
