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
       Schema::create('mobi_dlr', function (Blueprint $table) {

            $table->id();

            $table->string('message_id');

            $table->string('status', 50)->nullable();

            $table->string('error_code', 50)->nullable();

            $table->dateTime('dlr_date')->nullable();

            $table->string('msisdn', 20)->nullable();

            $table->boolean('sent')->default(false);

            $table->timestamp('created_at')
                ->useCurrent();

            $table->integer('dlr_id')->nullable();

            $table->string('ref_id', 50)->nullable();

            $table->string('network')->nullable();

            $table->index([
                'created_at',
                'error_code'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobi_dlr');
    }
};
