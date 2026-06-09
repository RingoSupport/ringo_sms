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
       Schema::create('client_users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('api_client_id')
                ->constrained('api_clients')
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('email')->unique();

            $table->string('password');

            $table->string('role')->default('user');

            $table->boolean('is_active')->default(true);

            $table->timestamp('last_login_at')->nullable();

            $table->rememberToken();

            $table->timestamps();

            $table->index(['api_client_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_users');
    }
};
