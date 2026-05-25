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
    Schema::create('messages', function (Blueprint $table) {

        $table->string('id', 20)
            ->primary();

        /*
        |--------------------------------------------------------------------------
        | Ownership
        |--------------------------------------------------------------------------
        */

        $table->foreignId('client_id')
            ->nullable()
            ->constrained('api_clients')
            ->nullOnDelete();

        /*
        |--------------------------------------------------------------------------
        | Message Payload
        |--------------------------------------------------------------------------
        */

        $table->text('msisdn')
            ->nullable();

        $table->integer('pages')
            ->default(1);

        $table->text('text')
            ->nullable();

        /*
        |--------------------------------------------------------------------------
        | Vendor / DLR Response
        |--------------------------------------------------------------------------
        */

        $table->text('response')
            ->nullable();

        $table->string('dlr_status', 20)
            ->default('0');

        $table->tinyInteger('dlr_report')
            ->default(0);

        $table->enum('dlr', ['1', '0'])
            ->nullable();

        $table->enum('status', ['1', '0'])
            ->default('0');

        /*
        |--------------------------------------------------------------------------
        | Sender / Routing
        |--------------------------------------------------------------------------
        */

        $table->string('senderid', 50)
            ->nullable();

        $table->enum('counter', ['0', '1'])
            ->default('0');

        $table->text('dlr_request')
            ->nullable();

        $table->text('dlr_results')
            ->nullable();

        $table->string('network', 10)
            ->nullable();

        /*
        |--------------------------------------------------------------------------
        | Timestamps
        |--------------------------------------------------------------------------
        */

        $table->timestamp('created_at')
            ->nullable();

        $table->timestamp('updated_at')
            ->nullable();

        /*
        |--------------------------------------------------------------------------
        | Existing Index Alignment
        |--------------------------------------------------------------------------
        */

        $table->index('client_id');

        $table->index('dlr_status');

        $table->index('dlr_report');

        $table->index('created_at');

        $table->index('pages');

        $table->index('network');

        $table->index('status');

        /*
        |--------------------------------------------------------------------------
        | Future Expansion
        |--------------------------------------------------------------------------
        */

        $table->string('vendor')
            ->nullable();

        $table->decimal('cost', 18, 2)
            ->nullable();

        $table->json('meta')
            ->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
